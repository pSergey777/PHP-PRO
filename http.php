<?php

use Starscy\Project\Http\Request;
use Starscy\Project\Http\SuccessfulResponse;
use Starscy\Project\models\Exceptions\HttpException;
use Starscy\Project\Http\Actions\User\FindByUsername;
use Starscy\Project\Http\Actions\Post\CreatePost;
use Starscy\Project\Http\Actions\Comment\CreateComment;
use Starscy\Project\Http\Actions\User\CreateUser;
use Starscy\Project\Http\Actions\Likes\CreatePostLike;
use Starscy\Project\Http\Actions\Post\DeletePost;
use Starscy\Project\models\Repositories\User\SqliteUserRepository;
use Starscy\Project\models\Repositories\Post\SqlitePostRepository;
use Starscy\Project\models\Repositories\Comment\SqliteCommentRepository;
use Starscy\Project\Http\ErrorResponse;
$container = require __DIR__ . '/bootstrap.php';
$request = new Request(
    $_GET, 
    $_SERVER, 
    file_get_contents('php://input')
);
try{
    $path = $request->path();
} catch (HttpException){
    (new ErrorResponse)->send();
    return;
}

try {
    $method = $request->method();
} catch (HttpException) {
    (new ErrorResponse)->send();
    return;
}
$routes = [

    'GET' => [
        '/users/show' => FindByUsername::class,
    ],

    'POST' => [
        '/users/create' => CreateUser::class,
        '/posts/create' => CreatePost::class,
        '/posts/comment' => CreateComment::class,
        '/posts/fav' => CreatePostLike::class,
    ],

    'DELETE' =>[
        '/posts' => DeletePost::class,
    ],
];
if (!array_key_exists($method, $routes)) {
    (new ErrorResponse("Route not found: $method $path"))->send();
    return;
}
if (!array_key_exists($path, $routes[$method])) {
    (new ErrorResponse("Route not found: $method $path"))->send();
    return;
}
$actionClassName = $routes[$method][$path];
$action = $container->get($actionClassName);

try {
    $response = $action->handle($request);
    $response->send();
} catch (Exception $e) {

    (new ErrorResponse($e->getMessage()))->send();
}



