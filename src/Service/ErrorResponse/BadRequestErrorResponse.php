<?php

namespace App\Service\ErrorResponse;

use Symfony\Component\Serializer\SerializerInterface;

class BadRequestErrorResponse extends ErrorResponse
{
    /**
     * __construct.
     *
     * @param SerializerInterface $serializer
     */
    public function __construct(SerializerInterface $serializer, ErrorHateoas $errorHateoas)
    {
        parent::__construct($serializer, self::HTTP_BAD_REQUEST, $errorHateoas);
    }
}
