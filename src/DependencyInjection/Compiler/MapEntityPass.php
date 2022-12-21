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

namespace Evrinoma\ArticleBundle\DependencyInjection\Compiler;

use Evrinoma\ArticleBundle\DependencyInjection\EvrinomaArticleExtension;
use Evrinoma\ArticleBundle\Entity\Type\BaseType;
use Evrinoma\ArticleBundle\Model\Article\ArticleInterface;
use Evrinoma\ArticleBundle\Model\Type\TypeInterface;
use Evrinoma\UtilsBundle\DependencyInjection\Compiler\AbstractMapEntity;
use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Reference;

class MapEntityPass extends AbstractMapEntity implements CompilerPassInterface
{
    /**
     * {@inheritDoc}
     */
    public function process(ContainerBuilder $container)
    {
        if ('orm' === $container->getParameter('evrinoma.article.storage')) {
            $this->setContainer($container);

            $driver = $container->findDefinition('doctrine.orm.default_metadata_driver');
            $referenceAnnotationReader = new Reference('annotations.reader');

            $this->cleanMetadata($driver, [EvrinomaArticleExtension::ENTITY]);

            $this->loadMetadata($driver, $referenceAnnotationReader, '%s/Model/Type', '%s/Entity/Type');
            $this->addResolveTargetEntity(
                [
                    BaseType::class => [TypeInterface::class => []],
                ],
                false
            );

            $entityArticle = $container->getParameter('evrinoma.article.entity');
            if (str_contains($entityArticle, EvrinomaArticleExtension::ENTITY)) {
                $this->loadMetadata($driver, $referenceAnnotationReader, '%s/Model/Article', '%s/Entity/Article');
            }
            $this->addResolveTargetEntity([$entityArticle => [ArticleInterface::class => []]], false);
        }
    }
}
