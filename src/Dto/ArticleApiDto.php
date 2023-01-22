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

use Evrinoma\ArticleBundle\DtoCommon\ValueObject\Mutable\ClassifierApiDtoTrait;
use Evrinoma\ArticleBundle\DtoCommon\ValueObject\Mutable\TypeApiDtoTrait;
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
use Symfony\Component\HttpFoundation\File\File;
use Symfony\Component\HttpFoundation\Request;

class ArticleApiDto extends AbstractDto implements ArticleApiDtoInterface
{
    use ActiveTrait;
    use AttachmentTrait;
    use BodyTrait;
    use ClassifierApiDtoTrait;
    use DescriptionTrait;
    use IdTrait;
    use ImageTrait;
    use PositionTrait;
    use PreviewTrait;
    use StartTrait;
    use TitleTrait;
    use TypeApiDtoTrait;

    /**
     * @Dto(class="Evrinoma\ArticleBundle\Dto\TypeApiDto", generator="genRequestTypeApiDto")
     */
    protected ?TypeApiDtoInterface $typeApiDto = null;

    /**
     * @Dto(class="Evrinoma\ArticleBundle\Dto\ClassifierApiDto", generator="genRequestClassifierApiDto")
     */
    protected ?ClassifierApiDtoInterface $classifierApiDto = null;

    public function toDto(Request $request): DtoInterface
    {
        $class = $request->get(DtoInterface::DTO_CLASS);

        if ($class === $this->getClass()) {
            $id = $request->get(ArticleApiDtoInterface::ID);
            $active = $request->get(ArticleApiDtoInterface::ACTIVE);
            $title = $request->get(ArticleApiDtoInterface::TITLE);
            $position = $request->get(ArticleApiDtoInterface::POSITION);
            $body = $request->get(ArticleApiDtoInterface::BODY);
            $description = $request->get(ArticleApiDtoInterface::DESCRIPTION);
            $start = $request->get(ArticleApiDtoInterface::START);

            $files = ($request->files->has($this->getClass())) ? $request->files->get($this->getClass()) : [];

            try {
                if (\array_key_exists(ArticleApiDtoInterface::IMAGE, $files)) {
                    $image = $files[ArticleApiDtoInterface::IMAGE];
                } else {
                    $image = $request->get(ArticleApiDtoInterface::IMAGE);
                    if (null !== $image) {
                        $image = new File($image);
                    }
                }
            } catch (\Exception $e) {
                $image = null;
            }

            try {
                if (\array_key_exists(ArticleApiDtoInterface::PREVIEW, $files)) {
                    $preview = $files[ArticleApiDtoInterface::PREVIEW];
                } else {
                    $preview = $request->get(ArticleApiDtoInterface::PREVIEW);
                    if (null !== $preview) {
                        $preview = new File($preview);
                    }
                }
            } catch (\Exception $e) {
                $preview = null;
            }

            try {
                if (\array_key_exists(ArticleApiDtoInterface::ATTACHMENT, $files)) {
                    $attachment = $files[ArticleApiDtoInterface::ATTACHMENT];
                } else {
                    $attachment = $request->get(ArticleApiDtoInterface::ATTACHMENT);
                    if (null !== $attachment) {
                        $attachment = new File($attachment);
                    }
                }
            } catch (\Exception $e) {
                $attachment = null;
            }

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
