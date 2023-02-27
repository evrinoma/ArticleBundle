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

namespace Evrinoma\ArticleBundle\Manager\Classifier;

use Evrinoma\ArticleBundle\Dto\ClassifierApiDtoInterface;
use Evrinoma\ArticleBundle\Exception\Classifier\ClassifierNotFoundException;
use Evrinoma\ArticleBundle\Exception\Classifier\ClassifierProxyException;
use Evrinoma\ArticleBundle\Model\Classifier\ClassifierInterface;
use Evrinoma\ArticleBundle\Repository\Classifier\ClassifierQueryRepositoryInterface;

final class QueryManager implements QueryManagerInterface
{
    private ClassifierQueryRepositoryInterface $repository;

    public function __construct(ClassifierQueryRepositoryInterface $repository)
    {
        $this->repository = $repository;
    }

    /**
     * @param ClassifierApiDtoInterface $dto
     *
     * @return array
     *
     * @throws ClassifierNotFoundException
     */
    public function criteria(ClassifierApiDtoInterface $dto): array
    {
        try {
            $classifier = $this->repository->findByCriteria($dto);
        } catch (ClassifierNotFoundException $e) {
            throw $e;
        }

        return $classifier;
    }

    /**
     * @param ClassifierApiDtoInterface $dto
     *
     * @return ClassifierInterface
     *
     * @throws ClassifierProxyException
     */
    public function proxy(ClassifierApiDtoInterface $dto): ClassifierInterface
    {
        try {
            if ($dto->hasId()) {
                $classifier = $this->repository->proxy($dto->idToString());
            } else {
                throw new ClassifierProxyException('Id value is not set while trying get proxy object');
            }
        } catch (ClassifierProxyException $e) {
            throw $e;
        }

        return $classifier;
    }

    /**
     * @param ClassifierApiDtoInterface $dto
     *
     * @return ClassifierInterface
     *
     * @throws ClassifierNotFoundException
     */
    public function get(ClassifierApiDtoInterface $dto): ClassifierInterface
    {
        try {
            $classifier = $this->repository->find($dto->idToString());
        } catch (ClassifierNotFoundException $e) {
            throw $e;
        }

        return $classifier;
    }
}
