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
use Evrinoma\ArticleBundle\Model\Article\ArticleInterface;

interface ArticleFactoryInterface
{
    /**
     * @param ArticleApiDtoInterface $dto
     *
     * @return ArticleInterface
     */
    public function create(ArticleApiDtoInterface $dto): ArticleInterface;
}
