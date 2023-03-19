<?php

namespace Starscy\Project\Http\Actions\Post;

use Starscy\Project\Http\Auth\AuthenticationInterface;
use Starscy\Project\Http\Auth\AuthException;
use Starscy\Project\Http\Auth\TokenAuthenticationInterface;
use Starscy\Project\models\Exceptions\InvalidArgumentException;
use Starscy\Project\Http\Actions\ActionInterface;
use Starscy\Project\models\Exceptions\HttpException;
use Starscy\Project\Http\Request;
use Starscy\Project\Http\Response;
use Starscy\Project\Http\SuccessfulResponse;
use Starscy\Project\models\Blog\Post;
use Starscy\Project\models\Repositories\Post\PostRepositoryInterface;
use Starscy\Project\models\Exceptions\UserNotFoundException;
use Starscy\Project\models\Repositories\User\UserRepositoryInterface;
use Starscy\Project\models\UUID;
use Psr\Log\LoggerInterface;
use Starscy\Project\Http\ErrorResponse;

class CreatePost implements ActionInterface
{
    public function __construct(

        private PostRepositoryInterface $postsRepository,
        private TokenAuthenticationInterface $authentication,
        private LoggerInterface $logger,

    ) {
    }

    public function handle(Request $request): Response
    {
        try{
            $author = $this->authentication->user($request);
        } catch (AuthException $e){
            return new ErrorResponse($e->getMessage());
        }
            
        $newPostUuid = UUID::random();

        try {
            $post = new Post(
                $newPostUuid,
                $author,
                $request->jsonBodyField('title'),
                $request->jsonBodyField('text'),
            );
        } catch (HttpException $e) {
            return new ErrorResponse($e->getMessage());
        }
        
        $this->postsRepository->save($post);
        $this->logger->info("Post created: $newPostUuid");

        return new SuccessfulResponse([
            'uuid' => (string)$newPostUuid,
        ]);
    }
}