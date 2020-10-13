<?php

namespace App\Service;

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
        PhoneRepository $phoneRepository,
        SerializerInterface $serializer,
        ConstantsIni $constantsIni
    ) {
        $this->phoneRepository = $phoneRepository;
        $this->serializer = $serializer;
        $this->constantsIni = $constantsIni;
    }

    /**
     * getSerializedPaginatedPhones.
     *
     * @see PhoneServiceInterface
     *
     * @return string
     */
    public function getSerializedPaginatedPhones(Request $request): string
    {
        $constants = $this->constantsIni->getConstantsIni();
        $page = (int) $request->get('page', 1);
        $limit = (int) $request->get('limit', $constants['phones']['number_per_page']);

        $max = $constants['phones']['limit_max'];
        if ($limit > $max) {
            $limit = $max;
        }

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
