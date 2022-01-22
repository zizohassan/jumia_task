<?php

namespace App\Http\Controllers\Apis;

use App\Http\Controllers\Controller;
use App\Http\Traits\ApiTrait;

class BaseApiController extends Controller
{
    use ApiTrait;

    //// this controller will exetends by all apis controller so
    /// in some time we can apply some logic on all api controller
}
