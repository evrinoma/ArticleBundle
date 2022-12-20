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
use Evrinoma\UtilsBundle\QueryBuilder\QueryBuilderInterface;

interface QueryMediatorInterface
{
    /**
     * @return string
     */
    public function alias(): string;

    /**
     * @param ArticleApiDtoInterface $dto
     * @param QueryBuilderInterface  $builder
     *
     * @return mixed
     */
    public function createQuery(ArticleApiDtoInterface $dto, QueryBuilderInterface $builder): void;

    /**
     * @param ArticleApiDtoInterface $dto
     * @param QueryBuilderInterface  $builder
     *
     * @return array
     */
    public function getResult(ArticleApiDtoInterface $dto, QueryBuilderInterface $builder): array;
}
