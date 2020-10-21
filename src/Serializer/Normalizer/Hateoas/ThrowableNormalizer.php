<?php

namespace App\Serializer\Normalizer\Hateoas;

use App\Service\UserByClientService;
use Symfony\Component\Serializer\Normalizer\NormalizerInterface;
use Throwable;

class ThrowableNormalizer extends HateoasNormalizer implements NormalizerInterface
{    
    /**
     * normalize : add ['_links']['self', 'list']['href'] to a phone data (HATEOAS)
     *
     * @param  Throwable $object
     * @param  string $format
     * @param  array<string, mixed> $context
     * 
     * @return array<string, mixed>
     */
    public function normalize($object, string $format = null, array $context = []): array
    {
        $data = $this->normalizer->normalize($object, $format, $context);
        
        $data = $this->addRel($data, 'phones', self::GET_METHOD, 'api_phones_collection_get');

        $data = $this->addRel($data, 'users', self::GET_METHOD, 'api_users_by_client_collection_get');
        
        $data = $this->addRel($data, 'create_user', self::POST_METHOD, 'api_users_by_client_collection_post');
        $data['_links']['create_user']['request_body'] = UserByClientService::VALID_PROPERTIES;

        return $data;
    }
    
    /**
     * supportsNormalization
     *
     * @param  Throwable $data
     * @param  string $format
     * 
     * @return bool
     */
    public function supportsNormalization($data, string $format = null): bool
    {
        return $data instanceof Throwable;
    }
}
