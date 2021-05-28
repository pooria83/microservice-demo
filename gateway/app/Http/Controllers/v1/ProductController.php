<?php

namespace App\Http\Controllers\v1;

use App\Traits\ApiResponser;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Services\ProductService;

class ProductController extends Controller
{
    use ApiResponser;

    /**
     * The service to consume the product micro-service
     * @var ProductService
     */
    public $productService;

    public function __construct(ProductService $productService)
    {
        $this->productService = $productService;
    }

    public function store(Request $request)
    {
        return $this->successResponse($this->productService->store($request));
    }

    public function list(Request $request)
    {
        return $this->successResponse($this->productService->list($request));
    }

    public function edit(Request $request)
    {
        return $this->successResponse($this->productService->edit($request));
    }

    public function delete(Request $request)
    {
        return $this->successResponse($this->productService->delete($request));
    }

    public function single(Request $request)
    {
        return $this->successResponse($this->productService->single($request));
    }

    public function public_link(Request $request)
    {
        return $this->successResponse($this->productService->public_link($request));
    }
}
