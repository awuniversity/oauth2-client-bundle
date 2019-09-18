<?php

/*
 * OAuth2 Client Bundle
 * Copyright (c) AwUniversity <http://awuniversity.com/>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace AwU\OAuth2ClientBundle\DependencyInjection;

use AwU\OAuth2ClientBundle\DependencyInjection\Providers\AwProviderConfigurator;
use AwU\OAuth2ClientBundle\DependencyInjection\Providers\ProviderConfiguratorInterface;
use Symfony\Component\Config\Definition\Builder\NodeDefinition;
use Symfony\Component\Config\Definition\Builder\TreeBuilder;
use Symfony\Component\Config\Definition\Exception\InvalidConfigurationException;
use Symfony\Component\Config\Definition\Processor;
use Symfony\Component\DependencyInjection\Alias;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Reference;
use Symfony\Component\HttpKernel\DependencyInjection\Extension;
use Symfony\Component\DependencyInjection\Loader\XmlFileLoader;
use Symfony\Component\Config\FileLocator;

class AwUOAuth2ClientExtension extends Extension
{
    /** @var bool */
    private $checkExternalClassExistence;

    /** @var array */
    private $configurators = [];

    /** @var array */
    private $duplicateProviderTypes = [];

    /** @var array */
    private static $supportedProviderTypes = [
        'aw' => AwProviderConfigurator::class
    ];

    /**
     * AwUOAuth2ClientExtension constructor.
     *
     * @param bool $checkExternalClassExistence
     */
    public function __construct($checkExternalClassExistence = true)
    {
        $this->checkExternalClassExistence = $checkExternalClassExistence;
    }

    /**
     * Load the bundle configuration.
     *
     * @param array $configs
     * @param ContainerBuilder $container
     */
    public function load(array $configs, ContainerBuilder $container)
    {
        $processor = new Processor();
        $configuration = new Configuration();
        $config = $processor->processConfiguration($configuration, $configs);

        $loader = new XmlFileLoader($container, new FileLocator(__DIR__ . '/../Resources/config'));
        $loader->load('services.xml');

        $httpClient = $config['http_client'];
        $httpClientOptions = $config['http_client_options'];
        $clientConfigurations = $config['clients'];

        $clientServiceKeys = [];
        foreach ($clientConfigurations as $key => $clientConfig) {
            // manually make sure "type" is there
            if (!isset($clientConfig['type'])) {
                throw new InvalidConfigurationException(sprintf(
                    'Your "awu_oauth2_client.clients.%s" config entry is missing the "type" key.',
                    $key
                ));
            }

            $type = $clientConfig['type'];
            unset($clientConfig['type']);
            if (!isset(self::$supportedProviderTypes[$type])) {
                throw new InvalidConfigurationException(sprintf(
                    'The "awu_oauth2_client.clients" config "type" key "%s" is not supported. We support (%s)',
                    $type,
                    implode(', ', self::$supportedProviderTypes)
                ));
            }

            // process the configuration
            $tree = new TreeBuilder('awu_oauth2_client/clients/' . $key);
            $node = method_exists($tree, 'getRootNode')
                ? $tree->getRootNode()
                : $tree->root('awu_oauth2_client/clients/' . $key);

            $this->buildConfigurationForType($node, $type);
            $processor = new Processor();
            $config = $processor->process($tree->buildTree(), [$clientConfig]);

            $configurator = $this->getConfigurator($type);

            $providerOptions = $configurator->getProviderOptions($config);

            $collaborators = [];
            if ($httpClient) {
                $collaborators['httpClient'] = new Reference($httpClient);
            } else {
                $providerOptions = array_merge($providerOptions, $httpClientOptions);
            }
            // hey, we should add the provider/client service!
            $clientServiceKey = $this->configureProviderAndClient(
                $container,
                $type,
                $key,
                $configurator->getProviderClass($config),
                $configurator->getClientClass($config),
                $configurator->getPackagistName(),
                $providerOptions,
                $config['redirect_route'],
                $config['redirect_params'],
                $config['use_state'],
                $collaborators
            );

            $clientServiceKeys[$key] = $clientServiceKey;
        }

        $container->getDefinition('awu.oauth2.registry')
            ->replaceArgument(1, $clientServiceKeys);
    }

    /**
     * @param ContainerBuilder $container
     * @param string $providerType  The "type" used in the config - e.g. "facebook"
     * @param string $providerKey   The config key used for this - e.g. "facebook_client", "my_facebook"
     * @param string $providerClass Provider class
     * @param string $clientClass   Class to use for the Client
     * @param string $packageName   Packagist package name required
     * @param array $options        Options passed to when constructing the provider
     * @param string $redirectRoute Route name for the redirect URL
     * @param array $redirectParams Route params for the redirect URL
     * @param bool $useState
     * @param array $collaborators
     * @return string The client service id
     */
    private function configureProviderAndClient(ContainerBuilder $container, $providerType, $providerKey, $providerClass, $clientClass, $packageName, array $options, $redirectRoute, array $redirectParams, $useState, array $collaborators)
    {
        if ($this->checkExternalClassExistence && !class_exists($providerClass)) {
            throw new \LogicException(sprintf(
                'Run `composer require %s` in order to use the "%s" OAuth provider.',
                $packageName,
                $providerType
            ));
        }

        $providerServiceKey = sprintf('awu.oauth2.provider.%s', $providerKey);

        $providerDefinition = $container->register(
            $providerServiceKey,
            $providerClass
        );
        $providerDefinition->setPublic(false);

        $providerDefinition->setFactory([
            new Reference('awu.oauth2.provider_factory'),
            'createProvider',
        ]);

        $providerDefinition->setArguments([
            $providerClass,
            $options,
            $redirectRoute,
            $redirectParams,
            $collaborators,
        ]);

        $clientServiceKey = sprintf('awu.oauth2.client.%s', $providerKey);
        $clientDefinition = $container->register(
            $clientServiceKey,
            $clientClass
        );
        $clientDefinition->setArguments([
            new Reference($providerServiceKey),
            new Reference('request_stack'),
        ]);
        $clientDefinition->setPublic(true);

        // if stateless, do it!
        if (!$useState) {
            $clientDefinition->addMethodCall('setAsStateless');
        }

        // add an alias, but only if a provider type is used only 1 time
        if (!in_array($providerType, $this->duplicateProviderTypes, true)) {
            // alias already exists? This is a duplicate type, record it
            if ($container->hasAlias($clientClass)) {
                $this->duplicateProviderTypes[] = $providerType;
            } else {
                // all good, add the alias
                $container->setAlias($clientClass, new Alias($clientServiceKey, false));
            }
        }

        return $clientServiceKey;
    }

    public static function getAllSupportedTypes()
    {
        return array_keys(self::$supportedProviderTypes);
    }

   /**
    * @param string $type
    * @return ProviderConfiguratorInterface
    */
   public function getConfigurator($type)
   {
       if (!isset($this->configurators[$type])) {
           $class = self::$supportedProviderTypes[$type];

           $this->configurators[$type] = new $class();
       }

       return $this->configurators[$type];
   }

    /**
     * Overridden so the alias isn't "aw_uo_auth2_client".
     *
     * @return string
     */
    public function getAlias()
    {
        return 'awu_oauth2_client';
    }

    private function buildConfigurationForType(NodeDefinition $node, $type)
    {
        $optionsNode = $node->children();
        $optionsNode
            ->scalarNode('client_id')->isRequired()->end()
            ->scalarNode('client_secret')->isRequired()->end()
            ->scalarNode('redirect_route')->isRequired()->end()
            ->arrayNode('redirect_params')
                ->prototype('scalar')->end()
            ->end()
            ->booleanNode('use_state')->defaultValue(true)->end()
        ;

        // allow the specific provider to add more options
        $this->getConfigurator($type)
            ->buildConfiguration($optionsNode);
        $optionsNode->end();
    }
}
