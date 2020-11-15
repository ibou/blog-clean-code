<?php

namespace App\Tests;

use Generator;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class HomeTest extends WebTestCase
{
    /**
     * @dataProvider provideUri
     * @param string $uri
     */

    public  function testHome(string $uri): void
    {
        $client = static::createClient();
        $client->request(Request::METHOD_GET, $uri);
        self::assertResponseStatusCodeSame(Response::HTTP_OK);

    }


    public function provideUri(): Generator
    {
        yield ['/'];
        yield ['/?page=2'];
        yield ['?page=2&limit=25&field=p.title&order=desc'];
    }


}
