<?php

namespace App\Routing;

use App\Controller\PostController;
use App\Repository\PostRepository;
use Doctrine\Common\Collections\Criteria;
use Symfony\Component\Config\Loader\Loader;
use Symfony\Component\Routing\Route;
use Symfony\Component\Routing\RouteCollection;

class RouteAliasLoader extends Loader
{
    private PostRepository $postRepository;

    public function __construct(PostRepository $postRepository)
    {
        parent::__construct();
        $this->postRepository = $postRepository;
    }


    public function load($resource, string $type = null): RouteCollection
    {
        $collection = new RouteCollection();
        $criteria = new Criteria();
        $criteria->where($criteria->expr()->neq('alias', null));
        $posts = $this->postRepository->matching($criteria);
        if (null !== $posts) {
            foreach ($posts as $post) {
                $route = new Route($post->getAlias(), [
                    '_controller' => PostController::class . '::show',
                    'id' => $post->getId()
                ]);
                $routeName = 'alias_' . $post->getId();
                $collection->add($routeName, $route);
            }
        }
        return $collection;
    }

    public function supports($resource, string $type = null): bool
    {
        return 'alias' === $type;
    }
}