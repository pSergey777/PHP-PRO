<?php

namespace Starscy\Project\Http\Actions\User;

use Starscy\Project\Http\Actions\ActionInterface;
use Starscy\Project\Http\ErrorResponse;
use Starscy\Project\models\Exceptions\HttpException;
use Starscy\Project\Http\Request;
use Starscy\Project\Http\Response;
use Starscy\Project\Http\SuccessfulResponse;
use Starscy\Project\models\Exceptions\UserNotFoundException;
use Starscy\Project\models\Repositories\User\UserRepositoryInterface;

// Класс реализует контракт действия

class FindByUsername implements ActionInterface
{

    // Нам понадобится репозиторий пользователей,
    // внедряем его контракт в качестве зависимости

    public function __construct(
        private UserRepositoryInterface $userRepository
    ) 
    {
    }

    public function handle(Request $request): Response
    {
        try {
            $username = $request->query('username');
        } catch (HttpException $e) {
            return new ErrorResponse($e->getMessage());
        }
        
        try {

        $user = $this->userRepository->getByUsername($username);
        } catch (UserNotFoundException $e) {

        return new ErrorResponse($e->getMessage());
    }
    return new SuccessfulResponse([
        'username' => $user->getUsername(),
        'name' => $user->getName()->getFirst() . ' ' . $user->getName()->getSecond(),
    ]);
    }
}