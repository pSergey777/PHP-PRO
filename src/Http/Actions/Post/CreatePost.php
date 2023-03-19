<?php

namespace Starscy\Project\Http\Actions\Post;

use Starscy\Project\models\Exceptions\InvalidArgumentException;
use Starscy\Project\Http\Actions\ActionInterface;
use Starscy\Project\Http\ErrorResponse;
use Starscy\Project\models\Exceptions\HttpException;
use Starscy\Project\Http\Request;
use Starscy\Project\Http\Response;
use Starscy\Project\Http\SuccessfulResponse;
use Starscy\Project\models\Blog\Post;
use Starscy\Project\models\Repositories\Post\PostRepositoryInterface;
use Starscy\Project\models\Exceptions\UserNotFoundException;
use Starscy\Project\models\Repositories\User\UserRepositoryInterface;
use Starscy\Project\models\UUID;

class CreatePost implements ActionInterface
{

    public function __construct(

        private PostRepositoryInterface $postsRepository,
        private UserRepositoryInterface $usersRepository,

    ) {
    }

    public function handle(Request $request): Response
    {

        try {
            $authorUuid = new UUID($request->jsonBodyField('author_uuid'));
        } catch (HttpException | InvalidArgumentException $e) {
            return new ErrorResponse($e->getMessage());
        }

        try {
           $user= $this->usersRepository->get($authorUuid);
        } catch (UserNotFoundException $e) {
            return new ErrorResponse($e->getMessage());
        }
            
        $newPostUuid = UUID::random();

        try {

            $post = new Post(
                $newPostUuid,
                $user,
                $request->jsonBodyField('title'),
                $request->jsonBodyField('text'),
            );
        } catch (HttpException $e) {
            return new ErrorResponse($e->getMessage());
        }

        $this->postsRepository->save($post);


        return new SuccessfulResponse([
            'uuid' => (string)$newPostUuid,
        ]);
    }
}