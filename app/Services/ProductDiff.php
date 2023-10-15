<?php

namespace App\Services;

use Illuminate\Support\Arr;

class ProductDiff
{
    public function diffPayloads(array $payload1, array $payload2): array
    {
        $result = [];

        $sameId = $this->sameId($payload1, $payload2);

        if ($sameId !== null) {
            $result[] = $sameId;

            return $result;
        }

        $result[] = $this->sameTitle($payload1, $payload2);
        $result[] = $this->sameDescription($payload1, $payload2);
        $result[] = $this->sameNumberOfImages($payload1, $payload2);
        $result[] = $this->sameNumberOfVariants($payload1, $payload2);
        $result[] = $this->sameImages($payload1, $payload2);
        $result[] = $this->sameVariants($payload1, $payload2);

        return array_values(Arr::where(Arr::flatten($result), fn ($item) => $item !== null));
    }

    protected function sameId(array $payload1, array $payload2): string|null
    {
        $line = 'The id is different in the second payload';

        if ($payload1['id'] !== $payload2['id']) {
            return $line;
        }

        return null;
    }

    protected function sameTitle(array $payload1, array $payload2): string|null
    {
        $line = 'The title is different in the second payload';

        if ($payload1['title'] !== $payload2['title']) {
            return $line;
        }

        return null;
    }

    protected function sameDescription(array $payload1, array $payload2): string|null
    {
        $line = 'The description is different in the second payload';

        if ($payload1['description'] !== $payload2['description']) {
            return $line;
        }

        return null;
    }

    protected function sameNumberOfImages(array $payload1, array $payload2): string|null
    {
        $line = 'The number of images is different in the second payload';

        if (count($payload1['images']) !== count($payload2['images'])) {
            return $line;
        }

        return null;
    }

    protected function sameNumberOfVariants(array $payload1, array $payload2): string|null
    {
        $line = 'The number of variants is different in the second payload';

        if (count($payload1['variants']) !== count($payload2['variants'])) {
            return $line;
        }

        return null;
    }

    protected function sameImages(array $payload1, array $payload2): array
    {
        $results = [];

        foreach ($payload1['images'] as $image) {
            $found = false;

            // check for deleted images
            foreach ($payload2['images'] as $image2) {
                if ($image['id'] === $image2['id']) {
                    $found = $image2;
                    break;
                }
            }

            if (!$found) {
                $results[] = "Image with ID: {$image['id']} has been removed";

                continue;
            }

            // check position changes
            if ($image['position'] !== $found['position']) {
                $results[] = "Image with ID: {$image['id']} has updated its position";
            }

            // check url changes
            if ($image['url'] !== $found['url']) {
                $results[] = "Image with ID: {$image['id']} has updated its url";
            }
        }

        foreach ($payload2['images'] as $image) {
            $found = false;

            // check for new images
            foreach ($payload1['images'] as $image2) {
                if ($image['id'] === $image2['id']) {
                    $found = true;
                    break;
                }
            }

            if (!$found) {
                $results[] = "Image with ID: {$image['id']} has been added";
            }
        }

        return $results;
    }

    protected function sameVariants(array $payload1, array $payload2): array
    {
        $results = [];

        foreach ($payload1['variants'] as $variant) {
            $found = false;

            // check for deleted variants
            foreach ($payload2['variants'] as $variant2) {
                if ($variant['id'] === $variant2['id']) {
                    $found = $variant2;
                    break;
                }
            }

            if (!$found) {
                $results[] = "Variant with ID: {$variant['id']} has been removed";

                continue;
            }

            // check sku changes
            if ($variant['sku'] !== $found['sku']) {
                $results[] = "Variant with ID: {$variant['id']} has updated its sku";
            }

            // check barcode changes
            if ($variant['barcode'] !== $found['barcode']) {
                $results[] = "Variant with ID: {$variant['id']} has updated its barcode";
            }

            // check image_id changes
            if ($variant['image_id'] !== $found['image_id']) {
                $results[] = "Variant with ID: {$variant['id']} has updated its image_id";
            }

            // check inventory_quantity changes
            if ($variant['inventory_quantity'] !== $found['inventory_quantity']) {
                $results[] = "Variant with ID: {$variant['id']} has updated its inventory_quantity";
            }
        }

        foreach ($payload2['variants'] as $variant) {
            $found = false;

            // check for new variants
            foreach ($payload1['variants'] as $variant2) {
                if ($variant['id'] === $variant2['id']) {
                    $found = true;
                    break;
                }
            }

            if (!$found) {
                $results[] = "Variant with ID: {$variant['id']} has been added";
            }
        }

        return $results;
    }
}
