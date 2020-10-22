<?php

namespace App\Service;

use App\Entity\Client;
use App\Entity\User;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;

interface UserModifyServiceInterface
{
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
}
