<?php

namespace App\Repositories\Repository;

use App\Models\CustomerAuthorisedPerson;
use App\Repositories\Interface\CustomerAuthorisedPersonRepositoryInterface;
use Illuminate\Support\Facades\Log;

class CustomerAuthorisedPersonRepository implements CustomerAuthorisedPersonRepositoryInterface
{
    public function createOrUpdate(array $data, string $id = null)
    {
        if (!isset($id)) {
            $entity = new CustomerAuthorisedPerson($data);
        } else {
            $entity = CustomerAuthorisedPerson::find($id);

            foreach ($data as $key => $value) {
                $entity->$key = $value;
            }
        }
        $entity->save();
        return $entity;
    }

    public function getAll($draw = null, $start = null, $rawperpage = null): array
    {

        $entity = CustomerAuthorisedPerson::where('id', '<>', null);

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
        $entity = CustomerAuthorisedPerson::where('id', '=', $id);

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
        return CustomerAuthorisedPerson::where($field_name, $field_value)->first();
    }

}
