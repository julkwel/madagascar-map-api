<?php

use ApiPlatform\Symfony\Bundle\Test\ApiTestCase;
use Symfony\Contracts\HttpClient\Exception\TransportExceptionInterface;

/**
 * @author julienrajerison5@gmail.com jul
 *
 * Date : 08/01/2024
 */
class RegionTest extends ApiTestCase
{

    /**
     * @throws TransportExceptionInterface
     */
    public function testListRegions(): void
    {
        static::createClient()->request(
            'GET',
            '/api/regions', [
            'headers' => [
                'Content-Type' => 'application/ld+json',
            ],
        ]);

        $this->assertResponseStatusCodeSame(200);
    }
}