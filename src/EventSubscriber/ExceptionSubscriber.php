<?php

namespace App\EventSubscriber;

use App\Normalizer\AppNormalizerInterface;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Event\ExceptionEvent;
use Symfony\Component\HttpKernel\KernelEvents;

/**
 * ExceptionSubscriber
 * Run the right service (tagged app.normalizer) depending on the type of the exception.
 * 
 * @package App\EventSubscriber
 */
class ExceptionSubscriber implements EventSubscriberInterface
{    
    /**
     * serializer
     *
     * @var SerializerInterface $serializer
     */
    private SerializerInterface $serializer;
        
    /**
     * normalizers
     *
     * @var AppNormalizerInterface[] $normalizers
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
     * @return void
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
            $result['code'] = Response::HTTP_BAD_REQUEST;

            $result['body'] = [
                'code' => Response::HTTP_BAD_REQUEST,
                'message' => $event->getThrowable()->getMessage()
            ];
        }

        $body = $this->serializer->serialize($result['body'], 'json');

        $event->setResponse(new JsonResponse($body, $result['code'], [], true));
    }
    
    /**
     * addNormalizer
     * Method in charge of adding normalizers to ExceptionSubscriber
     *
     * @param AppNormalizerInterface $normalizer
     * @return void
     */
    public function addNormalizer(AppNormalizerInterface $normalizer): void
    {
        $this->normalizers[] = $normalizer;
    }
        
    /**
     * getSubscribedEvents
     * @see EventSubscriberInterface
     *
     * @return array<string, mixed>
     */
    public static function getSubscribedEvents(): array
    {
        return [
            KernelEvents::EXCEPTION => [['processException', 255]]
        ];
    }
}
