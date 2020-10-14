<?php

namespace App\Service;

use Symfony\Component\Serializer\SerializerInterface;

class ErrorBadRequestService extends AbstractErrorService
{    
    /**
     * __construct
     *
     * @param SerializerInterface $serializer
     */
    public function __construct(SerializerInterface $serializer)
    {
        parent::__construct($serializer, 400);
    }
}
