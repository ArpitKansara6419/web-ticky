<?php

namespace App\Repositories\Repository;

use App\Models\Role;
use App\Repositories\Interface\RoleRepositoryInterface;
use Illuminate\Support\Facades\Log;

class RoleRepository implements RoleRepositoryInterface
{
    public function createOrUpdate(array $data, ?string $id = null)
    {
        // dd($data);
        if (!isset($id)) {
            $entity = new Role($data);
        } else {
            $entity = Role::find($id);

            foreach ($data as $key => $value) {
                $entity->$key = $value;
            }
        }
        $entity->save();
        return $entity;
    }

    public function getAll($draw = null, $start = null, $rawperpage = null): array
    {

        $entity = Role::where('id', '<>', null);

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
        $entity = Role::where('id', '=', $id);

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
        return Role::where($field_name, $field_value)->first();
    }

}
