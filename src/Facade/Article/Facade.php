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

namespace Evrinoma\ArticleBundle\Facade\Article;

use Evrinoma\ArticleBundle\Manager\Article\CommandManagerInterface;
use Evrinoma\ArticleBundle\Manager\Article\QueryManagerInterface;
use Evrinoma\ArticleBundle\PreValidator\Article\DtoPreValidatorInterface;
use Evrinoma\UtilsBundle\Adaptor\AdaptorRegistryInterface;
use Evrinoma\UtilsBundle\Facade\FacadeTrait;
use Evrinoma\UtilsBundle\Handler\HandlerInterface;

final class Facade implements FacadeInterface
{
    use FacadeTrait;

    protected CommandManagerInterface $commandManager;

    protected QueryManagerInterface $queryManager;

    protected DtoPreValidatorInterface $preValidator;

    public function __construct(
        CommandManagerInterface $commandManager,
        QueryManagerInterface $queryManager,
        AdaptorRegistryInterface $adaptorRegistry,
        DtoPreValidatorInterface $preValidator,
        HandlerInterface $handler
    ) {
        $this->adaptorRegistry = $adaptorRegistry;
        $this->commandManager = $commandManager;
        $this->queryManager = $queryManager;
        $this->preValidator = $preValidator;
        $this->handler = $handler;
    }
}
