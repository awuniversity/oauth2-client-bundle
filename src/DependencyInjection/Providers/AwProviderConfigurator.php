<?php

/*
 * OAuth2 Client Bundle
 * Copyright (c) AwUniversity <http://awuniversity.com/>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace AwU\OAuth2ClientBundle\DependencyInjection\Providers;

use Symfony\Component\Config\Definition\Builder\NodeBuilder;

class AwProviderConfigurator implements ProviderConfiguratorInterface
{
    public function buildConfiguration(NodeBuilder $node)
    {
        $node
            ->scalarNode('access_type')
                ->defaultNull()
                ->info('Optional value for sending access_type parameter. More detail: https://awuniversity...')
            ->end()
            ->scalarNode('hosted_domain')
                ->defaultNull()
                ->info('Optional value for sending hd parameter. More detail: https://awuniversity')
            ->end()
            ->arrayNode('user_fields')
                ->prototype('scalar')->end()
                ->info('Optional value for additional fields to be requested from the user profile. If set, these values will be included with the defaults. More details: https://awuniversity...')
            ->end()
            ->booleanNode('use_oidc_mode')->defaultFalse()
                ->info('Optional value ...')
            ->end()
        ;
    }

    public function getProviderClass(array $config)
    {
        return 'AwU\OAuth2\Client\Provider\Aw';
    }

    public function getProviderOptions(array $config)
    {
        $options = [
            'clientId' => $config['client_id'],
            'clientSecret' => $config['client_secret'],
        ];

        if ($config['access_type']) {
            $options['accessType'] = $config['access_type'];
        }

        if ($config['hosted_domain']) {
            $options['hostedDomain'] = $config['hosted_domain'];
        }

        if (!empty($config['user_fields'])) {
            $options['userFields'] = $config['user_fields'];
        }

        if (!empty($config['use_oidc_mode'])) {
            $options['useOidcMode'] = $config['use_oidc_mode'];
        }

        return $options;
    }

    public function getPackagistName()
    {
        return 'awuniversity/oauth2-aw';
    }

    public function getLibraryHomepage()
    {
        return 'https://github.com/awuniversity/oauth2-aw';
    }

    public function getProviderDisplayName()
    {
        return 'Aw';
    }

    public function getClientClass(array $config)
    {
        return 'AwU\OAuth2ClientBundle\Client\Provider\AwClient';
    }
}
