<?php

namespace App\Http\Controllers\Apis;

use App\Http\Resources\JobCollection;
use App\Http\Resources\JobResource;
use App\Models\Job;
use App\Models\User;
use App\Notifications\NewJobStore;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Notification;

class JobsController extends BaseApiController
{

    public function list()
    {
        if (auth()->user()->user_type == User::Manager) {
            return $this->response(new JobCollection(Job::paginate(10)));
        }

        return $this->response(new JobCollection(Job::where('user_id', auth()->user()->id)->paginate(10)));
    }

    public function store(Request $request)
    {
        ///validation
        $request->validate([
            'title' => 'required|min:6|max:100',
            'description' => 'required|min:10|max:65535',
        ]);

        /// create new job
        $job = Job::create($request->all() + ['user_id' => auth()->user()->id]);

        /// notify managers
        $this->notifyManagers($job);

        /// standardize && transform response
        return $this->response(new JobResource($job));
    }


    /**
     * @param Job $job
     * @return void
     */
    protected function notifyManagers(Job $job)
    {
        Notification::send(User::where('user_type', User::Manager)->get(), new NewJobStore($job));
    }
}
