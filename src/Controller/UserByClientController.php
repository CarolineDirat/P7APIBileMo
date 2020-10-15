<?php

namespace App\Controller;

use App\Entity\Client;
use App\Entity\User;
use App\Form\AppFormFactoryInterface;
use App\Repository\UserRepository;
use App\Service\BodyRequestServiceInterface;
use App\Service\ErrorResponse\ForbiddenErrorResponse;
use App\Service\ErrorResponse\InternalServerErrorResponse;
use App\Service\UserByClientService;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\Encoder\DecoderInterface;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;

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
            Response::HTTP_OK,
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
            Response::HTTP_OK,
            [],
            true
        );
    }

        
    /**
     * post
     * To add a user linked by a client
     * 
     * @Route(
     *      path="",
     *      name="collection_post",
     *      methods={"POST"},
     * )
     *
     * @param Client $client
     * 
     * @return JsonResponse
     */
    public function post(
        Client $client,
        Request $request,
        AppFormFactoryInterface $appFormFactory,
        SerializerInterface $serializer,
        ValidatorInterface $validator,
        DecoderInterface $decoder,
        BodyRequestServiceInterface $bodyRequestService,
        UserRepository $userRepository
    ): JsonResponse {
        // check data body
        $data =  $decoder->decode($request->getContent(), 'json');
        $validProperties = ['email', 'lastname', 'firstname', 'password'];
        if (!$bodyRequestService->isValid($data, $validProperties)) {
            return $bodyRequestService->getBadRequestError()->returnErrorJsonResponse();
        }

        // process data body
        $user = $serializer->deserialize($request->getContent(), User::class, 'json');

        $form = $appFormFactory->create('post-user', $user, ['csrf_protection' => false]);
        $form->submit($user);

        if (!($user instanceof User)) {
            $internalServerError = new InternalServerErrorResponse($serializer);
            $internalServerError->addBodyValue('message', 'Internal Server Error. You can join us by email : bilemo@email.com');
            return $internalServerError->returnErrorJsonResponse();     
        }

        $errors = $validator->validate($user);

        if (count($errors) > 0) {
            /*
            * Uses a __toString method on the $errors variable which is a
            * ConstraintViolationList object. This gives us a nice string
            * for debugging.
            */
            // $errorsString = (string) $errors;
    
            return new JsonResponse(
                $serializer->serialize($errors, 'json'),
                JsonResponse::HTTP_BAD_REQUEST,
                [],
                true
            );
            
        }

        // email property must be unique in users list linked by a client
        // else return forbidden error (403)
        $users = $userRepository->findBy(['client' => $client]);
        foreach ($users as $value) {
            if ($value->getEmail() === $user->getEmail()) {
                throw new AccessDeniedHttpException(
                    'Forbidden. The email <'. $user->getEmail() .'> already exists.',
                    null,
                    JsonResponse::HTTP_FORBIDDEN
                );
            }
        }

        $user->setClient($client);
        $user->setPassword(password_hash($user->getPassword(), PASSWORD_BCRYPT));

        $em = $this->getDoctrine()->getManager();
        $em->persist($user);
        $em->flush();

        return new JsonResponse(
            $serializer->serialize($user, 'json', ['groups' => 'get']), 
            JsonResponse::HTTP_CREATED,
            [],
            true
        );
    }
}
