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

use Evrinoma\ArticleBundle\Dto\TypeApiDtoInterface;
use Evrinoma\ArticleBundle\DtoCommon\ValueObject\Immutable\TypeApiDtoTrait as TypeApiDtoImmutableTrait;
use Evrinoma\DtoBundle\Dto\DtoInterface;

trait TypeApiDtoTrait
{
    use TypeApiDtoImmutableTrait;

    /**
     * @param TypeApiDtoInterface $typeApiDto
     *
     * @return DtoInterface
     */
    public function setTypeApiDto(TypeApiDtoInterface $typeApiDto): DtoInterface
    {
        $this->typeApiDto = $typeApiDto;

        return $this;
    }
}
