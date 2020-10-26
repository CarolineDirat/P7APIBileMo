<?php

namespace App\Response;

use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;

class AppJsonResponse extends JsonResponse
{
    /**
     * Rewrite Response::isNotModified() to fix a bug
     * because of added quotation marks in $response->getEtag()
     * see dump($request->getETags()); -> 'etag' without quotation marks
     * see dd($response->getEtag()); -> '"etag"' with quotation marks
     * so in_array($responseEtag, $etags) can never be true
     * 
     * Determines if the Response validators (ETag, Last-Modified) match
     * a conditional value specified in the Request.
     *
     * If the Response is not modified, it sets the status code to 304 and
     * removes the actual content by calling the setNotModified() method.
     *
     * @return bool true if the Response validators match the Request, false otherwise
     *
     * @final
     */
    public function isNotModified(Request $request): bool
    {
        if (!$request->isMethodCacheable()) {
            return false;
        }

        $notModified = false;
        $lastModified = $this->headers->get('Last-Modified');
        $modifiedSince = $request->headers->get('If-Modified-Since');
        $responseEtag = str_replace('"', '', $this->getEtag()); // fix the bug in $response->isNotModified($request)

        if ($etags = $request->getETags()) {
            $notModified = \in_array($responseEtag, $etags) || \in_array('*', $etags);
        }

        if ($modifiedSince && $lastModified) {
            $notModified = strtotime($modifiedSince) >= strtotime($lastModified) && (!$etags || $notModified);
        }

        if ($notModified) {
            $this->setNotModified();
        }

        return $notModified;
    }
}
