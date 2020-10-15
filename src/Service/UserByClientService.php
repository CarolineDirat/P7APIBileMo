<?php

namespace App\Service;

use App\Entity\Client;
use App\Repository\UserRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Serializer\SerializerInterface;

class UserByClientService implements UserByClientServiceInterface
{
    /**
     * phoneRepository.
     *
     * @var UserRepository
     */
    private UserRepository $userRepository;

    /**
     * serializer.
     *
     * @var SerializerInterface;
     */
    private SerializerInterface $serializer;

    /**
     * constantsIni.
     *
     * @var PaginationServiceInterface
     */
    private PaginationServiceInterface $paginationService;

    /**
     * __construct.
     *
     * @param UserRepository             $userRepository,
     * @param SerializerInterface        $serializer
     * @param PaginationServiceInterface $paginationService
     */
    public function __construct(
        UserRepository $userRepository,
        SerializerInterface $serializer,
        PaginationServiceInterface $paginationService
    ) {
        $this->userRepository = $userRepository;
        $this->serializer = $serializer;
        $this->paginationService = $paginationService;
    }

    /**
     * getSerializedPaginatedUsersByClient.
     *
     * @see UserServiceInterface
     *
     * @param Client  $client
     * @param Request $request
     *
     * @return string
     */
    public function getSerializedPaginatedUsersByClient(Client $client, Request $request): string
    {
        $params = $this->paginationService->getQueryParameters($request, 'users');
        $page = $params['page'];
        $limit = $params['limit'];

        $users = $this->userRepository->getPaginatedUsersByClient($client, $page, $limit);

        return $this->paginationService->getSerializedPaginatedData($users, $page, $limit, ['groups' => 'get']);
    }
}
