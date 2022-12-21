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

namespace Evrinoma\ArticleBundle\Factory\Classifier;

use Evrinoma\ArticleBundle\Dto\ClassifierApiDtoInterface;
use Evrinoma\ArticleBundle\Entity\Classifier\BaseClassifier;
use Evrinoma\ArticleBundle\Model\Classifier\ClassifierInterface;

class Factory implements FactoryInterface
{
    private static string $entityClass = BaseClassifier::class;

    /**
     * @param ClassifierApiDtoInterface $dto
     *
     * @return ClassifierInterface
     */
    public function create(ClassifierApiDtoInterface $dto): ClassifierInterface
    {
        /* @var BaseClassifier $article */
        return new self::$entityClass();
    }
}
