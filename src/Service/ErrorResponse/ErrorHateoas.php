<?php

namespace App\Service\ErrorResponse;

use App\Serializer\Normalizer\Hateoas\HateoasNormalizer;
use App\Service\UserByClientService;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;

class ErrorHateoas
{
    /**
     * router.
     *
     * @var UrlGeneratorInterface
     */
    private UrlGeneratorInterface $router;

    /**
     * __construct.
     *
     * @param UrlGeneratorInterface $router
     */
    public function __construct(UrlGeneratorInterface $router)
    {
        $this->router = $router;
    }

    /**
     * addErrorHateoas : add _links to an array corresponding to an error response.
     *
     * @param array<string, mixed> $body
     *
     * @return array<string, mixed>
     */
    public function addErrorHateoas(array $body): array
    {
        $body['_links']['phones']['href'] = $this->router->generate('api_phones_collection_get');
        $body['_links']['phones']['method'] = HateoasNormalizer::GET_METHOD;

        $body['_links']['users']['href'] = $this->router->generate('api_users_by_client_collection_get');
        $body['_links']['users']['method'] = HateoasNormalizer::GET_METHOD;

        $body['_links']['create_user']['href'] = $this->router->generate('api_users_by_client_collection_post');
        $body['_links']['create_user']['method'] = HateoasNormalizer::POST_METHOD;
        $body['_links']['create_user']['request_body'] = UserByClientService::VALID_PROPERTIES;

        $body['_links']['login']['href'] = $this->router->generate('api_login_check');
        $body['_links']['login']['method'] = HateoasNormalizer::POST_METHOD;
        $body['_links']['login']['request_body'] = ['username' => 'string', 'password' => 'string'];

        $body['_links']['refresh_token']['href'] = $this->router->generate('gesdinet_jwt_refresh_token');
        $body['_links']['refresh_token']['method'] = HateoasNormalizer::POST_METHOD;
        $body['_links']['refresh_token']['request_body'] = ['refresh_token' => 'string'];

        return $body;
    }
}
