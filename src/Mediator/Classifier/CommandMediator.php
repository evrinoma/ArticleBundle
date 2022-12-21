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
use Evrinoma\ArticleBundle\Model\Classifier\ClassifierInterface;
use Evrinoma\DtoBundle\Dto\DtoInterface;
use Evrinoma\UtilsBundle\Mediator\AbstractCommandMediator;

class CommandMediator extends AbstractCommandMediator implements CommandMediatorInterface
{
    public function onUpdate(DtoInterface $dto, $entity): ClassifierInterface
    {
        /* @var $dto ClassifierApiDtoInterface */
        $entity
            ->setDescription($dto->getDescription())
            ->setBrief($dto->getBrief())
            ->setActive($dto->getActive());

        return $entity;
    }

    public function onDelete(DtoInterface $dto, $entity): void
    {
        $entity
            ->setActiveToDelete();
    }

    public function onCreate(DtoInterface $dto, $entity): ClassifierInterface
    {
        /* @var $dto ClassifierApiDtoInterface */
        $entity
            ->setDescription($dto->getDescription())
            ->setBrief($dto->getBrief())
            ->setActiveToActive();

        return $entity;
    }
}
