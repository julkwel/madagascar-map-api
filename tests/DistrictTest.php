<?php

use ApiPlatform\Symfony\Bundle\Test\ApiTestCase;
use Symfony\Contracts\HttpClient\Exception\TransportExceptionInterface;

/**
 * @author julienrajerison5@gmail.com jul
 *
 * Date : 08/01/2024
 */
class DistrictTest extends ApiTestCase
{

    /**
     * @throws TransportExceptionInterface
     */
    public function testListDistricts(): void
    {
        static::createClient()->request(
            'GET',
            '/api/districts', [
            'headers' => [
                'Content-Type' => 'application/ld+json',
            ],
        ]);

        $this->assertResponseStatusCodeSame(200);
    }
}