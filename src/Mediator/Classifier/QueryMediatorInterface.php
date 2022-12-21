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

namespace Evrinoma\ArticleBundle\Mediator\Classifier;

use Evrinoma\ArticleBundle\Dto\ClassifierApiDtoInterface;
use Evrinoma\UtilsBundle\QueryBuilder\QueryBuilderInterface;

interface QueryMediatorInterface
{
    /**
     * @return string
     */
    public function alias(): string;

    /**
     * @param ClassifierApiDtoInterface $dto
     * @param QueryBuilderInterface     $builder
     *
     * @return mixed
     */
    public function createQuery(ClassifierApiDtoInterface $dto, QueryBuilderInterface $builder): void;

    /**
     * @param ClassifierApiDtoInterface $dto
     * @param QueryBuilderInterface     $builder
     *
     * @return array
     */
    public function getResult(ClassifierApiDtoInterface $dto, QueryBuilderInterface $builder): array;
}
