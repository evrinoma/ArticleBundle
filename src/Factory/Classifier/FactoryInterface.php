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

namespace Evrinoma\ArticleBundle\Factory\Classifier;

use Evrinoma\ArticleBundle\Dto\ClassifierApiDtoInterface;
use Evrinoma\ArticleBundle\Model\Classifier\ClassifierInterface;

interface FactoryInterface
{
    /**
     * @param ClassifierApiDtoInterface $dto
     *
     * @return ClassifierInterface
     */
    public function create(ClassifierApiDtoInterface $dto): ClassifierInterface;
}
