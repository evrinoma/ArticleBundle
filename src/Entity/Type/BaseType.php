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

namespace Evrinoma\ArticleBundle\Entity\Type;

use Doctrine\ORM\Mapping as ORM;
use Evrinoma\ArticleBundle\Model\Article\AbstractArticle;

/**
 * @ORM\Table(name="e_article_type")
 * @ORM\Entity
 */
class BaseType extends AbstractArticle
{
}
