<?php

namespace Tests\Unit\Services;

use App\Services\ProductDiff;
use PHPUnit\Framework\TestCase;

class ProductDiffTest extends TestCase
{
    private ProductDiff $diff;

    private array $product1 = [];
    private array $product2 = [];

    public function setUp(): void
    {
        parent::setUp();

        $this->product1 = json_decode(file_get_contents('tests/resources/payload-1.json'), true);
        $this->product2 = $this->product1;

        /** @var ProductDiff $service */
        $this->diff = resolve(ProductDiff::class);
    }

    public function test_different_product_id_rejected()
    {
        $product1 = [
            'id' => 1,
        ];
        $product2 = [
            'id' => 2,
        ];

        $result = $this->diff->diffPayloads($product1, $product2);

        $this->assertEquals('The id is different in the second payload', $result[0]);
    }

    public function test_no_differences()
    {
        $result = $this->diff->diffPayloads($this->product1, $this->product2);

        $this->assertEquals([], $result);
    }

    public function test_different_product_title()
    {
        $this->product2['title'] = 'Different';

        $result = $this->diff->diffPayloads($this->product1, $this->product2);

        $this->assertEquals('The title is different in the second payload', $result[0]);
    }

    public function test_different_product_description()
    {
        $this->product2['description'] = 'Different';

        $result = $this->diff->diffPayloads($this->product1, $this->product2);

        $this->assertEquals('The description is different in the second payload', $result[0]);
    }

    public function test_product_image_added()
    {
        $this->product2['images'][] = ['id' => 111];

        $result = $this->diff->diffPayloads($this->product1, $this->product2);

        $this->assertEquals(
            [
                'The number of images is different in the second payload',
                'Image with ID: 111 has been added',
            ],
            $result
        );
    }

    public function test_product_image_removed()
    {
        array_shift($this->product2['images']);

        $result = $this->diff->diffPayloads($this->product1, $this->product2);

        $this->assertEquals(
            [
                'The number of images is different in the second payload',
                'Image with ID: 26372 has been removed',
            ],
            $result
        );
    }

    public function test_product_image_position_changed()
    {
        $this->product2['images'][0]['position'] = 10;

        $result = $this->diff->diffPayloads($this->product1, $this->product2);

        $this->assertEquals(
            'Image with ID: 26372 has updated its position',
            $result[0]
        );
    }

    public function test_product_image_url_changed()
    {
        $this->product2['images'][0]['url'] = 'https://google.com';

        $result = $this->diff->diffPayloads($this->product1, $this->product2);

        $this->assertEquals(
            'Image with ID: 26372 has updated its url',
            $result[0]
        );
    }

    public function test_product_variant_added()
    {
        $this->product2['variants'][] = ['id' => 111];

        $result = $this->diff->diffPayloads($this->product1, $this->product2);

        $this->assertEquals(
            [
                'The number of variants is different in the second payload',
                'Variant with ID: 111 has been added',
            ],
            $result
        );
    }

    public function test_product_variant_removed()
    {
        array_shift($this->product2['variants']);

        $result = $this->diff->diffPayloads($this->product1, $this->product2);

        $this->assertEquals(
            [
                'The number of variants is different in the second payload',
                'Variant with ID: 433232 has been removed',
            ],
            $result
        );
    }

    public function test_product_variant_sku_changed()
    {
        $this->product2['variants'][0]['sku'] = 'SKU-II-NEW';

        $result = $this->diff->diffPayloads($this->product1, $this->product2);

        $this->assertEquals(
            'Variant with ID: 433232 has updated its sku',
            $result[0]
        );
    }

    public function test_product_variant_barcode_changed()
    {
        $this->product2['variants'][0]['barcode'] = 'BAR_CODE_NEXT';

        $result = $this->diff->diffPayloads($this->product1, $this->product2);

        $this->assertEquals(
            'Variant with ID: 433232 has updated its barcode',
            $result[0]
        );
    }

    public function test_product_variant_image_id_changed()
    {
        $this->product2['variants'][0]['image_id'] = 99999;

        $result = $this->diff->diffPayloads($this->product1, $this->product2);

        $this->assertEquals(
            'Variant with ID: 433232 has updated its image_id',
            $result[0]
        );
    }

    public function test_product_variant_inventory_quantity_changed()
    {
        $this->product2['variants'][0]['inventory_quantity'] = 1000;

        $result = $this->diff->diffPayloads($this->product1, $this->product2);

        $this->assertEquals(
            'Variant with ID: 433232 has updated its inventory_quantity',
            $result[0]
        );
    }
}
