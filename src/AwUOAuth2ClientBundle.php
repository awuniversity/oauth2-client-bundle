<?php

/*
 * OAuth2 Client Bundle
 * Copyright (c) AwUniversity <http://awuniversity.com/>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace AwU\OAuth2ClientBundle;

use AwU\OAuth2ClientBundle\DependencyInjection\AwUOAuth2ClientExtension;
use Symfony\Component\HttpKernel\Bundle\Bundle;

class AwUOAuth2ClientBundle extends Bundle
{
    /**
     * Overridden to allow for the custom extension alias.
     *
     * @return AwUOAuth2ClientExtension
     */
    public function getContainerExtension()
    {
        if (null === $this->extension) {
            return new AwUOAuth2ClientExtension();
        }

        return $this->extension;
    }
}
