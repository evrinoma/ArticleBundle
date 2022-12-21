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

use Evrinoma\ArticleBundle\Exception\Article\ArticleCannotBeRemovedException;
use Evrinoma\ArticleBundle\Exception\Article\ArticleCannotBeSavedException;
use Evrinoma\ArticleBundle\Model\Article\ArticleInterface;

interface ArticleCommandRepositoryInterface
{
    /**
     * @param ArticleInterface $article
     *
     * @return bool
     *
     * @throws ArticleCannotBeSavedException
     */
    public function save(ArticleInterface $article): bool;

    /**
     * @param ArticleInterface $article
     *
     * @return bool
     *
     * @throws ArticleCannotBeRemovedException
     */
    public function remove(ArticleInterface $article): bool;
}
