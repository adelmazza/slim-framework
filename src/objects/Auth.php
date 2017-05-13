<?php

namespace App\Objects;

use App\Models\User;
use Carbon\Carbon;

class Auth
{
    private $container;

    public function __construct($container)
    {
        $this->container = $container;
    }

    public function attempt($username, $password)
    {
        $user = User::where('UserEmail', $username)->first();

        if (!$user)
            throw new \Exception('Auth failed');

        if (!password_verify($password, $user->UserPassword))
            throw new \Exception('Auth failed');

        if (!$user->UserIsActive)
            throw new \Exception('User disabled');

        $this->container->session->set('user', [
            'UserID' => $user->UserID,
            'UserEmail' => $user->UserEmail,
            'UserLastLoginDate' => $user->UserLastLoginDate,
        ]);

        $user->UserLastLoginDate = Carbon::now()->format('Y-m-d H:i:s');
        $user->save();

        return true;
    }

    public function check()
    {
        return isset($this->container->session->user['UserID']);
    }

    public function user()
    {
        return User::find($this->container->session->user['UserID']);
    }
}