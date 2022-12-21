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

namespace Evrinoma\ArticleBundle\Mediator\Classifier;

use Evrinoma\ArticleBundle\Dto\ClassifierApiDtoInterface;
use Evrinoma\ArticleBundle\Exception\Classifier\ClassifierCannotBeCreatedException;
use Evrinoma\ArticleBundle\Exception\Classifier\ClassifierCannotBeRemovedException;
use Evrinoma\ArticleBundle\Exception\Classifier\ClassifierCannotBeSavedException;
use Evrinoma\ArticleBundle\Model\Classifier\ClassifierInterface;

interface CommandMediatorInterface
{
    /**
     * @param ClassifierApiDtoInterface $dto
     * @param ClassifierInterface       $entity
     *
     * @return ClassifierInterface
     *
     * @throws ClassifierCannotBeSavedException
     */
    public function onUpdate(ClassifierApiDtoInterface $dto, ClassifierInterface $entity): ClassifierInterface;

    /**
     * @param ClassifierApiDtoInterface $dto
     * @param ClassifierInterface       $entity
     *
     * @throws ClassifierCannotBeRemovedException
     */
    public function onDelete(ClassifierApiDtoInterface $dto, ClassifierInterface $entity): void;

    /**
     * @param ClassifierApiDtoInterface $dto
     * @param ClassifierInterface       $entity
     *
     * @return ClassifierInterface
     *
     * @throws ClassifierCannotBeSavedException
     * @throws ClassifierCannotBeCreatedException
     */
    public function onCreate(ClassifierApiDtoInterface $dto, ClassifierInterface $entity): ClassifierInterface;
}
