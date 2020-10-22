<?php

namespace App\Service;

use App\Entity\User;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\HttpFoundation\JsonResponse;

class UserDeleteService implements UserDeleteServiceInterface
{
    /**
     * managerRegistry.
     *
     * @var ManagerRegistry
     */
    private ManagerRegistry $managerRegistry;

    /**
     * __construct.
     *
     * @param ManagerRegistry $managerRegistry
     */
    public function __construct(
        ManagerRegistry $managerRegistry
    ) {
        $this->managerRegistry = $managerRegistry;
    }

    /**
     * processDeleteUserByClient
     * Delete a user linked by a client.
     *
     * @param User $user
     *
     * @return JsonResponse
     */
    public function processDeleteUserByClient(User $user): JsonResponse
    {
        $em = $this->managerRegistry->getManager();
        $em->remove($user);
        $em->flush();

        return new JsonResponse(
            null,
            JsonResponse::HTTP_NO_CONTENT
        );
    }
}
