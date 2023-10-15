<?php

namespace Tests\Feature;

use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class ProductTest extends TestCase
{
    public function setUp(): void
    {
        parent::setUp();

        Storage::delete('product-432232523.json');
    }

    public function test_update_product_new_product_submitted(): void
    {
        $data = json_decode(file_get_contents('tests/resources/payload-1.json'), true);

        $response = $this->put('/api/products/432232523', $data);

        $response->assertStatus(201);
    }

    public function test_update_product_with_changes(): void
    {
        $data = json_decode(file_get_contents('tests/resources/payload-1.json'), true);

        $response = $this->put('/api/products/432232523', $data);

        $response->assertStatus(201);

        $data = json_decode(file_get_contents('tests/resources/payload-2.json'), true);

        $response = $this->put('/api/products/432232523', $data);

        $response->assertStatus(200);

        $this->assertEquals(
            [
                "differences" => [
                    0 => "The description is different in the second payload",
                    1 => "The number of images is different in the second payload",
                    2 => "Image with ID: 23445 has been removed",
                    3 => "Image with ID: 34566 has updated its url",
                    4 => "Image with ID: 33253 has been removed",
                    5 => "Image with ID: 56353 has updated its position",
                    6 => "Image with ID: 33245 has been added",
                    7 => "Image with ID: 33213 has been added",
                    8 => "Image with ID: 34546 has been added",
                    9 => "Variant with ID: 433232 has updated its image_id",
                    10 => "Variant with ID: 231544 has updated its image_id",
                    11 => "Variant with ID: 323245 has updated its sku",
                    12 => "Variant with ID: 323245 has updated its barcode",
                    13 => "Variant with ID: 323245 has updated its image_id",
                    14 => "Variant with ID: 323445 has updated its sku",
                    15 => "Variant with ID: 323445 has updated its image_id",
                ]
            ],
            $response->json()
        );
    }

    public function test_update_product_with_no_changes(): void
    {
        $data = json_decode(file_get_contents('tests/resources/payload-1.json'), true);

        $response = $this->put('/api/products/432232523', $data);

        $response->assertStatus(201);

        $response = $this->put('/api/products/432232523', $data);

        $response->assertStatus(200);

        $this->assertEquals(
            [
                "differences" => []
            ],
            $response->json()
        );
    }
}
