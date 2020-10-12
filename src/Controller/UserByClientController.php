<?php

namespace App\Controller;

use App\Entity\Client;
use App\Repository\UserRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\SerializerInterface;

/**
 * UserByClientController
 * 
 * @Route("/clients/{uuid}/users", name = "users_by_client_")
 */
class UserByClientController extends AbstractController
{
    /**
     * @Route(
     *  "/",
     *  name="collection_get",
     *  methods={"GET"},
     *  stateless=true
     * )
     */
    public function collection(Client $client, UserRepository $userRepository, SerializerInterface $serializer) : JsonResponse
    {
        $users = $userRepository->findBy(['client' => $client]);
        return new JsonResponse(
            $serializer->serialize(
                $users,
                'json',
                ['groups' => 'collection']
            ),
            Response::HTTP_OK,
            [],
            true
        );
    }
}
