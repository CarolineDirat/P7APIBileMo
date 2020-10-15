<?php

namespace App\Controller;

use App\Entity\Client;
use App\Entity\User;
use App\Service\UserByClientService;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\SerializerInterface;

/**
 * UserByClientController.
 *
 * @Route("/clients/{uuid}/users", name="users_by_client_")
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
     * @param Client              $client
     * @param UserByClientService $userService,
     * @param Request             $request
     *
     * @return JsonResponse
     */
    public function collection(
        Client $client,
        UserByClientService $userService,
        Request $request
    ): JsonResponse {
        return new JsonResponse(
            $userService->getSerializedPaginatedUsersByClient($client, $request),
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
     *     path="/{user_uuid}",
     *     name="item_get",
     *     methods={"GET"},
     *     stateless=true
     * )
     *
     * @ParamConverter("user", options={"mapping": {"user_uuid": "uuid"}})
     *
     * @param Client              $client
     * @param User                $user
     * @param SerializerInterface $serializer
     *
     * @throws AccessDeniedHttpException
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
                'You cannot access to the user by this client.',
                null,
                JsonResponse::HTTP_FORBIDDEN
            );
        }

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
     * @param Client              $client
     * @param Request             $request
     * @param UserByClientService $userService
     *
     * @return JsonResponse
     */
    public function post(
        Client $client,
        Request $request,
        UserByClientService $userService
    ): JsonResponse {
        return $userService->processPostUserByClient($client, $request);
    }

    /**
     * put
     * To edit a user linked by a client.
     *
     * @Route(
     *     path="/{user_uuid}",
     *     name="collection_put",
     *     methods={"PUT"}
     * )
     *
     * @ParamConverter("user", options={"mapping": {"user_uuid": "uuid"}})
     *
     * @param Client              $client
     * @param User                $user
     * @param Request             $request
     * @param UserByClientService $userService
     *
     * @return JsonResponse
     */
    public function put(
        Client $client,
        User $user,
        Request $request,
        UserByClientService $userService
    ): JsonResponse {
        if ($user->getClient() !== $client) {
            throw new AccessDeniedHttpException(
                'You cannot access to the user by this client.',
                null,
                JsonResponse::HTTP_FORBIDDEN
            );
        }

        return $userService->processPutUserByClient($client, $user, $request);
    }

    /**
     * delete
     * To delete a user linked by a client.
     *
     * @Route(
     *     path="/{user_uuid}",
     *     name="collection_delete",
     *     methods={"DELETE"}
     * )
     *
     * @ParamConverter("user", options={"mapping": {"user_uuid": "uuid"}})
     *
     * @param Client              $client
     * @param User                $user
     *
     * @return JsonResponse
     */
    public function delete(Client $client, User $user): JsonResponse
    {
        if ($user->getClient() !== $client) {
            throw new AccessDeniedHttpException(
                'You cannot access to the user by this client.',
                null,
                JsonResponse::HTTP_FORBIDDEN
            );
        }

        $em = $this->getDoctrine()->getManager();
        $em->remove($user);
        $em->flush();
        
        return new JsonResponse(
            null,
            JsonResponse::HTTP_NO_CONTENT
        );
    }
}
