<?php

namespace App\EventSubscriber;

use App\Normalizer\AppNormalizerInterface;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Event\ExceptionEvent;
use Symfony\Component\HttpKernel\KernelEvents;
use Symfony\Component\Serializer\SerializerInterface;

/**
 * ExceptionSubscriber
 * Run the right service (tagged app.normalizer) depending on the type of the exception.
 */
class ExceptionSubscriber implements EventSubscriberInterface
{
    /**
     * serializer.
     *
     * @var SerializerInterface
     */
    private SerializerInterface $serializer;

    /**
     * normalizers.
     *
     * @var AppNormalizerInterface[]
     */
    private array $normalizers = [];

    public function __construct(SerializerInterface $serializer)
    {
        $this->serializer = $serializer;
    }

    /**
     * processException
     * Define the JSON response with the code and its message
     * corresponding to the exception event.
     *
     * @param ExceptionEvent $event
     */
    public function processException(ExceptionEvent $event): void
    {
        $result = null;

        foreach ($this->normalizers as $normalizer) {
            if ($normalizer->supports($event->getThrowable())) {
                $result = $normalizer->normalize($event->getThrowable());

                break;
            }
        }

        if (null === $result) {
            $result['code'] = $event->getThrowable()->getCode();

            $result['body'] = [
                'code' => $event->getThrowable()->getCode(),
                'message' => $event->getThrowable()->getMessage(),
            ];
        }

        $body = $this->serializer->serialize($result['body'], 'json');

        $event->setResponse(new JsonResponse($body, $result['code'], [], true));
    }

    /**
     * addNormalizer
     * Method in charge of adding normalizers to ExceptionSubscriber.
     *
     * @param AppNormalizerInterface $normalizer
     */
    public function addNormalizer(AppNormalizerInterface $normalizer): void
    {
        $this->normalizers[] = $normalizer;
    }

    /**
     * getSubscribedEvents.
     *
     * @see EventSubscriberInterface
     *
     * @return array<string, mixed>
     */
    public static function getSubscribedEvents(): array
    {
        return [
            KernelEvents::EXCEPTION => [['processException', 255]],
        ];
    }
}
