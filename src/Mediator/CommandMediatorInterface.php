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

namespace Evrinoma\ArticleBundle\Mediator;

use Evrinoma\ArticleBundle\Dto\ArticleApiDtoInterface;
use Evrinoma\ArticleBundle\Exception\ArticleCannotBeCreatedException;
use Evrinoma\ArticleBundle\Exception\ArticleCannotBeRemovedException;
use Evrinoma\ArticleBundle\Exception\ArticleCannotBeSavedException;
use Evrinoma\ArticleBundle\Model\Article\ArticleInterface;

interface CommandMediatorInterface
{
    /**
     * @param ArticleApiDtoInterface $dto
     * @param ArticleInterface       $entity
     *
     * @return ArticleInterface
     *
     * @throws ArticleCannotBeSavedException
     */
    public function onUpdate(ArticleApiDtoInterface $dto, ArticleInterface $entity): ArticleInterface;

    /**
     * @param ArticleApiDtoInterface $dto
     * @param ArticleInterface       $entity
     *
     * @throws ArticleCannotBeRemovedException
     */
    public function onDelete(ArticleApiDtoInterface $dto, ArticleInterface $entity): void;

    /**
     * @param ArticleApiDtoInterface $dto
     * @param ArticleInterface       $entity
     *
     * @return ArticleInterface
     *
     * @throws ArticleCannotBeSavedException
     * @throws ArticleCannotBeCreatedException
     */
    public function onCreate(ArticleApiDtoInterface $dto, ArticleInterface $entity): ArticleInterface;
}
