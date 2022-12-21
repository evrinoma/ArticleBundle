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

use Evrinoma\ArticleBundle\Exception\Type\TypeCannotBeRemovedException;
use Evrinoma\ArticleBundle\Exception\Type\TypeCannotBeSavedException;
use Evrinoma\ArticleBundle\Model\Type\TypeInterface;

interface TypeCommandRepositoryInterface
{
    /**
     * @param TypeInterface $article
     *
     * @return bool
     *
     * @throws TypeCannotBeSavedException
     */
    public function save(TypeInterface $article): bool;

    /**
     * @param TypeInterface $article
     *
     * @return bool
     *
     * @throws TypeCannotBeRemovedException
     */
    public function remove(TypeInterface $article): bool;
}
