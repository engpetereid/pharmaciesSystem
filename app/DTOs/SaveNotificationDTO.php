<?php
namespace App\DTOs;

use App\Http\Requests\Notification\SaveNotificationRequest;


class SaveNotificationDTO
{
    public function __construct(
        public readonly int $pharmacy_id,
        public readonly int $drug_id,
        public readonly string $message,
    ) {}

    public static function fromRequest(
        SaveNotificationRequest $request
    ): self {
        return new self(
            pharmacy_id: $request->pharmacy_id,
            drug_id: $request->drug_id,
            message: $request->message,
        );
    }
}
