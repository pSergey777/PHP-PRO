<?php

use Starscy\Project\Http\Request;
use Starscy\Project\Http\SuccessfulResponse;
use Starscy\Project\models\Exceptions\HttpException;
use Starscy\Project\Http\Actions\User\FindByUsername;
use Starscy\Project\Http\Actions\Post\CreatePost;
use Starscy\Project\Http\Actions\Comment\CreateComment;
use Starscy\Project\Http\Actions\User\CreateUser;
use Starscy\Project\Http\Actions\Post\DeletePost;
use Starscy\Project\models\Repositories\User\SqliteUserRepository;
use Starscy\Project\models\Repositories\Post\SqlitePostRepository;
use Starscy\Project\models\Repositories\Comment\SqliteCommentRepository;
use Starscy\Project\Http\ErrorResponse;


require_once __DIR__ . '/vendor/autoload.php';

$request = new Request(
    $_GET, 
    $_SERVER, 
    file_get_contents('php://input')
);

$routes = [

    'GET' => [
        '/users/show' => new FindByUsername(
            new SqliteUserRepository(
                new PDO('sqlite:' . __DIR__ . '/db.sqlite')
            )
        )
    ],


    'POST' => [
        '/users/create' => new CreateUser(
            new SqliteUserRepository(
                new PDO('sqlite:' . __DIR__ . '/db.sqlite')
            )
        ),

        '/posts/create' => new CreatePost(
            new SqlitePostRepository(
                new PDO('sqlite:' . __DIR__ . '/db.sqlite')
            ),
            new SqliteUserRepository(
                new PDO('sqlite:' . __DIR__ . '/db.sqlite')
            )
        ),

        '/posts/comment' => new CreateComment(
            new SqliteCommentRepository(
                new PDO('sqlite:' . __DIR__ . '/db.sqlite')
            ),
            new SqlitePostRepository(
                new PDO('sqlite:' . __DIR__ . '/db.sqlite')
            ),
            new SqliteUserRepository(
                new PDO('sqlite:' . __DIR__ . '/db.sqlite')
            ),
        ),
    ],

    'DELETE' =>[

        '/posts' => new DeletePost(
            new SqlitePostRepository(
                new PDO('sqlite:' . __DIR__ . '/db.sqlite')
            ),
        ),
    ],
    

];

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
if (!array_key_exists($method, $routes)) {
    (new ErrorResponse('Not found'))->send();
    return;
}
if (!array_key_exists($path, $routes[$method])) {
    (new ErrorResponse('Not found'))->send();
    return;
}
$action = $routes[$method][$path];

try {
    $response = $action->handle($request);
    $response->send();
} catch (Exception $e) {
    (new ErrorResponse($e->getMessage()))->send();
}
 

