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
use Evrinoma\ArticleBundle\Exception\ArticleCannotBeRemovedException;
use Evrinoma\ArticleBundle\Exception\ArticleInvalidException;
use Evrinoma\ArticleBundle\Exception\ArticleNotFoundException;
use Evrinoma\ArticleBundle\Model\Article\ArticleInterface;

interface CommandManagerInterface
{
    /**
     * @param ArticleApiDtoInterface $dto
     *
     * @return ArticleInterface
     *
     * @throws ArticleInvalidException
     */
    public function post(ArticleApiDtoInterface $dto): ArticleInterface;

    /**
     * @param ArticleApiDtoInterface $dto
     *
     * @return ArticleInterface
     *
     * @throws ArticleInvalidException
     * @throws ArticleNotFoundException
     */
    public function put(ArticleApiDtoInterface $dto): ArticleInterface;

    /**
     * @param ArticleApiDtoInterface $dto
     *
     * @throws ArticleCannotBeRemovedException
     * @throws ArticleNotFoundException
     */
    public function delete(ArticleApiDtoInterface $dto): void;
}
