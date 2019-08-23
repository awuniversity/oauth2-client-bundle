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

class DribbbleProviderConfigurator implements ProviderConfiguratorInterface
{
    public function buildConfiguration(NodeBuilder $node)
    {
        // no custom options
    }

    public function getProviderClass(array $config)
    {
        return 'CrewLabs\OAuth2\Client\Provider\Dribbble';
    }

    public function getProviderOptions(array $config)
    {
        return [
            'clientId' => $config['client_id'],
            'clientSecret' => $config['client_secret'],
        ];
    }

    public function getPackagistName()
    {
        return 'crewlabs/oauth2-dribbble';
    }

    public function getLibraryHomepage()
    {
        return 'https://github.com/crewlabs/oauth2-dribbble';
    }

    public function getProviderDisplayName()
    {
        return 'Dribbble';
    }

    public function getClientClass(array $config)
    {
        return 'AwU\OAuth2ClientBundle\Client\Provider\DribbbleClient';
    }
}
