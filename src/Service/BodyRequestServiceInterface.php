<?php

namespace App\Service;

use Symfony\Component\HttpFoundation\JsonResponse;

interface BodyRequestServiceInterface
{
    /**
     * checkData
     * Checks properties of data of body request
     *
     * @param  string[] $data            array of data properties of body request
     * @param  string[] $validProperties properties corresponding to the entity we want to add ou update.
     * 
     * @return bool
     */
    public function isValid(array $data, array $validProperties): bool;

    /**
     * Get object that define code and body of the json response for a bad request error
     *
     * @return  ErrorBadRequestService
     */ 
    public function getErrorBadRequest(): ErrorBadRequestService;
}
