<?php

namespace App\Factories;

use App\Contracts\FactoryInterface;
use App\Repositories\Api\TodoApiRepository;

class TodoFactory implements FactoryInterface
{

	static public function create() {
        // return new UserRepository();
    }

	static public function createApi() {
        return new TodoApiRepository();
    }

}