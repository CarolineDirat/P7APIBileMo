<?php

namespace App\Service\ErrorResponse;

use Symfony\Component\HttpFoundation\JsonResponse;

interface ErrorResponseInterface
{
    /**
     * returnErrorJsonResponse
     * Return a JsonResponse corresponding to the error with its body
     *
     * @return JsonResponse
     */
    public function returnErrorJsonResponse(): JsonResponse;

    /**
     * addBodyValue
     * Add a data (string : string) to the body response 
     * 
     * @param  string $key
     * @param  string $value
     * 
     * @return self
     */
    public function addBodyValue(string $key, string $value): self;


    /**
     * addBodyArray
     * Add a data (string : array) to the body response 
     *
     * @param string $key
     * @param array<mixed, mixed> $value
     *
     * @return  self
     */ 
    public function addBodyArray(string $key, array $value): self;

     /**
     * addBodyValueToArray
     * Add a data (string[] : string) to the body response 
     *
     * @param string $key
     * @param string $value
     *
     * @return  self
     */ 
    public function addBodyValueToArray(string $key, string $value): self;
}
