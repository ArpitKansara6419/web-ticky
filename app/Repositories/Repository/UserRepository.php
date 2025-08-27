<?php

namespace App\Repositories\Repository;

use App\Models\User;
use App\Repositories\Interface\UserRepositoryInterface;
use Illuminate\Support\Facades\Log;

class UserRepository implements UserRepositoryInterface
{
    public function createOrUpdate(array $data, string $id = null)
    {
        if (!isset($id)) {
            $entity = new User($data);
        } else {
            $entity = User::find($id);

            foreach ($data as $key => $value) {
                $entity->$key = $value;
            }
        }
        $entity->save();
        return $entity;
    }

    public function getAll($draw = null, $start = null, $rawperpage = null): array
    {

        $entity = User::where('id', '<>', null);

        $clone_entity = clone $entity;
        $totalRecords = $clone_entity->count();

        if ($rawperpage) {
            $entity->take($rawperpage)->skip($start);
        }

        $result = $entity->get();

        $response = [
            "total" => $totalRecords,
            "data" => $result
        ];
        return $response;
    }

    public function getRecordById(string $id, array $with = []){
        $entity = User::where('id', '=', $id);

        if($with)
        {
            if(in_array('batch_master', $with))
            {
                $entity->with('batch_master');
            }
        }

        $result = $entity->firstorfail();

        return $result;
    }

    public function getRecordByField(string $field_name, string $field_value){
        return User::where($field_name, $field_value)->first();
    }

}
