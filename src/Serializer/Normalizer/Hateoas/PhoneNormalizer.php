<?php

namespace App\Serializer\Normalizer\Hateoas;

use App\Entity\Phone;
use Symfony\Component\Serializer\Normalizer\NormalizerInterface;

class PhoneNormalizer extends HateoasNormalizer implements NormalizerInterface
{
    /**
     * normalize : add ['_links']['self', 'list']['href'] to a phone data (HATEOAS).
     *
     * @param Phone                $object
     * @param string               $format
     * @param array<string, mixed> $context
     *
     * @return array<string, mixed>
     */
    public function normalize($object, string $format = null, array $context = []): array
    {
        $data = $this->normalizer->normalize($object, $format, $context);

        $data = $this->addRel($data, 'self', self::GET_METHOD, 'api_phones_item_get', ['uuid' => $object->getUuid()]);
        $data = $this->addRel($data, 'list', self::GET_METHOD, 'api_phones_collection_get');

        return $this->addRelDocJson($data);
    }

    /**
     * supportsNormalization.
     *
     * @param Phone  $data
     * @param string $format
     *
     * @return bool
     */
    public function supportsNormalization($data, string $format = null): bool
    {
        return $data instanceof Phone;
    }
}
