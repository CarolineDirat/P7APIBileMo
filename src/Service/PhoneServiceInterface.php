<?php

namespace App\Service;

use Symfony\Component\HttpFoundation\Request;

interface PhoneServiceInterface
{    
    /**
     * getSerializedPaginatedPhones
     * Return a page of serialized phones (in json format).
     * By default page=1, and limit is define in constants.ini file.
     *
     * @param  Request  $request It can contain as parameters the 'page' and the number of phones per pages ('limit').
     * @return string
     */
    public function getSerializedPaginatedPhones(Request $request): string;
}
