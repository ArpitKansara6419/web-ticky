<?php

namespace App\Repositories\Interface;

interface EngineerNotificationRepositoryInterface
{
    public function createOrUpdate(array $data, ?string $id = null);

    public function getAll($draw = null, $start = null, $rawperpage = null, $where = []);

    public function getRecordById(string $id, array $with = []);

    public function getRecordByField(string $field_name, string $field_value);

    public function updateSeenAll(string $engineer_id);
}