<?php

namespace App\Controller;

use App\Entity\User;
use App\Service\UserByClientServiceInterface;
use App\Service\UserGetServiceInterface;
use App\Service\UserModifyServiceInterface;
use Nelmio\ApiDocBundle\Annotation\Model;
use Nelmio\ApiDocBundle\Annotation\Security;
use OpenApi\Annotations as OA;
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
     * collection - Get paged users belonging to the authenticated client.
     *
     * @Route(
     *     path="",
     *     name="collection_get",
     *     methods={"GET"},
     *     stateless=true
     * )
     *
     * @OA\Parameter(
     *     name="page",
     *     in="query",
     *     description="The page number of paginated users. ",
     *     @OA\Schema(type="integer", default=1, minimum=1)
     * )
     * @OA\Parameter(
     *     name="limit",
     *     in="query",
     *     description="The number of users per page. Its default value and its maximum value come from constants.ini file: [users]['number_per_page']=5 and [users]['limi_max']=100. ",
     *     @OA\Schema(type="integer", default=5, maximum=100, minimum=1)
     * )
     *
     * @OA\Response(
     *     response=206,
     *     description="Returns the page n°'page' of 'limit' users.",
     *     @OA\MediaType(
     *         mediaType="application/json",
     *         @OA\Schema(
     *             type="object",
     *             @OA\Property(
     *                 property="data",
     *                 type="array",
     *                 maxItems=100,
     *                 @OA\Items(ref=@Model(type=User::class, groups={"get"}))
     *             ),
     *             @OA\Property(
     *                 property="meta",
     *                 type="object",
     *                 @OA\Property(
     *                     property="current_page",
     *                     description="The page number in data response",
     *                     type="integer"
     *                 ),
     *                 @OA\Property(
     *                     property="number_per_page",
     *                     description="The number per page of phones in data response",
     *                     type="integer"
     *                 ),
     *                 @OA\Property(
     *                     property="total_page",
     *                     description="The number total of pages for all phones in database",
     *                     type="integer"
     *                 )
     *             )
     *         )
     *     )
     * )
     *
     * @OA\Response(
     *     response=404,
     *     description="When required page doesn't exist: The required page n°'page' doesn't exist. The maximum number of pages is x."
     * )
     *
     * @OA\Response(
     *     response=400,
     *     description="The query parameters 'page' and 'limit' must be integers and not null. || A Token was not found in the TokenStorage."
     * )
     *
     * @OA\Response(
     *     response=401,
     *     description="Invalid JWT Token. || Expired JWT Token."
     * )
     *
     * @Security(name="Bearer")
     *
     * @OA\Tag(name="Users")
     *
     * @param UserGetServiceInterface $userService,
     * @param Request                      $request
     *
     * @return JsonResponse
     */
    public function collection(Request $request, UserGetServiceInterface $userService): JsonResponse
    {
        return new JsonResponse(
            $userService->getSerializedPaginatedUsersByClient($this->getUser(), $request),
            JsonResponse::HTTP_PARTIAL_CONTENT,
            [],
            true
        );
    }

    /**
     * item - Get details of one user by its uuid and belonging to the authenticated client.
     *
     * @Route(
     *     path="/{uuid}",
     *     name="item_get",
     *     methods={"GET"},
     *     stateless=true
     * )
     *
     * @OA\Parameter(
     *     name="uuid",
     *     in="path",
     *     description="The unique identifier of the user.",
     *     @OA\Schema(type="string", maxLength=36, minLength=36)
     * )
     *
     * @OA\Response(
     *     response=200,
     *     description="Returns a user by its uuid",
     *     @Model(type=User::class, groups={"get"})
     * )
     *
     * @OA\Response(
     *     response=404,
     *     description="User not found."
     * )
     *
     * @OA\Response(
     *     response=403,
     *     description="Access Denied. You can only access to your own users."
     * )
     *
     * @OA\Response(
     *     response=400,
     *     description="A Token was not found in the TokenStorage."
     * )
     *
     * @OA\Response(
     *     response=401,
     *     description="Invalid JWT Token. || Expired JWT Token."
     * )
     *
     * @Security(name="Bearer")
     *
     * @OA\Tag(name="Users")
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
     * post - To add a user belonging to the authenticated client.
     *
     * @Route(
     *     path="",
     *     name="collection_post",
     *     methods={"POST"},
     * )
     *
     * @OA\RequestBody(
     *     request="UserArray",
     *     description="List of user properties.",
     *     required=true,
     *     @OA\MediaType(
     *         mediaType="application/json",
     *         @OA\Schema(
     *             type="object",
     *             @OA\Property(
     *                 property="email",
     *                 description="Email of the new user",
     *                 type="string"
     *             ),
     *             @OA\Property(
     *                 property="password",
     *                 description="Password of the new user",
     *                 type="string",
     *                 minLength=8,
     *                 maxLength=255
     *             ),
     *             @OA\Property(
     *                 property="firstname",
     *                 description="First name of the new user",
     *                 type="string",
     *                 minLength=2,
     *                 maxLength=45
     *             ),
     *             @OA\Property(
     *                 property="lastname",
     *                 description="Last name of the new user",
     *                 type="string",
     *                 minLength=2,
     *                 maxLength=45
     *             ),
     *         )
     *     )
     * )
     *
     * @OA\Response(
     *     response=201,
     *     description="Returns the created user (without its password).",
     *     @Model(type=User::class, groups={"get"})
     * )
     * 
     * @OA\Response(
     *     response=403,
     *     description="Forbidden. The email 'email' already exists."
     * )
     *
     * @OA\Response(
     *     response=400,
     *     description="A Token was not found in the TokenStorage. || Syntax Error. || The data name 'property' is not valid. || The data name 'property' is missing."
     * )
     *
     * @OA\Response(
     *     response=401,
     *     description="Invalid JWT Token. || Expired JWT Token."
     * )
     *
     * @Security(name="Bearer")
     *
     * @OA\Tag(name="Users")
     *
     * @param Request                      $request
     * @param UserModifyServiceInterface $userService
     *
     * @return JsonResponse
     */
    public function post(Request $request, UserModifyServiceInterface $userService): JsonResponse
    {
        return $userService->processPostUserByClient($this->getUser(), $request);
    }

    /**
     * put - To edit a user identified by its uuid, and belonging to the authenticated client.
     *
     * @Route(
     *     path="/{uuid}",
     *     name="collection_put",
     *     methods={"PUT"}
     * )
     *
     * @OA\Parameter(
     *     name="uuid",
     *     in="path",
     *     description="The unique identifier of the user.",
     *     @OA\Schema(type="string", maxLength=36, minLength=36)
     * )
     *
     * @OA\RequestBody(
     *     request="UserArray",
     *     description="List of user properties.",
     *     required=true,
     *     @OA\MediaType(
     *         mediaType="application/json",
     *         @OA\Schema(
     *             type="object",
     *             @OA\Property(
     *                 property="email",
     *                 description="Email of the new user",
     *                 type="string"
     *             ),
     *             @OA\Property(
     *                 property="password",
     *                 description="Password of the new user",
     *                 type="string",
     *                 minLength=8,
     *                 maxLength=255
     *             ),
     *             @OA\Property(
     *                 property="firstname",
     *                 description="First name of the new user",
     *                 type="string",
     *                 minLength=2,
     *                 maxLength=45
     *             ),
     *             @OA\Property(
     *                 property="lastname",
     *                 description="Last name of the new user",
     *                 type="string",
     *                 minLength=2,
     *                 maxLength=45
     *             ),
     *         )
     *     )
     * )
     *
     * @OA\Response(
     *     response=200,
     *     description="Returns the updated user (without its password).",
     *     @Model(type=User::class, groups={"get"})
     * )
     *
     * @OA\Response(
     *     response=404,
     *     description="User not found."
     * )
     *
     * @OA\Response(
     *     response=403,
     *     description="Access Denied. You can only access to your own users."
     * )
     *
     * @OA\Response(
     *     response=400,
     *     description="A Token was not found in the TokenStorage. || Syntax Error. || The data name 'property' is not valid. || The data name 'property' is missing."
     * )
     *
     * @OA\Response(
     *     response=401,
     *     description="Invalid JWT Token. || Expired JWT Token."
     * )
     *
     * @Security(name="Bearer")
     *
     * @OA\Tag(name="Users")
     *
     * @isGranted("client", subject="user", message="Access Denied. You can only access your own users.")
     *
     * @param User                         $user
     * @param Request                      $request
     * @param UserModifyServiceInterface $userService
     *
     * @return JsonResponse
     */
    public function put(
        User $user,
        Request $request,
        UserModifyServiceInterface $userService
    ): JsonResponse {
        return $userService->processPutUserByClient($this->getUser(), $user, $request);
    }

    /**
     * delete - To delete a user identified by its uuid, and belonging to the authenticated client.
     *
     * @Route(
     *     path="/{uuid}",
     *     name="collection_delete",
     *     methods={"DELETE"}
     * )
     *
     * @OA\Parameter(
     *     name="uuid",
     *     in="path",
     *     description="The unique identifier of the user.",
     *     @OA\Schema(type="string", maxLength=36, minLength=36)
     * )
     *
     * @OA\Response(
     *     response=204,
     *     description="Returns no content.",
     * )
     *
     * @OA\Response(
     *     response=404,
     *     description="User not found."
     * )
     *
     * @OA\Response(
     *     response=403,
     *     description="Access Denied. You can only access to your own users."
     * )
     *
     * @OA\Response(
     *     response=400,
     *     description="A Token was not found in the TokenStorage."
     * )
     *
     * @OA\Response(
     *     response=401,
     *     description="Invalid JWT Token. || Expired JWT Token."
     * )
     *
     * @Security(name="Bearer")
     *
     * @OA\Tag(name="Users")
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
