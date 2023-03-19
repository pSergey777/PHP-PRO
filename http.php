<?php

use Starscy\Project\Http\Actions\Auth\LogIn;
use Starscy\Project\Http\Actions\Auth\LogOut;
use Starscy\Project\Http\Request;
use Starscy\Project\Http\SuccessfulResponse;
use Starscy\Project\models\Exceptions\HttpException;
use Starscy\Project\Http\Actions\User\FindByUsername;
use Starscy\Project\Http\Actions\Post\CreatePost;
use Starscy\Project\Http\Actions\Comment\CreateComment;
use Starscy\Project\Http\Actions\User\CreateUser;
use Starscy\Project\Http\Actions\Likes\CreatePostLike;
use Starscy\Project\Http\Actions\Post\DeletePost;
use Starscy\Project\Http\ErrorResponse;
use Psr\Log\LoggerInterface;

$container = require __DIR__ . '/bootstrap.php';
$logger = $container->get(LoggerInterface::class);
$request = new Request(
    $_GET, 
    $_SERVER, 
    file_get_contents('php://input')
);

try{
    $path = $request->path();
} catch (HttpException $e){
    $logger->warning($e->getMessage(), ['exception' => $e]);
    (new ErrorResponse)->send();
    return;
}

try {
    $method = $request->method();
} catch (HttpException $e) {
    $logger->warning($e->getMessage(), ['exception' => $e]);
    (new ErrorResponse)->send();
    return;
}

$routes = [

    'GET' => [
        '/users/show' => FindByUsername::class,
    ],

    'POST' => [
        '/login' => LogIn::class,
        '/logout' => LogOut::class,
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
    $message = "Route not found: $method $path";
    $logger->notice($message);
    (new ErrorResponse($message))->send();
    return;
}

if (!array_key_exists($path, $routes[$method])) {
    $message = "Route not found: $method $path";
    $logger->notice($message);
    (new ErrorResponse($message))->send();
    return;
}

$actionClassName = $routes[$method][$path];
$action = $container->get($actionClassName);

try {

    $response = $action->handle($request);
    $response->send();
} catch (Exception $e) {
    $logger->error($e->getMessage(), ['exception' => $e]);
    (new ErrorResponse($e->getMessage()))->send();
}



