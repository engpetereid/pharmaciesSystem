<?php

namespace Tests\Feature;

use App\Models\Drug;
use App\Models\Invoice;
use App\Models\Pharma;
use App\Models\User;
use App\Models\Warehouse;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class InvoiceTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        $this->actingAs(
            User::factory()->create([
                'role' => 'admin'
            ]),
            'sanctum'
        );
    }
    public function test_get_invoices()
    {
        // Arrange
        Invoice::factory()->count(3)->create();

        // Act
        $response = $this->getJson('/api/v1/invoices');

        // Assert
        $response->assertStatus(200)
            ->assertJson([
                'status' => true
            ])
            ->assertJsonCount(3, 'data.data');
    }
    public function test_create_invoice()
    {
        $pharmacy=Pharma::factory()->create();
        $drug=Drug::factory()->create();
        $warehouse = Warehouse::factory()->create([
            'pharmacy_id' => $pharmacy->id,
            'drug_id' => $drug->id,
            'quantity' => 10,
            'minimum_quantity' => 2
        ]);
        $data = [
            'date' => now()->format('Y-m-d') ,
            'pharmacy_id' => $pharmacy->id,
            'items'=>[
                [
                    'drug_id'=>$drug->id,
                    'quantity'=>1,
                    'unit_price'=>$drug->price,
                ]
            ]
        ];

        $response = $this->postJson('/api/v1/invoices', $data);

        $response->assertStatus(201)
            ->assertJson([
                'status' => true
            ]);

        $this->assertDatabaseHas('invoices', [
            'pharmacy_id' => $pharmacy->id,
        ]);
    }
    public function test_update_invoice()
    {
        // Arrange

        $pharmacy=Pharma::factory()->create();
        $drug=Drug::factory()->create();
        $warehouse = Warehouse::factory()->create([
            'pharmacy_id' => $pharmacy->id,
            'drug_id' => $drug->id,
            'quantity' => 10,
            'minimum_quantity' => 2
        ]);
        $dto = new \App\DTOs\SaveInvoiceDTO(
            pharmacy_id: $pharmacy->id,
            price: 0,
            date: now(),
            items: [
                new \App\DTOs\SaveInvoiceItemDTO(
                    drug_id: $drug->id,
                    quantity: 1,
                    unit_price: $drug->price
                )
            ]
        );
        $invoice = app(\App\Services\Admin\IInvoiceService::class)->store($dto);

        $data = [
            'date' => now()->format('Y-m-d') ,
            'pharmacy_id' => $pharmacy->id,
            'items'=>[
                [
                    'drug_id' => $drug->id,
                    'quantity' => 3,
                    'unit_price' => $drug->price,
                ]
            ]
        ];

        // Act
        $response = $this->putJson('/api/v1/invoices/' . $invoice->id, $data);

        // Assert
        $response->assertStatus(200)
            ->assertJson([
                'status' => true
            ]);

        $this->assertDatabaseHas('invoices', [
            'id' => $invoice->id,
            'pharmacy_id' => $pharmacy->id,
        ]);

        $this->assertDatabaseHas('warehouses', [
            'id' => $warehouse->id,
            'quantity' => 7,
        ]);
    }
    public function test_delete_invoice(){
        $pharmacy = Pharma::factory()->create();
        $drug = Drug::factory()->create();
        $warehouse = Warehouse::factory()->create([
            'pharmacy_id' => $pharmacy->id,
            'drug_id' => $drug->id,
            'quantity' => 10,
            'minimum_quantity' => 2
        ]);

        $dto = new \App\DTOs\SaveInvoiceDTO(
            pharmacy_id: $pharmacy->id,
            price: 0,
            date: now(),
            items: [
                new \App\DTOs\SaveInvoiceItemDTO(
                    drug_id: $drug->id,
                    quantity: 2,
                    unit_price: $drug->price
                )
            ]
        );
        $invoice = app(\App\Services\Admin\IInvoiceService::class)->store($dto);

        $response = $this->deleteJson('/api/v1/invoices/'.$invoice->id);

        $response->assertStatus(200);

        $this->assertDatabaseMissing('invoices', [
            'id' => $invoice->id,
        ]);

        $this->assertDatabaseHas('warehouses', [
            'id' => $warehouse->id,
            'quantity' => 10,
        ]);
    }
    public function test_invoice_validation()
    {
        $response = $this->postJson('/api/v1/invoices', []);

        $response->assertStatus(422)
            ->assertJsonValidationErrors([
                'date',
                'pharmacy_id'
            ]);
    }
}
