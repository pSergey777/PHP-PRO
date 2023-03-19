<?php

use Starscy\Project\models\Container\DIContainer;
use Starscy\Project\models\Repositories\Post\PostRepositoryInterface;
use Starscy\Project\models\Repositories\Post\SqlitePostRepository;
use Starscy\Project\models\Repositories\User\SqliteUserRepository;
use Starscy\Project\models\Repositories\User\UserRepositoryInterface;
use Starscy\Project\models\Repositories\Likes\LikesRepositoryInterface;
use Starscy\Project\models\Repositories\Likes\SqliteLikesRepository;

require_once __DIR__ . '/vendor/autoload.php';

$container = new DIContainer();

    $container->bind(
    PDO::class,
    new PDO('sqlite:' . __DIR__ . '/db.sqlite')
);

$container->bind(
    PostRepositoryInterface::class,
    SqlitePostRepository::class
);
$container->bind(
    UserRepositoryInterface::class,
    SqliteUserRepository::class
);
$container->bind(
    LikesRepositoryInterface::class,
    SqliteLikesRepository::class
);

return $container;