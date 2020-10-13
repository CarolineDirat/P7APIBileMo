<?php

namespace App\Service;

use App\Entity\Phone;
use App\Repository\PhoneRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;
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
     * getSerializedPhone
     *
     * @param  Phone $phone
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
     * @return string
     */
    public function getSerializedPaginatedPhones(Request $request): string
    {
        $constants = $this->constantsIni->getConstantsIni();
        $page = $request->query->getInt('page', 1);
        $limit = $request->query->getInt('limit', $constants['phones']['number_per_page']);

        if (empty($page) || empty($limit)) {
            throw new BadRequestHttpException("The query parameters 'page' and 'limit' must be integers and not null.");
        }
        
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
