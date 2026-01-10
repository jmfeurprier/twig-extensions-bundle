<?php

namespace Jmf\TwigExtensionsBundle;

use Jmf\Twig\Extension\Array\ArrayExtension;
use Jmf\Twig\Extension\Currency\CurrencyExtension;
use Jmf\Twig\Extension\Inline\InlineExtension;
use Jmf\Twig\Extension\Sort\SortExtension;
use Jmf\Twig\Extension\Time\TimeExtension;
use Jmf\Twig\Extension\Type\TypeExtension;
use Override;
use Symfony\Component\Config\Definition\Configurator\DefinitionConfigurator;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Loader\Configurator\ContainerConfigurator;
use Symfony\Component\HttpKernel\Bundle\AbstractBundle;

class JmfTwigExtensionsBundle extends AbstractBundle
{
    protected string $extensionAlias = 'jmf_twig_extensions';

    #[Override]
    public function configure(DefinitionConfigurator $definition): void
    {
        $definition->import('../config/definition.php');
    }

    #[Override]
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
            'array.prefix'     => $config['array']['prefix'],
            'currency.prefix'  => $config['currency']['prefix'],
            'inline.base_path' => $config['inline']['basePath'],
            'inline.prefix'    => $config['inline']['prefix'],
            'sort.prefix'      => $config['sort']['prefix'],
            'time.locale'      => $config['time']['locale'],
            'time.prefix'      => $config['time']['prefix'],
            'type.prefix'      => $config['type']['prefix'],
        ];

        foreach ($map as $parameter => $value) {
            $container->parameters()->set(
                "{$this->extensionAlias}.{$parameter}",
                $value,
            );
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
