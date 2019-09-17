<?php

/*
 * OAuth2 Client Bundle
 * Copyright (c) AwUniversity <http://awuniversity.com/>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace AwU\OAuth2ClientBundle\tests;

use GuzzleHttp\Client;
use AwU\OAuth2ClientBundle\Tests\app\TestKernel;
use PHPUnit\Framework\TestCase;

class FunctionalTest extends TestCase
{
    public function testServicesAreUsable()
    {
        $kernel = new TestKernel('dev', true);
        $kernel->boot();
        $container = $kernel->getContainer();
        $this->assertTrue($container->has('awu.oauth2.client.my_facebook'));

        /** @var \AwU\OAuth2ClientBundle\Client\OAuth2Client $client */
        $client = $container->get('awu.oauth2.client.my_facebook');
        $this->assertInstanceOf('AwU\OAuth2ClientBundle\Client\Provider\FacebookClient', $client);
        $this->assertInstanceOf('AwU\OAuth2\Client\Provider\Facebook', $client->getOAuth2Provider());

        $client2 = $container->get('awu.oauth2.registry')
            ->getClient('my_facebook');
        $this->assertSame($client, $client2);
        $this->assertTrue($container->has('oauth2.registry'));
        $this->assertInstanceOf(Client::class, $client2->getOAuth2Provider()->getHttpClient());
        $this->assertSame(
            'foo',
            $client2->getOAuth2Provider()->getHttpClient()->getConfig('uri')
        );

        try {
            $container->get('awu.oauth2.registry')->getClient('');
            $this->assertEquals(false, true);
        } catch (\InvalidArgumentException $e) {
            $this->assertInstanceOf('\InvalidArgumentException', $e);
        }
    }
}
