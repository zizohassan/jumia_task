<?php

namespace Tests\Unit;


use Illuminate\Foundation\Testing\DatabaseMigrations;
use Tests\TestCase;
use Tests\Traits\DataTrait;
use Tests\Traits\HttpTrait;

class AuthTest extends TestCase
{

    use DatabaseMigrations, HttpTrait, DataTrait;

    public function __construct(?string $name = null, array $data = [], $dataName = '')
    {
        parent::__construct($name, $data, $dataName);
        $this->setUserData();
    }

    /**
     * happy scenario
     *
     * @return void
     */
    public function test_login_success()
    {
        $this->createNewUser();

        $this->doPost('api/login', [
            'email' => $this->user['email'],
            'password' => $this->originPassword,
            'device_name' => $this->user['name']
        ])->assertStatus(200);
    }

    /**
     * not register email need login
     *
     * @return void
     */
    public function test_not_valid_email_fail()
    {
        $this->createNewUser();

        $this->doPost('/api/login', [
            'email' => 'different@zizo.com',
            'password' => $this->originPassword,
            'device_name' => $this->user['name'],
        ])->assertStatus(403);
    }

    /***
     * wrong password
     *
     * @return void
     */
    public function test_not_valid_password_fail()
    {
        $this->createNewUser();

        $this->doPost('/api/login', [
            'email' => 'different@zizo.com',
            'password' => $this->originPassword . 'randomstring',
            'device_name' => $this->user['name'],
        ])->assertStatus(403);
    }

    /***
     * missed attr test
     */

    /**
     * @return void
     */
    public function test_miss_send_email_value_fail()
    {
        $this->createNewUser();

        $this->doPost('/api/login', [
            'password' => $this->originPassword,
            'device_name' => $this->user['name'],
        ])->assertStatus(422);
    }

    /**
     * @return void
     */
    public function test_miss_send_password_value_fail()
    {
        $this->createNewUser();

        $this->doPost('/api/login', [
            'email' => $this->user['email'],
            'device_name' => $this->user['name'],
        ])->assertStatus(422);
    }

    /***
     * @return void
     */
    public function test_miss_send_device_name_value_fail()
    {
        $this->createNewUser();

        $this->doPost('/api/login', [
            'email' => $this->user['email'],
            'password' => $this->originPassword,
        ])->assertStatus(422);
    }

    /***
     * test min and max attr validations
     */

    /**
     * @return void
     */
    public function test_min_password_fail()
    {
        $this->createNewUser();

        $this->doPost('/api/login', [
            'email' => $this->user['email'],
            'password' => '1234',
            'device_name' => $this->user['name'],
        ])->assertStatus(422);
    }

    /**
     * @return void
     */
    public function test_max_password_fail()
    {
        $this->createNewUser();

        $this->doPost('/api/login', [
            'email' => $this->user['email'],
            'password' => '12341234123412341234123412341234123412341234',
            'device_name' => $this->user['name'],
        ])->assertStatus(422);
    }


    /**
     * @return void
     */
    public function test_min_email_fail()
    {
        $this->createNewUser();

        $this->doPost('/api/login', [
            'email' => 'a@a.o',
            'password' => $this->originPassword,
            'device_name' => $this->user['name'],
        ])->assertStatus(422);
    }

    /**
     * @return void
     */
    public function test_max_email_fail()
    {
        $this->createNewUser();

        $this->doPost('/api/login', [
            'email' => 'difasdasdasdasdasdasdasdasdasdasdferent@zasdasdasdasdasdasdasdasdasdizo.com',
            'password' => $this->originPassword,
            'device_name' => $this->user['name'],
        ])->assertStatus(422);
    }


    /**
     * @return void
     */
    public function test_min_device_name_fail()
    {
        $this->createNewUser();

        $this->doPost('/api/login', [
            'email' => $this->user['email'],
            'password' => $this->originPassword,
            'device_name' => 'w',
        ])->assertStatus(422);
    }

    /**
     * @return void
     */
    public function test_max_device_name_fail()
    {
        $this->createNewUser();

        $this->doPost('/api/login', [
            'email' => $this->user['email'],
            'password' => $this->originPassword,
            'device_name' => '12341234123412341234123412341234123412341234',
        ])->assertStatus(422);
    }
}
