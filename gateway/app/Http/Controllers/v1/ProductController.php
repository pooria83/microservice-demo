<?php

namespace App\Http\Controllers\v1;

use App\Models\Product;
use App\Traits\ApiResponser;
use Illuminate\Http\Request;
use App\Exceptions\DbErrorException;
use App\Exceptions\ValidationErrorException;
use App\Http\Controllers\Controller;
use App\Http\Resources\v1\Product as V1Product;
use App\Http\Resources\v1\ProductCollection;
use App\Services\ProductService;
use Carbon\Carbon;
use Illuminate\Validation\Rule;
use Illuminate\Database\QueryException;
use Illuminate\Support\Facades\Validator;
use Dusterio\LinkPreview\Client;


class ProductController extends Controller
{
    use ApiResponser;

    /**
     * The service to consume the product micro-service
     * @var ProductService
     */
    public $publicService;

    public function __construct(ProductService $productService)
    {
        $this->productService = $productService;
    }

    public function store(Request $request)
    {
        return $this->successResponse($this->ProductService->store($request));
    }

    public function list(Request $request)
    {
        return $this->successResponse($this->ProductService->list($request));
    }

    public function edit(Request $request)
    {
        return $this->successResponse($this->ProductService->edit($request));
    }

    public function delete(Request $request)
    {
        return $this->successResponse($this->ProductService->delete($request));
    }

    public function single(Request $request)
    {
        return $this->successResponse($this->ProductService->single($request));
    }

    public function public_link(Request $request)
    {
        return $this->successResponse($this->ProductService->public_link($request));
    }
}
