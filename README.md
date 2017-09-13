# Amazon Provider for OAuth 2.0 Client
[![Latest Version](https://img.shields.io/github/release/luchianenco/oauth2-amazon.svg?style=flat-square)](https://github.com/luchianenco/oauth2-amazon/releases)
[![Total Downloads](https://img.shields.io/packagist/dt/luchianenco/oauth2-amazon.svg?style=flat-square)](https://packagist.org/packages/luchianenco/oauth2-amazon)


This package provides Amazon OAuth 2.0 support for the PHP League's [OAuth 2.0 Client](https://github.com/thephpleague/oauth2-client).

## Installation

To install, use composer:

```
composer require luchianenco/oauth2-amazon
```

## Usage

Usage is the same as The League's OAuth client, using `\Luchianenco\OAuth2\Client\Provider\Amazon` as the provider.

### Authorization Code Flow

```php

$provider = new Luchianenco\OAuth2\Client\Provider\Amazon([
    'clientId'          => '{amazon-client-id}',
    'clientSecret'      => '{amazon-client-secret}',
    'redirectUri'       => 'https://example.com/callback_url',
]);

// Send OAuth Request
// If we don't have an authorization code then we can get one
$authUrl = $provider->getAuthorizationUrl();
$_SESSION['OAuth2State'] = $provider->getState();

...

// OAuth2 Callback URL
// Compare given state against previously stored one to block CSRF attack
if (empty($_GET['state']) || ($_GET['state'] !== $_SESSION['OAuth2State'])) {

    exit('Invalid state');
    
} else {

    // Try to get an access token
    $token = $provider->getAccessToken('authorization_code', [
        'code' => $_GET['code']
    ]);

    // Now we can look up users profile
    try {

        // Get the user's details
        $user = $provider->getResourceOwner($token);

        printf('Hello %s!', $user->getName());

    } catch (Exception $e) {

        // Failed to get user details
        exit('Oh no ... ...');
    }

    // We can use token to make other API calls
    echo $token->getToken();
}

```

## Testing

``` bash
$ ./vendor/bin/phpunit
```
