<?php
require_once __DIR__."/vendor/autoload.php";

use Starscy\Project\models\Commands\FakeData\PopulateDB;
use Starscy\Project\models\Commands\Post\DeletePost;
use Starscy\Project\models\Commands\User\CreateUser;
use Starscy\Project\models\Commands\User\UpdateUser;
use Symfony\Component\Console\Application;

$container = require __DIR__ . '/bootstrap.php';

$application = new Application();

$commandsClasses = [
    CreateUser::class,
    UpdateUser::class,
    DeletePost::class,
    PopulateDB::class,
];

foreach ($commandsClasses as $commandClass) {
    $command = $container->get($commandClass);
    $application->add($command);
}
$application->run();
