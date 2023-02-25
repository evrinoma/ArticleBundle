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
use Evrinoma\ArticleBundle\Dto\ClassifierApiDtoInterface;
use Evrinoma\ArticleBundle\Exception\Classifier\ClassifierCannotBeSavedException;
use Evrinoma\ArticleBundle\Exception\Classifier\ClassifierInvalidException;
use Evrinoma\ArticleBundle\Exception\Classifier\ClassifierNotFoundException;
use Evrinoma\ArticleBundle\Facade\Classifier\FacadeInterface;
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

final class ClassifierApiController extends AbstractWrappedApiController implements ApiControllerInterface
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
     * @Rest\Post("/api/article/classifier/create", options={"expose": true}, name="api_article_classifier_create")
     * @OA\Post(
     *     tags={"article"},
     *     description="the method perform create article classifier",
     *     @OA\RequestBody(
     *         @OA\MediaType(
     *             mediaType="application/json",
     *             @OA\Schema(
     *                 example={
     *                     "class": "Evrinoma\ArticleBundle\Dto\ClassifierApiDtoo",
     *                     "active": "b",
     *                     "brief": "publicist",
     *                     "description": "Публикации",
     *                 },
     *                 type="object",
     *                 @OA\Property(property="class", type="string", default="Evrinoma\ArticleBundle\Dto\ClassifierApiDto"),
     *                 @OA\Property(property="description", type="string"),
     *                 @OA\Property(property="brief", type="string"),
     *                 @OA\Property(property="active", type="string"),
     *             )
     *         )
     *     )
     * )
     * @OA\Response(response=200, description="Create article classifier")
     *
     * @return JsonResponse
     */
    public function postAction(): JsonResponse
    {
        /** @var ClassifierApiDtoInterface $articleApiDto */
        $articleApiDto = $this->factoryDto->setRequest($this->request)->createDto($this->dtoClass);

        $this->setStatusCreated();

        $json = [];
        $error = [];
        $group = GroupInterface::API_POST_ARTICLE_CLASSIFIER;

        try {
            $this->facade->post($articleApiDto, $group, $json);
        } catch (\Exception $e) {
            $json = [];
            $error = $this->setRestStatus($e);
        }

        return $this->setSerializeGroup($group)->JsonResponse('Create article classifier', $json, $error);
    }

    /**
     * @Rest\Put("/api/article/classifier/save", options={"expose": true}, name="api_article_classifier_save")
     * @OA\Put(
     *     tags={"article"},
     *     description="the method perform save article classifier for current entity",
     *     @OA\RequestBody(
     *         @OA\MediaType(
     *             mediaType="application/json",
     *             @OA\Schema(
     *                 example={
     *                     "class": "Evrinoma\ArticleBundle\Dto\ClassifierApiDtoo",
     *                     "id": "48",
     *                     "active": "b",
     *                     "brief": "publicist",
     *                     "description": "Публикации",
     *                 },
     *                 type="object",
     *                 @OA\Property(property="class", type="string", default="Evrinoma\ArticleBundle\Dto\ClassifierApiDto"),
     *                 @OA\Property(property="id", type="string"),
     *                 @OA\Property(property="description", type="string"),
     *                 @OA\Property(property="brief", type="string"),
     *                 @OA\Property(property="active", type="string"),
     *             )
     *         )
     *     )
     * )
     * @OA\Response(response=200, description="Save article classifier")
     *
     * @return JsonResponse
     */
    public function putAction(): JsonResponse
    {
        /** @var ClassifierApiDtoInterface $articleApiDto */
        $articleApiDto = $this->factoryDto->setRequest($this->request)->createDto($this->dtoClass);

        $json = [];
        $error = [];
        $group = GroupInterface::API_PUT_ARTICLE_CLASSIFIER;

        try {
            $this->facade->put($articleApiDto, $group, $json);
        } catch (\Exception $e) {
            $json = [];
            $error = $this->setRestStatus($e);
        }

        return $this->setSerializeGroup($group)->JsonResponse('Save article classifier', $json, $error);
    }

    /**
     * @Rest\Delete("/api/article/classifier/delete", options={"expose": true}, name="api_article_classifier_delete")
     * @OA\Delete(
     *     tags={"article"},
     *     @OA\Parameter(
     *         description="class",
     *         in="query",
     *         name="class",
     *         required=true,
     *         @OA\Schema(
     *             type="string",
     *             default="Evrinoma\ArticleBundle\Dto\ClassifierApiDto",
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
     * @OA\Response(response=200, description="Delete article classifier")
     *
     * @return JsonResponse
     */
    public function deleteAction(): JsonResponse
    {
        /** @var ClassifierApiDtoInterface $articleApiDto */
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

        return $this->JsonResponse('Delete article classifier', $json, $error);
    }

    /**
     * @Rest\Get("/api/article/classifier/criteria", options={"expose": true}, name="api_article_classifier_criteria")
     * @OA\Get(
     *     tags={"article"},
     *     @OA\Parameter(
     *         description="class",
     *         in="query",
     *         name="class",
     *         required=true,
     *         @OA\Schema(
     *             type="string",
     *             default="Evrinoma\ArticleBundle\Dto\ClassifierApiDto",
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
     * @OA\Response(response=200, description="Return article classifier")
     *
     * @return JsonResponse
     */
    public function criteriaAction(): JsonResponse
    {
        /** @var ClassifierApiDtoInterface $articleApiDto */
        $articleApiDto = $this->factoryDto->setRequest($this->request)->createDto($this->dtoClass);

        $json = [];
        $error = [];
        $group = GroupInterface::API_CRITERIA_ARTICLE_CLASSIFIER;

        try {
            $this->facade->criteria($articleApiDto, $group, $json);
        } catch (\Exception $e) {
            $json = [];
            $error = $this->setRestStatus($e);
        }

        return $this->setSerializeGroup($group)->JsonResponse('Get article classifier', $json, $error);
    }

    /**
     * @Rest\Get("/api/article/classifier", options={"expose": true}, name="api_article_classifier")
     * @OA\Get(
     *     tags={"article"},
     *     @OA\Parameter(
     *         description="class",
     *         in="query",
     *         name="class",
     *         required=true,
     *         @OA\Schema(
     *             type="string",
     *             default="Evrinoma\ArticleBundle\Dto\ClassifierApiDto",
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
     * @OA\Response(response=200, description="Return article classifier")
     *
     * @return JsonResponse
     */
    public function getAction(): JsonResponse
    {
        /** @var ClassifierApiDtoInterface $articleApiDto */
        $articleApiDto = $this->factoryDto->setRequest($this->request)->createDto($this->dtoClass);

        $json = [];
        $error = [];
        $group = GroupInterface::API_GET_ARTICLE_CLASSIFIER;

        try {
            $this->facade->get($articleApiDto, $group, $json);
        } catch (\Exception $e) {
            $json = [];
            $error = $this->setRestStatus($e);
        }

        return $this->setSerializeGroup($group)->JsonResponse('Get article classifier', $json, $error);
    }

    /**
     * @param \Exception $e
     *
     * @return array
     */
    public function setRestStatus(\Exception $e): array
    {
        switch (true) {
            case $e instanceof ClassifierCannotBeSavedException:
                $this->setStatusNotImplemented();
                break;
            case $e instanceof UniqueConstraintViolationException:
                $this->setStatusConflict();
                break;
            case $e instanceof ClassifierNotFoundException:
                $this->setStatusNotFound();
                break;
            case $e instanceof ClassifierInvalidException:
                $this->setStatusUnprocessableEntity();
                break;
            default:
                $this->setStatusBadRequest();
        }

        return [$e->getMessage()];
    }
}
