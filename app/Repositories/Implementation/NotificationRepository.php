<?php
namespace App\Repositories\Implementation;
use App\Models\Notification;
use App\DTOs\SaveNotificationDTO;
use App\Repositories\INotificationRepository;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;

class NotificationRepository implements INotificationRepository{
    public function store(SaveNotificationDTO $dto): Notification
    {
        return Notification::create([
            'message' => $dto->message,
            'pharmacy_id' => $dto->pharmacy_id,
            'drug_id'=>$dto->drug_id
        ]);
    }
    public function update(Notification $notification, SaveNotificationDTO $dto): Notification
    {
        $notification->update([
            'message' => $dto->message,
            'pharmacy_id' => $dto->pharmacy_id,
            'drug_id'=>$dto->drug_id
        ]);
        return $notification->refresh();
    }
    public function delete(Notification $notification): bool
    {
        $notification->delete();
        return true;
    }
    public function paginate(): LengthAwarePaginator
    {
        return Notification::paginate(25);
    }

    public function findById(int $id): Notification
    {
        return Notification::findOrFail($id);
    }

    public function all(): Collection
    {
        return Notification::all();
    }
}
