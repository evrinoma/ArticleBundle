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

namespace Evrinoma\ArticleBundle\Repository\Article;

use Doctrine\ORM\Exception\ORMException;
use Doctrine\ORM\ORMInvalidArgumentException;
use Evrinoma\ArticleBundle\Dto\ArticleApiDtoInterface;
use Evrinoma\ArticleBundle\Exception\Article\ArticleCannotBeSavedException;
use Evrinoma\ArticleBundle\Exception\Article\ArticleNotFoundException;
use Evrinoma\ArticleBundle\Exception\Article\ArticleProxyException;
use Evrinoma\ArticleBundle\Mediator\Article\QueryMediatorInterface;
use Evrinoma\ArticleBundle\Model\Article\ArticleInterface;

trait ArticleRepositoryTrait
{
    private QueryMediatorInterface $mediator;

    /**
     * @param ArticleInterface $article
     *
     * @return bool
     *
     * @throws ArticleCannotBeSavedException
     * @throws ORMException
     */
    public function save(ArticleInterface $article): bool
    {
        try {
            $this->persistWrapped($article);
        } catch (ORMInvalidArgumentException $e) {
            throw new ArticleCannotBeSavedException($e->getMessage());
        }

        return true;
    }

    /**
     * @param ArticleInterface $article
     *
     * @return bool
     */
    public function remove(ArticleInterface $article): bool
    {
        $article
            ->setUpdatedAt(new \DateTimeImmutable())
            ->setActiveToDelete();

        return true;
    }

    /**
     * @param ArticleApiDtoInterface $dto
     *
     * @return array
     *
     * @throws ArticleNotFoundException
     */
    public function findByCriteria(ArticleApiDtoInterface $dto): array
    {
        $builder = $this->createQueryBuilderWrapped($this->mediator->alias());

        $this->mediator->createQuery($dto, $builder);

        $articles = $this->mediator->getResult($dto, $builder);

        if (0 === \count($articles)) {
            throw new ArticleNotFoundException('Cannot find article by findByCriteria');
        }

        return $articles;
    }

    /**
     * @param      $id
     * @param null $lockMode
     * @param null $lockVersion
     *
     * @return mixed
     *
     * @throws ArticleNotFoundException
     */
    public function find($id, $lockMode = null, $lockVersion = null): ArticleInterface
    {
        /** @var ArticleInterface $article */
        $article = $this->findWrapped($id);

        if (null === $article) {
            throw new ArticleNotFoundException("Cannot find article with id $id");
        }

        return $article;
    }

    /**
     * @param string $id
     *
     * @return ArticleInterface
     *
     * @throws ArticleProxyException
     * @throws ORMException
     */
    public function proxy(string $id): ArticleInterface
    {
        $article = $this->referenceWrapped($id);

        if (!$this->containsWrapped($article)) {
            throw new ArticleProxyException("Proxy doesn't exist with $id");
        }

        return $article;
    }
}
