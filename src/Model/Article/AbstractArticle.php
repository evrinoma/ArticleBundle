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
use Evrinoma\UtilsBundle\Entity\DescriptionTrait;
use Evrinoma\UtilsBundle\Entity\IdTrait;
use Evrinoma\UtilsBundle\Entity\ImageTrait;
use Evrinoma\UtilsBundle\Entity\PositionTrait;
use Evrinoma\UtilsBundle\Entity\PreviewTrait;
use Evrinoma\UtilsBundle\Entity\StartTrait;
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
    use DescriptionTrait;
    use IdTrait;
    use ImageTrait;
    use PositionTrait;
    use PreviewTrait;
    use StartTrait;
    use TitleTrait;

    /**
     * @var string
     *
     * @ORM\Column(name="description", type="text", length=65535, nullable=false)
     */
    protected $description;

    /**
     * @ORM\ManyToOne(targetEntity="Evrinoma\ArticleBundle\Model\Type\TypeInterface")
     * @ORM\JoinColumn(name="type_id", referencedColumnName="id", nullable=false)
     */
    protected TypeInterface $type;

    /**
     * @ORM\ManyToOne(targetEntity="Evrinoma\ArticleBundle\Model\Classifier\ClassifierInterface")
     * @ORM\JoinColumn(name="classifier_id", referencedColumnName="id", nullable=false)
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
