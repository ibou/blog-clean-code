<?php

namespace App\Tests;

use App\Application\Entity\Post;
use Doctrine\ORM\EntityManagerInterface;
use Generator;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;

class ReadTest extends WebTestCase
{
    use AuthenticationTrait;

    public function testSuccessfulWithoutAuthentication()
    {
        $client = static::createClient();
        /** @var UrlGeneratorInterface $router */
        $urlGenerator = $client->getContainer()->get("router");
        /** @var EntityManagerInterface $entityManager */
        $entityManager = $client->getContainer()->get('doctrine.orm.entity_manager');

        /** @var Post $post */
        $post = $entityManager->getRepository(Post::class)->findOneBy([]);

        $count = $post->getComments()->count();

        $crawler = $client->request(
            Request::METHOD_GET,
            $urlGenerator->generate('blog_read', ['id' => $post->getId()])
        );
        self::assertResponseStatusCodeSame(Response::HTTP_OK);

        $form = $crawler->filter("form[name=comment]")
            ->form(
                [
                    "comment[author]" => 'author999',
                    "comment[content]" => 'Nouveau commentaire content',
                ]
            );
        $client->submit($form);

        $this->assertResponseStatusCodeSame(Response::HTTP_FOUND);
        $client->followRedirect();

        $this->assertSelectorTextContains("html", "Nouveau commentaire content");

        $this->assertSame($count, $post->getComments()->count());

        $this->assertCount(
            ($count + 1) > 10 ? 10 : $count + 1,
            $crawler->filter('html main ul:not(.pagination) li')
        );
    }

    /**
     * @dataProvider provideFailed
     * @param array $formData
     * @param string $errorMessage
     */
    public function testFailed(array $formData, string $errorMessage)
    {
        $client = static::createClient();
        /** @var UrlGeneratorInterface $router */
        $urlGenerator = $client->getContainer()->get("router");
        /** @var EntityManagerInterface $entityManager */
        $entityManager = $client->getContainer()->get('doctrine.orm.entity_manager');

        /** @var Post $post */
        $post = $entityManager->getRepository(Post::class)->findOneBy([]);

        $count = $post->getComments()->count();

        $crawler = $client->request(
            Request::METHOD_GET,
            $urlGenerator->generate('blog_read', ['id' => $post->getId()])
        );
        self::assertResponseStatusCodeSame(Response::HTTP_OK);

        $form = $crawler->filter("form[name=comment]")
            ->form($formData);
        $client->submit($form);

        $this->assertResponseStatusCodeSame(Response::HTTP_OK);


        $this->assertSelectorTextContains("html", $errorMessage);
    }

    public function provideFailed(): Generator
    {
        yield [
            [
                'comment[author]' => 'Author',
                'comment[content]' => ''
            ],
            'This value should not be blank.'
        ];



        yield [
            [
                'comment[author]' => 'Author',
                'comment[content]' => 'Fail'
            ],
            'This value is too short. It should have 5 characters or more.'
        ];
    }

    public function testSuccessfulWithAuthentication()
    {
        $client = static::createClient();
        /** @var UrlGeneratorInterface $router */
        $urlGenerator = $client->getContainer()->get("router");
        /** @var EntityManagerInterface $entityManager */
        $entityManager = $client->getContainer()->get('doctrine.orm.entity_manager');

        /** @var Post $post */
        $post = $entityManager->getRepository(Post::class)->findOneBy([]);

        $count = $post->getComments()->count();

        $crawler = $client->request(
            Request::METHOD_GET,
            $urlGenerator->generate('blog_read', ['id' => $post->getId()])
        );
        self::assertResponseStatusCodeSame(Response::HTTP_OK);

        $form = $crawler->filter("form[name=comment]")
            ->form(
                [
                    "comment[content]" => 'Nouveau commentaire content',
                ]
            );
        $client->submit($form);

        $this->assertResponseStatusCodeSame(Response::HTTP_FOUND);
        $client->followRedirect();

        $this->assertSelectorTextContains("html", "Nouveau commentaire content");

        $this->assertSame($count, $post->getComments()->count());

        $this->assertCount(
            ($count + 1) > 10 ? 10 : $count + 1,
            $crawler->filter('html main ul:not(.pagination) li')
        );
    }
}
