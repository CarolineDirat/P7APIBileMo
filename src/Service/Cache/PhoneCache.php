<?php

namespace App\Service\Cache;

use App\Entity\Phone;
use App\Response\AppJsonResponse;
use Symfony\Component\HttpFoundation\Request;

class PhoneCache implements PhoneCacheInterface
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
     * @param AppJsonResponse $response
     * @param Phone    $phone
     * 
     * @return AppJsonResponse
     */
    public function phoneCacheableResponse(Request $request, AppJsonResponse $response, Phone $phone): AppJsonResponse
    {
        $response->setCache(
            [
                'public' => true,
                'no_cache' => true,
                'max_age' => 86400,
                'must_revalidate' => true,
            ]
        );
        $response->setEtag($phone->computeEtag());
        
        // Check that the Response is not modified for the given Request.
        if ($response->isNotModified($request)) {
            // return the 304 Response immediately
            return $response;
        }
        
        return $response;
    }
}
