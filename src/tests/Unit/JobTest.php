<?php

namespace Tests\Unit;

use App\Models\Job;
use App\Models\User;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Tests\TestCase;
use Tests\Traits\DataTrait;
use Tests\Traits\HttpTrait;

class JobTest extends TestCase
{

    use DatabaseMigrations, HttpTrait, DataTrait;

    protected $job;

    public function __construct(?string $name = null, array $data = [], $dataName = '')
    {
        parent::__construct($name, $data, $dataName);
        $this->setJobData();
    }

    /**
     * @return void
     */
    protected function setJobData()
    {
        $this->job = [
            'title' => 'Test Title',
            'description' => "There are many variations of passages of Lorem Ipsum available, but the majority have suffered alteration in some form, by injected humour, or randomised words which don't look even slightly believable. If you are going to use a passage of Lorem Ipsum, you need to be sure there isn't anything embarrassing hidden in the middle of text. All the Lorem Ipsum generators on the Internet tend to repeat predefined chunks as necessary, making this the first true generator on the Internet. It uses a dictionary of over 200 Latin words, combined with a handful of model sentence structures, to generate Lorem Ipsum which looks reasonable. The generated Lorem Ipsum is therefore always free from repetition, injected humour, or non-characteristic words etc.",
        ];
    }

    /**
     * check api security with check not login user cannot access
     * even they know the url
     */

    /***
     * not auth user try to access list job
     */
    public function test_not_auth_user_access_list_jobs_fail()
    {
        $this->doGet('api/jobs', [
            'Authorization' => 'Bearer asdasdasdasdasdasd'
        ])->assertStatus(401);
    }

    /***
     * auth user try to access add job
     */
    public function test_auth_user_jobs_list_success()
    {
        $this->doPost('api/jobs', $this->job, [
            'Authorization' => 'Bearer ' . $this->createUserAndLoginFirst()
        ])->assertStatus(200);
    }

    /***
     * regular can see his own jobs
     *
     * @return void
     */
    public function test_regular_user_can_list_jobs_success()
    {
        $token = $this->createUserAndLoginFirst();
        /// create user and login and creat new job
        $this->doPost('api/jobs', $this->job, [
            'Authorization' => 'Bearer ' . $token
        ])->assertStatus(200);

        $this->doGet('api/jobs', [
            'Authorization' => 'Bearer ' . $token
        ])->assertStatus(200)->assertJson(['data' => ['pagination' => ['total' => 1]]]);
    }

    /***
     * regular can not see Others jobs
     *
     * @return void
     */
    public function test_regular_user_con_not_list_other_jobs_fail()
    {
        $token = $this->createUserAndLoginFirst();
        /// create new job with other user id
        Job::create($this->job + ['user_id' => 2]);

        $this->doGet('api/jobs', [
            'Authorization' => 'Bearer ' . $token
        ])->assertStatus(200)->assertJson(['data' => ['pagination' => ['total' => 0]]]);
    }

    /***
     * manager can see his own jobs
     *
     * @return void
     */
    public function test_manager_user_can_list_his_own_jobs_success()
    {
        $token = $this->createUserAndLoginFirst(User::Manager);
        /// create user and login and creat new job
        $this->doPost('api/jobs', $this->job, [
            'Authorization' => 'Bearer ' . $token
        ])->assertStatus(200);

        $this->doGet('api/jobs', [
            'Authorization' => 'Bearer ' . $token
        ])->assertStatus(200)->assertJson(['data' => ['pagination' => ['total' => 1]]]);
    }

    /***
     * manager can see Others jobs
     *
     * @return void
     */
    public function test_manager_can_list_others_jobs_success()
    {
        $token = $this->createUserAndLoginFirst(User::Manager);
        /// create new job with other user id
        Job::create($this->job + ['user_id' => 2]);

        $this->doGet('api/jobs', [
            'Authorization' => 'Bearer ' . $token
        ])->assertStatus(200)->assertJson(['data' => ['pagination' => ['total' => 1]]]);
    }

    /***
     * test miss attr
     */

    /***
     * @return void
     */
    public function test_miss_title_fail()
    {
        unset($this->job['title']);

        $this->doPost('api/jobs', $this->job, [
            'Authorization' => 'Bearer ' . $this->createUserAndLoginFirst()
        ])->assertStatus(422);
    }

    /***
     * @return void
     */
    public function test_miss_description_fail()
    {
        unset($this->job['description']);

        $this->doPost('api/jobs', $this->job, [
            'Authorization' => 'Bearer ' . $this->createUserAndLoginFirst()
        ])->assertStatus(422);
    }

    /***
     * test min max attr
     */

    /***
     * @return void
     */
    public function test_min_description_fail()
    {
        $this->job['description'] = 'a';

        $this->doPost('api/jobs', $this->job, [
            'Authorization' => 'Bearer ' . $this->createUserAndLoginFirst()
        ])->assertStatus(422);
    }

    /***
     * @return void
     */
    public function test_min_title_fail()
    {
        $this->job['title'] = 'a';

        $this->doPost('api/jobs', $this->job, [
            'Authorization' => 'Bearer ' . $this->createUserAndLoginFirst()
        ])->assertStatus(422);
    }

    /***
     * @return void
     */
    public function test_max_title_fail()
    {
        $this->job['title'] = 'There are many variations of passages of Lorem Ipsum available, but the majority have suffered alteration in some form, by injected humour, or randomised words which don';

        $this->doPost('api/jobs', $this->job, [
            'Authorization' => 'Bearer ' . $this->createUserAndLoginFirst()
        ])->assertStatus(422);
    }

    /**
     * happy scenario
     *
     * @return void
     */
    public function test_create_job_success()
    {
        ///create manager user to get the email
        User::create(['name' => 'Abdel Aziz',
                'user_type' => User::Manager,
                'password' => bcrypt('1234567890'),
                'email' => 'manager@example.com']
        );
        $this->doPost('api/jobs', $this->job, [
            'Authorization' => 'Bearer ' . $this->createUserAndLoginFirst()
        ])->assertStatus(200);

        /// sleep to make sure that cronjob run to send email
        /// this important because every test hole database dropped and migrate again
        /// email must show here http://127.0.0.1:8025
        sleep(10);
    }

    /**
     * create user hit login api the get token from response
     *
     * @return mixed
     */
    protected function createUserAndLoginFirst($type = null)
    {
        $this->setUserData();
        if ($type) {
            $this->user['user_type'] = $type;
        }
        $this->createNewUser();
        $response = $this->doPost('api/login', [
            'email' => $this->user['email'],
            'password' => $this->originPassword,
            'device_name' => $this->user['name']
        ])->assertStatus(200);
        return json_decode($response->getContent())->data->token;
    }

}
