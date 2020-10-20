<?php

namespace App\Controller;

use App\Entity\Phone;
use App\Service\PhoneService;
use App\Service\PhoneServiceInterface;
use Nelmio\ApiDocBundle\Annotation\Model;
use Nelmio\ApiDocBundle\Annotation\Security;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use OpenApi\Annotations as OA;

/**
 * PhoneController.
 *
 * @Route("/phones", name="phones_")
 */
class PhoneController extends AbstractController
{
    /**
     * item : get a phone by its uuid
     *
     * @Route(
     *     "/{uuid}",
     *     name="item_get",
     *     methods={"GET"},
     *     stateless=true
     * )
     * 
     * @OA\Parameter(
     *     name="uuid",
     *     in="path",
     *     description="The unique identifier of the phone.",
     *     @OA\Schema(type="string", maxLength=36, minLength=36)
     * )
     * 
     * @OA\Response(
     *     response=200,
     *     description="Returns a phone by its uuid",
     *     @Model(type=Phone::class, groups={"get_phone"})
     * )
     * 
     * @OA\Response(
     *     response=404,
     *     description="Phone not found"
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
     * @OA\Tag(name="Phones")
     *
     * @param Phone        $phone
     * @param PhoneService $phoneService
     *
     * @return JsonResponse
     */
    public function item(Phone $phone, PhoneService $phoneService): JsonResponse
    {
        return new JsonResponse(
            $phoneService->getSerializedPhone($phone),
            JsonResponse::HTTP_PARTIAL_CONTENT,
            [],
            true
        );
    }

    /**
     * collection : get a page of paginated phones
     *
     * @Route(
     *     "/",
     *     name="collection_get",
     *     methods={"GET"},
     *     stateless=true
     * )
     * 
     * @OA\Parameter(
     *     name="page",
     *     in="query",
     *     description="The page number of paginated phones. ",
     *     @OA\Schema(type="integer", default=1, minimum=1)
     * )
     * @OA\Parameter(
     *     name="limit",
     *     in="query",
     *     description="The number of phones per page. Its default value and its maximum value come from constants.ini file: [phones]['number_per_page']=5 and [phones]['limi_max']=100. ",
     *     @OA\Schema(type="integer", default=5, maximum=100, minimum=1)
     * )
     * 
     * @OA\Response(
     *     response=206,
     *     description="Returns the page n°'page' of 'limit' phones.",
     *     @OA\JsonContent(
     *        type="array",
     *        maxItems=100,
     *        @OA\Items(ref=@Model(type=Phone::class, groups={"collection"}))
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
     * @OA\Tag(name="Phones")
     *
     * @param PhoneServiceInterface $phoneService
     * @param Request               $request
     *
     * @return JsonResponse
     */
    public function collection(PhoneServiceInterface $phoneService, Request $request): JsonResponse
    {
        return new JsonResponse(
            $phoneService->getSerializedPaginatedPhones($request),
            JsonResponse::HTTP_OK,
            [],
            true
        );
    }
}
