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
use Evrinoma\ArticleBundle\Exception\Classifier\ClassifierCannotBeCreatedException;
use Evrinoma\ArticleBundle\Exception\Classifier\ClassifierCannotBeRemovedException;
use Evrinoma\ArticleBundle\Exception\Classifier\ClassifierCannotBeSavedException;
use Evrinoma\ArticleBundle\Exception\Classifier\ClassifierInvalidException;
use Evrinoma\ArticleBundle\Exception\Classifier\ClassifierNotFoundException;
use Evrinoma\ArticleBundle\Factory\Classifier\FactoryInterface;
use Evrinoma\ArticleBundle\Mediator\Classifier\CommandMediatorInterface;
use Evrinoma\ArticleBundle\Model\Classifier\ClassifierInterface;
use Evrinoma\ArticleBundle\Repository\Classifier\ClassifierRepositoryInterface;
use Evrinoma\UtilsBundle\Validator\ValidatorInterface;

final class CommandManager implements CommandManagerInterface
{
    private ClassifierRepositoryInterface $repository;
    private ValidatorInterface            $validator;
    private FactoryInterface           $factory;
    private CommandMediatorInterface      $mediator;

    /**
     * @param ValidatorInterface            $validator
     * @param ClassifierRepositoryInterface $repository
     * @param FactoryInterface              $factory
     * @param CommandMediatorInterface      $mediator
     */
    public function __construct(ValidatorInterface $validator, ClassifierRepositoryInterface $repository, FactoryInterface $factory, CommandMediatorInterface $mediator)
    {
        $this->validator = $validator;
        $this->repository = $repository;
        $this->factory = $factory;
        $this->mediator = $mediator;
    }

    /**
     * @param ClassifierApiDtoInterface $dto
     *
     * @return ClassifierInterface
     *
     * @throws ClassifierInvalidException
     * @throws ClassifierCannotBeCreatedException
     * @throws ClassifierCannotBeSavedException
     */
    public function post(ClassifierApiDtoInterface $dto): ClassifierInterface
    {
        $classifier = $this->factory->create($dto);

        $this->mediator->onCreate($dto, $classifier);

        $errors = $this->validator->validate($classifier);

        if (\count($errors) > 0) {
            $errorsString = (string) $errors;

            throw new ClassifierInvalidException($errorsString);
        }

        $this->repository->save($classifier);

        return $classifier;
    }

    /**
     * @param ClassifierApiDtoInterface $dto
     *
     * @return ClassifierInterface
     *
     * @throws ClassifierInvalidException
     * @throws ClassifierNotFoundException
     * @throws ClassifierCannotBeSavedException
     */
    public function put(ClassifierApiDtoInterface $dto): ClassifierInterface
    {
        try {
            $classifier = $this->repository->find($dto->idToString());
        } catch (ClassifierNotFoundException $e) {
            throw $e;
        }

        $this->mediator->onUpdate($dto, $classifier);

        $errors = $this->validator->validate($classifier);

        if (\count($errors) > 0) {
            $errorsString = (string) $errors;

            throw new ClassifierInvalidException($errorsString);
        }

        $this->repository->save($classifier);

        return $classifier;
    }

    /**
     * @param ClassifierApiDtoInterface $dto
     *
     * @throws ClassifierCannotBeRemovedException
     * @throws ClassifierNotFoundException
     */
    public function delete(ClassifierApiDtoInterface $dto): void
    {
        try {
            $classifier = $this->repository->find($dto->idToString());
        } catch (ClassifierNotFoundException $e) {
            throw $e;
        }
        $this->mediator->onDelete($dto, $classifier);
        try {
            $this->repository->remove($classifier);
        } catch (ClassifierCannotBeRemovedException $e) {
            throw $e;
        }
    }
}
