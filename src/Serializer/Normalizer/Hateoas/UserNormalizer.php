<?php

namespace App\Serializer\Normalizer\Hateoas;

use App\Entity\User;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Component\Serializer\Normalizer\NormalizerInterface;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;

class UserNormalizer implements NormalizerInterface
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
     * normalize : add ['_links']['self','modify','delete']['href'] to a user data (HATEOAS)
     *
     * @param  User $object
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
                'api_users_by_client_item_get',
                ['uuid' => $object->getUuid()],
                UrlGeneratorInterface::ABSOLUTE_PATH
            )
        ;
        $data['_links']['self']['method'] = 'GET';

        $data['_links']['modify']['href'] = $this->router
            ->generate(
                'api_users_by_client_collection_put',
                ['uuid' => $object->getUuid()],
                UrlGeneratorInterface::ABSOLUTE_PATH
            )
        ;
        $data['_links']['modify']['method'] = 'PUT';

        $data['_links']['delete']['href'] = $this->router
            ->generate(
                'api_users_by_client_collection_delete',
                ['uuid' => $object->getUuid()],
                UrlGeneratorInterface::ABSOLUTE_PATH
            )
        ;
        $data['_links']['delete']['method'] = 'DELETE';

        $data['_links']['list']['href'] = $this->router
            ->generate(
                'api_users_by_client_collection_get',
                [],
                UrlGeneratorInterface::ABSOLUTE_PATH
            )
        ;
        $data['_links']['list']['method'] = 'GET';

        $data['_links']['create']['href'] = $this->router
            ->generate(
                'api_users_by_client_collection_post',
                ['uuid' => $object->getUuid()],
                UrlGeneratorInterface::ABSOLUTE_PATH
            )
        ;
        $data['_links']['create']['method'] = 'POST';

        return $data;
    }
    
    /**
     * supportsNormalization
     *
     * @param  User $data
     * @param  string $format
     * 
     * @return bool
     */
    public function supportsNormalization($data, string $format = null): bool
    {
        return $data instanceof User;
    }
}
