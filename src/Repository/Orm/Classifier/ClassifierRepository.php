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

namespace Evrinoma\ArticleBundle\Repository\Orm\Classifier;

use Doctrine\Persistence\ManagerRegistry;
use Evrinoma\ArticleBundle\Mediator\Classifier\QueryMediatorInterface;
use Evrinoma\ArticleBundle\Repository\Classifier\ClassifierRepositoryInterface;
use Evrinoma\ArticleBundle\Repository\Classifier\ClassifierRepositoryTrait;
use Evrinoma\UtilsBundle\Repository\Orm\RepositoryWrapper;
use Evrinoma\UtilsBundle\Repository\RepositoryWrapperInterface;

class ClassifierRepository extends RepositoryWrapper implements ClassifierRepositoryInterface, RepositoryWrapperInterface
{
    use ClassifierRepositoryTrait;

    /**
     * @param ManagerRegistry        $registry
     * @param string                 $entityClass
     * @param QueryMediatorInterface $mediator
     */
    public function __construct(ManagerRegistry $registry, string $entityClass, QueryMediatorInterface $mediator)
    {
        parent::__construct($registry, $entityClass);
        $this->mediator = $mediator;
    }
}
