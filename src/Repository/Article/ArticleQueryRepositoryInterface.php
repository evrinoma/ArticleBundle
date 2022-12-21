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
use Evrinoma\ArticleBundle\Dto\ArticleApiDtoInterface;
use Evrinoma\ArticleBundle\Exception\Article\ArticleNotFoundException;
use Evrinoma\ArticleBundle\Exception\Article\ArticleProxyException;
use Evrinoma\ArticleBundle\Model\Article\ArticleInterface;

interface ArticleQueryRepositoryInterface
{
    /**
     * @param ArticleApiDtoInterface $dto
     *
     * @return array
     *
     * @throws ArticleNotFoundException
     */
    public function findByCriteria(ArticleApiDtoInterface $dto): array;

    /**
     * @param string $id
     * @param null   $lockMode
     * @param null   $lockVersion
     *
     * @return ArticleInterface
     *
     * @throws ArticleNotFoundException
     */
    public function find(string $id, $lockMode = null, $lockVersion = null): ArticleInterface;

    /**
     * @param string $id
     *
     * @return ArticleInterface
     *
     * @throws ArticleProxyException
     * @throws ORMException
     */
    public function proxy(string $id): ArticleInterface;
}
