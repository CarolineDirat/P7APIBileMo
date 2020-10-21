<?php

namespace App\Service\ErrorResponse;

use Symfony\Component\Serializer\SerializerInterface;

class InternalServerErrorResponse extends AbstractErrorResponse
{
    /**
     * __construct.
     *
     * @param SerializerInterface $serializer
     */
    public function __construct(SerializerInterface $serializer, ErrorHateoas $errorHateoas)
    {
        parent::__construct($serializer, 500, $errorHateoas);
    }
}
