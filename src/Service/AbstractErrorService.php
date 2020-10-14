<?php

namespace App\Service;

use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Serializer\SerializerInterface;

abstract class AbstractErrorService implements ErrorServiceInterface
{
     /**
     * code
     * error code HTTP : 4XX
     *
     * @var int
     */
    protected int $code = 0;
    
    /**
     * body
     * array of the body content of an error as JsonResponse
     *
     * @var array<string, mixed>
     */
    protected array $body = [];

    /**
     * serializer
     *
     * @var SerializerInterface
     */
    private SerializerInterface $serializer;
    
    /**
     * __construct
     *
     * @param SerializerInterface $serializer
     */
    public function __construct(SerializerInterface $serializer, int $code)
    {
        $this->serializer = $serializer;
        $this->code = $code;
        $this->body['code'] = $this->code;
    }

    /**
     * returnErrorJsonResponse
     * Return a JsonResponse with
     *
     * @return JsonResponse
     */
    public function returnErrorJsonResponse(): JsonResponse
    {
        return new JsonResponse(
            $this->serializer->serialize($this->body, 'json'),
            $this->code,
            [],
            true
        );
    }

    /**
     * Get mixed>
     *
     * @return  array<string, mixed>
     */ 
    public function getBody()
    {
        return $this->body;
    }

    /**
     * addBodyArray
     * Add a data (string : array) to the body response 
     *
     * @param string $key
     * @param array<mixed, mixed> $value
     *
     * @return  self
     */ 
    public function addBodyArray(string $key, array $value): self
    {
        $this->body[$key] = $value;

        return $this;
    }

    /**
     * addBodyValueToArray
     * Add a data (string[] : string) to the body response 
     *
     * @param string $key
     * @param string $value
     *
     * @return  self
     */ 
    public function addBodyValueToArray(string $key, string $value): self
    {
        $this->body[$key][] = $value;

        return $this;
    }
    
    /**
     * addBodyValue
     * Add a data (string : string) to the body response 
     * 
     * @param  string $key
     * @param  string $value
     * 
     * @return self
     */
    public function addBodyValue(string $key, string $value): self
    {
        $this->body[$key] = $value;

        return $this;
    }

    /**
     * Get error code HTTP : 4XX
     *
     * @return  int
     */ 
    public function getCode()
    {
        return $this->code;
    }

    /**
     * Set error code HTTP : 4XX
     *
     * @param  int  $code  error code HTTP : 4XX
     *
     * @return  self
     */ 
    public function setCode(int $code): self
    {
        $this->code = $code;

        return $this;
    }
}
