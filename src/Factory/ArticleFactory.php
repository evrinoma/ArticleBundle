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

namespace Evrinoma\ArticleBundle\Factory;

use Evrinoma\ArticleBundle\Dto\ArticleApiDtoInterface;
use Evrinoma\ArticleBundle\Entity\Article\BaseArticle;
use Evrinoma\ArticleBundle\Model\Article\ArticleInterface;

class ArticleFactory implements ArticleFactoryInterface
{
    private static string $entityClass = BaseArticle::class;

    public function __construct(string $entityClass)
    {
        self::$entityClass = $entityClass;
    }

    /**
     * @param ArticleApiDtoInterface $dto
     *
     * @return ArticleInterface
     */
    public function create(ArticleApiDtoInterface $dto): ArticleInterface
    {
        /* @var BaseArticle $article */
        return new self::$entityClass();
    }
}
