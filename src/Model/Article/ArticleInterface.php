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

namespace Evrinoma\ArticleBundle\Model\Article;

use Evrinoma\ArticleBundle\Model\Classifier\ClassifierInterface;
use Evrinoma\ArticleBundle\Model\Type\TypeInterface;
use Evrinoma\UtilsBundle\Entity\ActiveInterface;
use Evrinoma\UtilsBundle\Entity\AttachmentInterface;
use Evrinoma\UtilsBundle\Entity\BodyInterface;
use Evrinoma\UtilsBundle\Entity\CreateUpdateAtInterface;
use Evrinoma\UtilsBundle\Entity\DescriptionInterface;
use Evrinoma\UtilsBundle\Entity\IdInterface;
use Evrinoma\UtilsBundle\Entity\ImageInterface;
use Evrinoma\UtilsBundle\Entity\PositionInterface;
use Evrinoma\UtilsBundle\Entity\PreviewInterface;
use Evrinoma\UtilsBundle\Entity\StartInterface;
use Evrinoma\UtilsBundle\Entity\TitleInterface;

interface ArticleInterface extends ActiveInterface, CreateUpdateAtInterface, IdInterface, BodyInterface, TitleInterface, PositionInterface, PreviewInterface, ImageInterface, AttachmentInterface, DescriptionInterface, StartInterface
{
    public function hasAttachment(): bool;

    public function resetAttachment(): ArticleInterface;

    public function setType(TypeInterface $type): ArticleInterface;

    public function getType(): TypeInterface;

    public function setClassifier(ClassifierInterface $classifier): ArticleInterface;

    public function getClassifier(): ClassifierInterface;
}
