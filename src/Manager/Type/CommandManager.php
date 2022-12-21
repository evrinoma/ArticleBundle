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

namespace Evrinoma\ArticleBundle\Manager\Type;

use Evrinoma\ArticleBundle\Dto\TypeApiDtoInterface;
use Evrinoma\ArticleBundle\Exception\Type\TypeCannotBeCreatedException;
use Evrinoma\ArticleBundle\Exception\Type\TypeCannotBeRemovedException;
use Evrinoma\ArticleBundle\Exception\Type\TypeCannotBeSavedException;
use Evrinoma\ArticleBundle\Exception\Type\TypeInvalidException;
use Evrinoma\ArticleBundle\Exception\Type\TypeNotFoundException;
use Evrinoma\ArticleBundle\Factory\Type\FactoryInterface;
use Evrinoma\ArticleBundle\Mediator\Type\CommandMediatorInterface;
use Evrinoma\ArticleBundle\Model\Type\TypeInterface;
use Evrinoma\ArticleBundle\Repository\Type\TypeRepositoryInterface;
use Evrinoma\UtilsBundle\Validator\ValidatorInterface;

final class CommandManager implements CommandManagerInterface
{
    private TypeRepositoryInterface $repository;
    private ValidatorInterface            $validator;
    private FactoryInterface           $factory;
    private CommandMediatorInterface      $mediator;

    /**
     * @param ValidatorInterface       $validator
     * @param TypeRepositoryInterface  $repository
     * @param FactoryInterface         $factory
     * @param CommandMediatorInterface $mediator
     */
    public function __construct(ValidatorInterface $validator, TypeRepositoryInterface $repository, FactoryInterface $factory, CommandMediatorInterface $mediator)
    {
        $this->validator = $validator;
        $this->repository = $repository;
        $this->factory = $factory;
        $this->mediator = $mediator;
    }

    /**
     * @param TypeApiDtoInterface $dto
     *
     * @return TypeInterface
     *
     * @throws TypeInvalidException
     * @throws TypeCannotBeCreatedException
     * @throws TypeCannotBeSavedException
     */
    public function post(TypeApiDtoInterface $dto): TypeInterface
    {
        $article = $this->factory->create($dto);

        $this->mediator->onCreate($dto, $article);

        $errors = $this->validator->validate($article);

        if (\count($errors) > 0) {
            $errorsString = (string) $errors;

            throw new TypeInvalidException($errorsString);
        }

        $this->repository->save($article);

        return $article;
    }

    /**
     * @param TypeApiDtoInterface $dto
     *
     * @return TypeInterface
     *
     * @throws TypeInvalidException
     * @throws TypeNotFoundException
     * @throws TypeCannotBeSavedException
     */
    public function put(TypeApiDtoInterface $dto): TypeInterface
    {
        try {
            $article = $this->repository->find($dto->idToString());
        } catch (TypeNotFoundException $e) {
            throw $e;
        }

        $this->mediator->onUpdate($dto, $article);

        $errors = $this->validator->validate($article);

        if (\count($errors) > 0) {
            $errorsString = (string) $errors;

            throw new TypeInvalidException($errorsString);
        }

        $this->repository->save($article);

        return $article;
    }

    /**
     * @param TypeApiDtoInterface $dto
     *
     * @throws TypeCannotBeRemovedException
     * @throws TypeNotFoundException
     */
    public function delete(TypeApiDtoInterface $dto): void
    {
        try {
            $article = $this->repository->find($dto->idToString());
        } catch (TypeNotFoundException $e) {
            throw $e;
        }
        $this->mediator->onDelete($dto, $article);
        try {
            $this->repository->remove($article);
        } catch (TypeCannotBeRemovedException $e) {
            throw $e;
        }
    }
}
