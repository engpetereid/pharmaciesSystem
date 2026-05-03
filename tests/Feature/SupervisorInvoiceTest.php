<?php

namespace Tests\Feature;

use App\Models\Drug;
use App\Models\Invoice;
use App\Models\Pharma;
use App\Models\User;
use App\Models\Warehouse;
use App\Services\Supervisor\InvoiceService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class SupervisorInvoiceTest extends TestCase
{
    use RefreshDatabase;

    protected User  $user;
    protected Pharma $pharmacy;

    protected function setUp(): void
    {
        parent::setUp();

        // Create a supervisor user with a linked pharmacy so the controller's
        // IDOR scoping (pharmacy_id) resolves correctly in every test.
        $this->user     = User::factory()->create(['role' => 'supervisor']);
        $this->pharmacy = Pharma::factory()->create(['user_id' => $this->user->id]);

        $this->actingAs($this->user, 'sanctum');
    }

    public function test_get_invoices(): void
    {
        // Invoices must belong to the acting user's pharmacy so they pass
        // the IDOR scope and are returned by the index endpoint.
        Invoice::factory()->count(3)->create(['pharmacy_id' => $this->pharmacy->id]);

        $response = $this->getJson('/api/v1/supervisor-invoices');

        $response->assertStatus(200)
            ->assertJson(['status' => true])
            ->assertJsonCount(3, 'data.data');
    }

    /**
     * Fix Issue 12: the previous test created a second, unlinked pharmacy and
     * seeded the warehouse against it. The acting supervisor is linked to
     * $this->pharmacy, so the stock check inside InvoiceService would look for
     * warehouse rows under $this->pharmacy->id — finding nothing and throwing
     * "Not enough stock". Fixed by using $this->pharmacy everywhere.
     */
    public function test_create_invoice(): void
    {
        $drug = Drug::factory()->create();

        Warehouse::factory()->create([
            'pharmacy_id' => $this->pharmacy->id,
            'drug_id'     => $drug->id,
            'quantity'    => 10,
            'minimum'     => 2,
        ]);

        $data = [
            'date'        => now()->format('Y-m-d'),
            'pharmacy_id' => $this->pharmacy->id,
            'items'       => [
                [
                    'drug_id'  => $drug->id,
                    'quantity' => 1,
                ],
            ],
        ];

        $this->postJson('/api/v1/supervisor-invoices', $data)
            ->assertStatus(201)
            ->assertJson(['status' => true]);

        $this->assertDatabaseHas('invoices', [
            'pharmacy_id' => $this->pharmacy->id,
        ]);
    }

    public function test_update_invoice(): void
    {
        $drug = Drug::factory()->create();

        Warehouse::factory()->create([
            'pharmacy_id' => $this->pharmacy->id,
            'drug_id'     => $drug->id,
            'quantity'    => 10,
            'minimum'     => 2,
        ]);

        // Create via service so warehouse stock is properly decremented.
        $invoice = app(InvoiceService::class)->create([
            'pharmacy_id' => $this->pharmacy->id,
            'date'        => now()->format('Y-m-d'),
            'items'       => [
                ['drug_id' => $drug->id, 'quantity' => 1],
            ],
        ]);

        $data = [
            'date'        => now()->format('Y-m-d'),
            'pharmacy_id' => $this->pharmacy->id,
            'items'       => [
                [
                    'drug_id'  => $drug->id,
                    'quantity' => 1,
                ],
            ],
        ];

        $this->putJson('/api/v1/supervisor-invoices/' . $invoice->id, $data)
            ->assertStatus(200)
            ->assertJson(['status' => true]);

        $this->assertDatabaseHas('invoices', [
            'id'          => $invoice->id,
            'pharmacy_id' => $this->pharmacy->id,
        ]);
    }

    /**
     * Fix Issue 13: the previous test created the invoice via factory (no
     * invoice_details rows, no warehouse). The delete service iterates items
     * to restore stock — if items are empty the restore is silently skipped,
     * giving false confidence. Fixed by creating via the service and asserting
     * that the stock quantity is restored after deletion.
     */
    public function test_delete_invoice(): void
    {
        $drug = Drug::factory()->create();

        Warehouse::factory()->create([
            'pharmacy_id' => $this->pharmacy->id,
            'drug_id'     => $drug->id,
            'quantity'    => 5,
            'minimum'     => 0,
        ]);

        // Create via service so invoice_details rows exist.
        $invoice = app(InvoiceService::class)->create([
            'pharmacy_id' => $this->pharmacy->id,
            'date'        => now()->format('Y-m-d'),
            'items'       => [
                ['drug_id' => $drug->id, 'quantity' => 2],
            ],
        ]);

        // Stock should now be 3 (5 - 2).
        $this->assertDatabaseHas('warehouses', [
            'pharmacy_id' => $this->pharmacy->id,
            'drug_id'     => $drug->id,
            'quantity'    => 3,
        ]);

        $this->deleteJson('/api/v1/supervisor-invoices/' . $invoice->id)
            ->assertStatus(200)
            ->assertJson([
                'status'  => true,
                'message' => 'Invoice deleted successfully',
            ]);

        // Stock must be restored to original value after deletion.
        $this->assertDatabaseHas('warehouses', [
            'pharmacy_id' => $this->pharmacy->id,
            'drug_id'     => $drug->id,
            'quantity'    => 5,
        ]);

        // Invoice row must be gone.
        $this->assertDatabaseMissing('invoices', ['id' => $invoice->id]);
    }

    public function test_invoice_validation(): void
    {
        $this->postJson('/api/v1/supervisor-invoices', [])
            ->assertStatus(422)
            ->assertJsonValidationErrors(['date', 'pharmacy_id']);
    }
}
