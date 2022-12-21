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

namespace Evrinoma\ArticleBundle\Repository\Type;

use Doctrine\ORM\Exception\ORMException;
use Doctrine\ORM\ORMInvalidArgumentException;
use Evrinoma\ArticleBundle\Dto\TypeApiDtoInterface;
use Evrinoma\ArticleBundle\Exception\Type\TypeCannotBeSavedException;
use Evrinoma\ArticleBundle\Exception\Type\TypeNotFoundException;
use Evrinoma\ArticleBundle\Exception\Type\TypeProxyException;
use Evrinoma\ArticleBundle\Mediator\Type\QueryMediatorInterface;
use Evrinoma\ArticleBundle\Model\Type\TypeInterface;

trait TypeRepositoryTrait
{
    private QueryMediatorInterface $mediator;

    /**
     * @param TypeInterface $article
     *
     * @return bool
     *
     * @throws TypeCannotBeSavedException
     * @throws ORMException
     */
    public function save(TypeInterface $article): bool
    {
        try {
            $this->persistWrapped($article);
        } catch (ORMInvalidArgumentException $e) {
            throw new TypeCannotBeSavedException($e->getMessage());
        }

        return true;
    }

    /**
     * @param TypeInterface $article
     *
     * @return bool
     */
    public function remove(TypeInterface $article): bool
    {
        return true;
    }

    /**
     * @param TypeApiDtoInterface $dto
     *
     * @return array
     *
     * @throws TypeNotFoundException
     */
    public function findByCriteria(TypeApiDtoInterface $dto): array
    {
        $builder = $this->createQueryBuilderWrapped($this->mediator->alias());

        $this->mediator->createQuery($dto, $builder);

        $articles = $this->mediator->getResult($dto, $builder);

        if (0 === \count($articles)) {
            throw new TypeNotFoundException('Cannot find article by findByCriteria');
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
     * @throws TypeNotFoundException
     */
    public function find($id, $lockMode = null, $lockVersion = null): TypeInterface
    {
        /** @var TypeInterface $article */
        $article = $this->findWrapped($id);

        if (null === $article) {
            throw new TypeNotFoundException("Cannot find article with id $id");
        }

        return $article;
    }

    /**
     * @param string $id
     *
     * @return TypeInterface
     *
     * @throws TypeProxyException
     * @throws ORMException
     */
    public function proxy(string $id): TypeInterface
    {
        $article = $this->referenceWrapped($id);

        if (!$this->containsWrapped($article)) {
            throw new TypeProxyException("Proxy doesn't exist with $id");
        }

        return $article;
    }
}
