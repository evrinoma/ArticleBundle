<?php

declare(strict_types=1);

/*
 * This file is part of the package.
 *
 * (c) Nikolay Nikolaev <evrinoma@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Evrinoma\ArticleBundle\Controller;

use Doctrine\DBAL\Exception\UniqueConstraintViolationException;
use Evrinoma\ArticleBundle\Dto\TypeApiDtoInterface;
use Evrinoma\ArticleBundle\Exception\Type\TypeCannotBeSavedException;
use Evrinoma\ArticleBundle\Exception\Type\TypeInvalidException;
use Evrinoma\ArticleBundle\Exception\Type\TypeNotFoundException;
use Evrinoma\ArticleBundle\Facade\Type\FacadeInterface;
use Evrinoma\ArticleBundle\Serializer\GroupInterface;
use Evrinoma\DtoBundle\Factory\FactoryDtoInterface;
use Evrinoma\UtilsBundle\Controller\AbstractWrappedApiController;
use Evrinoma\UtilsBundle\Controller\ApiControllerInterface;
use FOS\RestBundle\Controller\Annotations as Rest;
use JMS\Serializer\SerializerInterface;
use OpenApi\Annotations as OA;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\RequestStack;

final class TypeApiController extends AbstractWrappedApiController implements ApiControllerInterface
{
    private string $dtoClass;

    private ?Request $request;

    private FactoryDtoInterface $factoryDto;

    private FacadeInterface $facade;

    public function __construct(
        SerializerInterface $serializer,
        RequestStack $requestStack,
        FactoryDtoInterface $factoryDto,
        FacadeInterface $facade,
        string $dtoClass
    ) {
        parent::__construct($serializer);
        $this->request = $requestStack->getCurrentRequest();
        $this->factoryDto = $factoryDto;
        $this->dtoClass = $dtoClass;
        $this->facade = $facade;
    }

    /**
     * @Rest\Post("/api/article/type/create", options={"expose": true}, name="api_article_type_create")
     * @OA\Post(
     *     tags={"article"},
     *     description="the method perform create article type",
     *     @OA\RequestBody(
     *         @OA\MediaType(
     *             mediaType="application/json",
     *             @OA\Schema(
     *                 example={
     *                     "class": "Evrinoma\ArticleBundle\Dto\TypeApiDtoo",
     *                     "brief": "news",
     *                     "description": "новости",
     *                 },
     *                 type="object",
     *                 @OA\Property(property="class", type="string", default="Evrinoma\ArticleBundle\Dto\TypeApiDto"),
     *                 @OA\Property(property="description", type="string"),
     *                 @OA\Property(property="brief", type="string"),
     *             )
     *         )
     *     )
     * )
     * @OA\Response(response=200, description="Create article type")
     *
     * @return JsonResponse
     */
    public function postAction(): JsonResponse
    {
        /** @var TypeApiDtoInterface $articleApiDto */
        $articleApiDto = $this->factoryDto->setRequest($this->request)->createDto($this->dtoClass);

        $this->setStatusCreated();

        $json = [];
        $error = [];
        $group = GroupInterface::API_POST_ARTICLE_TYPE;

        try {
            $this->facade->post($articleApiDto, $group, $json);
        } catch (\Exception $e) {
            $json = [];
            $error = $this->setRestStatus($e);
        }

        return $this->setSerializeGroup($group)->JsonResponse('Create article type', $json, $error);
    }

    /**
     * @Rest\Put("/api/article/type/save", options={"expose": true}, name="api_article_type_save")
     * @OA\Put(
     *     tags={"article"},
     *     description="the method perform save article type for current entity",
     *     @OA\RequestBody(
     *         @OA\MediaType(
     *             mediaType="application/json",
     *             @OA\Schema(
     *                 example={
     *                     "class": "Evrinoma\ArticleBundle\Dto\TypeApiDto",
     *                     "id": "48",
     *                     "active": "b",
     *                     "brief": "news",
     *                     "description": "новости",
     *                 },
     *                 type="object",
     *                 @OA\Property(property="class", type="string", default="Evrinoma\ArticleBundle\Dto\TypeApiDto"),
     *                 @OA\Property(property="id", type="string"),
     *                 @OA\Property(property="description", type="string"),
     *                 @OA\Property(property="brief", type="string"),
     *                 @OA\Property(property="active", type="string"),
     *             )
     *         )
     *     )
     * )
     * @OA\Response(response=200, description="Save article type")
     *
     * @return JsonResponse
     */
    public function putAction(): JsonResponse
    {
        /** @var TypeApiDtoInterface $articleApiDto */
        $articleApiDto = $this->factoryDto->setRequest($this->request)->createDto($this->dtoClass);

        $json = [];
        $error = [];
        $group = GroupInterface::API_PUT_ARTICLE_TYPE;

        try {
            $this->facade->put($articleApiDto, $group, $json);
        } catch (\Exception $e) {
            $json = [];
            $error = $this->setRestStatus($e);
        }

        return $this->setSerializeGroup($group)->JsonResponse('Save article type', $json, $error);
    }

    /**
     * @Rest\Delete("/api/article/type/delete", options={"expose": true}, name="api_article_type_delete")
     * @OA\Delete(
     *     tags={"article"},
     *     @OA\Parameter(
     *         description="class",
     *         in="query",
     *         name="class",
     *         required=true,
     *         @OA\Schema(
     *             type="string",
     *             default="Evrinoma\ArticleBundle\Dto\TypeApiDto",
     *             readOnly=true
     *         )
     *     ),
     *     @OA\Parameter(
     *         description="id Entity",
     *         in="query",
     *         name="id",
     *         required=true,
     *         @OA\Schema(
     *             type="string",
     *             default="3",
     *         )
     *     )
     * )
     * @OA\Response(response=200, description="Delete article type")
     *
     * @return JsonResponse
     */
    public function deleteAction(): JsonResponse
    {
        /** @var TypeApiDtoInterface $articleApiDto */
        $articleApiDto = $this->factoryDto->setRequest($this->request)->createDto($this->dtoClass);

        $this->setStatusAccepted();

        $json = [];
        $error = [];

        try {
            $this->facade->delete($articleApiDto, '', $json);
        } catch (\Exception $e) {
            $json = [];
            $error = $this->setRestStatus($e);
        }

        return $this->JsonResponse('Delete article type', $json, $error);
    }

    /**
     * @Rest\Get("/api/article/type/criteria", options={"expose": true}, name="api_article_type_criteria")
     * @OA\Get(
     *     tags={"article"},
     *     @OA\Parameter(
     *         description="class",
     *         in="query",
     *         name="class",
     *         required=true,
     *         @OA\Schema(
     *             type="string",
     *             default="Evrinoma\ArticleBundle\Dto\TypeApiDto",
     *             readOnly=true
     *         )
     *     ),
     *     @OA\Parameter(
     *         description="id Entity",
     *         in="query",
     *         name="id",
     *         @OA\Schema(
     *             type="string",
     *         )
     *     ),
     *     @OA\Parameter(
     *         description="active",
     *         in="query",
     *         name="active",
     *         @OA\Schema(
     *             type="string",
     *         )
     *     ),
     *     @OA\Parameter(
     *         description="description",
     *         in="query",
     *         name="description",
     *         @OA\Schema(
     *             type="string",
     *         )
     *     ),
     *     @OA\Parameter(
     *         description="brief",
     *         in="query",
     *         name="brief",
     *         @OA\Schema(
     *             type="string",
     *         )
     *     )
     * )
     * @OA\Response(response=200, description="Return article type")
     *
     * @return JsonResponse
     */
    public function criteriaAction(): JsonResponse
    {
        /** @var TypeApiDtoInterface $articleApiDto */
        $articleApiDto = $this->factoryDto->setRequest($this->request)->createDto($this->dtoClass);

        $json = [];
        $error = [];
        $group = GroupInterface::API_CRITERIA_ARTICLE_TYPE;

        try {
            $this->facade->criteria($articleApiDto, $group, $json);
        } catch (\Exception $e) {
            $json = [];
            $error = $this->setRestStatus($e);
        }

        return $this->setSerializeGroup($group)->JsonResponse('Get article type', $json, $error);
    }

    /**
     * @Rest\Get("/api/article/type", options={"expose": true}, name="api_article_type")
     * @OA\Get(
     *     tags={"article"},
     *     @OA\Parameter(
     *         description="class",
     *         in="query",
     *         name="class",
     *         required=true,
     *         @OA\Schema(
     *             type="string",
     *             default="Evrinoma\ArticleBundle\Dto\TypeApiDto",
     *             readOnly=true
     *         )
     *     ),
     *     @OA\Parameter(
     *         description="id Entity",
     *         in="query",
     *         name="id",
     *         required=true,
     *         @OA\Schema(
     *             type="string",
     *             default="3",
     *         )
     *     )
     * )
     * @OA\Response(response=200, description="Return article type")
     *
     * @return JsonResponse
     */
    public function getAction(): JsonResponse
    {
        /** @var TypeApiDtoInterface $articleApiDto */
        $articleApiDto = $this->factoryDto->setRequest($this->request)->createDto($this->dtoClass);

        $json = [];
        $error = [];
        $group = GroupInterface::API_GET_ARTICLE_TYPE;

        try {
            $this->facade->get($articleApiDto, $group, $json);
        } catch (\Exception $e) {
            $json = [];
            $error = $this->setRestStatus($e);
        }

        return $this->setSerializeGroup($group)->JsonResponse('Get article type', $json, $error);
    }

    /**
     * @param \Exception $e
     *
     * @return array
     */
    public function setRestStatus(\Exception $e): array
    {
        switch (true) {
            case $e instanceof TypeCannotBeSavedException:
                $this->setStatusNotImplemented();
                break;
            case $e instanceof UniqueConstraintViolationException:
                $this->setStatusConflict();
                break;
            case $e instanceof TypeNotFoundException:
                $this->setStatusNotFound();
                break;
            case $e instanceof TypeInvalidException:
                $this->setStatusUnprocessableEntity();
                break;
            default:
                $this->setStatusBadRequest();
        }

        return [$e->getMessage()];
    }
}
