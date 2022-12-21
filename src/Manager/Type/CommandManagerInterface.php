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
use Evrinoma\ArticleBundle\Exception\Type\TypeCannotBeRemovedException;
use Evrinoma\ArticleBundle\Exception\Type\TypeInvalidException;
use Evrinoma\ArticleBundle\Exception\Type\TypeNotFoundException;
use Evrinoma\ArticleBundle\Model\Type\TypeInterface;

interface CommandManagerInterface
{
    /**
     * @param TypeApiDtoInterface $dto
     *
     * @return TypeInterface
     *
     * @throws TypeInvalidException
     */
    public function post(TypeApiDtoInterface $dto): TypeInterface;

    /**
     * @param TypeApiDtoInterface $dto
     *
     * @return TypeInterface
     *
     * @throws TypeInvalidException
     * @throws TypeNotFoundException
     */
    public function put(TypeApiDtoInterface $dto): TypeInterface;

    /**
     * @param TypeApiDtoInterface $dto
     *
     * @throws TypeCannotBeRemovedException
     * @throws TypeNotFoundException
     */
    public function delete(TypeApiDtoInterface $dto): void;
}
