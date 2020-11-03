<?php

namespace App\Controller;

use App\Entity\Comment;
use App\Entity\Post;
use App\Handler\CommentHandler;
use App\Handler\PostHandler;
use App\Presenter\PresenterInterface;
use App\Security\Voter\PostVoter;
use Doctrine\ORM\Tools\Pagination\Paginator;

use App\Paginator\CommentPaginator;
use App\Paginator\PostPaginator;
use App\Repository\PostRepository;
use App\Representation\Representation;
use App\Representation\RepresentationBuilderInterface;
use App\Representation\RepresentationFactoryInterface;
use App\Representation\RepresentationInterface;

use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Component\Security\Core\Authorization\AuthorizationCheckerInterface;
use Twig\Environment;

/**
 * Class BlogController
 * @package App\Controller
 */
class BlogController
{

    use AuthorizationTrait;

    /**
     * @var Environment
     */
    private Environment $twig;

    /**
     * BlogController constructor.
     * @param Environment $twig
     */
    public function __construct(Environment $twig)
    {
        $this->twig = $twig;
    }


    /**
     * @Route("/", name="index")
     * @param Request $request
     * @param RepresentationFactoryInterface $representationFactory
     */
    public function index(
        Request $request,
        RepresentationFactoryInterface $representationFactory
    ): Response {
        $representation = $representationFactory->create(PostPaginator::class)->handleRequest($request);

        return new Response(
            $this->twig->render(
                "blog/index.html.twig",
                [
                    "representation" => $representation->paginate()
                ]
            )
        );
    }

    /**
     * @Route("/article-{id}", name="blog_read")
     * @param Post $post
     * @param Request $request
     * @param CommentHandler $commentHandler
     * @param AuthorizationCheckerInterface $authorizationChecker
     * @param UrlGeneratorInterface $urlGenerator
     * @param RepresentationFactoryInterface $representationFactory
     * @return Response
     * @throws \Twig\Error\LoaderError
     * @throws \Twig\Error\RuntimeError
     * @throws \Twig\Error\SyntaxError
     */
    public function read(
        Post $post,
        Request $request,
        CommentHandler $commentHandler,
        AuthorizationCheckerInterface $authorizationChecker,
        UrlGeneratorInterface $urlGenerator,
        RepresentationFactoryInterface $representationFactory
    ): Response {
        $representation = $representationFactory->create(CommentPaginator::class)->handleRequest($request);
        $comment = new Comment();
        $comment->setPost($post);
        $options = [
            "validation_groups" => $authorizationChecker->isGranted("ROLE_USER") ? "Default" : ["Default", "anonymous"],
        ];
        if ($commentHandler->handle($request, $comment, $options)) {
            return new RedirectResponse($urlGenerator->generate("blog_read", ["id" => $post->getId()]));
        }

        return new Response(
            $this->twig->render(
                "blog/read.html.twig",
                [
                    "post" => $post,
                    "form" => $commentHandler->createView(),
                    "representation" => $representation->paginate([
                        "post" => $post,
                        "route_params" => [
                            "id" => $post->getId()
                        ]
                    ])
                ]
            )
        );
    }

    /**
     * @Route("/publier-article", name="blog_create")
     * @param Request $request
     * @param PostHandler $postHandler
     * @param UrlGeneratorInterface $urlGenerator
     * @return Response
     * @throws \Twig\Error\LoaderError
     * @throws \Twig\Error\RuntimeError
     * @throws \Twig\Error\SyntaxError
     */
    public function create(
        Request $request,
        PostHandler $postHandler,
        UrlGeneratorInterface $urlGenerator
    ): Response {
        $this->denyAccessUnlessGranted('ROLE_USER');
        $post = new Post();
        $options = [
            "validation_groups" => ['Default', 'create'],
        ];
        if ($postHandler->handle($request, $post, $options)) {
            return new RedirectResponse($urlGenerator->generate("index"));
        }

        return new Response(
            $this->twig->render(
                'blog/create.html.twig',
                [
                    'form' => $postHandler->createView(),
                ]

            )
        );
    }

    /**
     * @Route("/modifier-article/{id}", name="blog_update")
     * @param Request $request
     * @param Post $post
     * @param PostHandler $postHandler
     * @param UrlGeneratorInterface $urlGenerator
     * @return Response
     * @throws \Twig\Error\LoaderError
     * @throws \Twig\Error\RuntimeError
     * @throws \Twig\Error\SyntaxError
     */
    public function update(
        Request $request,
        Post $post,
        PostHandler $postHandler,
        UrlGeneratorInterface $urlGenerator
    ): Response {
        $this->denyAccessUnlessGranted(PostVoter::EDIT, $post);

        if ($postHandler->handle($request, $post)) {
            return new RedirectResponse($urlGenerator->generate("blog_read", ["id" => $post->getId()]));
        }

        return new Response(
            $this->twig->render(
                'blog/update.html.twig',
                [
                    'form' => $postHandler->createView(),
                ]
            )
        );
    }
}
