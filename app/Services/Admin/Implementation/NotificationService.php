<?php

namespace App\Services\Admin\Implementation;

use App\DTOs\SaveNotificationDTO;
use App\DTOs\SaveWarehouseDTO;
use App\Models\Notification;
use App\Repositories\INotificationRepository;
use App\Repositories\Implementation\NotificationRepository;
use App\Repositories\IWarehouseRepository;
use App\Services\Admin\INotificationService;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\DB;


class NotificationService implements INotificationService
{
    public function __construct(
        protected INotificationRepository $NotificationRepository,
    ) {}
    public function store(SaveNotificationDTO $dto): Notification
    {
        return $this->NotificationRepository->store($dto);
    }

    public function update(Notification $notification , SaveNotificationDTO $dto): Notification
    {
        return $this->NotificationRepository->update($notification, $dto);
    }

    public function delete(Notification $notification): bool
    {
        return $this->NotificationRepository->delete($notification);
    }
    public function findById(int $id): Notification
    {
        return $this->NotificationRepository->findById($id);
    }

    public function paginate(): LengthAwarePaginator
    {
        return $this->NotificationRepository->paginate();
    }

    public function all()
    {
        return $this->NotificationRepository->all();
    }



}
