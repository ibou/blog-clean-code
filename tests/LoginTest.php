<?php

namespace App\Tests;


use Generator;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;

class LoginTest extends WebTestCase
{

    /**
     *
     * @dataProvider providerEmails
     * @param string $email
     */
    public function testSuccessFulLogin(string $email)
    {
        $client = static::createClient();

        /** @var UrlGeneratorInterface $router */
        $urlGenerator = $client->getContainer()->get("router");

        $crawler = $client->request(Request::METHOD_GET, $urlGenerator->generate('security_login'));

        $form = $crawler->filter("form[name=login]")
            ->form(
                [
                    "login[username]" => $email,
                    "login[password]" => 'dev',
                ]
            );
        $client->submit($form);

        $this->assertResponseStatusCodeSame(Response::HTTP_FOUND);
        $client->followRedirect();
        $this->assertRouteSame("index");
    }

    /**
     * @dataProvider providerEmailsFailed
     * @param array $formData
     * @param string $errorMessage
     */
    public function testFailLogin(array $formData, string $errorMessage)
    {
        $client = static::createClient();

        /** @var UrlGeneratorInterface $router */
        $urlGenerator = $client->getContainer()->get("router");

        $crawler = $client->request(Request::METHOD_GET, $urlGenerator->generate('security_login'));

        $form = $crawler->filter("form[name=login]")->form($formData);
        $client->submit($form);

        $this->assertResponseStatusCodeSame(Response::HTTP_FOUND);
        $client->followRedirect();
        $this->assertSelectorTextContains("html", $errorMessage);
    }

    public function providerEmails(): Generator
    {
        yield['email1@gmail.com'];
        yield['email2@gmail.com'];
    }

    public function providerEmailsFailed(): Generator
    {
        yield[
            [
                "login[username]" => 'email1@gmail.com',
                "login[password]" => 'fail',
            ],
            'Password not valid',
        ];
        yield[
            [
                "login[username]" => 'email1111@gmail.com',
                "login[password]" => 'fail',
            ],
            'User "email1111@gmail.com" not found',
        ];
        yield[
            [
                "login[username]" => '',
                "login[password]" => '',
            ],
            "Username and||or Password is||are not valid.",
        ];
    }

}
