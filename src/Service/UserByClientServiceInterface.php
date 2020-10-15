<?php

namespace App\Service;

use App\Entity\Client;
use App\Entity\User;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;

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
     * @param Client  $client
     * @param Request $request
     *
     * @return JsonResponse
     */
    public function processPostUserByClient(Client $client, Request $request): JsonResponse;

    /**
     * processPutUserByClient.
     *
     * @param Client  $client
     * @param User    $user
     * @param Request $request
     *
     * @return JsonResponse
     */
    public function processPutUserByClient(Client $client, User $user, Request $request): JsonResponse;

    /**
     * processDeleteUserByClient
     * Delete a user linked by a client.
     *
     * @param User $user
     *
     * @return JsonResponse
     */
    public function processDeleteUserByClient(User $user): JsonResponse;
}
