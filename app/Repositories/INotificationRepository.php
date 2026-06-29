<?php

namespace App\Repositories;

use App\Models\Notification;
use App\DTOs\SaveNotificationDTO;
use Illuminate\Support\Collection;

interface INotificationRepository
{
    public function store(SaveNotificationDTO $dto): Notification;
    public function update(Notification $notification, SaveNotificationDTO $dto): Notification;
    public function delete(Notification $notification): bool;

    public function paginate();

    public function findById(int $id): Notification;

    public function all(): Collection;

}
