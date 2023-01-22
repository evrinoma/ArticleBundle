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

use Evrinoma\ArticleBundle\Dto\ClassifierApiDtoInterface;
use Evrinoma\ArticleBundle\DtoCommon\ValueObject\Immutable\ClassifierApiDtoTrait as ClassifierApiDtoImmutableTrait;
use Evrinoma\DtoBundle\Dto\DtoInterface;

trait ClassifierApiDtoTrait
{
    use ClassifierApiDtoImmutableTrait;

    /**
     * @param ClassifierApiDtoInterface $classifierApiDto
     *
     * @return DtoInterface
     */
    public function setClassifierApiDto(ClassifierApiDtoInterface $classifierApiDto): DtoInterface
    {
        $this->classifierApiDto = $classifierApiDto;

        return $this;
    }
}
