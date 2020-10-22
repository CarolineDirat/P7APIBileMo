<?php

namespace App\Serializer\Normalizer\Exception;

use App\Service\ErrorResponse\ErrorHateoas;
use Throwable;

/**
 * AppAbstractNormalizer.
 */
abstract class AbstractExceptionNormalizer implements ExceptionNormalizerInterface
{
    /**
     * exceptionTypes
     * Types of exceptions supported by the normalizer.
     *
     * @var string[]
     */
    protected array $exceptionTypes;

    /**
     * errorHateoas.
     *
     * @var ErrorHateoas
     */
    protected ErrorHateoas $errorHateoas;

    /**
     * __construct.
     *
     * @param string[] $exceptionTypes
     */
    public function __construct(array $exceptionTypes, ErrorHateoas $errorHateoas)
    {
        $this->exceptionTypes = $exceptionTypes;
        $this->errorHateoas = $errorHateoas;
    }

    public function supports(Throwable $exception): bool
    {
        return in_array(get_class($exception), $this->exceptionTypes);
    }
}
