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

namespace Evrinoma\ArticleBundle\Model\Classifier;

use Evrinoma\UtilsBundle\Entity\ActiveInterface;
use Evrinoma\UtilsBundle\Entity\BriefInterface;
use Evrinoma\UtilsBundle\Entity\DescriptionInterface;
use Evrinoma\UtilsBundle\Entity\IdInterface;

interface ClassifierInterface extends ActiveInterface, IdInterface, DescriptionInterface, BriefInterface
{
}
