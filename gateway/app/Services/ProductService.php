<?php

namespace App\Services;

use App\Traits\ConsumeExternalService;
use Carbon\Carbon;

class ProductService
{
    use ConsumeExternalService;

    /**
     * The base uri to consume users service
     * @var string
     */
    public $baseUri;

    /**
     * Authorization secret to pass to author api
     * @var string
     */
    public $secret;

    public function __construct()
    {
        $this->baseUri =  env('PRODUCTS_SERVICE_BASE_URL');
        $this->secret = env('PRODUCTS_SERVICE_SECRET');
    }


    /**
     * Submit a new product
     */
    public function store($request)
    {
        $data = [
            'url' => $request->input('url'),
            'title' => $request->input('title'),
            'description' => $request->input('title'),
            'user_id' => $request->input('user_id'),
            "created_at" => Carbon::now(),
            "updated_at" => Carbon::now(),
            'user_id' => $request->input('user_type'),
            'image' => $request->image
        ];
        return $this->performRequest('POST', '/api/v1/product/store', $data);
    }

    /**
     * Get product list of specific user
     */
    public function list($request)
    {
        $data = [
            'user_id' => $request->input('user_type')
        ];
        return $this->performRequest('POST', '/api/v1/product/list', $data);
    }
    /**
     * Edit a product base on ID
     */

    public function edit($request)
    {
        $data = [];
        $data['user_id'] =  $request->input('user_type');
        if ($request->filled('title'))
            $data['title'] =  $request->input('title');
        if ($request->filled('url'))
            $data['url'] =  $request->input('url');
        if ($request->filled('description'))
            $data['description'] =  $request->input('description');
        if ($request->hasFile('image'))
            $data['image'] = $request->input('product_image');


        return $this->performRequest('POST', '/api/v1/product/edit', $data);
    }

    /**
     * Delete a product by ID
     */

    public function delete($request)
    {
        $data['user_id'] = $request->input('user_type');
        $data['product_id'] = $request->input('product_id');

        return $this->performRequest('POST', '/api/v1/product/delete', $data);
    }

    /**
     * Show a product
     */

     public function single($request)
     {
        $data['user_id'] = $request->input('user_type');
         $data['product_id'] = $request->input('product_id');

         return $this->performRequest('POST', '/api/v1/product/single', $data);

     }

     /**
      * Create a public link for specific product by ID
      */

      public function public_link($request)
      {
        $data['id'] = $request->input('id');

        return $this->performRequest('POST', '/api/v1/product/public_link', $data);

      }


}
