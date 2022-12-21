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

namespace Evrinoma\ArticleBundle;

use Evrinoma\ArticleBundle\DependencyInjection\Compiler\Constraint\Complex\ArticlePass;
use Evrinoma\ArticleBundle\DependencyInjection\Compiler\Constraint\Property\ArticlePass as PropertyArticlePass;
use Evrinoma\ArticleBundle\DependencyInjection\Compiler\Constraint\Property\ClassifierPass as PropertyClassifierPass;
use Evrinoma\ArticleBundle\DependencyInjection\Compiler\Constraint\Property\TypePass as PropertyTypePass;
use Evrinoma\ArticleBundle\DependencyInjection\Compiler\DecoratorPass;
use Evrinoma\ArticleBundle\DependencyInjection\Compiler\MapEntityPass;
use Evrinoma\ArticleBundle\DependencyInjection\Compiler\ServicePass;
use Evrinoma\ArticleBundle\DependencyInjection\EvrinomaArticleExtension;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\HttpKernel\Bundle\Bundle;

class EvrinomaArticleBundle extends Bundle
{
    public const BUNDLE = 'article';

    public function build(ContainerBuilder $container)
    {
        parent::build($container);
        $container
            ->addCompilerPass(new MapEntityPass($this->getNamespace(), $this->getPath()))
            ->addCompilerPass(new PropertyArticlePass())
            ->addCompilerPass(new PropertyClassifierPass())
            ->addCompilerPass(new PropertyTypePass())
            ->addCompilerPass(new ArticlePass())
            ->addCompilerPass(new DecoratorPass())
            ->addCompilerPass(new ServicePass())
        ;
    }

    public function getContainerExtension()
    {
        if (null === $this->extension) {
            $this->extension = new EvrinomaArticleExtension();
        }

        return $this->extension;
    }
}
