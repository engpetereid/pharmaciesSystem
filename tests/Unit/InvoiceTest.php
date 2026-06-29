<?php

namespace Tests\Unit;

use App\DTOs\SaveInvoiceDTO;
use App\DTOs\SaveInvoiceItemDTO;
use App\Models\Drug;
use App\Models\Pharma;
use App\Models\User;
use App\Models\Warehouse;
use App\Services\Admin\IInvoiceService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class InvoiceTest extends TestCase
{
    use RefreshDatabase;

    public function test_create_invoice(): void
    {
        $service = app(IInvoiceService::class);
        $user    = User::factory()->create();
        $pharma  = Pharma::factory()->create(['user_id' => $user->id]);

        $this->actingAs($user);

        $drug      = Drug::factory()->create();
        $warehouse = Warehouse::factory()->create([
            'pharmacy_id' => $pharma->id,
            'drug_id'     => $drug->id,
            'quantity'    => 10,
            'minimum_quantity'     => 2,
        ]);

        $dto = new SaveInvoiceDTO(
            pharmacy_id: $pharma->id,
            price: 0, // السيرفيس هتحسبه
            date: now(),
            items: [
                new SaveInvoiceItemDTO(drug_id: $drug->id, quantity: 1, unit_price: 100)
            ]
        );

        // 2. استخدام دالة store
        $invoice = $service->store($dto);

        $this->assertDatabaseHas('invoices', [
            'id' => $invoice->id,
        ]);
        $this->assertDatabaseHas('invoice_items', [
            'invoice_id' => $invoice->id,
            'drug_id'    => $drug->id,
        ]);
        $this->assertDatabaseHas('warehouses', [
            'id'       => $warehouse->id,
            'quantity' => 9,
        ]);
    }

    public function test_update_invoice(): void
    {
        $service = app(IInvoiceService::class);
        $user    = User::factory()->create();
        $pharma  = Pharma::factory()->create(['user_id' => $user->id]);

        $this->actingAs($user);

        $drug      = Drug::factory()->create();
        $warehouse = Warehouse::factory()->create([
            'pharmacy_id' => $pharma->id,
            'drug_id'     => $drug->id,
            'quantity'    => 10,
            'minimum_quantity'     => 2,
        ]);

        $oldDto = new SaveInvoiceDTO(
            pharmacy_id: $pharma->id, price: 0, date: now(),
            items: [new SaveInvoiceItemDTO(drug_id: $drug->id, quantity: 1, unit_price: 100)]
        );
        $invoice = $service->store($oldDto);

        $invoice->refresh();


        $newDto = new SaveInvoiceDTO(
            pharmacy_id: $pharma->id, price: 0, date: now(),
            items: [new SaveInvoiceItemDTO(drug_id: $drug->id, quantity: 2, unit_price: 100)]
        );

        $service->update($invoice, $newDto);


        $this->assertDatabaseHas('invoice_items', [
            'invoice_id' => $invoice->id,
            'drug_id'    => $drug->id,
            'quantity'   => 2,
        ]);

        $this->assertDatabaseHas('warehouses', [
            'id'       => $warehouse->id,
            'quantity' => 8,
        ]);
    }

    public function test_delete_invoice(): void
    {
        $service = app(IInvoiceService::class);
        $user    = User::factory()->create();
        $pharma  = Pharma::factory()->create(['user_id' => $user->id]);

        $this->actingAs($user);

        $drug      = Drug::factory()->create();
        $warehouse = Warehouse::factory()->create([
            'pharmacy_id' => $pharma->id,
            'drug_id'     => $drug->id,
            'quantity'    => 10,
            'minimum_quantity'     => 2,
        ]);

        $dto = new SaveInvoiceDTO(
            pharmacy_id: $pharma->id, price: 0, date: now(),
            items: [new SaveInvoiceItemDTO(drug_id: $drug->id, quantity: 3, unit_price: 100)]
        );
        $invoice = $service->store($dto);

        $invoice->refresh();
        $service->delete($invoice);

        $this->assertDatabaseMissing('invoices', [
            'id' => $invoice->id,
        ]);
        $this->assertDatabaseHas('warehouses', [
            'id'       => $warehouse->id,
            'quantity' => 10,
        ]);
    }
}
