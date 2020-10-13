<?php

namespace App\Service;

use Doctrine\ORM\Tools\Pagination\Paginator;
use Symfony\Component\HttpFoundation\Request;

interface PaginationServiceInterface
{
    /**
     * getQueryParameters
     * Checks and get query parameters which can be 'page' and 'limit'
     * 'page' = page of paginated elements
     * 'limit'  = number of elements by page
     *
     * @param Request $request
     * @param string  $entities name of elements = keys from constants.ini file
     * 
     * @return array<string,string>
     */
    public function getQueryParameters(Request $request, string $entities): array;

    /**
     * getSerializedPaginatedData
     *
     * @param Paginator $data    Paginated data
     * @param int       $page
     * @param int       $limit   Number of data per page
     * @param array     $context Options normalizer/encoders have to access
     * 
     * @return string
     */
    public function getSerializedPaginatedData(Paginator $data, int $page, int $limit, array $context): string;
}
