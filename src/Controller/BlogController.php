<?php

namespace App\Controller;

use App\Entity\Comment;
use App\Entity\Post;
use App\Handler\CommentHandler;
use App\Handler\PostHandler;
use App\Security\Voter\PostVoter;
use Doctrine\ORM\Tools\Pagination\Paginator;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Security\Core\Authorization\AuthorizationCheckerInterface;
use Twig\Environment;

/**
 * Class BlogController
 * @package App\Controller
 */
class BlogController
{


    /**
     * @Route("/", name="blog_index")
     * @param Request $request
     * @param ManagerRegistry $managerRegistry
     * @param Environment $twig
     * @return Response
     * @throws \Twig\Error\LoaderError
     * @throws \Twig\Error\RuntimeError
     * @throws \Twig\Error\SyntaxError
     */
    public function index(
        Request $request,
        ManagerRegistry $managerRegistry,
        Environment $twig
    ): Response {
        $limit = (int)$request->get('limit', 10);
        $page = (int)$request->get('page', 1);
        $page = ($page > 0) ? $page : '1';
        /** @var Paginator $posts */
        $posts = $managerRegistry->getRepository(Post::class)->getPaginatedPosts(
            $page,
            $limit
        );
        $pages = ceil($posts->count() / $limit);

        $range = range(
            max($page - 3, 1),
            min($page + 3, $pages)
        );

        return new Response(
            $twig->render(
                "blog/index.html.twig",
                [
                    "posts" => $posts,
                    "pages" => $pages,
                    "page" => $page,
                    "limit" => $limit,
                    'range' => $range,
                ]
            )
        );
    }

    /**
     * @Route("/article-{id}", name="blog_read")
     * @param Post $post
     * @param Request $request
     * @param CommentHandler $commentHandler
     * @param Environment $twig
     * @param AuthorizationCheckerInterface $authorizationChecker
     * @return Response
     * @throws \Twig\Error\LoaderError
     * @throws \Twig\Error\RuntimeError
     * @throws \Twig\Error\SyntaxError
     */
    public function read(
        Post $post,
        Request $request,
        CommentHandler $commentHandler,
        Environment $twig,
        AuthorizationCheckerInterface $authorizationChecker
    ): Response {
        $comment = new Comment();
        $comment->setPost($post);
        $options = [
            "validation_groups" => $authorizationChecker->isGranted("ROLE_USER") ? "Default" : ["Default", "anonymous"],
        ];
        if ($commentHandler->handle($request, $comment, $options)) {
            return $this->redirectToRoute("blog_read", ["id" => $post->getId()]);
        }

        return new Response($twig->render(
            "blog/read.html.twig",
            [
                "post" => $post,
                "form" => $commentHandler->createView(),
            ]
        ));
    }

    /**
     * @Route("/publier-article", name="blog_create")
     * @param Request $request
     * @param PostHandler $postHandler
     * @param Environment $twig
     * @return Response
     * @throws \Twig\Error\LoaderError
     * @throws \Twig\Error\RuntimeError
     * @throws \Twig\Error\SyntaxError
     */
    public function create(
        Request $request,
        PostHandler $postHandler,
        Environment $twig
    ): Response {
        $this->denyAccessUnlessGranted('ROLE_USER');
        $post = new Post();
        $options = [
            "validation_groups" => ['Default', 'create'],
        ];
        $post->setUser($this->getUser());
        if ($postHandler->handle($request, $post, $options)) {
            return $this->redirectToRoute("blog_index");
        }

        return new Response($twig->render(
            'blog/create.html.twig',
            [
                'form' => $postHandler->createView(),
            ]

        ));
    }

    /**
     * @Route("/modifier-article/{id}", name="blog_update")
     * @param Request $request
     * @param Post $post
     * @param PostHandler $postHandler
     * @param Environment $twig
     * @return Response
     * @throws \Twig\Error\LoaderError
     * @throws \Twig\Error\RuntimeError
     * @throws \Twig\Error\SyntaxError
     */
    public function update(
        Request $request,
        Post $post,
        PostHandler $postHandler,
        Environment $twig
    ): Response {
        $this->denyAccessUnlessGranted(PostVoter::EDIT, $post);

        if ($postHandler->handle($request, $post)) {
            return $this->redirectToRoute("blog_read", ["id" => $post->getId()]);
        }

        return new Response($twig->render(
            'blog/update.html.twig',
            [
                'form' => $postHandler->createView(),
            ]
        ));
    }
}
