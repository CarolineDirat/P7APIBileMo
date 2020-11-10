<?php

namespace App\Serializer\Normalizer\Hateoas;

use App\Entity\User;
use Symfony\Component\Serializer\Normalizer\NormalizerInterface;

class UserNormalizer extends HateoasNormalizer implements NormalizerInterface
{
    /**
     * normalize : add ['_links']['self','modify','delete', 'list', 'create'] to a user data (HATEOAS).
     *
     * @param User                 $object
     * @param string               $format
     * @param array<string, mixed> $context
     *
     * @return array<string, mixed>
     */
    public function normalize($object, string $format = null, array $context = []): array
    {
        $data = $this->normalizer->normalize($object, $format, $context);

        $data = $this->addRel($data, 'self', self::GET_METHOD, 'api_users_by_client_item_get', ['uuid' => $object->getUuid()]);

        $data = $this->addRel($data, 'modify', self::PUT_METHOD, 'api_users_by_client_collection_put', ['uuid' => $object->getUuid()]);

        $data = $this->addRel($data, 'delete', self::DELETE_METHOD, 'api_users_by_client_collection_delete', ['uuid' => $object->getUuid()]);

        $data = $this->addRel($data, 'list', self::GET_METHOD, 'api_users_by_client_collection_get');

        return $this->addRel($data, 'create', self::POST_METHOD, 'api_users_by_client_collection_post');
    }

    /**
     * supportsNormalization.
     *
     * @param User   $data
     * @param string $format
     *
     * @return bool
     */
    public function supportsNormalization($data, string $format = null): bool
    {
        return $data instanceof User;
    }
}
