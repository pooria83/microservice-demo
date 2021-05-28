<?php

namespace App\Http\Controllers\v1;



use App\Models\Product;
use App\Traits\ApiResponser;
use App\Exceptions\DbErrorException;
use App\Exceptions\ValidationErrorException;
use App\Http\Controllers\Controller;
use App\Http\Resources\v1\Product as V1Product;
use App\Http\Resources\v1\ProductCollection;
use Carbon\Carbon;
use Illuminate\Validation\Rule;
use Illuminate\Database\QueryException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Dusterio\LinkPreview\Client;


class ProductController extends Controller
{
    use ApiResponser;
    public function store(Request $request)
    {
        $validate = Validator::make($request->all(), [
            'url' => 'required|url|' .
                Rule::unique('products')
                ->where('url', $request->url)
                ->where('user_id',  (string) $request->user_id),
            'title' => 'required|string',
            'description' => 'required|string',
            'image' => 'required|image|mimes:jpeg,png,jpg,bmp|max:2048',

        ]);
        if ($validate->fails()) {
            throw new ValidationErrorException($validate->errors()->messages());
        }
        try {
            $product_id = Product::insertGetId([
                'url' => $request->input('url'),
                'title' => $request->input('title'),
                'description' => $request->input('title'),
                'user_id' => $request->input('user_id'),
                "created_at" =>   Carbon::now(),
                "updated_at" => Carbon::now(),
            ]);
        } catch (QueryException $exception) {
            throw new DbErrorException($exception->errorInfo);
        }
        $imageName = $product_id . '.' . $request->image->extension();

        $request->image->move(public_path('images'), $imageName);
        try {
            Product::whereId($product_id)->update(['image' => $imageName]);
        } catch (QueryException $exception) {
            throw new DbErrorException($exception->errorInfo);
        }
        return [
            'status' => true,
            'result' => [
                'product_id' => $product_id
            ]
        ];
    }

    public function list(Request $request)
    {
        $user_id = $request->input('user_id');
        try {
            $products = Product::withTrashed()
                ->where('user_id', $user_id)
                ->get();
        } catch (QueryException $exception) {
            throw new DbErrorException($exception->errorInfo);
        }

        return [
            'status' => true,
            'result' =>  new ProductCollection($products)
        ];
    }

    public function edit(Request $request)
    {
        $data = [];
        if ($request->filled('title'))
            $data['title'] =  $request->input('title');
        if ($request->filled('url'))
            $data['url'] =  $request->input('url');
        if ($request->filled('description'))
            $data['description'] =  $request->input('description');
        if ($request->hasFile('image')) {
            unlink(public_path('images/' . $request->input('product_image')));
            $imageName = $request->input('product_id') . '.' . $request->image->extension();
            $request->image->move(public_path('images'), $imageName);
            $data['image'] = $imageName;
        }

        if (!empty($data)) {
            $data['updated_at'] = Carbon::now();
            try {
                Product::whereId($request->input('product_id'))->update($data);
            } catch (QueryException $exception) {
                throw new DbErrorException($exception->errorInfo);
            }
            return [
                'status' => true,
                'result' => [
                    'message' => 'Product edited!'
                ]
            ];
        }
        return [
            'status' => false,
            'result' => [
                'message' => 'at least one of the title , url , description , image is required for update'
            ]
        ];
    }

    public function delete(Request $request)
    {
        try {
            Product::whereId($request->input('product_id'))->delete();
        } catch (QueryException $exception) {
            throw new DbErrorException($exception->errorInfo);
        }

        return [
            'status' => true,
            'result' => [
                'message' => 'image deleted'
            ]
        ];
    }

    public function single(Request $request)
    {
        try {
            $product =   Product::find($request->input('product_id'));
        } catch (QueryException $exception) {
            throw new DbErrorException($exception->errorInfo);
        }
        return [
            'status' => true,
            'result' =>   new V1Product($product)
        ];
    }

    public function public_link(Request $request)
    {

        $validate = Validator::make($request->all(), [
            'id' => 'required|numeric|min:1'
        ]);
        if ($validate->fails()) {
            throw new ValidationErrorException($validate->errors()->messages());
        }

        try {
            $product = Product::find($request->input('id'));
        } catch (QueryException $exception) {
            throw new DbErrorException($exception->errorInfo);
        }
        $previewClient = new  Client('https://www.boogiecall.com/en/Melbourne');

        // Get previews from all available parsers
        $previews = $previewClient->getPreviews();

        // Get a preview from specific parser
        $preview = $previewClient->getPreview('general');
        dd($preview);

        // Convert output to array
        $preview = $preview->toArray();
    }
}
