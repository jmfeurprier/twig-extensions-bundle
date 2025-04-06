<?php

namespace Jmf\TwigExtensionsBundle;

use Jmf\Twig\Extension\Array\ArrayExtension;
use Jmf\Twig\Extension\Currency\CurrencyExtension;
use Jmf\Twig\Extension\Inline\InlineExtension;
use Jmf\Twig\Extension\Sort\SortExtension;
use Jmf\Twig\Extension\Time\TimeExtension;
use Jmf\Twig\Extension\Type\TypeExtension;
use Symfony\Component\Config\Definition\Configurator\DefinitionConfigurator;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Loader\Configurator\ContainerConfigurator;
use Symfony\Component\HttpKernel\Bundle\AbstractBundle;

class JmfTwigExtensionsBundle extends AbstractBundle
{
    protected string $extensionAlias = 'jmf_twig_extensions';

    public function configure(DefinitionConfigurator $definition): void
    {
        $definition->rootNode()
            ->children()
                ->arrayNode('array')
                    ->info('Twig "array" extension configuration.')
                    ->addDefaultsIfNotSet()
                    ->children()
                        ->booleanNode('enabled')
                            ->defaultTrue()
                        ->end()
                        ->scalarNode('prefix')
                            ->info('Optional prefix before function and filter names.')
                            ->defaultValue('')
                        ->end()
                    ->end()
                ->end()
                ->arrayNode('currency')
                    ->info('Twig "currency" extension configuration.')
                    ->addDefaultsIfNotSet()
                    ->children()
                        ->booleanNode('enabled')
                            ->defaultTrue()
                        ->end()
                        ->scalarNode('prefix')
                            ->info('Optional prefix before function and filter names.')
                            ->defaultValue('')
                        ->end()
                    ->end()
                ->end()
                ->arrayNode('inline')
                    ->info('Twig "inline" extension configuration.')
                    ->addDefaultsIfNotSet()
                    ->children()
                        ->scalarNode('basePath')
                            ->info(
                                'Base path to restrict file inlining. ' .
                                'Also, all calls to the "inline" function will be relative to this path.',
                            )
                            ->cannotBeEmpty()
                            ->defaultValue('%kernel.project_dir%/templates')
                        ->end()
                        ->booleanNode('enabled')
                            ->defaultTrue()
                        ->end()
                        ->scalarNode('prefix')
                            ->info('Optional prefix before function and filter names.')
                            ->defaultValue('')
                        ->end()
                    ->end()
                ->end()
                ->arrayNode('sort')
                    ->info('Twig "sort" extension configuration.')
                    ->addDefaultsIfNotSet()
                    ->children()
                        ->booleanNode('enabled')
                            ->defaultTrue()
                        ->end()
                        ->scalarNode('prefix')
                            ->info('Optional prefix before function and filter names.')
                            ->defaultValue('')
                        ->end()
                    ->end()
                ->end()
                ->arrayNode('time')
                    ->info('Twig "time" extension configuration.')
                    ->addDefaultsIfNotSet()
                    ->children()
                        ->booleanNode('enabled')
                            ->defaultTrue()
                        ->end()
                        ->scalarNode('locale')
                            ->info('Optional locale for date and time representation.')
                            ->defaultNull()
                        ->end()
                        ->scalarNode('prefix')
                            ->info('Optional prefix before function and filter names.')
                            ->defaultValue('')
                        ->end()
                    ->end()
                ->end()
                ->arrayNode('type')
                    ->info('Twig "type" extension configuration.')
                    ->addDefaultsIfNotSet()
                    ->children()
                        ->booleanNode('enabled')
                            ->defaultTrue()
                        ->end()
                        ->scalarNode('prefix')
                            ->info('Optional prefix before function and filter names.')
                            ->defaultValue('')
                        ->end()
                    ->end()
                ->end()
            ->end()
        ;
    }

    public function loadExtension(
        array $config,
        ContainerConfigurator $container,
        ContainerBuilder $builder,
    ): void {
        $container->import('../config/services.yaml');

        $this->loadParameters($config, $container);
        $this->disableServices($config, $container);
    }

    /**
     * @param array<string, mixed> $config
     */
    private function loadParameters(
        array $config,
        ContainerConfigurator $container,
    ): void {
        $map = [
            'jmf_twig_extensions.array.prefix'     => $config['array']['prefix'],
            'jmf_twig_extensions.currency.prefix'  => $config['currency']['prefix'],
            'jmf_twig_extensions.inline.base_path' => $config['inline']['basePath'],
            'jmf_twig_extensions.inline.prefix'    => $config['inline']['prefix'],
            'jmf_twig_extensions.sort.prefix'      => $config['sort']['prefix'],
            'jmf_twig_extensions.time.locale'      => $config['time']['locale'],
            'jmf_twig_extensions.time.prefix'      => $config['time']['prefix'],
            'jmf_twig_extensions.type.prefix'      => $config['type']['prefix'],
        ];

        foreach ($map as $parameter => $value) {
            $container->parameters()->set($parameter, $value);
        }
    }

    /**
     * @param array<string, mixed> $config
     */
    private function disableServices(
        array $config,
        ContainerConfigurator $container,
    ): void {
        $map = [
            'array'    => ArrayExtension::class,
            'currency' => CurrencyExtension::class,
            'inline'   => InlineExtension::class,
            'sort'     => SortExtension::class,
            'time'     => TimeExtension::class,
            'type'     => TypeExtension::class,
        ];

        foreach ($map as $key => $class) {
            if (!($config[$key]['enabled'] ?? true)) {
                $container->services()->remove($class);
            }
        }
    }
}
