<?php

namespace App\Service;

use App\Entity\Phone;
use Symfony\Component\HttpFoundation\Request;

interface PhoneServiceInterface
{
    /**
     * getSerializedPaginatedPhones
     * Return a page of serialized phones (in json format).
     * By default page=1, and limit is define in constants.ini file.
     *
     * @param Request $request it can contain as parameters the 'page' and the number of phones per pages ('limit')
     *
     * @return string
     */
    public function getSerializedPaginatedPhones(Request $request): string;

    /**
     * getSerializedPhone
     * Return a serialize data in json format corresponding to an object phone.
     *
     * @param Phone $phone
     *
     * @return string
     */
    public function getSerializedPhone(Phone $phone): string;
}
