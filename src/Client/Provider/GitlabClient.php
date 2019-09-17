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
use Omines\OAuth2\Client\Provider\GitlabResourceOwner;

/**
 * GitlabClient.
 *
 * @author Niels Keurentjes <niels.keurentjes@omines.com>
 */
class GitlabClient extends OAuth2Client
{
    /**
     * @param AccessToken $accessToken
     * @return GitlabResourceOwner
     */
    public function fetchUserFromToken(AccessToken $accessToken)
    {
        return parent::fetchUserFromToken($accessToken);
    }

    /**
     * @return GitlabResourceOwner
     */
    public function fetchUser()
    {
        return parent::fetchUser();
    }
}
