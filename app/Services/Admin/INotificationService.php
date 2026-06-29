<?php

namespace App\Services\Admin;

use App\DTOs\SaveNotificationDTO;
use App\Models\Notification;
use Illuminate\Pagination\LengthAwarePaginator;

interface INotificationService
{
    public function paginate(): LengthAwarePaginator;

    public function all();

    public function findById(int $id): Notification;

    public function store(SaveNotificationDTO $dto): Notification;

    public function update(Notification $notification, SaveNotificationDTO $dto): Notification;

    public function delete(Notification $notification): bool;
}
