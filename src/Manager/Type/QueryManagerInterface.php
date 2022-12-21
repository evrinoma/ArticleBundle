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

namespace Evrinoma\ArticleBundle\Manager\Type;

use Evrinoma\ArticleBundle\Dto\TypeApiDtoInterface;
use Evrinoma\ArticleBundle\Exception\Type\TypeNotFoundException;
use Evrinoma\ArticleBundle\Exception\Type\TypeProxyException;
use Evrinoma\ArticleBundle\Model\Type\TypeInterface;

interface QueryManagerInterface
{
    /**
     * @param TypeApiDtoInterface $dto
     *
     * @return array
     *
     * @throws TypeNotFoundException
     */
    public function criteria(TypeApiDtoInterface $dto): array;

    /**
     * @param TypeApiDtoInterface $dto
     *
     * @return TypeInterface
     *
     * @throws TypeNotFoundException
     */
    public function get(TypeApiDtoInterface $dto): TypeInterface;

    /**
     * @param TypeApiDtoInterface $dto
     *
     * @return TypeInterface
     *
     * @throws TypeProxyException
     */
    public function proxy(TypeApiDtoInterface $dto): TypeInterface;
}
