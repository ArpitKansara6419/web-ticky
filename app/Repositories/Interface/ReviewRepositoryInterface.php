<?php

namespace App\Repositories\Interface;

interface ReviewRepositoryInterface
{
    public function createOrUpdate(array $data, ?string $id = null);

    public function getAll($draw = null, $start = null, $rawperpage = null);

    public function getRecordById(string $id, array $with = []);

    public function getRecordByField(string $field_name, string $field_value);
}