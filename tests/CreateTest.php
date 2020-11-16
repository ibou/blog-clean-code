<?php


namespace App\Tests;


use Generator;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;

/**
 * Class CreateTest
 * @package App\Tests
 * @group create
 */
class CreateTest extends WebTestCase
{

    use AuthenticationTrait;
    use UploadTrait;

    public function testAccessDenied()
    {
        $client = static::createClient();

        /** @var UrlGeneratorInterface $router */
        $urlGenerator = $client->getContainer()->get("router");

        $client->request(Request::METHOD_GET, $urlGenerator->generate('blog_create'));

        $this->assertResponseStatusCodeSame(Response::HTTP_FOUND);
        $client->followRedirect();
        $this->assertRouteSame('security_login');
    }

    public function testSuccessful()
    {
        $client = static::createAuthenticatedClient();

        /** @var UrlGeneratorInterface $router */
        $urlGenerator = $client->getContainer()->get("router");

        $crawler = $client->request(Request::METHOD_GET, $urlGenerator->generate('blog_create'));

        $form = $crawler->filter("form[name=post]")
            ->form(
                [
                    "post[title]" => "asdsds",
                    "post[content]" => 'a content test here.',
                    "post[image]" => static::createImage(),
                ]
            );
        $client->submit($form);

        $this->assertResponseStatusCodeSame(Response::HTTP_FOUND);
    }

    /**
     * @param array $formData
     * @param string $errorMessage
     * @dataProvider provideFailed
     */
    public function testFailed(array $formData, string $errorMessage)
    {
        $client = static::createAuthenticatedClient();

        /** @var UrlGeneratorInterface $router */
        $urlGenerator = $client->getContainer()->get("router");

        $crawler = $client->request(Request::METHOD_GET, $urlGenerator->generate('blog_create'));

        $form = $crawler->filter("form[name=post]")
            ->form(
                $formData
            );
        $client->submit($form);

        $this->assertSelectorTextContains("html", $errorMessage);
    }

    /**
     * @return Generator
     */
    public function provideFailed(): Generator
    {
        yield [
            [
                'post[title]' => '',
                'post[content]' => 'un contenten valide',
                "post[image]" => static::createImage(),
            ],
            'This value should not be blank.',
        ];


        yield [
            [
                'post[title]' => 'a title not null',
                'post[content]' => '',
                "post[image]" => static::createImage(),
            ],
            'This value should not be blank.',
        ];

        yield [
            [
                'post[title]' => 'a',
                'post[content]' => 'fajkehfkaehfb',
                "post[image]" => static::createImage(),
            ],
            ' This value is too short. It should have 6 characters or more.',
        ];

        yield [
            [
                'post[title]' => 'a title long title',
                'post[content]' => '2sh',
                "post[image]" => static::createImage(),
            ],
            ' This value is too short. It should have 10 characters or more.',
        ];

        yield [
            [
                'post[title]' => 'a title long title',
                'post[content]' => 'a long content is read',
            ],
            'cannot be null',
        ];

        yield [
            [
                'post[title]' => 'a title long title',
                'post[content]' => 'a long content is read',
            ],
            'cannot be null',
        ];

        yield [
            [
                'post[title]' => 'a title long title',
                'post[content]' => 'a content  teste here',
                'post[image]' => static::createTextFile(),
            ],
            'This file is not a valid image.',
        ];
    }
}