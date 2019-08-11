<?php

namespace App\Factories;

use App\Contracts\FactoryInterface;
use App\Repositories\UserApiRepository;

class UserFactory implements FactoryInterface
{

	static public function create() {
        // return new UserRepository();
    }

	static public function createApi() {
        return new UserApiRepository();
    }

}