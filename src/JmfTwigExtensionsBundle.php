<?php

declare(strict_types=1);

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

    /**
     * @param array{
     *     enabled: bool,
     *     prefix: string,
     *     extensions: array{
     *         array:    array{enabled: bool|null, prefix: string|null},
     *         currency: array{enabled: bool|null, prefix: string|null},
     *         inline:   array{basePath: string, enabled: bool|null, prefix: string|null},
     *         sort:     array{enabled: bool|null, prefix: string|null},
     *         time:     array{enabled: bool|null, locale: string|null, prefix: string|null},
     *         type:     array{enabled: bool|null, prefix: string|null},
     *     },
     * } $config
     */
    #[Override]
    public function loadExtension(
        array $config,
        ContainerConfigurator $configurator,
        ContainerBuilder $container,
    ): void {
        $configurator->import('../config/services.yaml');

        $this->loadParameters($config, $configurator);
        $this->disableServices($config, $configurator);
    }

    /**
     * @param array{
     *     enabled: bool,
     *     prefix: string,
     *     extensions: array{
     *         array:    array{enabled: bool|null, prefix: string|null},
     *         currency: array{enabled: bool|null, prefix: string|null},
     *         inline:   array{basePath: string, enabled: bool|null, prefix: string|null},
     *         sort:     array{enabled: bool|null, prefix: string|null},
     *         time:     array{enabled: bool|null, locale: string|null, prefix: string|null},
     *         type:     array{enabled: bool|null, prefix: string|null},
     *     },
     * } $config
     */
    private function loadParameters(
        array $config,
        ContainerConfigurator $containerConfigurator,
    ): void {
        $rootPrefix = $config['prefix'];

        $map = [
            'array.prefix'     => $config['extensions']['array']['prefix'] ?? $rootPrefix,
            'currency.prefix'  => $config['extensions']['currency']['prefix'] ?? $rootPrefix,
            'inline.base_path' => $config['extensions']['inline']['basePath'],
            'inline.prefix'    => $config['extensions']['inline']['prefix'] ?? $rootPrefix,
            'sort.prefix'      => $config['extensions']['sort']['prefix'] ?? $rootPrefix,
            'time.locale'      => $config['extensions']['time']['locale'],
            'time.prefix'      => $config['extensions']['time']['prefix'] ?? $rootPrefix,
            'type.prefix'      => $config['extensions']['type']['prefix'] ?? $rootPrefix,
        ];

        foreach ($map as $parameter => $value) {
            $containerConfigurator->parameters()->set(
                "{$this->extensionAlias}.{$parameter}",
                $value,
            );
        }
    }

    /**
     * @param array{
     *     enabled: bool,
     *     prefix: string,
     *     extensions: array{
     *         array:    array{enabled: bool|null, prefix: string|null},
     *         currency: array{enabled: bool|null, prefix: string|null},
     *         inline:   array{enabled: bool|null, prefix: string|null, basePath: string},
     *         sort:     array{enabled: bool|null, prefix: string|null},
     *         time:     array{enabled: bool|null, locale: string|null, prefix: string|null},
     *         type:     array{enabled: bool|null, prefix: string|null},
     *     },
     * } $config
     */
    private function disableServices(
        array $config,
        ContainerConfigurator $containerConfigurator,
    ): void {
        $map = [
            'array'    => ArrayExtension::class,
            'currency' => CurrencyExtension::class,
            'inline'   => InlineExtension::class,
            'sort'     => SortExtension::class,
            'time'     => TimeExtension::class,
            'type'     => TypeExtension::class,
        ];

        $rootEnabled = $config['enabled'];

        foreach ($map as $key => $class) {
            $enabled = $config['extensions'][$key]['enabled'] ?? $rootEnabled;

            if (!$enabled) {
                $containerConfigurator->services()->remove($class);
            }
        }
    }
}
