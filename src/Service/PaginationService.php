<?php

namespace App\Service;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;

class PaginationService implements PaginationServiceInterface
{
    /**
     * constantsIni.
     *
     * @var ConstantsIni
     */
    private ConstantsIni $constantsIni;

    /**
     * __construct.
     *
     * @param ConstantsIni        $constantsIni
     */
    public function __construct(
        ConstantsIni $constantsIni
    ) {
        $this->constantsIni = $constantsIni;
    }

    /**
     * getQueryParameters
     *
     * @param Request $request
     * @param string  $entities keys from constants.ini file
     * @return array<string,string>
     */
    public function getQueryParameters(Request $request, string $entities): array
    {
        $constants = $this->constantsIni->getConstantsIni();
        $page = $request->query->getInt('page', 1);
        $limit = $request->query->getInt('limit', $constants['phones']['number_per_page']);

        if (empty($page) || empty($limit)) {
            throw new BadRequestHttpException("The query parameters 'page' and 'limit' must be integers and not null.");
        }

        $max = $constants[$entities]['limit_max'];
        if ($limit > $max) {
            $limit = $max;
        }

        return ['page' => $page, 'limit' => $limit];
    }
}
