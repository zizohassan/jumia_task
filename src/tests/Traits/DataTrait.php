<?php

namespace Tests\Traits;

use App\Models\Job;
use App\Models\User;

trait DataTrait
{

    protected $user;

    protected $originPassword = '1234567890';

    /**
     * @return void
     */
    protected function createNewJob()
    {
        Job::create($this->job);
    }


    protected function setUserData()
    {
        $this->user = [
            'name' => 'Abdel Aziz',
            'email' => 'zizo@example.com',
            'user_type' => User::Regular,
        ];
    }

    protected function createNewUser()
    {
        $this->user['password'] = bcrypt($this->originPassword);

        User::create($this->user);
    }


}
