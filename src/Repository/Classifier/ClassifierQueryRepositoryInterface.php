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

use Doctrine\ORM\Exception\ORMException;
use Evrinoma\ArticleBundle\Dto\ClassifierApiDtoInterface;
use Evrinoma\ArticleBundle\Exception\Classifier\ClassifierNotFoundException;
use Evrinoma\ArticleBundle\Exception\Classifier\ClassifierProxyException;
use Evrinoma\ArticleBundle\Model\Classifier\ClassifierInterface;

interface ClassifierQueryRepositoryInterface
{
    /**
     * @param ClassifierApiDtoInterface $dto
     *
     * @return array
     *
     * @throws ClassifierNotFoundException
     */
    public function findByCriteria(ClassifierApiDtoInterface $dto): array;

    /**
     * @param string $id
     * @param null   $lockMode
     * @param null   $lockVersion
     *
     * @return ClassifierInterface
     *
     * @throws ClassifierNotFoundException
     */
    public function find(string $id, $lockMode = null, $lockVersion = null): ClassifierInterface;

    /**
     * @param string $id
     *
     * @return ClassifierInterface
     *
     * @throws ClassifierProxyException
     * @throws ORMException
     */
    public function proxy(string $id): ClassifierInterface;
}
