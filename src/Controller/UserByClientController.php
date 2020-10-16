<?php

namespace App\Controller;

use App\Entity\User;
use App\Service\UserByClientServiceInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\SerializerInterface;

/**
 * UserByClientController.
 *
 * @Route("/clients/users", name="users_by_client_")
 */
class UserByClientController extends AbstractController
{
    /**
     * @Route(
     *     path="",
     *     name="collection_get",
     *     methods={"GET"},
     *     stateless=true
     * )
     *
     * @param UserByClientServiceInterface $userService,
     * @param Request                      $request
     *
     * @return JsonResponse
     */
    public function collection(Request $request, UserByClientServiceInterface $userService): JsonResponse
    {
        return new JsonResponse(
            $userService->getSerializedPaginatedUsersByClient($this->getUser(), $request),
            JsonResponse::HTTP_OK,
            [],
            true
        );
    }

    /**
     * item
     * Get details of one user linked by a client.
     *
     * @Route(
     *     path="/{uuid}",
     *     name="item_get",
     *     methods={"GET"},
     *     stateless=true
     * )
     *
     * @isGranted("client", subject="user", message="Access Denied. You can only access your own users.")
     *
     * @param User                $user
     * @param SerializerInterface $serializer
     *
     * @return JsonResponse
     */
    public function item(User $user, SerializerInterface $serializer): JsonResponse
    {
        return new JsonResponse(
            $serializer->serialize($user, 'json', ['groups' => 'get']),
            JsonResponse::HTTP_OK,
            [],
            true
        );
    }

    /**
     * post
     * To add a user linked by a client.
     *
     * @Route(
     *     path="",
     *     name="collection_post",
     *     methods={"POST"},
     * )
     *
     * @param Request                      $request
     * @param UserByClientServiceInterface $userService
     *
     * @return JsonResponse
     */
    public function post(Request $request, UserByClientServiceInterface $userService): JsonResponse
    {
        return $userService->processPostUserByClient($this->getUser(), $request);
    }

    /**
     * put
     * To edit a user linked by a client.
     *
     * @Route(
     *     path="/{uuid}",
     *     name="collection_put",
     *     methods={"PUT"}
     * )
     *
     * @isGranted("client", subject="user", message="Access Denied. You can only access your own users.")
     *
     * @param User                         $user
     * @param Request                      $request
     * @param UserByClientServiceInterface $userService
     *
     * @return JsonResponse
     */
    public function put(
        User $user,
        Request $request,
        UserByClientServiceInterface $userService
    ): JsonResponse {
        return $userService->processPutUserByClient($this->getUser(), $user, $request);
    }

    /**
     * delete
     * To delete a user linked by a client.
     *
     * @Route(
     *     path="/{uuid}",
     *     name="collection_delete",
     *     methods={"DELETE"}
     * )
     *
     * @isGranted("client", subject="user", message="Access Denied. You can only access your own users.")
     *
     * @param User                         $user
     * @param UserByClientServiceInterface $userService
     *
     * @return JsonResponse
     */
    public function delete(User $user, UserByClientServiceInterface $userService): JsonResponse
    {
        return $userService->processDeleteUserByClient($user);
    }
}
