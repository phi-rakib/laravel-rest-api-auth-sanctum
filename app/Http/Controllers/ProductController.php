<?php

namespace App\Http\Controllers;

use App\Http\Resources\ProductResource;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class ProductController extends BaseController
{
    public function index()
    {
        $products = Product::all();
        
        return $this->sendResponse(ProductResource::collection($products), 'Products retrieved Successfully');
    }

    public function store(Request $request)
    {
        $input = $request->all();

        $validator = Validator::make($input, [
            'name' => 'required',
            'detail' => 'required',
        ]);

        if($validator->fails())
        {
            return $this->sendError('Validation Errors', $validator->errors());
        }

        $product = Product::create($input);

        return $this->sendResponse(new ProductResource($product), 'Product Created Successfully');
    }

    public function show($id)
    {
        $product = Product::find($id);

        if(is_null($product)) {
            return $this->sendError('Product Not Found');
        }

        return $this->sendResponse(new ProductResource($product), 'Product Retrieved Successfully');
    }

    public function update(Request $request, Product $product)
    {
        $input = $request->all();

        $validator = Validator::make($input, [
            'name' => 'required',
            'detail' => 'required',
        ]);

        if($validator->fails())
        {
            return $this->sendError('Validation Error', $validator->errors());
        }

        $product->name = $input['name'];
        $product->detail = $input['detail'];
        $product->save();

        return $this->sendResponse(new ProductResource($product), 'Product Updated Successfully');
    }

    public function destroy(Product $product)
    {
        $product->delete();

        return $this->sendResponse([], 'Product Deleted Successfully');
    }
}
