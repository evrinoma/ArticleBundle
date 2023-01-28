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
use Doctrine\ORM\ORMInvalidArgumentException;
use Evrinoma\ArticleBundle\Dto\ClassifierApiDtoInterface;
use Evrinoma\ArticleBundle\Exception\Classifier\ClassifierCannotBeSavedException;
use Evrinoma\ArticleBundle\Exception\Classifier\ClassifierNotFoundException;
use Evrinoma\ArticleBundle\Exception\Classifier\ClassifierProxyException;
use Evrinoma\ArticleBundle\Mediator\Classifier\QueryMediatorInterface;
use Evrinoma\ArticleBundle\Model\Classifier\ClassifierInterface;

trait ClassifierRepositoryTrait
{
    private QueryMediatorInterface $mediator;

    /**
     * @param ClassifierInterface $classifier
     *
     * @return bool
     *
     * @throws ClassifierCannotBeSavedException
     * @throws ORMException
     */
    public function save(ClassifierInterface $classifier): bool
    {
        try {
            $this->persistWrapped($classifier);
        } catch (ORMInvalidArgumentException $e) {
            throw new ClassifierCannotBeSavedException($e->getMessage());
        }

        return true;
    }

    /**
     * @param ClassifierInterface $classifier
     *
     * @return bool
     */
    public function remove(ClassifierInterface $classifier): bool
    {
        return true;
    }

    /**
     * @param ClassifierApiDtoInterface $dto
     *
     * @return array
     *
     * @throws ClassifierNotFoundException
     */
    public function findByCriteria(ClassifierApiDtoInterface $dto): array
    {
        $builder = $this->createQueryBuilderWrapped($this->mediator->alias());

        $this->mediator->createQuery($dto, $builder);

        $classifiers = $this->mediator->getResult($dto, $builder);

        if (0 === \count($classifiers)) {
            throw new ClassifierNotFoundException('Cannot find сlassifier by findByCriteria');
        }

        return $classifiers;
    }

    /**
     * @param      $id
     * @param null $lockMode
     * @param null $lockVersion
     *
     * @return mixed
     *
     * @throws ClassifierNotFoundException
     */
    public function find($id, $lockMode = null, $lockVersion = null): ClassifierInterface
    {
        /** @var ClassifierInterface $сlassifier */
        $classifier = $this->findWrapped($id);

        if (null === $classifier) {
            throw new ClassifierNotFoundException("Cannot find сlassifier with id $id");
        }

        return $classifier;
    }

    /**
     * @param string $id
     *
     * @return ClassifierInterface
     *
     * @throws ClassifierProxyException
     * @throws ORMException
     */
    public function proxy(string $id): ClassifierInterface
    {
        $classifier = $this->referenceWrapped($id);

        if (!$this->containsWrapped($classifier)) {
            throw new ClassifierProxyException("Proxy doesn't exist with $id");
        }

        return $classifier;
    }
}
