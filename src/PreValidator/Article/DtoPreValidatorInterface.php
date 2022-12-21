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

namespace Evrinoma\ArticleBundle\PreValidator\Article;

use Evrinoma\ArticleBundle\Dto\ArticleApiDtoInterface;
use Evrinoma\ArticleBundle\Exception\Article\ArticleInvalidException;

interface DtoPreValidatorInterface
{
    /**
     * @param ArticleApiDtoInterface $dto
     *
     * @throws ArticleInvalidException
     */
    public function onPost(ArticleApiDtoInterface $dto): void;

    /**
     * @param ArticleApiDtoInterface $dto
     *
     * @throws ArticleInvalidException
     */
    public function onPut(ArticleApiDtoInterface $dto): void;

    /**
     * @param ArticleApiDtoInterface $dto
     *
     * @throws ArticleInvalidException
     */
    public function onDelete(ArticleApiDtoInterface $dto): void;
}
