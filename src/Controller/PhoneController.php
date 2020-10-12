<?php

namespace App\Controller;

use App\Entity\Phone;
use App\Repository\PhoneRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\SerializerInterface;

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
     * @param Phone               $phone
     * @param SerializerInterface $serializer
     *
     * @return JsonResponse
     */
    public function item(Phone $phone, SerializerInterface $serializer): JsonResponse
    {
        $phone = $serializer->serialize(
            $phone,
            'json',
            [
                'circular_reference_handler' => function (object $object) {
                    return $object->getId();
                },
            ]
        );

        // after circular reference handling, there are some useless elements in linked objects
        $phone = json_decode($phone, true);
        foreach ($phone as $key => $value) {
            if (is_array($value)) {
                // delete useless id key (always first property)
                array_shift($value);
                // delete 4 last useless keys : "phone", "__initializer__", "__cloner__", "__isInitialized__"
                array_splice($value, -4);
                $phone[$key] = $value;
            }
        }
        $phone = json_encode($phone);

        return new JsonResponse($phone, 200, [], true);
    }

    /**
     * collection.
     *
     * @Route(
     *     "/",
     *     name="collection_get",
     *     methods={"GET"},
     *     stateless=true
     * )
     *
     * @return JsonResponse
     */
    public function collection(PhoneRepository $phoneRepository, SerializerInterface $serializer): JsonResponse
    {
        return new JsonResponse(
            $serializer->serialize(
                $phoneRepository->findAll(),
                'json',
                ['groups' => 'collection']
            ),
            Response::HTTP_OK,
            [],
            true
        );
    }
}
