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

namespace Evrinoma\ArticleBundle\Mediator\Article;

use Evrinoma\ArticleBundle\Dto\ArticleApiDtoInterface;
use Evrinoma\ArticleBundle\Model\Article\ArticleInterface;
use Evrinoma\ArticleBundle\System\FileSystemInterface;
use Evrinoma\DtoBundle\Dto\DtoInterface;
use Evrinoma\UtilsBundle\Mediator\AbstractCommandMediator;

class CommandMediator extends AbstractCommandMediator implements CommandMediatorInterface
{
    private FileSystemInterface $fileSystem;

    public function __construct(FileSystemInterface $fileSystem)
    {
        $this->fileSystem = $fileSystem;
    }

    public function onUpdate(DtoInterface $dto, $entity): ArticleInterface
    {
        /* @var $dto ArticleApiDtoInterface */
        $filePreview = $this->fileSystem->save($dto->getPreview());
        $fileImage = $this->fileSystem->save($dto->getImage());
        $entity
            ->setDescription($dto->getDescription())
            ->setTitle($dto->getTitle())
            ->setPosition($dto->getPosition())
            ->setBody($dto->getBody())
            ->setPreview($filePreview->getPathname())
            ->setImage($fileImage->getPathname())
            ->setUpdatedAt(new \DateTimeImmutable())
            ->setActive($dto->getActive());

        if ($dto->hasStart()) {
            $entity->setStart(new \DateTimeImmutable($dto->getStart()));
        }

        if ($dto->hasAttachment()) {
            $fileAttachment = $this->fileSystem->save($dto->getAttachment());
            $entity->setAttachment($fileAttachment->getPathname());
        } else {
            $entity->resetAttachment();
        }

        return $entity;
    }

    public function onDelete(DtoInterface $dto, $entity): void
    {
        $entity
            ->setActiveToDelete();
    }

    public function onCreate(DtoInterface $dto, $entity): ArticleInterface
    {
        /* @var $dto ArticleApiDtoInterface */
        $filePreview = $this->fileSystem->save($dto->getPreview());
        $fileImage = $this->fileSystem->save($dto->getImage());
        $entity
            ->setDescription($dto->getDescription())
            ->setTitle($dto->getTitle())
            ->setPosition($dto->getPosition())
            ->setBody($dto->getBody())
            ->setPreview($filePreview->getPathname())
            ->setImage($fileImage->getPathname())
            ->setCreatedAt(new \DateTimeImmutable())
            ->setActiveToActive();

        if ($dto->hasStart()) {
            $entity->setStart(new \DateTimeImmutable($dto->getStart()));
        }

        if ($dto->hasAttachment()) {
            $fileAttachment = $this->fileSystem->save($dto->getAttachment());
            $entity->setAttachment($fileAttachment->getPathname());
        } else {
            $entity->resetAttachment();
        }

        return $entity;
    }
}
