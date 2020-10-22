<?php

namespace App\Service;

use App\Entity\User;
use Symfony\Component\HttpFoundation\JsonResponse;

interface UserDeleteServiceInterface
{
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
