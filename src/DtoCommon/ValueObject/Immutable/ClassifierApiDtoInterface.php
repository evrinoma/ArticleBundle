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

namespace Evrinoma\ArticleBundle\DtoCommon\ValueObject\Immutable;

use Evrinoma\ArticleBundle\Dto\ClassifierApiDtoInterface as BaseClassifierApiDtoInterface;

interface ClassifierApiDtoInterface
{
    public const CLASSIFIER = BaseClassifierApiDtoInterface::CLASSIFIER;

    public function hasClassifierApiDto(): bool;

    public function getClassifierApiDto(): BaseClassifierApiDtoInterface;
}
