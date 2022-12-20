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

namespace Evrinoma\ArticleBundle\Manager;

use Evrinoma\ArticleBundle\Dto\ArticleApiDtoInterface;
use Evrinoma\ArticleBundle\Exception\ArticleNotFoundException;
use Evrinoma\ArticleBundle\Exception\ArticleProxyException;
use Evrinoma\ArticleBundle\Model\Article\ArticleInterface;
use Evrinoma\ArticleBundle\Repository\Article\ArticleQueryRepositoryInterface;

final class QueryManager implements QueryManagerInterface
{
    private ArticleQueryRepositoryInterface $repository;

    public function __construct(ArticleQueryRepositoryInterface $repository)
    {
        $this->repository = $repository;
    }

    /**
     * @param ArticleApiDtoInterface $dto
     *
     * @return array
     *
     * @throws ArticleNotFoundException
     */
    public function criteria(ArticleApiDtoInterface $dto): array
    {
        try {
            $article = $this->repository->findByCriteria($dto);
        } catch (ArticleNotFoundException $e) {
            throw $e;
        }

        return $article;
    }

    /**
     * @param ArticleApiDtoInterface $dto
     *
     * @return ArticleInterface
     *
     * @throws ArticleProxyException
     */
    public function proxy(ArticleApiDtoInterface $dto): ArticleInterface
    {
        try {
            if ($dto->hasId()) {
                $article = $this->repository->proxy($dto->idToString());
            } else {
                throw new ArticleProxyException('Id value is not set while trying get proxy object');
            }
        } catch (ArticleProxyException $e) {
            throw $e;
        }

        return $article;
    }

    /**
     * @param ArticleApiDtoInterface $dto
     *
     * @return ArticleInterface
     *
     * @throws ArticleNotFoundException
     */
    public function get(ArticleApiDtoInterface $dto): ArticleInterface
    {
        try {
            $article = $this->repository->find($dto->idToString());
        } catch (ArticleNotFoundException $e) {
            throw $e;
        }

        return $article;
    }
}
