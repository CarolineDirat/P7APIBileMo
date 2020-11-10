<?php

namespace App\Serializer\Normalizer\Hateoas;

use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;

class HateoasNormalizer implements HateoasNormalizerInterface
{
    const GET_METHOD = 'GET';
    const PUT_METHOD = 'PUT';
    const POST_METHOD = 'POST';
    const DELETE_METHOD = 'DELETE';

    /**
     * router.
     *
     * @var UrlGeneratorInterface
     */
    protected UrlGeneratorInterface $router;

    /**
     * normalizer.
     *
     * @var ObjectNormalizer
     */
    protected ObjectNormalizer $normalizer;

    /**
     * __construct.
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
     * addRel : add a property $rel[href, method] to $data['_links'].
     *
     * @param array<string, mixed> $data
     * @param string               $rel
     * @param string               $method
     * @param string               $route
     * @param array<string, mixed> $parameter
     *
     * @return array<string, mixed>
     */
    public function addRel(array $data, string $rel, string $method, string $route, array $parameter = []): array
    {
        $data['_links'][$rel]['href'] = $this->router
            ->generate(
                $route,
                $parameter
            )
        ;
        $data['_links'][$rel]['method'] = $method;

        return $data;
    }
    
    /**
     * addRelDoc : add $data[_links]['api_doc'], link to get the api documentation in JSON format
     *
     * @param  array<string, mixed> $data normalized object
     * @return array<string, mixed>
     */
    public function addRelDocJson(array $data): array
    {
        $data['_links']['api_doc']['href'] = 'api/doc.json';
        $data['_links']['api_doc']['method'] = 'GET';

        return $data;
    }
}
