<?php

namespace App\Controller;

use App\Entity\Client;
use App\Entity\User;
use App\Repository\UserRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\SerializerInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException;

/**
 * UserByClientController.
 *
 * @Route("/clients/{uuid}/users", name="users_by_client_")
 */
class UserByClientController extends AbstractController
{
    /**
     * @Route(
     *     "/",
     *     name="collection_get",
     *     methods={"GET"},
     *     stateless=true
     * )
     */
    public function collection(
        Client $client,
        UserRepository $userRepository,
        SerializerInterface $serializer
    ): JsonResponse {
        $users = $userRepository->findBy(['client' => $client]);

        return new JsonResponse(
            $serializer->serialize(
                $users,
                'json',
                ['groups' => 'get']
            ),
            Response::HTTP_OK,
            [],
            true
        );
    }

    /**
     * item
     * Get details of one user linked by a client
     * 
     * @Route(
     *  "/{user_uuid}",
     *  name="item_get",
     *  methods={"GET"},
     *  stateless=true
     * )
     * 
     * @ParamConverter("user", options={"mapping": {"user_uuid": "uuid"}})
     * 
     * @param Client $client
     * @param User $user
     * @param SerializerInterface $serializer
     *
     * @return JsonResponse
     */
    public function item(
        Client $client,
        User $user,
        SerializerInterface $serializer
    ): JsonResponse {

        if ($user->getClient() !== $client) {
            throw new AccessDeniedHttpException(
                "You cannot access to the user by this client.",
                null,
                Response::HTTP_FORBIDDEN
            );
        }

        return new JsonResponse(
            $serializer->serialize($user, 'json', ['groups' => "get"]),
            Response::HTTP_OK,
            [],
            true
        );
    }
}
