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

namespace Evrinoma\ArticleBundle\Factory\Type;

use Evrinoma\ArticleBundle\Dto\TypeApiDtoInterface;
use Evrinoma\ArticleBundle\Entity\Type\BaseType;
use Evrinoma\ArticleBundle\Model\Type\TypeInterface;

class Factory implements FactoryInterface
{
    private static string $entityClass = BaseType::class;

    public function __construct(string $entityClass)
    {
        self::$entityClass = $entityClass;
    }

    /**
     * @param TypeApiDtoInterface $dto
     *
     * @return TypeInterface
     */
    public function create(TypeApiDtoInterface $dto): TypeInterface
    {
        /* @var BaseType $article */
        return new self::$entityClass();
    }
}
