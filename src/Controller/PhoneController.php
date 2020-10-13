<?php

namespace App\Controller;

use App\Entity\Phone;
use App\Service\PhoneService;
use App\Service\PhoneServiceInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * PhoneController.
 *
 * @Route("/phones", name="phones_")
 */
class PhoneController extends AbstractController
{
    /**
     * item.
     *
     * @Route(
     *     "/{uuid}",
     *     name="item_get",
     *     methods={"GET"},
     *     stateless=true
     * )
     *
     * @param Phone $phone
     * @param PhoneService $phoneService
     *
     * @return JsonResponse
     */
    public function item(Phone $phone, PhoneService $phoneService): JsonResponse
    {
        return new JsonResponse(
            $phoneService->getSerializedPhone($phone),
            Response::HTTP_OK,
            [],
            true
        );
    }

    /**
     * collection.
     * Get a page of paginated phones.
     *
     * @Route(
     *     "/",
     *     name="collection_get",
     *     methods={"GET"},
     *     stateless=true
     * )
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
            Response::HTTP_OK,
            [],
            true
        );
    }
}
