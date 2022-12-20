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

namespace Evrinoma\ArticleBundle\Manager;

use Evrinoma\ArticleBundle\Dto\ArticleApiDtoInterface;
use Evrinoma\ArticleBundle\Exception\ArticleNotFoundException;
use Evrinoma\ArticleBundle\Exception\ArticleProxyException;
use Evrinoma\ArticleBundle\Model\Article\ArticleInterface;

interface QueryManagerInterface
{
    /**
     * @param ArticleApiDtoInterface $dto
     *
     * @return array
     *
     * @throws ArticleNotFoundException
     */
    public function criteria(ArticleApiDtoInterface $dto): array;

    /**
     * @param ArticleApiDtoInterface $dto
     *
     * @return ArticleInterface
     *
     * @throws ArticleNotFoundException
     */
    public function get(ArticleApiDtoInterface $dto): ArticleInterface;

    /**
     * @param ArticleApiDtoInterface $dto
     *
     * @return ArticleInterface
     *
     * @throws ArticleProxyException
     */
    public function proxy(ArticleApiDtoInterface $dto): ArticleInterface;
}
