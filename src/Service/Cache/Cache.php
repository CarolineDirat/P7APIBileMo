<?php

namespace App\Service\Cache;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class Cache implements CacheInterface
{
    /**
     * Determines if the Response validator ETag match
     * a conditional value specified in the Request.
     *
     * If the Response is not modified, it sets the status code to 304 and
     * removes the actual content by calling the setNotModified() method.
     * 
     * @param Request $request
     * @param Response $response
     *
     * @return bool true if the Response validators match the Request, false otherwise
     */
    public function etagIsNotModified(Request $request, Response $response): bool
    {
        if (!$request->isMethodCacheable()) {
            return false;
        }

        $notModified = false;
        $etag = str_replace('"', '', $response->getEtag()); // fix bug in $response->isNotModified($request)

        if ($etags = $request->getETags()) {
            $notModified = \in_array($etag, $etags) || \in_array('*', $etags);
        }

        if ($notModified) {
            $response->setNotModified();
        }

        return $notModified;
    }
}
