<?php

namespace App\Containers\AppSection\User\Actions;

use Apiato\Core\Foundation\Facades\Apiato;
use App\Containers\AppSection\User\Events\UserRegisteredEvent;
use App\Containers\AppSection\User\Mails\UserRegisteredMail;
use App\Containers\AppSection\User\Models\User;
use App\Containers\AppSection\User\Notifications\UserRegisteredNotification;
use App\Containers\AppSection\User\UI\API\Requests\RegisterUserRequest;
use App\Ship\Parents\Actions\Action;
use Illuminate\Contracts\Bus\Dispatcher;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Notification;

class RegisterUserAction extends Action
{
    public function run(RegisterUserRequest $data): User
    {
        $user = Apiato::call('User@CreateUserByCredentialsTask', [
            false,
            $data->email,
            $data->password,
            $data->name,
            $data->gender,
            $data->birth
        ]);

        Mail::send(new UserRegisteredMail($user));

        Notification::send($user, new UserRegisteredNotification($user));

        App::make(Dispatcher::class)->dispatch(new UserRegisteredEvent($user));

        return $user;
    }
}