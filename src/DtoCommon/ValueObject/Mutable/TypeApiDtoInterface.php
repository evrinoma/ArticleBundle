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

namespace Evrinoma\ArticleBundle\DtoCommon\ValueObject\Mutable;

use Evrinoma\ArticleBundle\Dto\TypeApiDtoInterface as BaseTypeApiDtoInterface;
use Evrinoma\DtoBundle\Dto\DtoInterface;

interface TypeApiDtoInterface
{
    public function setTypeApiDto(BaseTypeApiDtoInterface $typeApiDto): DtoInterface;
}
