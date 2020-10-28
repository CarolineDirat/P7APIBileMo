<?php

namespace App\Service\Cache;

use App\Entity\Phone;
use App\Response\AppJsonResponse;
use Symfony\Component\HttpFoundation\Request;

interface PhoneCacheInterface
{
    /**
     * getCacheableResponse
     * Set  Cache-Control and Etag headers.
     *
     * @param Request         $request
     * @param AppJsonResponse $response
     * @param Phone           $phone
     *
     * @return AppJsonResponse
     */
    public function phoneCacheableResponse(Request $request, AppJsonResponse $response, Phone $phone): AppJsonResponse;

    /**
     * phonesCacheableResponse
     * Set  Cache-Control and Etag headers,
     * and check that the Response is not modified for the given Request.
     *
     * Return 304 Not Modified if the response is not modified,
     * else return 200 OK with the JSON response of the phone
     *
     * @param Request         $request
     * @param AppJsonResponse $response
     * @param string          $etag
     *
     * @return AppJsonResponse
     */
    public function phonesCacheableResponse(Request $request, AppJsonResponse $response, string $etag): AppJsonResponse;
}
