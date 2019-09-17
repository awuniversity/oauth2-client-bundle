<?php

/*
 * OAuth2 Client Bundle
 * Copyright (c) AwUniversity <http://awuniversity.com/>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace AwU\OAuth2ClientBundle\Client\Provider;

use AwU\OAuth2ClientBundle\Client\OAuth2Client;
use AwU\OAuth2\Client\Token\AccessToken;
use AdamPaterson\OAuth2\Client\Provider\StripeResourceOwner;

class StripeClient extends OAuth2Client
{
    /**
     * @param AccessToken $accessToken
     * @return StripeResourceOwner
     */
    public function fetchUserFromToken(AccessToken $accessToken)
    {
        return parent::fetchUserFromToken($accessToken);
    }

    /**
     * @return StripeResourceOwner
     */
    public function fetchUser()
    {
        return parent::fetchUser();
    }
}
