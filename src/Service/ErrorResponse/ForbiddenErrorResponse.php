<?php

namespace App\Service\ErrorResponse;

use Symfony\Component\Serializer\SerializerInterface;

class ForbiddenErrorResponse extends AbstractErrorResponse
{
    /**
     * __construct.
     *
     * @param SerializerInterface $serializer
     */
    public function __construct(SerializerInterface $serializer, ErrorHateoas $errorHateoas)
    {
        parent::__construct($serializer, self::HTTP_FORBIDDEN, $errorHateoas);
    }
}
