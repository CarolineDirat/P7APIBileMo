<?php

namespace App\Service;

use Doctrine\ORM\Tools\Pagination\Paginator;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Serializer\SerializerInterface;

class PaginationService implements PaginationServiceInterface
{
    /**
     * constantsIni.
     *
     * @var ConstantsIni
     */
    private ConstantsIni $constantsIni;

    /**
     * serializer.
     *
     * @var SerializerInterface;
     */
    private SerializerInterface $serializer;

    /**
     * __construct.
     *
     * @param ConstantsIni $constantsIni
     */
    public function __construct(
        ConstantsIni $constantsIni,
        SerializerInterface $serializer
    ) {
        $this->constantsIni = $constantsIni;
        $this->serializer = $serializer;
    }

    /**
     * getQueryParameters.
     *
     * @param Request $request
     * @param string  $entities keys from constants.ini file
     *
     * @return array<string, int>
     */
    public function getQueryParameters(Request $request, string $entities): array
    {
        $constants = $this->constantsIni->getConstantsIni();
        $page = $request->query->getInt('page', 1);
        $limit = $request->query->getInt('limit', $constants['phones']['number_per_page']);

        if (empty($page) || empty($limit)) {
            throw new BadRequestHttpException("The query parameters 'page' and 'limit' must be integers and not null.");
        }

        $max = $constants[$entities]['limit_max'];
        if ($limit > $max) {
            $limit = $max;
        }

        return ['page' => $page, 'limit' => $limit];
    }

    /**
     * getSerializedPaginatedData.
     *
     * @param Paginator<object>    $data
     * @param int                  $page
     * @param int                  $limit
     * @param array<string, mixed> $context Options normalizer/encoders have to access
     *
     * @return string
     */
    public function getSerializedPaginatedData(Paginator $data, int $page, int $limit, array $context): string
    {
        $pages = ceil($data->count() / $limit);

        if ($page > $pages) {
            throw new NotFoundHttpException(
                'The asked page n°'.$page." doesn't exist. The maximum number of pages is ".$pages.'.',
                null,
                Response::HTTP_BAD_REQUEST
            );
        }

        $result['data'] = $data;
        $result['meta'] = ['current_page' => $page, 'number_per_page' => $limit, 'total_pages' => $pages];

        return $this->serializer->serialize(
            $result,
            'json',
            $context
        );
    }
}
