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

use Evrinoma\DtoBundle\Dto\AbstractDto;
use Evrinoma\DtoBundle\Dto\DtoInterface;
use Evrinoma\DtoCommon\ValueObject\Mutable\ActiveTrait;
use Evrinoma\DtoCommon\ValueObject\Mutable\AttachmentTrait;
use Evrinoma\DtoCommon\ValueObject\Mutable\BodyTrait;
use Evrinoma\DtoCommon\ValueObject\Mutable\IdTrait;
use Evrinoma\DtoCommon\ValueObject\Mutable\ImageTrait;
use Evrinoma\DtoCommon\ValueObject\Mutable\PositionTrait;
use Evrinoma\DtoCommon\ValueObject\Mutable\PreviewTrait;
use Evrinoma\DtoCommon\ValueObject\Mutable\TitleTrait;
use Symfony\Component\HttpFoundation\Request;

class ArticleApiDto extends AbstractDto implements ArticleApiDtoInterface
{
    use ActiveTrait;
    use AttachmentTrait;
    use BodyTrait;
    use IdTrait;
    use ImageTrait;
    use PositionTrait;
    use PreviewTrait;
    use TitleTrait;

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
        }

        return $this;
    }
}
