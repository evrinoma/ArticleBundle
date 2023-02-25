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
use Evrinoma\ArticleBundle\Dto\ArticleApiDtoInterface;
use Evrinoma\ArticleBundle\Exception\Article\ArticleCannotBeSavedException;
use Evrinoma\ArticleBundle\Exception\Article\ArticleInvalidException;
use Evrinoma\ArticleBundle\Exception\Article\ArticleNotFoundException;
use Evrinoma\ArticleBundle\Facade\Article\FacadeInterface;
use Evrinoma\ArticleBundle\Serializer\GroupInterface;
use Evrinoma\DtoBundle\Factory\FactoryDtoInterface;
use Evrinoma\UtilsBundle\Controller\AbstractWrappedApiController;
use Evrinoma\UtilsBundle\Controller\ApiControllerInterface;
use FOS\RestBundle\Controller\Annotations as Rest;
use JMS\Serializer\SerializerInterface;
use Nelmio\ApiDocBundle\Annotation\Model;
use OpenApi\Annotations as OA;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\RequestStack;

final class ArticleApiController extends AbstractWrappedApiController implements ApiControllerInterface
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
     * @Rest\Post("/api/article/create", options={"expose": true}, name="api_article_create")
     * @OA\Post(
     *     tags={"article"},
     *     description="the method perform create article",
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\MediaType(
     *             mediaType="multipart/form-data",
     *             @OA\Schema(
     *                 allOf={
     *                     @OA\Schema(
     *                         type="object",
     *                         @OA\Property(property="class", type="string", default="Evrinoma\ArticleBundle\Dto\ArticleApiDto"),
     *                         @OA\Property(property="body", type="string"),
     *                         @OA\Property(property="title", type="string"),
     *                         @OA\Property(property="position", type="int"),
     *                         @OA\Property(property="start", type="string"),
     *                         @OA\Property(property="classifier[brief]", type="string"),
     *                         @OA\Property(property="type[brief]", type="string"),
     *                         @OA\Property(property="image", type="string"),
     *                         @OA\Property(property="preview", type="string"),
     *                         @OA\Property(property="attachment", type="string"),
     *                         @OA\Property(property="Evrinoma\ArticleBundle\Dto\ArticleApiDto[image]", type="string",  format="binary"),
     *                         @OA\Property(property="Evrinoma\ArticleBundle\Dto\ArticleApiDto[preview]", type="string",  format="binary"),
     *                         @OA\Property(property="Evrinoma\ArticleBundle\Dto\ArticleApiDto[attachment]", type="string",  format="binary")
     *                     )
     *                 }
     *             )
     *         )
     *     )
     * )
     * @OA\Response(response=200, description="Create article")
     *
     * @return JsonResponse
     */
    public function postAction(): JsonResponse
    {
        /** @var ArticleApiDtoInterface $articleApiDto */
        $articleApiDto = $this->factoryDto->setRequest($this->request)->createDto($this->dtoClass);

        $this->setStatusCreated();

        $json = [];
        $error = [];
        $group = GroupInterface::API_POST_ARTICLE;

        try {
            $this->facade->post($articleApiDto, $group, $json);
        } catch (\Exception $e) {
            $json = [];
            $error = $this->setRestStatus($e);
        }

        return $this->setSerializeGroup($group)->JsonResponse('Create article', $json, $error);
    }

    /**
     * @Rest\Post("/api/article/save", options={"expose": true}, name="api_article_save")
     * @OA\Post(
     *     tags={"article"},
     *     description="the method perform save article for current entity",
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\MediaType(
     *             mediaType="multipart/form-data",
     *             @OA\Schema(
     *                 allOf={
     *                     @OA\Schema(
     *                         type="object",
     *                         @OA\Property(property="class", type="string", default="Evrinoma\ArticleBundle\Dto\ArticleApiDto"),
     *                         @OA\Property(property="id", type="string"),
     *                         @OA\Property(property="active", type="string"),
     *                         @OA\Property(property="body", type="string"),
     *                         @OA\Property(property="title", type="string"),
     *                         @OA\Property(property="start", type="string"),
     *                         @OA\Property(property="position", type="int"),
     *                         @OA\Property(property="classifier[brief]", type="string"),
     *                         @OA\Property(property="type[brief]", type="string"),
     *                         @OA\Property(property="image", type="string"),
     *                         @OA\Property(property="preview", type="string"),
     *                         @OA\Property(property="attachment", type="string"),
     *                         @OA\Property(property="Evrinoma\ArticleBundle\Dto\ArticleApiDto[image]", type="string",  format="binary"),
     *                         @OA\Property(property="Evrinoma\ArticleBundle\Dto\ArticleApiDto[preview]", type="string",  format="binary"),
     *                         @OA\Property(property="Evrinoma\ArticleBundle\Dto\ArticleApiDto[attachment]", type="string",  format="binary")
     *                     )
     *                 }
     *             )
     *         )
     *     )
     * )
     * @OA\Response(response=200, description="Save article")
     *
     * @return JsonResponse
     */
    public function putAction(): JsonResponse
    {
        /** @var ArticleApiDtoInterface $articleApiDto */
        $articleApiDto = $this->factoryDto->setRequest($this->request)->createDto($this->dtoClass);

        $json = [];
        $error = [];
        $group = GroupInterface::API_PUT_ARTICLE;

        try {
            $this->facade->put($articleApiDto, $group, $json);
        } catch (\Exception $e) {
            $json = [];
            $error = $this->setRestStatus($e);
        }

        return $this->setSerializeGroup($group)->JsonResponse('Save article', $json, $error);
    }

    /**
     * @Rest\Delete("/api/article/delete", options={"expose": true}, name="api_article_delete")
     * @OA\Delete(
     *     tags={"article"},
     *     @OA\Parameter(
     *         description="class",
     *         in="query",
     *         name="class",
     *         required=true,
     *         @OA\Schema(
     *             type="string",
     *             default="Evrinoma\ArticleBundle\Dto\ArticleApiDto",
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
     * @OA\Response(response=200, description="Delete article")
     *
     * @return JsonResponse
     */
    public function deleteAction(): JsonResponse
    {
        /** @var ArticleApiDtoInterface $articleApiDto */
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

        return $this->JsonResponse('Delete article', $json, $error);
    }

    /**
     * @Rest\Get("/api/article/criteria", options={"expose": true}, name="api_article_criteria")
     * @OA\Get(
     *     tags={"article"},
     *     @OA\Parameter(
     *         description="class",
     *         in="query",
     *         name="class",
     *         required=true,
     *         @OA\Schema(
     *             type="string",
     *             default="Evrinoma\ArticleBundle\Dto\ArticleApiDto",
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
     *         description="position",
     *         in="query",
     *         name="position",
     *         @OA\Schema(
     *             type="string",
     *         )
     *     ),
     *     @OA\Parameter(
     *         description="title",
     *         in="query",
     *         name="title",
     *         @OA\Schema(
     *             type="string",
     *         )
     *     ),
     *     @OA\Parameter(
     *         description="body",
     *         in="query",
     *         name="body",
     *         @OA\Schema(
     *             type="string",
     *         )
     *     ),
     *     @OA\Parameter(
     *         name="classifier[brief]",
     *         in="query",
     *         description="Classifier Article",
     *         @OA\Schema(
     *             type="array",
     *             @OA\Items(
     *                 type="string",
     *                 ref=@Model(type=Evrinoma\ArticleBundle\Form\Rest\Classifier\ClassifierChoiceType::class, options={"data": "brief"})
     *             ),
     *         ),
     *         style="form"
     *     ),
     *     @OA\Parameter(
     *         name="type[brief]",
     *         in="query",
     *         description="Type Article",
     *         @OA\Schema(
     *             type="array",
     *             @OA\Items(
     *                 type="string",
     *                 ref=@Model(type=Evrinoma\ArticleBundle\Form\Rest\Type\TypeChoiceType::class, options={"data": "brief"})
     *             ),
     *         ),
     *         style="form"
     *     ),
     * )
     * @OA\Response(response=200, description="Return article")
     *
     * @return JsonResponse
     */
    public function criteriaAction(): JsonResponse
    {
        /** @var ArticleApiDtoInterface $articleApiDto */
        $articleApiDto = $this->factoryDto->setRequest($this->request)->createDto($this->dtoClass);

        $json = [];
        $error = [];
        $group = GroupInterface::API_CRITERIA_ARTICLE;

        try {
            $this->facade->criteria($articleApiDto, $group, $json);
        } catch (\Exception $e) {
            $json = [];
            $error = $this->setRestStatus($e);
        }

        return $this->setSerializeGroup($group)->JsonResponse('Get article', $json, $error);
    }

    /**
     * @Rest\Get("/api/article", options={"expose": true}, name="api_article")
     * @OA\Get(
     *     tags={"article"},
     *     @OA\Parameter(
     *         description="class",
     *         in="query",
     *         name="class",
     *         required=true,
     *         @OA\Schema(
     *             type="string",
     *             default="Evrinoma\ArticleBundle\Dto\ArticleApiDto",
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
     * @OA\Response(response=200, description="Return article")
     *
     * @return JsonResponse
     */
    public function getAction(): JsonResponse
    {
        /** @var ArticleApiDtoInterface $articleApiDto */
        $articleApiDto = $this->factoryDto->setRequest($this->request)->createDto($this->dtoClass);

        $json = [];
        $error = [];
        $group = GroupInterface::API_GET_ARTICLE;

        try {
            $this->facade->get($articleApiDto, $group, $json);
        } catch (\Exception $e) {
            $json = [];
            $error = $this->setRestStatus($e);
        }

        return $this->setSerializeGroup($group)->JsonResponse('Get article', $json, $error);
    }

    /**
     * @param \Exception $e
     *
     * @return array
     */
    public function setRestStatus(\Exception $e): array
    {
        switch (true) {
            case $e instanceof ArticleCannotBeSavedException:
                $this->setStatusNotImplemented();
                break;
            case $e instanceof UniqueConstraintViolationException:
                $this->setStatusConflict();
                break;
            case $e instanceof ArticleNotFoundException:
                $this->setStatusNotFound();
                break;
            case $e instanceof ArticleInvalidException:
                $this->setStatusUnprocessableEntity();
                break;
            default:
                $this->setStatusBadRequest();
        }

        return [$e->getMessage()];
    }
}
