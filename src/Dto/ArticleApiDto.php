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

namespace Evrinoma\ArticleBundle\Dto;

use Evrinoma\DtoBundle\Annotation\Dto;
use Evrinoma\DtoBundle\Dto\AbstractDto;
use Evrinoma\DtoBundle\Dto\DtoInterface;
use Evrinoma\DtoCommon\ValueObject\Mutable\ActiveTrait;
use Evrinoma\DtoCommon\ValueObject\Mutable\AttachmentTrait;
use Evrinoma\DtoCommon\ValueObject\Mutable\BodyTrait;
use Evrinoma\DtoCommon\ValueObject\Mutable\DescriptionTrait;
use Evrinoma\DtoCommon\ValueObject\Mutable\IdTrait;
use Evrinoma\DtoCommon\ValueObject\Mutable\ImageTrait;
use Evrinoma\DtoCommon\ValueObject\Mutable\PositionTrait;
use Evrinoma\DtoCommon\ValueObject\Mutable\PreviewTrait;
use Evrinoma\DtoCommon\ValueObject\Mutable\StartTrait;
use Evrinoma\DtoCommon\ValueObject\Mutable\TitleTrait;
use Symfony\Component\HttpFoundation\Request;

class ArticleApiDto extends AbstractDto implements ArticleApiDtoInterface
{
    use ActiveTrait;
    use AttachmentTrait;
    use BodyTrait;
    use DescriptionTrait;
    use IdTrait;
    use ImageTrait;
    use PositionTrait;
    use PreviewTrait;
    use StartTrait;
    use TitleTrait;

    /**
     * @Dto(class="Evrinoma\ArticleBundle\Dto\TypeApiDto", generator="genRequestTypeApiDto")
     */
    private ?TypeApiDtoInterface $typeApiDto = null;

    /**
     * @param TypeApiDto $typeApiDto
     *
     * @return DtoInterface
     */
    public function setTypeApiDto(TypeApiDtoInterface $typeApiDto): DtoInterface
    {
        $this->typeApiDto = $typeApiDto;

        return $this;
    }

    public function hasTypeApiDto(): bool
    {
        return null !== $this->typeApiDto;
    }

    public function getTypeApiDto(): TypeApiDtoInterface
    {
        return $this->typeApiDto;
    }

    public function genRequestTypeApiDto(?Request $request): ?\Generator
    {
        if ($request) {
            $type = $request->get(TypeApiDtoInterface::TYPE);
            if ($type) {
                $newRequest = $this->getCloneRequest();
                $type[DtoInterface::DTO_CLASS] = TypeApiDto::class;
                $newRequest->request->add($type);

                yield $newRequest;
            }
        }
    }

    /**
     * @Dto(class="Evrinoma\ArticleBundle\Dto\ClassifierApiDto", generator="genRequestClassifierApiDto")
     */
    private ?ClassifierApiDtoInterface $classifierApiDto = null;

    /**
     * @param ClassifierApiDto $classifierApiDto
     *
     * @return DtoInterface
     */
    public function setClassifierApiDto(ClassifierApiDtoInterface $classifierApiDto): DtoInterface
    {
        $this->classifierApiDto = $classifierApiDto;

        return $this;
    }

    public function hasClassifierApiDto(): bool
    {
        return null !== $this->classifierApiDto;
    }

    public function getClassifierApiDto(): ClassifierApiDtoInterface
    {
        return $this->classifierApiDto;
    }

    public function genRequestClassifierApiDto(?Request $request): ?\Generator
    {
        if ($request) {
            $classifier = $request->get(ClassifierApiDtoInterface::CLASSIFIER);
            if ($classifier) {
                $newRequest = $this->getCloneRequest();
                $classifier[DtoInterface::DTO_CLASS] = ClassifierApiDto::class;
                $newRequest->request->add($classifier);

                yield $newRequest;
            }
        }
    }

    public function toDto(Request $request): DtoInterface
    {
        $class = $request->get(DtoInterface::DTO_CLASS);

        if ($class === $this->getClass()) {
            $id = $request->get(ArticleApiDtoInterface::ID);
            $active = $request->get(ArticleApiDtoInterface::ACTIVE);
            $title = $request->get(ArticleApiDtoInterface::TITLE);
            $position = $request->get(ArticleApiDtoInterface::POSITION);
            $body = $request->get(ArticleApiDtoInterface::BODY);
            $image = $request->files->get(ArticleApiDtoInterface::IMAGE);
            $attachment = $request->files->get(ArticleApiDtoInterface::ATTACHMENT);
            $preview = $request->files->get(ArticleApiDtoInterface::PREVIEW);
            $description = $request->get(ArticleApiDtoInterface::DESCRIPTION);
            $start = $request->get(ArticleApiDtoInterface::START);

            if ($active) {
                $this->setActive($active);
            }
            if ($id) {
                $this->setId($id);
            }
            if ($position) {
                $this->setPosition($position);
            }
            if ($title) {
                $this->setTitle($title);
            }
            if ($body) {
                $this->setBody($body);
            }
            if ($attachment) {
                $this->setAttachment($attachment);
            }
            if ($preview) {
                $this->setPreview($preview);
            }
            if ($image) {
                $this->setImage($image);
            }
            if ($description) {
                $this->setDescription($description);
            }
            if ($start) {
                $this->setStart($start);
            }
        }

        return $this;
    }
}
