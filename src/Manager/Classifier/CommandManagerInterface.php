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

namespace Evrinoma\ArticleBundle\Manager\Classifier;

use Evrinoma\ArticleBundle\Dto\ClassifierApiDtoInterface;
use Evrinoma\ArticleBundle\Exception\Classifier\ClassifierCannotBeRemovedException;
use Evrinoma\ArticleBundle\Exception\Classifier\ClassifierInvalidException;
use Evrinoma\ArticleBundle\Exception\Classifier\ClassifierNotFoundException;
use Evrinoma\ArticleBundle\Model\Classifier\ClassifierInterface;

interface CommandManagerInterface
{
    /**
     * @param ClassifierApiDtoInterface $dto
     *
     * @return ClassifierInterface
     *
     * @throws ClassifierInvalidException
     */
    public function post(ClassifierApiDtoInterface $dto): ClassifierInterface;

    /**
     * @param ClassifierApiDtoInterface $dto
     *
     * @return ClassifierInterface
     *
     * @throws ClassifierInvalidException
     * @throws ClassifierNotFoundException
     */
    public function put(ClassifierApiDtoInterface $dto): ClassifierInterface;

    /**
     * @param ClassifierApiDtoInterface $dto
     *
     * @throws ClassifierCannotBeRemovedException
     * @throws ClassifierNotFoundException
     */
    public function delete(ClassifierApiDtoInterface $dto): void;
}
