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

namespace Evrinoma\ArticleBundle\Repository\Classifier;

use Evrinoma\ArticleBundle\Exception\Classifier\ClassifierCannotBeRemovedException;
use Evrinoma\ArticleBundle\Exception\Classifier\ClassifierCannotBeSavedException;
use Evrinoma\ArticleBundle\Model\Classifier\ClassifierInterface;

interface ClassifierCommandRepositoryInterface
{
    /**
     * @param ClassifierInterface $сlassifier
     *
     * @return bool
     *
     * @throws ClassifierCannotBeSavedException
     */
    public function save(ClassifierInterface $classifier): bool;

    /**
     * @param ClassifierInterface $сlassifier
     *
     * @return bool
     *
     * @throws ClassifierCannotBeRemovedException
     */
    public function remove(ClassifierInterface $classifier): bool;
}
