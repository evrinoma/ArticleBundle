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

namespace Evrinoma\ArticleBundle\DependencyInjection;

use Evrinoma\ArticleBundle\DependencyInjection\Compiler\Constraint\Complex\ArticlePass;
use Evrinoma\ArticleBundle\DependencyInjection\Compiler\Constraint\Property\ArticlePass as PropertyArticlePass;
use Evrinoma\ArticleBundle\Dto\ArticleApiDto;
use Evrinoma\ArticleBundle\Dto\TypeApiDto;
use Evrinoma\ArticleBundle\Entity\Article\BaseArticle;
use Evrinoma\ArticleBundle\Entity\Type\BaseType;
use Evrinoma\ArticleBundle\EvrinomaArticleBundle;
use Evrinoma\ArticleBundle\Factory\Article\Factory as ArticleFactory;
use Evrinoma\ArticleBundle\Mediator\Article\QueryMediatorInterface as ArticleQueryMediatorInterface;
use Evrinoma\ArticleBundle\Mediator\Type\QueryMediatorInterface as TypeQueryMediatorInterface;
use Evrinoma\UtilsBundle\Adaptor\AdaptorRegistry;
use Evrinoma\UtilsBundle\DependencyInjection\HelperTrait;
use Evrinoma\UtilsBundle\Handler\BaseHandler;
use Symfony\Component\Config\FileLocator;
use Symfony\Component\DependencyInjection\Alias;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Definition;
use Symfony\Component\DependencyInjection\Extension\Extension;
use Symfony\Component\DependencyInjection\Loader\YamlFileLoader;
use Symfony\Component\DependencyInjection\Reference;

class EvrinomaArticleExtension extends Extension
{
    use HelperTrait;

    public const ENTITY = 'Evrinoma\ArticleBundle\Entity';
    public const MODEL = 'Evrinoma\ArticleBundle\Model';
    public const ENTITY_FACTORY_ARTICLE = ArticleFactory::class;
    public const ENTITY_BASE_ARTICLE = BaseArticle::class;
    public const DTO_BASE_ARTICLE = ArticleApiDto::class;
    public const HANDLER = BaseHandler::class;

    /**
     * @var array
     */
    private static array $doctrineDrivers = [
        'orm' => [
            'registry' => 'doctrine',
            'tag' => 'doctrine.event_subscriber',
        ],
    ];

    public function load(array $configs, ContainerBuilder $container)
    {
        $loader = new YamlFileLoader($container, new FileLocator(__DIR__.'/../Resources/config'));
        $loader->load('services.yml');

        if ('prod' !== $container->getParameter('kernel.environment')) {
            $loader->load('fixtures.yml');
        }

        if ('test' === $container->getParameter('kernel.environment')) {
            $loader->load('tests.yml');
        }

        $configuration = $this->getConfiguration($configs, $container);
        $config = $this->processConfiguration($configuration, $configs);

        if (self::ENTITY_FACTORY_ARTICLE !== $config['factory']) {
            $this->wireFactory($container, $config['factory'], $config['entity']);
        } else {
            $definitionFactory = $container->getDefinition('evrinoma.'.$this->getAlias().'.article.factory');
            $definitionFactory->setArgument(0, $config['entity']);
        }

        $registry = null;

        if (isset(self::$doctrineDrivers[$config['db_driver']]) && 'orm' === $config['db_driver']) {
            $loader->load('doctrine.yml');
            $container->setAlias('evrinoma.'.$this->getAlias().'.doctrine_registry', new Alias(self::$doctrineDrivers[$config['db_driver']]['registry'], false));
            $registry = new Reference('evrinoma.'.$this->getAlias().'.doctrine_registry');
            $container->setParameter('evrinoma.'.$this->getAlias().'.backend_type_'.$config['db_driver'], true);
            $objectManager = $container->getDefinition('evrinoma.'.$this->getAlias().'.object_manager');
            $objectManager->setFactory([$registry, 'getManager']);
        }

        if (isset(self::$doctrineDrivers[$config['db_driver']]) && 'api' === $config['db_driver']) {
            // @ToDo
        }

        if (null !== $registry) {
            $this->wireAdaptorRegistry($container, $registry);
        }

        $this->wireMediator($container, ArticleQueryMediatorInterface::class, $config['db_driver'], 'article');
        $this->wireMediator($container, TypeQueryMediatorInterface::class, $config['db_driver'], 'type');

        $this->remapParametersNamespaces(
            $container,
            $config,
            [
                '' => [
                    'db_driver' => 'evrinoma.'.$this->getAlias().'.storage',
                    'entity' => 'evrinoma.'.$this->getAlias().'.entity',
                ],
            ]
        );

        if ($registry && isset(self::$doctrineDrivers[$config['db_driver']])) {
            $this->wireRepository($container, $registry, ArticleQueryMediatorInterface::class, 'article', $config['entity'], $config['db_driver']);
            $this->wireRepository($container, $registry, TypeQueryMediatorInterface::class, 'type', BaseType::class, $config['db_driver']);
        }

        $this->wireController($container, 'article', $config['dto']);
        $this->wireController($container, 'type', TypeApiDto::class);

        $this->wireValidator($container, 'article', $config['entity']);
        $this->wireValidator($container, 'type', BaseType::class);

        if ($config['constraints']) {
            $loader->load('validation.yml');
        }

        $this->wireConstraintTag($container);

        if ($config['decorates']) {
            $remap = [];
            foreach ($config['decorates'] as $key => $service) {
                if (null !== $service) {
                    switch ($key) {
                        case 'command':
                            $remap['command'] = 'evrinoma.'.$this->getAlias().'.decorates.command';
                            break;
                        case 'query':
                            $remap['query'] = 'evrinoma.'.$this->getAlias().'.decorates.query';
                            break;
                    }
                }
            }

            $this->remapParametersNamespaces(
                $container,
                $config['decorates'],
                ['' => $remap]
            );
        }

        if ($config['services']) {
            $remap = [];
            foreach ($config['services'] as $key => $service) {
                if (null !== $service) {
                    switch ($key) {
                        case 'pre_validator':
                            $remap['pre_validator'] = 'evrinoma.'.$this->getAlias().'.services.pre.validator';
                            break;
                        case 'handler':
                            $remap['handler'] = 'evrinoma.'.$this->getAlias().'.services.handler';
                            break;
                        case 'file_system':
                            $remap['file_system'] = 'evrinoma.'.$this->getAlias().'.services.system.file_system';
                            break;
                    }
                }
            }

            $this->remapParametersNamespaces(
                $container,
                $config['services'],
                ['' => $remap]
            );
        }
    }

