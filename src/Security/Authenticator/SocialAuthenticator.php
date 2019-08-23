<?php

/*
 * OAuth2 Client Bundle
 * Copyright (c) AwUniversity <http://awuniversity.com/>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace AwU\OAuth2ClientBundle\Security\Authenticator;

use AwU\OAuth2ClientBundle\Exception\InvalidStateException;
use AwU\OAuth2ClientBundle\Security\Exception\IdentityProviderAuthenticationException;
use AwU\OAuth2ClientBundle\Security\Exception\InvalidStateAuthenticationException;
use AwU\OAuth2ClientBundle\Security\Exception\NoAuthCodeAuthenticationException;
use AwU\OAuth2ClientBundle\Exception\MissingAuthorizationCodeException;
use AwU\OAuth2ClientBundle\Security\Helper\FinishRegistrationBehavior;
use AwU\OAuth2ClientBundle\Security\Helper\PreviousUrlHelper;
use AwU\OAuth2ClientBundle\Security\Helper\SaveAuthFailureMessage;
use League\OAuth2\Client\Provider\Exception\IdentityProviderException;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Security\Guard\AbstractGuardAuthenticator;
use AwU\OAuth2ClientBundle\Client\OAuth2ClientInterface;

abstract class SocialAuthenticator extends AbstractGuardAuthenticator
{
    use FinishRegistrationBehavior;
    use PreviousUrlHelper;
    use SaveAuthFailureMessage;

    protected function fetchAccessToken(OAuth2ClientInterface $client)
    {
        try {
            return $client->getAccessToken();
        } catch (MissingAuthorizationCodeException $e) {
            throw new NoAuthCodeAuthenticationException();
        } catch (IdentityProviderException $e) {
            throw new IdentityProviderAuthenticationException($e);
        } catch (InvalidStateException $e) {
            throw new InvalidStateAuthenticationException($e);
        }
    }

    public function checkCredentials($credentials, UserInterface $user)
    {
        // do nothing - the fact that the access token works is enough
        return true;
    }

    public function supportsRememberMe()
    {
        return true;
    }
}
