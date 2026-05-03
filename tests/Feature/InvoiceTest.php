<?php

namespace Tests\Feature;

use App\Models\Category;
use App\Models\Drug;
use App\Models\Pharma;
use App\Models\User;
use App\Models\Warehouse;
use Tests\TestCase;
use App\Models\Invoice;
use Illuminate\Foundation\Testing\RefreshDatabase;

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
            'minimum' => 2
        ]);
        $data = [
            'date' => now()->format('Y-m-d') ,
            'pharmacy_id' => $pharmacy->id,
            'items'=>[
                [
                    'drug_id'=>$drug->id,
                    'quantity'=>1,
                    'price'=>$drug->price,
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
            'minimum' => 2
        ]);
        $invoice = app(\App\Services\Supervisor\InvoiceService::class)->create([
            'pharmacy_id' => $pharmacy->id,
            'date' => now(),
            'items' => [
                [
                    'drug_id' => $drug->id,
                    'quantity' => 1
                ]
            ]
        ]);

        $data = [
            'date' => now()->format('Y-m-d') ,
            'pharmacy_id' => $pharmacy->id,
            'items'=>[
                [
                    'drug_id'=>$drug->id,
                    'quantity'=>1,
                    'price'=>$drug->price,
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
    }
    public function test_delete_invoice(){
        $invoice = Invoice::factory()->create();
        $response = $this->deleteJson('/api/v1/invoices/'.$invoice->id);
        $response->assertStatus(200);
        $response->assertJson([
            'status' => true,
            'message' => 'Invoice deleted successfully',
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
