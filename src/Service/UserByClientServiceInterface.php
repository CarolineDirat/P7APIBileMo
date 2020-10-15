<?php

namespace App\Service;

use App\Entity\Client;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Validator\Validator\ValidatorInterface;

interface UserByClientServiceInterface
{
    /**
     * getSerializedPaginatedUsersByClient
     * Return a page of serialized users (in json format), linked by a client.
     * By default page=1, and limit is define in constants.ini file.
     *
     * @param Client  $client
     * @param Request $request it can contain as parameters the 'page' and the number of users per pages ('limit')
     *
     * @return string
     */
    public function getSerializedPaginatedUsersByClient(Client $client, Request $request): string;

    /**
     * processPostUserByClient
     * After checks, add the user to the client.
     *
     * @param Client             $client
     * @param Request            $request
     *
     * @return JsonResponse
     */
    public function processPostUserByClient(Client $client, Request $request): JsonResponse;
}
