<?php

declare(strict_types=1);

namespace App;

use App\Exception\DomainException;
use Exception;
use Psr\Log\LoggerInterface;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Event\ExceptionEvent;
use Symfony\Component\HttpKernel\KernelEvents;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\PresentationBundle\Dto\Output\ApiFormatter;
use Symfony\PresentationBundle\Exception\PresentationBundleException;
use Throwable;

class ExceptionSubscriber implements EventSubscriberInterface
{
    public function __construct(
        private SerializerInterface $serializer,
        private LoggerInterface $logger,
        private string $env,
        private bool $debug,
    ) {
    }

    public static function getSubscribedEvents(): array
    {
        return [
            KernelEvents::EXCEPTION => [
                ['logException', 2],
                ['onFormatterException', 1],
            ],
        ];
    }

    public function logException(ExceptionEvent $event): void
    {
        $exception = $event->getThrowable();
        try {
            throw $exception;
        } catch (DomainException $exception) {
            $this->logger->warning($exception->getMessage());
        } catch (Throwable $exception) {
            $this->logger->error($exception->getMessage());
        }
    }

    public function onFormatterException(ExceptionEvent $event): void
    {
        $format = (string) $event->getRequest()->attributes->get('_format', 'json');

        $exception = $event->getThrowable();

        try {
            $previous = $exception->getPrevious();
            if (!empty($previous) && is_subclass_of($previous, DomainException::class)) {
                throw $previous;
            }

            throw $exception;
        } catch (DomainException | PresentationBundleException $exception) {
            $response = new Response();
            $response->setContent(
                $this->serializer->serialize($this->toApiFormat($exception), $format)
            );
            $response->setStatusCode(Response::HTTP_OK);
            $event->allowCustomResponseCode();
        } catch (\Doctrine\DBAL\Exception $exception) {
            if ($this->isDev() || ($this->isTest() && $this->debug)) {
                return;
            }
            if ($this->isTest() || $this->isProd()) {
                $response = new Response();
                $response->setContent(
                    $this->serializer->serialize(
                        ApiFormatter::prepare(
                            null,
                            Response::HTTP_BAD_REQUEST,
                            ['Bad Request']
                        ),
                        $format
                    )
                );
                $response->setStatusCode(Response::HTTP_OK);
            }
        } catch (Throwable $exception) {
            if ($this->isDev() || ($this->isTest() && $this->debug)) {
                return;
            }
            if ($this->isTest() || $this->isProd()) {
                $response = new Response();
                $response->setContent(
                    $this->serializer->serialize(
                        ApiFormatter::prepare(
                            null,
                            Response::HTTP_INTERNAL_SERVER_ERROR,
                            'Internal Server Error'
                        ),
                        $format
                    )
                );
                $response->setStatusCode(Response::HTTP_OK);
            }
        }

        if (isset($response)) {
            $response->headers->add(['Content-Type' => "application/" . $format]);
            $event->setResponse($response);
        }
    }

    protected function isDev(): bool
    {
        return $this->env === 'dev';
    }

    protected function isTest(): bool
    {
        return $this->env === 'test';
    }

    protected function isProd(): bool
    {
        return $this->env === 'prod';
    }

    protected function toApiFormat(Exception $exception, ?int $code = null): array
    {
        $errors = $this->isValidJson($exception->getMessage())
            ? json_decode($exception->getMessage(), true, 512, JSON_THROW_ON_ERROR)
            : [$exception->getMessage()]
        ;

        return ApiFormatter::prepare(
            [],
            $code ?? $exception->getCode(),
            $errors
        );
    }

    /**
     * @param string $string
     * @return bool
     */
    protected function isValidJson($string): bool
    {
        return ((is_string($string) && (is_object(json_decode($string)) || is_array(json_decode($string)))));
    }
}
