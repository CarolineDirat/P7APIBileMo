<?php

namespace App\Service\Cache;

use App\Entity\Phone;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;

class PhoneCache extends Cache implements PhoneCacheInterface
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
    public function phoneCacheableResponse(Request $request, JsonResponse $response, Phone $phone): JsonResponse
    {
        $response->setCache(
            [
                'public' => true,
                'no_cache' => true,
                'max_age' => 5,
                'must_revalidate' => true,
            ]
        );
        $response->setEtag($phone->computeEtag());
        
        // Check that the Response is not modified for the given Request.
        // I don't use $response->isNotModified($request), because it doesn't work,
        // because of added quotation marks in $response->getEtag()
        // see dump($request->getETags());
        // see dd($response->getEtag());
        if ($this->etagIsNotModified($request, $response)) {
            // return the 304 Response immediately
            return $response;
        }
        
        return $response;
    }
}
