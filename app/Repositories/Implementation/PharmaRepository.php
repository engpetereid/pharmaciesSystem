<?php
namespace App\Repositories\Implementation;
use App\Models\Pharma;
use App\DTOs\SavePharmaDTO;
use App\Repositories\IPharmaRepository;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;

class PharmaRepository implements IPharmaRepository{
    public function store(SavePharmaDTO $dto): Pharma
    {
        return Pharma::create([
            'name' => $dto->name,
            'user_id' => $dto->user_id,
        ]);
    }
    public function update(Pharma $pharma, SavePharmaDTO $dto): Pharma
    {
        $pharma->update([
            'name'=> $dto->name,
            'user_id' => $dto->user_id,
        ]);
        return $pharma->refresh();
    }
    public function delete(Pharma $pharma): bool
    {
        $pharma->delete();
        return true;
    }
    public function paginate(): LengthAwarePaginator
    {
        return Pharma::paginate(25);
    }

    public function findById(int $id): Pharma
    {
        return Pharma::findOrFail($id);
    }

    public function all(): Collection
    {
        return Pharma::all();
    }
}
