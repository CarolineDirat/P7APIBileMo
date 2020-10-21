<?php

namespace App\Service;

use App\Entity\Phone;
use App\Repository\PhoneRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Serializer\SerializerInterface;

class PhoneService implements PhoneServiceInterface
{
    /**
     * phoneRepository.
     *
     * @var PhoneRepository
     */
    private PhoneRepository $phoneRepository;

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
     * @param PhoneRepository            $phoneRepository
     * @param SerializerInterface        $serializer
     * @param PaginationServiceInterface $paginationService
     */
    public function __construct(
        PhoneRepository $phoneRepository,
        SerializerInterface $serializer,
        PaginationServiceInterface $paginationService
    ) {
        $this->phoneRepository = $phoneRepository;
        $this->serializer = $serializer;
        $this->paginationService = $paginationService;
    }

    /**
     * getSerializedPhone.
     *
     * @see PhoneServiceInterface
     *
     * @param Phone $phone
     *
     * @return string
     */
    public function getSerializedPhone(Phone $phone): string
    {
        $phone->setScreen($phone->getScreen());
        $phone->setSize($phone->getSize());
        
        $phone = $this->serializer->serialize(
            $phone,
            'json',
            ['groups' => 'get_phone']
        );

        return $phone;
    }

    /**
     * getSerializedPaginatedPhones.
     *
     * @see PhoneServiceInterface
     *
     * @param Request $request it can contain as parameters the 'page' and the number of phones per pages ('limit')
     *
     * @return string
     */
    public function getSerializedPaginatedPhones(Request $request): string
    {
        $params = $this->paginationService->getQueryParameters($request, 'phones');
        $page = $params['page'];
        $limit = $params['limit'];

        $phones = $this->phoneRepository->getPaginatedPhones($page, $limit);

        return $this->paginationService->getSerializedPaginatedData($phones, $page, $limit, ['groups' => 'collection']);
    }
}
