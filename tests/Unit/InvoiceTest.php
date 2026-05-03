<?php

namespace Tests\Unit;

use App\Models\Drug;
use App\Models\Invoice;
use App\Models\Pharma;
use App\Models\User;
use App\Models\Warehouse;
use App\Services\Supervisor\InvoiceService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class InvoiceTest extends TestCase
{
    use RefreshDatabase;

    public function test_create_invoice(): void
    {
        $service = new InvoiceService();
        $user    = User::factory()->create();
        $pharma  = Pharma::factory()->create(['user_id' => $user->id]);

        $this->actingAs($user);

        $drug      = Drug::factory()->create();
        $warehouse = Warehouse::factory()->create([
            'pharmacy_id' => $pharma->id,
            'drug_id'     => $drug->id,
            'quantity'    => 10,
            'minimum'     => 2,
        ]);

        $invoice = $service->create([
            'pharmacy_id' => $pharma->id,
            'date'        => now()->format('Y-m-d'),
            'items'       => [
                ['drug_id' => $drug->id, 'quantity' => 1],
            ],
        ]);

        $this->assertDatabaseHas('invoices', [
            'id' => $invoice->id,
        ]);
        $this->assertDatabaseHas('invoice_details', [
            'invoice_id' => $invoice->id,
            'drug_id'    => $drug->id,
        ]);
    }

    public function test_update_invoice(): void
    {
        $service = new InvoiceService();
        $user    = User::factory()->create();
        $pharma  = Pharma::factory()->create(['user_id' => $user->id]);

        $this->actingAs($user);

        $drug      = Drug::factory()->create();
        $warehouse = Warehouse::factory()->create([
            'pharmacy_id' => $pharma->id,
            'drug_id'     => $drug->id,
            'quantity'    => 10,
            'minimum'     => 2,
        ]);

        $invoice = $service->create([
            'pharmacy_id' => $pharma->id,
            'date'        => now()->format('Y-m-d'),
            'items'       => [
                ['drug_id' => $drug->id, 'quantity' => 1],
            ],
        ]);

        // Fix: service now accepts an Invoice model instead of int $id.
        // Fetch the fresh model (so items relation is not stale) and pass it.
        $invoice->refresh();
        $service->update($invoice, [
            'date'  => now()->format('Y-m-d'),
            'items' => [
                ['drug_id' => $drug->id, 'quantity' => 2],
            ],
        ]);

        $this->assertDatabaseHas('invoices', [
            'id' => $invoice->id,
        ]);
        $this->assertDatabaseHas('invoice_details', [
            'invoice_id' => $invoice->id,
            'drug_id'    => $drug->id,
            'quantity'   => 2,
        ]);
    }

    public function test_delete_invoice(): void
    {
        $service = new InvoiceService();
        $user    = User::factory()->create();
        $pharma  = Pharma::factory()->create(['user_id' => $user->id]);

        $this->actingAs($user);

        $drug      = Drug::factory()->create();
        $warehouse = Warehouse::factory()->create([
            'pharmacy_id' => $pharma->id,
            'drug_id'     => $drug->id,
            'quantity'    => 10,
            'minimum'     => 2,
        ]);

        $invoice = $service->create([
            'pharmacy_id' => $pharma->id,
            'date'        => now()->format('Y-m-d'),
            'items'       => [
                ['drug_id' => $drug->id, 'quantity' => 1],
            ],
        ]);

        // Fix: service now accepts an Invoice model instead of int $id.
        $invoice->refresh();
        $service->delete($invoice);

        $this->assertDatabaseMissing('invoices', [
            'id' => $invoice->id,
        ]);
    }
}
