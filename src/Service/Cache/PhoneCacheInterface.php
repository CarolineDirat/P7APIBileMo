<?php

namespace App\Service\Cache;

use App\Entity\Phone;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;

interface PhoneCacheInterface
{
    /**
     * getCacheableResponse
     * Set  Cache-Control and Etag headers, 
     * and check that the Response is not modified for the given Request
     * 
     * Return 304 Not Modified if the response is not modified, 
     * else return 200 OK with the JSON response of the phone
     *
     * @param Request  $request
     * @param JsonResponse $response
     * @param Phone    $phone
     * 
     * @return JsonResponse
     */
    public function phoneCacheableResponse(Request $request, JsonResponse $response, Phone $phone): JsonResponse;
}
