<?php

namespace App\Serializer\Normalizer;

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
     * __construct.
     *
     * @param string[] $exceptionTypes
     */
    public function __construct(array $exceptionTypes)
    {
        $this->exceptionTypes = $exceptionTypes;
    }

    public function supports(Throwable $exception): bool
    {
        return in_array(get_class($exception), $this->exceptionTypes);
    }
}