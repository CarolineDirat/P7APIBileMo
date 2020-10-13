<?php

namespace App\Service;

use App\Entity\Client;
use App\Repository\UserRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Serializer\SerializerInterface;

class UserService implements UserServiceInterface
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
     * @var ConstantsIni
     */
    private ConstantsIni $constantsIni;

    /**
     * __construct.
     *
     * @param PhoneRepository     $phoneRepository
     * @param SerializerInterface $serializer
     * @param ConstantsIni        $constantsIni
     */
    public function __construct(
        UserRepository $userRepository,
        SerializerInterface $serializer,
        ConstantsIni $constantsIni
    ) {
        $this->userRepository = $userRepository;
        $this->serializer = $serializer;
        $this->constantsIni = $constantsIni;
    }
    
    /**
     * getSerializedPaginatedUsersByClient
     * 
     * @see UserServiceInterface
     *
     * @param Client $client
     * @param Request $request
     * 
     * @return string
     */
    public function getSerializedPaginatedUsersByClient(Client $client, Request $request): string
    {
        $constants = $this->constantsIni->getConstantsIni();
        $page = $request->query->getInt('page', 1);
        $limit = $request->query->getInt('limit', $constants['users']['number_per_page']);

        if (empty($page) || empty($limit)) {
            throw new BadRequestHttpException("The query parameters 'page' and 'limit' must be integers and not null.");
        }

        $max = $constants['users']['limit_max'];
        if ($limit > $max) {
            $limit = $max;
        }

        $users = $this->userRepository->getPaginatedUsersByClient($client, $page, $limit);
        
        $pages = ceil($users->count() / $limit);

        if ($page > $pages) {
            throw new NotFoundHttpException('The asked page n°'.$page." doesn't exist. The maximum number of pages is ".$pages.'.', null, Response::HTTP_BAD_REQUEST);
        }

        $result['data'] = $users;
        $result['meta'] = ['current_page' => $page, 'number_per_page' => $limit, 'total_pages' => $pages];
        
        return $this->serializer->serialize(
            $result,
            'json',
            ['groups' => 'get']
        );
    }
}
