<?php

namespace App\Service;

use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;

class ConstantsIni
{
    private ParameterBagInterface $parameterBag;

    /**
     * constantsIni
     * Array of constants contained in the constants.ini file.
     *
     * @var array<string, mixed>
     */
    private array $constantsIni;

    public function __construct(ParameterBagInterface $parameterBag)
    {
        $this->parameterBag = $parameterBag;
        $this->constantsIni = parse_ini_file(
            $this->parameterBag->get('app.constants_ini'),
            true,
            INI_SCANNER_TYPED
        );
    }

    /**
     * getConstantsIni.
     *
     * @return array<string, mixed>
     */
    public function getConstantsIni(): array
    {
        return $this->constantsIni;
    }
}
