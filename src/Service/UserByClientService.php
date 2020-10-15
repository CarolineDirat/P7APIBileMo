<?php

namespace App\Service;

use App\Entity\Client;
use App\Entity\User;
use App\Form\AppFormFactoryInterface;
use App\Repository\UserRepository;
use App\Service\ErrorResponse\InternalServerErrorResponse;
use DateTimeImmutable;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException;
use Symfony\Component\Serializer\Encoder\DecoderInterface;
use Symfony\Component\Serializer\Normalizer\AbstractNormalizer;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class UserByClientService implements UserByClientServiceInterface
{
    const VALID_PROPERTIES = ['email', 'lastname', 'firstname', 'password'];
    const PUT_METHOD = 'PUT';
    const POST_METHOD = 'POST';

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
     * decoder.
     *
     * @var DecoderInterface
     */
    private DecoderInterface $decoder;

    /**
     * bodyRequestService.
     *
     * @var BodyRequestServiceInterface
     */
    private BodyRequestServiceInterface $bodyRequestService;

    /**
     * appFormFactory.
     *
     * @var AppFormFactoryInterface
     */
    private AppFormFactoryInterface $appFormFactory;

    /**
     * managerRegistry.
     *
     * @var ManagerRegistry
     */
    private ManagerRegistry $managerRegistry;

    /**
     * validator.
     *
     * @var ValidatorInterface
     */
    private ValidatorInterface $validator;

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
     */
    public function __construct(
        UserRepository $userRepository,
        SerializerInterface $serializer,
        PaginationServiceInterface $paginationService,
        DecoderInterface $decoder,
        BodyRequestServiceInterface $bodyRequestService,
        AppFormFactoryInterface $appFormFactory,
        ManagerRegistry $managerRegistry,
        ValidatorInterface $validator
    ) {
        $this->userRepository = $userRepository;
        $this->serializer = $serializer;
        $this->paginationService = $paginationService;
        $this->decoder = $decoder;
        $this->bodyRequestService = $bodyRequestService;
        $this->appFormFactory = $appFormFactory;
        $this->managerRegistry = $managerRegistry;
        $this->validator = $validator;
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
     * processPostUserByClient.
     *
     * @param Client  $client
     * @param Request $request
     *
     * @return JsonResponse
     */
    public function processPostUserByClient(Client $client, Request $request): JsonResponse
    {
        return $this->processUserByClient($client, $request, self::POST_METHOD);
    }

    /**
     * processPutUserByClient.
     *
     * @param Client  $client
     * @param User    $user
     * @param Request $request
     *
     * @return JsonResponse
     */
    public function processPutUserByClient(Client $client, User $user, Request $request): JsonResponse
    {
        return $this->processUserByClient($client, $request, self::PUT_METHOD, $user);
    }

    /**
     * emailIsValid
     * email property must be unique in users list linked by a client
     * else throw forbidden error (403).
     *
     * @param Client $client
     * @param User   $user
     *
     * @throws AccessDeniedHttpException
     */
    public function emailPostIsValid(Client $client, User $user): void
    {
        $users = $this->userRepository->findBy(['client' => $client]);
        foreach ($users as $value) {
            if ($value->getEmail() === $user->getEmail()) {
                throw new AccessDeniedHttpException(
                    'Forbidden. The email <'.$user->getEmail().'> already exists.',
                    null,
                    JsonResponse::HTTP_FORBIDDEN
                );
            }
        }
    }

    /**
     * emailPutIsValid
     * email property must be unique in users list linked by a client
     * else throw forbidden error (403).
     *
     * @param Client $client
     * @param User   $user
     *
     * @throws AccessDeniedHttpException
     */
    public function emailPutIsValid(Client $client, User $user): void
    {
        $users = $this->userRepository->findBy(['client' => $client]);
        foreach ($users as $value) {
            if (
                $value->getEmail() === $user->getEmail() &&
                $value->getId() !== $user->getId()
            ) {
                throw new AccessDeniedHttpException(
                    'Forbidden. The email <'.$user->getEmail().'> already exists.',
                    null,
                    JsonResponse::HTTP_FORBIDDEN
                );
            }
        }
    }

    /**
     * processUserByClient.
     *
     * @param Client    $client
     * @param Request   $request
     * @param string    $method
     * @param null|User $user
     *
     * @return JsonResponse
     */
    public function processUserByClient(Client $client, Request $request, string $method, ?User $user = null): JsonResponse
    {
        // check data body
        $data = $this->decoder->decode($request->getContent(), 'json');
        $validProperties = self::VALID_PROPERTIES;
        if (!$this->bodyRequestService->isValid($data, $validProperties)) {
            return $this->bodyRequestService->getBadRequestError()->returnErrorJsonResponse();
        }

        // deserialize data body
        $user = ('POST' === $method) ?
            $this->serializer->deserialize($request->getContent(), User::class, 'json') :
            $this->serializer->deserialize($request->getContent(), User::class, 'json', [AbstractNormalizer::OBJECT_TO_POPULATE => $user]);

        $form = $this->appFormFactory->create('user', $user, ['csrf_protection' => false]);
        $form->submit($user);

        if (!($user instanceof User)) {
            $internalServerError = new InternalServerErrorResponse($this->serializer);
            $internalServerError->addBodyValue('message', 'Internal Server Error. You can join us by email : bilemo@email.com');

            return $internalServerError->returnErrorJsonResponse();
        }

        $errors = $this->validator->validate($user);
        if (count($errors) > 0) {
            return new JsonResponse(
                $this->serializer->serialize($errors, 'json'),
                JsonResponse::HTTP_BAD_REQUEST,
                [],
                true
            );
        }

        if ('PUT' === $method) {
            $this->emailPutIsValid($client, $user);
            $user->setUpdatedAt(new DateTimeImmutable());
        }

        $em = $this->managerRegistry->getManager();
        $user->setPassword(password_hash($user->getPassword(), PASSWORD_BCRYPT));

        if ('POST' === $method) {
            $this->emailPostIsValid($client, $user);
            $user->setClient($client);
            $em->persist($user);
        }
        $em->flush();

        return new JsonResponse(
            $this->serializer->serialize($user, 'json', ['groups' => 'get']),
            JsonResponse::HTTP_OK,
            [],
            true
        );
    }
    
    /**
     * processDeleteUserByClient
     * Delete a user linked by a client
     *
     * @param User $user
     * 
     * @return JsonResponse
     */
    public function processDeleteUserByClient(User $user): JsonResponse
    {
        $em = $this->managerRegistry->getManager();
        $em->remove($user);
        $em->flush();
        
        return new JsonResponse(
            null,
            JsonResponse::HTTP_NO_CONTENT
        );
    }
}