    private function wireConstraintTag(ContainerBuilder $container): void
    {
        foreach ($container->getDefinitions() as $key => $definition) {
            switch (true) {
                case str_contains($key, PropertyArticlePass::ARTICLE_CONSTRAINT)   :
                    $definition->addTag(PropertyArticlePass::ARTICLE_CONSTRAINT);
                    break;
//                case false !== strpos($key, ArticlePass::ARTICLE_CONSTRAINT):
//                    $definition->addTag(ArticlePass::ARTICLE_CONSTRAINT);
//                    break;
                default:
            }
        }
    }

    private function wireMediator(ContainerBuilder $container, string $class, string $driver, string $name): void
    {
        $definitionQueryMediator = $container->getDefinition('evrinoma.'.$this->getAlias().'.'.$name.'.query.'.$driver.'.mediator');
        $container->addDefinitions([$class => $definitionQueryMediator]);
    }

    private function wireAdaptorRegistry(ContainerBuilder $container, Reference $registry): void
    {
        $definitionAdaptor = new Definition(AdaptorRegistry::class);
        $definitionAdaptor->addArgument($registry);
        $container->addDefinitions(['evrinoma.'.$this->getAlias().'.adaptor' => $definitionAdaptor]);
    }

    private function wireRepository(ContainerBuilder $container, Reference $registry, string $madiator, string $name, string $class, string $driver): void
    {
        $definitionRepository = $container->getDefinition('evrinoma.'.$this->getAlias().'.'.$name.'.'.$driver.'.repository');
        $definitionQueryMediator = $container->getDefinition($madiator);
        $definitionRepository->setArgument(0, $registry);
        $definitionRepository->setArgument(1, $class);
        $definitionRepository->setArgument(2, $definitionQueryMediator);
        $array = $definitionRepository->getArguments();
        ksort($array);
        $definitionRepository->setArguments($array);
        $container->addDefinitions(['evrinoma.'.$this->getAlias().'.'.$name.'.repository' => $definitionRepository]);
    }

    private function wireFactory(ContainerBuilder $container, string $name, string $class, string $paramClass): void
    {
        $container->removeDefinition('evrinoma.'.$this->getAlias().'.'.$name.'.factory');
        $definitionFactory = new Definition($class);
        $definitionFactory->addArgument($paramClass);
        $alias = new Alias('evrinoma.'.$this->getAlias().'.'.$name.'.factory');
        $container->addDefinitions(['evrinoma.'.$this->getAlias().'.'.$name.'.factory' => $definitionFactory]);
        $container->addAliases([$class => $alias]);
    }

    private function wireController(ContainerBuilder $container, string $name, string $class): void
    {
        $definitionApiController = $container->getDefinition('evrinoma.'.$this->getAlias().'.'.$name.'.api.controller');
        $definitionApiController->setArgument(4, $class);
    }

    private function wireValidator(ContainerBuilder $container, string $name, string $class): void
    {
        $definitionApiController = $container->getDefinition('evrinoma.'.$this->getAlias().'.'.$name.'.validator');
        $definitionApiController->setArgument(0, new Reference('validator'));
        $definitionApiController->setArgument(1, $class);
    }

    public function getAlias()
    {
        return EvrinomaArticleBundle::BUNDLE;
    }
}
