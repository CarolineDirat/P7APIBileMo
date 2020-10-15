<?php

namespace App\Service;

use Symfony\Component\Serializer\SerializerInterface;
use App\Service\ErrorResponse\BadRequestErrorResponse;
use App\Service\ErrorResponse\ErrorResponseInterface;

class BodyRequestService implements BodyRequestServiceInterface
{    
    /**
     * serializer
     *
     * @var SerializerInterface
     */
    private SerializerInterface $serializer;
    
    /**
     * errorBadRequest
     * Object that define code and body of the json response for a bad request error
     *
     * @var ErrorResponseInterface
     */
    private ErrorResponseInterface $errorBadRequest;
    
    /**
     * __construct
     *
     * @param  SerializerInterface $serializer
     * @return void
     */
    public function __construct(SerializerInterface $serializer)
    {
        $this->serializer = $serializer;
        $this->errorBadRequest = new BadRequestErrorResponse($this->serializer);
    }
        
    /**
     * checkData
     * Checks properties of data of body request
     *
     * @param  string[] $data            array of data properties of body request
     * @param  string[] $validProperties properties corresponding to the entity we want to add ou update.
     * 
     * @return bool
     */
    public function isValid(array $data, array $validProperties): bool
    {
        $dataProperties = array_keys($data);
        $error = true;

        foreach ($dataProperties as $value) {
            if (!in_array($value, $validProperties, true)) {
                $error = false;
                $this->errorBadRequest->addBodyValueToArray('messages', 'Bad Request : The data name {' . (string) $value . '} is not valid.');
            }
        }
        
        $missingProperties = array_diff($validProperties, $dataProperties);
        if (!empty($missingProperties)) {
            $error = false;
            foreach ($missingProperties as $value) {
                $this->errorBadRequest->addBodyValueToArray('messages', 'Bad Request : The data name {' . (string) $value . '} is missing');
            }
        }

        if (empty($error)) {
            $this->errorBadRequest->addBodyArray('valid_properties', $validProperties);
        }

        return $error;
    }

    /**
     * Get object that define code and body of the json response for a bad request error
     *
     * @return ErrorResponseInterface
     */ 
    public function getErrorBadRequest(): ErrorResponseInterface
    {
        return $this->errorBadRequest;
    }
}
