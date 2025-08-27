<?php

namespace App\Repositories\Repository;

use App\Models\EngineerNotification;
use App\Repositories\Interface\EngineerNotificationRepositoryInterface;
use Illuminate\Support\Facades\Log;

class EngineerNotificationRepository implements EngineerNotificationRepositoryInterface
{
    public function createOrUpdate(array $data, ?string $id = null)
    {
        if (!isset($id)) {
            $entity = new EngineerNotification($data);
        } else {
            $entity = EngineerNotification::find($id);

            foreach ($data as $key => $value) {
                $entity->$key = $value;
            }
        }
        $entity->save();
        return $entity;
    }

    public function getAll($draw = null, $start = null, $rawperpage = null, $where = []): array
    {

        $entity = EngineerNotification::where('id', '<>', null);

        if(!empty($where))
        {
            if(isset($where['engineer_id']))
            {
                $entity->where('engineer_id', $where['engineer_id']);
            }
        }

        $clone_entity = clone $entity;
        $totalRecords = $clone_entity->count();

        $clone_entity_unseen = clone $entity;
        $totalUnseenRecords = $clone_entity_unseen->where('is_seen', 0)->count();

        if ($rawperpage) {
            $entity->take($rawperpage)->skip($start);
        }

        $entity->orderBy('created_at', 'DESC');

        $result = $entity->get();

        $response = [
            "total" => $totalRecords,
            "data" => $result,
            "total_unseen" => $totalUnseenRecords,
        ];
        return $response;
    }

    public function getRecordById(string $id, array $with = []){
        $entity = EngineerNotification::where('id', '=', $id);

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
        return EngineerNotification::where($field_name, $field_value)->first();
    }

    public function updateSeenAll(string $engineer_id)
    {
        return EngineerNotification::where('is_seen', 0)
            ->where('engineer_id', $engineer_id)
            ->update([
                'is_seen' => 1,
                'seen_at' => now()
            ]);
    }

}
