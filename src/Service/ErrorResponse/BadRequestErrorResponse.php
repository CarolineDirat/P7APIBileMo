<?php

namespace App\Service\ErrorResponse;

use Symfony\Component\Serializer\SerializerInterface;

class BadRequestErrorResponse extends AbstractErrorResponse
{
    /**
     * __construct.
     *
     * @param SerializerInterface $serializer
     */
    public function __construct(SerializerInterface $serializer, ErrorHateoas $errorHateoas)
    {
        parent::__construct($serializer, 400, $errorHateoas);
    }
}
