<?php

namespace Luchianenco\OAuth2\Client\Test\Provider;

use Luchianenco\OAuth2\Client\Provider\Amazon;
use PHPUnit\Framework\TestCase;

final class AmazonTest extends TestCase
{
    const CLIENT_ID = '0000000001';
    const CLIENT_SECRET = 'XXXXXXXXX';
    const REDIRECT_URI = 'https://example.com/connect/check';

    protected Amazon $provider;

    public function setUp(): void
    {
        $this->provider = new Amazon([
            'clientId' => self::CLIENT_ID,
            'clientSecret' => self::CLIENT_SECRET,
            'redirectUri' => self::REDIRECT_URI
        ]);
    }

    /** @test */
    public function testBaseAuthorizationUrl(): void
    {
        $url = $this->provider->getBaseAuthorizationUrl();
        $resource = parse_url($url);

        $this->assertEquals('www.amazon.com', $resource['host']);
        $this->assertEquals('/ap/oa', $resource['path']);
    }

    /** @test */
    public function testAuthorizationUrl(): void
    {
        $url = $this->provider->getAuthorizationUrl();
        $resource = parse_url($url);

        parse_str($resource['query'], $urlQuery);

        $this->assertArrayHasKey('state', $urlQuery);
        $this->assertNotNull($urlQuery['state']);
        $this->assertNotNull($this->provider->getState());

        $this->assertArrayHasKey('scope', $urlQuery);
        $this->assertNotNull($urlQuery['scope']);

        $this->assertArrayHasKey('redirect_uri', $urlQuery);
        $this->assertEquals($urlQuery['redirect_uri'], self::REDIRECT_URI);

        $this->assertArrayHasKey('client_id', $urlQuery);
        $this->assertEquals($urlQuery['client_id'], self::CLIENT_ID);

        $this->assertArrayHasKey('response_type', $urlQuery);
        $this->assertArrayHasKey('approval_prompt', $urlQuery);
    }

    /** @test */
    public function testBaseAccessTokenUrl(): void
    {
        $params = [];
        $url = $this->provider->getBaseAccessTokenUrl($params);
        $resource = parse_url($url);

        $this->assertEquals('api.amazon.com', $resource['host']);
        $this->assertEquals('/auth/o2/token', $resource['path']);
    }
}
