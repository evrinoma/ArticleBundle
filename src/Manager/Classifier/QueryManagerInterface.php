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

namespace Evrinoma\ArticleBundle\Manager\Classifier;

use Evrinoma\ArticleBundle\Dto\ClassifierApiDtoInterface;
use Evrinoma\ArticleBundle\Exception\Classifier\ClassifierNotFoundException;
use Evrinoma\ArticleBundle\Exception\Classifier\ClassifierProxyException;
use Evrinoma\ArticleBundle\Model\Classifier\ClassifierInterface;

interface QueryManagerInterface
{
    /**
     * @param ClassifierApiDtoInterface $dto
     *
     * @return array
     *
     * @throws ClassifierNotFoundException
     */
    public function criteria(ClassifierApiDtoInterface $dto): array;

    /**
     * @param ClassifierApiDtoInterface $dto
     *
     * @return ClassifierInterface
     *
     * @throws ClassifierNotFoundException
     */
    public function get(ClassifierApiDtoInterface $dto): ClassifierInterface;

    /**
     * @param ClassifierApiDtoInterface $dto
     *
     * @return ClassifierInterface
     *
     * @throws ClassifierProxyException
     */
    public function proxy(ClassifierApiDtoInterface $dto): ClassifierInterface;
}
