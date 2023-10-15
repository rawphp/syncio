<?php

namespace App\Http\Controllers;

use App\Http\Requests\UpdateProductRequest;
use App\Services\ProductDiff;
use Illuminate\Support\Facades\Storage;

class ProductController extends Controller
{
    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateProductRequest $request, int $id, ProductDiff $diffService)
    {
        $filename = "product-$id.json";

        if (!Storage::fileExists($filename)) {
            Storage::write($filename, json_encode($request->all()));

            return response()->json([], 201);
        }

        $payload1 = json_decode(Storage::read($filename), true);
        $payload2 = $request->validated();

        // compare payloads
        $result = $diffService->diffPayloads($payload1, $payload2);

        return response()->json(['differences' => array_values($result)]);
    }
}
