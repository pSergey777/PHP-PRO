<?php
require_once __DIR__."/vendor/autoload.php";

use Starscy\Project\models\User;
use Starscy\Project\models\Repositories\Post\SqlitePostRepository;
use Starscy\Project\models\Repositories\User\SqliteUserRepository;
use Starscy\Project\models\Repositories\Comment\SqliteCommentRepository;
use Starscy\Project\models\Repositories\Post\PostRepository;
use Starscy\Project\models\Repositories\User\UserRepository;
use Starscy\Project\models\Repositories\Comment\CommentRepository;
use Starscy\Project\models\Commands\CreateUserCommand;
use Starscy\Project\models\Person\Name;
use Starscy\Project\models\UUID;
use Starscy\Project\models\Blog\Post;
use Starscy\Project\models\Blog\Comment;
use Starscy\Project\models\Commands\Arguments;
use Starscy\Project\models\Blog\Like;
use Starscy\Project\models\Repositories\Likes\SqliteLikesRepository;

$container = require __DIR__ . '/bootstrap.php';
 $command = $container->get(CreateUserCommand::class);

try {
    $command->handle(Arguments::fromArgv($argv));
} catch (AppException $e) {
    echo "{$e->getMessage()}\n";
}


$faker = Faker\Factory::create('ru-Ru');
