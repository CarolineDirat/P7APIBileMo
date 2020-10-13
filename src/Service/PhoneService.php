<?php

namespace App\Service;

use App\Entity\Phone;
use App\Repository\PhoneRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
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
     * @param PhoneRepository     $phoneRepository
     * @param SerializerInterface $serializer
     * @param ConstantsIni        $constantsIni
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
        $phone = $this->serializer->serialize(
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

        return json_encode($phone);
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

        $pages = ceil($phones->count() / $limit);

        if ($page > $pages) {
            throw new NotFoundHttpException('The asked page n°'.$page." doesn't exist. The maximum number of pages is ".$pages.'.', null, Response::HTTP_BAD_REQUEST);
        }

        $result['data'] = $phones;
        $result['meta'] = ['current_page' => $page, 'number_per_page' => $limit, 'total_pages' => $pages];

        return $this->serializer->serialize(
            $result,
            'json',
            ['groups' => 'collection']
        );
    }
}
