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

namespace Evrinoma\ArticleBundle\DependencyInjection\Compiler\Constraint\Complex;

use Evrinoma\ArticleBundle\Validator\ArticleValidator;
use Evrinoma\UtilsBundle\DependencyInjection\Compiler\AbstractConstraint;
use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;

class ArticlePass extends AbstractConstraint implements CompilerPassInterface
{
    public const ARTICLE_CONSTRAINT = 'evrinoma.article.constraint.complex.article';

    protected static string $alias = self::ARTICLE_CONSTRAINT;
    protected static string $class = ArticleValidator::class;
    protected static string $methodCall = 'addConstraint';
}
