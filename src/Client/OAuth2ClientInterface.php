<?php

/*
 * OAuth2 Client Bundle
 * Copyright (c) AwUniversity <http://awuniversity.com/>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace AwU\OAuth2ClientBundle\Client;

use AwU\OAuth2\Client\Token\AccessToken;

interface OAuth2ClientInterface
{
    /**
     * Call this to avoid using and checking "state".
     */
    public function setAsStateless();

    /**
     * Creates a RedirectResponse that will send the user to the
     * OAuth2 server (e.g. send them to Facebook).
     *
     * @param array $scopes  The scopes you want (leave empty to use default)
     * @param array $options Extra options to pass to the "Provider" class
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function redirect(array $scopes, array $options);

    /**
     * Call this after the user is redirected back to get the access token.
     *
     * @return \AwU\OAuth2\Client\Token\AccessToken
     *
     * @throws \AwU\OAuth2ClientBundle\Exception\InvalidStateException
     * @throws \AwU\OAuth2ClientBundle\Exception\MissingAuthorizationCodeException
     * @throws \AwU\OAuth2\Client\Provider\Exception\IdentityProviderException If token cannot be fetched
     */
    public function getAccessToken();

    /**
     * Returns the "User" information (called a resource owner).
     *
     * @param AccessToken $accessToken
     * @return \AwU\OAuth2\Client\Provider\ResourceOwnerInterface
     */
    public function fetchUserFromToken(AccessToken $accessToken);

    /**
     * Shortcut to fetch the access token and user all at once.
     *
     * Only use this if you don't need the access token, but only
     * need the user.
     *
     * @return \AwU\OAuth2\Client\Provider\ResourceOwnerInterface
     */
    public function fetchUser();

    /**
     * Returns the underlying OAuth2 provider.
     *
     * @return \AwU\OAuth2\Client\Provider\AbstractProvider
     */
    public function getOAuth2Provider();
}
