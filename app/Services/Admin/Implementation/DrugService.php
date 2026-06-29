<?php

namespace App\Services\Admin\Implementation;

use App\DTOs\SaveDrugDTO;
use App\Models\Drug;
use App\Repositories\IDrugRepository;
use App\Repositories\Implementation\DrugRepository;
use App\Services\Admin\IDrugService;
use Illuminate\Pagination\LengthAwarePaginator;


class DrugService implements IDrugService
{
    public function __construct(
        protected IDrugRepository $DrugRepository
    ) {}
    public function store(SaveDrugDTO $dto): Drug
    {
        return $this->DrugRepository->store($dto);
    }

    public function update(Drug $drug , SaveDrugDTO $dto): Drug
    {
        return $this->DrugRepository->update($drug, $dto);
    }

    public function delete(Drug $drug): bool
    {
        return $this->DrugRepository->delete($drug);
    }
    public function findById(int $id): Drug
    {
        return $this->DrugRepository->findById($id);
    }

    public function paginate(): LengthAwarePaginator
    {
        return $this->DrugRepository->paginate();
    }

    public function all()
    {
        return $this->DrugRepository->all();
    }


}
