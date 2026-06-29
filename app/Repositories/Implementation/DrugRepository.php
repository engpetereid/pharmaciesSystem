<?php
namespace App\Repositories\Implementation;
use App\Models\Drug;
use App\DTOs\SaveDrugDTO;

use App\Repositories\IDrugRepository;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;

class DrugRepository implements IDrugRepository{
    public function store(SaveDrugDTO $dto): Drug
    {
        return Drug::create([
            'name' => $dto->name,
            'price' => $dto->price,
            'category_id' => $dto->category_id,
        ]);
    }
    public function update(Drug $Drug, SaveDrugDTO $dto): Drug
    {
        $Drug->update([
            'name'=> $dto->name,
            'price' => $dto->price,
            'category_id' => $dto->category_id,
        ]);
        return $Drug->refresh();
    }
    public function delete(Drug $Drug): bool
    {
        $Drug->delete();
        return true;
    }
    public function paginate(): LengthAwarePaginator
    {
        return Drug::paginate(25);
    }

    public function findById(int $id): Drug
    {
        return Drug::findOrFail($id);
    }

    public function all(): Collection
    {
        return Drug::all();
    }
}
