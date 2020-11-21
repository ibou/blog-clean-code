<?php


namespace App\Tests;

use Generator;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;

class RegistrationTest extends WebTestCase
{
    public function testSuccessful()
    {
        $client = static::createClient();

        /** @var UrlGeneratorInterface $router */
        $urlGenerator = $client->getContainer()->get("router");

        $crawler = $client->request(Request::METHOD_GET, $urlGenerator->generate('registration'));


        $form = $crawler->filter("form[name=user]")
            ->form(
                [
                    "user[email]" => 'email@gmail.com',
                    "user[pseudo]" => 'mypseudo',
                    "user[password][first]" => 'dev',
                    "user[password][second]" => 'dev',
                ]
            );
        $client->submit($form);

        $this->assertResponseStatusCodeSame(Response::HTTP_FOUND);
        $client->followRedirect();

        $this->assertRouteSame('security_login');
    }

    /**
     * @dataProvider providerEmailsFailed
     * @param array $formData
     * @param string $errorMessage
     */
    public function testFailRegistration(array $formData, string $errorMessage)
    {
        $client = static::createClient();

        /** @var UrlGeneratorInterface $router */
        $urlGenerator = $client->getContainer()->get("router");

        $crawler = $client->request(Request::METHOD_GET, $urlGenerator->generate('registration'));

        $form = $crawler->filter("form[name=user]")->form($formData);
        $client->submit($form);
        $this->assertResponseStatusCodeSame(Response::HTTP_OK);
        $this->assertSelectorTextContains("html", $errorMessage);
    }


    public function providerEmailsFailed(): Generator
    {
        yield[
            [
                "user[email]" => 'email@gmail.com',
                "user[pseudo]" => '',
                "user[password][first]" => 'dev',
                "user[password][second]" => 'dev',
            ],
            'This value should not be blank.',
        ];
        yield[
            [
                "user[email]" => '',
                "user[pseudo]" => 'jkqdfhuo',
                "user[password][first]" => 'dev',
                "user[password][second]" => 'dev',
            ],
            'This value should not be blank.',
        ];
        yield[
            [
                "user[email]" => 'email',
                "user[pseudo]" => 'jkqdfhuo',
                "user[password][first]" => 'dev',
                "user[password][second]" => 'dev',
            ],
            'value is not a valid email address.',
        ];
        yield[
            [
                "user[email]" => 'email1@gmail.com',
                "user[pseudo]" => 'jkqdfhuo',
                "user[password][first]" => 'dev',
                "user[password][second]" => 'dev',
            ],
            'L\'adresse email "email1@gmail.com" existe déjà ',
        ];
        yield[
            [
                "user[email]" => '@email@gmail.com',
                "user[pseudo]" => 'jkqdfhuo',
                "user[password][first]" => 'd',
                "user[password][second]" => 'd',
            ],
            'This value is too short',
        ];

        yield[
            [
                "user[email]" => '@email@gmail.com',
                "user[pseudo]" => 'jkqdfhuo',
                "user[password][first]" => 'dodo',
                "user[password][second]" => 'dada',
            ],
            'La confirmation n\'est pas similaire au mot de passe.',
        ];
    }
}
