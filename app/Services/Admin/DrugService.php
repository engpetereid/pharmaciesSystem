<?php

namespace App\Services\Admin;

use App\Models\Drug;

/**
 * Fix Issue 9: added PHP return type declarations to all methods.
 */
class DrugService
{
    public function create(array $data): Drug
    {
        return Drug::create($data);
    }

    public function update(int $id, array $data): Drug
    {
        $drug = Drug::findOrFail($id);
        $drug->update($data);
        return $drug;
    }

    public function delete(int $id): bool
    {
        return (bool) Drug::findOrFail($id)->delete();
    }
}
