<?php

namespace Phphub\Creators;

use Phphub\Listeners\UserCreatorListener;
use App\Models\User;

/**
* This class can call the following methods on the observer object:
*
* userValidationError($errors)
* userCreated($user)
*/
class UserCreator
{
    protected $userModel;

    public function __construct(User $userModel)
    {
        $this->userModel  = $userModel;
    }

    public function create(UserCreatorListener $observer, $data)
    {
        $data['password'] = bcrypt($data['password']);
        $user = User::create($data);
        if (! $user) {
            return $observer->userValidationError($user->getErrors());
        }
        $user->cacheAvatar();
        return $observer->userCreated($user);
    }
}
