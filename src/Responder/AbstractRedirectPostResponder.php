<?php


namespace App\Responder;

use App\Entity\Post;

/**
 * Class AbstractRedirectPostResponder
 * @package App\Responder
 */
class AbstractRedirectPostResponder
{
    /**
     * @var Post
     */
    private Post $post;

    /**
     * RedirectReadPostResponder constructor.
     * @param Post $post
     */
    public function __construct(Post $post)
    {
        $this->post = $post;
    }

    /**
     * @return Post
     */
    public function getPost(): Post
    {
        return $this->post;
    }



}