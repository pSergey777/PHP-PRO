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

$pdo = new PDO('sqlite:'.__DIR__.'/db.sqlite');

$userRepository = new SqliteUserRepository($pdo);
$postRepository = new SqlitePostRepository($pdo);
$commentRepository = new SqliteCommentRepository($pdo);

 $userRepositoryInMem = new UserRepository($pdo);
 $postRepositoryInMem = new PostRepository($pdo);
 $commentRepositoryInMem = new CommentRepository($pdo);

$faker = Faker\Factory::create('ru_RU');




$user = new User (
    UUID::random(),
    explode(" ", $faker->name())[1],
    new Name (
        explode(" ", $faker->name())[0], 
        explode(" ", $faker->name())[2]
    )
);

$post = new Post (
    UUID::random(),
    $user,
    $faker->text().PHP_EOL."!!!",
    $faker->text()
);

$comment = new Comment (
    UUID::random(),
    $post,
    $user,
    $faker->text()
);

$userRepository->save($user) ;
$postRepository->save($post) ;
$commentRepository->save($comment) ;


try{

     $comTest = $commentRepository->get(new UUID('a2932b14-cbe0-4669-a39c-7936eeadc786'));
     var_damp($comTest);

} catch  (Exception $e) {
    echo $e->getMessage();
}

