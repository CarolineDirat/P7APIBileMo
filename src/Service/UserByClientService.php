<?php

namespace App\Service;

use App\Entity\Client;
use App\Entity\User;
use App\Form\AppFormFactoryInterface;
use App\Repository\UserRepository;
use App\Service\ErrorResponse\InternalServerErrorResponse;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException;
use Symfony\Component\Serializer\Encoder\DecoderInterface;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class UserByClientService implements UserByClientServiceInterface
{
    const POST_VALID_PROPERTIES = ['email', 'lastname', 'firstname', 'password'];

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
     * decoder
     *
     * @var DecoderInterface
     */
    private DecoderInterface $decoder;
    
    /**
     * bodyRequestService
     *
     * @var BodyRequestServiceInterface
     */
    private BodyRequestServiceInterface $bodyRequestService;
    
    /**
     * appFormFactory
     *
     * @var AppFormFactoryInterface
     */
    private AppFormFactoryInterface $appFormFactory;
    
    /**
     * managerRegistry
     *
     * @var ManagerRegistry
     */
    private ManagerRegistry $managerRegistry;

    /**
     * __construct.
     *
     * @param UserRepository              $userRepository,
     * @param SerializerInterface         $serializer
     * @param PaginationServiceInterface  $paginationService
     * @param DecoderInterface            $decoder
     * @param BodyRequestServiceInterface $bodyRequestService
     * @param AppFormFactoryInterface     $appFormFactory
     * @param ManagerRegistry             $managerRegistry
     * 
     */
    public function __construct(
        UserRepository $userRepository,
        SerializerInterface $serializer,
        PaginationServiceInterface $paginationService,
        DecoderInterface $decoder,
        BodyRequestServiceInterface $bodyRequestService,
        AppFormFactoryInterface $appFormFactory,
        ManagerRegistry $managerRegistry
    ) {
        $this->userRepository = $userRepository;
        $this->serializer = $serializer;
        $this->paginationService = $paginationService;
        $this->decoder = $decoder;
        $this->bodyRequestService = $bodyRequestService;
        $this->appFormFactory = $appFormFactory;
        $this->managerRegistry = $managerRegistry;
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
    
    /**
     * processPostUserByClient
     * 
     * @param Client $client
     * @param Request $request
     * @param ValidatorInterface $validator
     *
     * @return JsonResponse
     */
    public function processPostUserByClient(Client $client, Request $request, ValidatorInterface $validator): JsonResponse
    {
        // check data body
        $data =  $this->decoder->decode($request->getContent(), 'json');
        $validProperties = self::POST_VALID_PROPERTIES;
        if (!$this->bodyRequestService->isValid($data, $validProperties)) {
            return $this->bodyRequestService->getBadRequestError()->returnErrorJsonResponse();
        }

        // deserialize data body
        $user = $this->serializer->deserialize($request->getContent(), User::class, 'json');

        $form = $this->appFormFactory->create('post-user', $user, ['csrf_protection' => false]);
        $form->submit($user);

        if (!($user instanceof User)) {
            $internalServerError = new InternalServerErrorResponse($this->serializer);
            $internalServerError->addBodyValue('message', 'Internal Server Error. You can join us by email : bilemo@email.com');
            return $internalServerError->returnErrorJsonResponse();     
        }

        $errors = $validator->validate($user);
        if (count($errors) > 0) {
            return new JsonResponse(
                $this->serializer->serialize($errors, 'json'),
                JsonResponse::HTTP_BAD_REQUEST,
                [],
                true
            );
        }

        $this->emailIsValid($client, $user);
        $user->setClient($client);
        $user->setPassword(password_hash($user->getPassword(), PASSWORD_BCRYPT));

        $em = $this->managerRegistry->getManager();
        $em->persist($user);
        $em->flush();

        return new JsonResponse(
            $this->serializer->serialize($user, 'json', ['groups' => 'get']), 
            JsonResponse::HTTP_CREATED,
            [],
            true
        );
    }
    
    /**
     * emailIsValid
     * email property must be unique in users list linked by a client
     * else throw forbidden error (403)
     * 
     * @param Client $client
     * @param User   $user
     * 
     * @throws AccessDeniedHttpException
     */
    public function emailIsValid(Client $client, User $user): void
    {
        $users = $this->userRepository->findBy(['client' => $client]);
        foreach ($users as $value) {
            if ($value->getEmail() === $user->getEmail()) {
                throw new AccessDeniedHttpException(
                    'Forbidden. The email <'. $user->getEmail() .'> already exists.',
                    null,
                    JsonResponse::HTTP_FORBIDDEN
                );
            }
        }
    }
}
