<?php

namespace App\Serializer\Normalizer\Hateoas;

use App\Entity\Phone;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Component\Serializer\Normalizer\NormalizerInterface;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;

class PhoneNormalizer implements NormalizerInterface
{    
    /**
     * router
     *
     * @var UrlGeneratorInterface
     */
    private UrlGeneratorInterface $router;

    /**
     * normalizer
     *
     * @var ObjectNormalizer
     */
    private ObjectNormalizer $normalizer;
    
    /**
     * __construct
     *
     * @param UrlGeneratorInterface $router
     * @param ObjectNormalizer      $normalizer
     */
    public function __construct(UrlGeneratorInterface $router, ObjectNormalizer $normalizer)
    {
        $this->router = $router;
        $this->normalizer = $normalizer;
    }

    /**
     * normalize : add ['_links']['self']['href'] to a phone data (HATEOAS)
     *
     * @param  Phone $object
     * @param  string $format
     * @param  array<string, mixed> $context
     * 
     * @return array<string, mixed>
     */
    public function normalize($object, string $format = null, array $context = []): array
    {
        $data = $this->normalizer->normalize($object, $format, $context);
        
        $data['_links']['self']['href'] = $this->router
            ->generate(
                'api_phones_item_get',
                ['uuid' => $object->getUuid()],
                UrlGeneratorInterface::ABSOLUTE_PATH
            )
        ;
        $data['_links']['self']['method'] = 'GET';

        $data['_links']['list']['href'] = $this->router
            ->generate(
                'api_phones_collection_get',
                [],
                UrlGeneratorInterface::ABSOLUTE_PATH
            )
        ;
        $data['_links']['list']['method'] = 'GET';

        return $data;
    }
    
    /**
     * supportsNormalization
     *
     * @param  Phone $data
     * @param  string $format
     * 
     * @return bool
     */
    public function supportsNormalization($data, string $format = null): bool
    {
        return $data instanceof Phone;
    }
}
