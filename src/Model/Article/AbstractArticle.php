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

use Doctrine\ORM\Mapping as ORM;
use Evrinoma\ArticleBundle\Model\Classifier\ClassifierInterface;
use Evrinoma\ArticleBundle\Model\Type\TypeInterface;
use Evrinoma\UtilsBundle\Entity\ActiveTrait;
use Evrinoma\UtilsBundle\Entity\AttachmentTrait;
use Evrinoma\UtilsBundle\Entity\BodyTrait;
use Evrinoma\UtilsBundle\Entity\CreateUpdateAtTrait;
use Evrinoma\UtilsBundle\Entity\IdTrait;
use Evrinoma\UtilsBundle\Entity\ImageTrait;
use Evrinoma\UtilsBundle\Entity\PositionTrait;
use Evrinoma\UtilsBundle\Entity\PreviewTrait;
use Evrinoma\UtilsBundle\Entity\TitleTrait;

/**
 * @ORM\MappedSuperclass
 */
abstract class AbstractArticle implements ArticleInterface
{
    use ActiveTrait;
    use AttachmentTrait;
    use BodyTrait;
    use CreateUpdateAtTrait;
    use IdTrait;
    use ImageTrait;
    use PositionTrait;
    use PreviewTrait;
    use TitleTrait;

    /**
     * @ORM\ManyToOne(targetEntity="Evrinoma\ArticleBundle\Model\Type\TypeInterface")
     * @ORM\JoinColumn(name="type_id", referencedColumnName="id")
     */
    protected TypeInterface $type;

    /**
     * @ORM\ManyToOne(targetEntity="Evrinoma\ArticleBundle\Model\Classifier\ClassifierInterface")
     * @ORM\JoinColumn(name="classifier_id", referencedColumnName="id")
     */
    protected ClassifierInterface $classifier;

    /**
     * @ORM\Column(name="attachment", type="string", length=2047, nullable=true)
     */
    protected $attachment = null;

    /**
     * @return ArticleInterface
     */
    public function resetAttachment(): ArticleInterface
    {
        $this->attachment = null;

        return $this;
    }

    public function hasAttachment(): bool
    {
        return null !== $this->attachment;
    }

    /**
     * @return TypeInterface
     */
    public function getType(): TypeInterface
    {
        return $this->type;
    }

    /**
     * @param TypeInterface $type
     *
     *  @return ArticleInterface
     */
    public function setType(TypeInterface $type): ArticleInterface
    {
        $this->type = $type;

        return $this;
    }

    /**
     * @return ClassifierInterface
     */
    public function getClassifier(): ClassifierInterface
    {
        return $this->classifier;
    }

    /**
     * @param ClassifierInterface $classifier
     *
     *  @return ArticleInterface
     */
    public function setClassifier(ClassifierInterface $classifier): ArticleInterface
    {
        $this->classifier = $classifier;

        return $this;
    }
}
