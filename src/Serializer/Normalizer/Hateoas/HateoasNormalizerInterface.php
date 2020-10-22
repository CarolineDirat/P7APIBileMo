<?php

namespace App\Serializer\Normalizer\Hateoas;

interface HateoasNormalizerInterface
{
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
    public function addRel(array $data, string $rel, string $method, string $route, array $parameter = []): array;
}
