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

namespace Evrinoma\ArticleBundle\Manager\Article;

use Evrinoma\ArticleBundle\Dto\ArticleApiDtoInterface;
use Evrinoma\ArticleBundle\Exception\Article\ArticleCannotBeCreatedException;
use Evrinoma\ArticleBundle\Exception\Article\ArticleCannotBeRemovedException;
use Evrinoma\ArticleBundle\Exception\Article\ArticleCannotBeSavedException;
use Evrinoma\ArticleBundle\Exception\Article\ArticleInvalidException;
use Evrinoma\ArticleBundle\Exception\Article\ArticleNotFoundException;
use Evrinoma\ArticleBundle\Factory\Article\FactoryInterface;
use Evrinoma\ArticleBundle\Mediator\Article\CommandMediatorInterface;
use Evrinoma\ArticleBundle\Model\Article\ArticleInterface;
use Evrinoma\ArticleBundle\Repository\Article\ArticleRepositoryInterface;
use Evrinoma\UtilsBundle\Validator\ValidatorInterface;

final class CommandManager implements CommandManagerInterface
{
    private ArticleRepositoryInterface $repository;
    private ValidatorInterface            $validator;
    private FactoryInterface           $factory;
    private CommandMediatorInterface      $mediator;

    /**
     * @param ValidatorInterface         $validator
     * @param ArticleRepositoryInterface $repository
     * @param FactoryInterface           $factory
     * @param CommandMediatorInterface   $mediator
     */
    public function __construct(ValidatorInterface $validator, ArticleRepositoryInterface $repository, FactoryInterface $factory, CommandMediatorInterface $mediator)
    {
        $this->validator = $validator;
        $this->repository = $repository;
        $this->factory = $factory;
        $this->mediator = $mediator;
    }

    /**
     * @param ArticleApiDtoInterface $dto
     *
     * @return ArticleInterface
     *
     * @throws ArticleInvalidException
     * @throws ArticleCannotBeCreatedException
     * @throws ArticleCannotBeSavedException
     */
    public function post(ArticleApiDtoInterface $dto): ArticleInterface
    {
        $article = $this->factory->create($dto);

        $this->mediator->onCreate($dto, $article);

        $errors = $this->validator->validate($article);

        if (\count($errors) > 0) {
            $errorsString = (string) $errors;

            throw new ArticleInvalidException($errorsString);
        }

        $this->repository->save($article);

        return $article;
    }

    /**
     * @param ArticleApiDtoInterface $dto
     *
     * @return ArticleInterface
     *
     * @throws ArticleInvalidException
     * @throws ArticleNotFoundException
     * @throws ArticleCannotBeSavedException
     */
    public function put(ArticleApiDtoInterface $dto): ArticleInterface
    {
        try {
            $article = $this->repository->find($dto->idToString());
        } catch (ArticleNotFoundException $e) {
            throw $e;
        }

        $this->mediator->onUpdate($dto, $article);

        $errors = $this->validator->validate($article);

        if (\count($errors) > 0) {
            $errorsString = (string) $errors;

            throw new ArticleInvalidException($errorsString);
        }

        $this->repository->save($article);

        return $article;
    }

    /**
     * @param ArticleApiDtoInterface $dto
     *
     * @throws ArticleCannotBeRemovedException
     * @throws ArticleNotFoundException
     */
    public function delete(ArticleApiDtoInterface $dto): void
    {
        try {
            $article = $this->repository->find($dto->idToString());
        } catch (ArticleNotFoundException $e) {
            throw $e;
        }
        $this->mediator->onDelete($dto, $article);
        try {
            $this->repository->remove($article);
        } catch (ArticleCannotBeRemovedException $e) {
            throw $e;
        }
    }
}
