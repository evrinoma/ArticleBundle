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

namespace Evrinoma\ArticleBundle\DependencyInjection\Compiler\Constraint\Property;

use Evrinoma\ArticleBundle\Validator\ClassifierValidator;
use Evrinoma\UtilsBundle\DependencyInjection\Compiler\AbstractConstraint;
use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;

class ClassifierPass extends AbstractConstraint implements CompilerPassInterface
{
    public const CLASSIFIER_CONSTRAINT = 'evrinoma.article.constraint.property.classifier';

    protected static string $alias = self::CLASSIFIER_CONSTRAINT;
    protected static string $class = ClassifierValidator::class;
    protected static string $methodCall = 'addPropertyConstraint';
}
