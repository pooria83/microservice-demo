<?php

namespace App\Http\Controllers\v1;

use App\Models\Product;
use App\Traits\ApiResponser;
use App\Exceptions\DbErrorException;
use App\Exceptions\ValidationErrorException;
use App\Http\Controllers\Controller;
use App\Http\Resources\v1\Product as V1Product;
use App\Http\Resources\v1\ProductCollection;
use App\Http\Resources\v1\ViewProductCountCollection;
use Carbon\Carbon;
use Illuminate\Validation\Rule;
use Illuminate\Database\QueryException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;

class Product_UserController extends Controller
{
    public function check_view_count(Request $request)
    {
        $params = [];
        try{
        $products_and_views = DB::table('products');
        $products_and_views = $products_and_views->join('product_user' , 'product_user.product_id' , '=' , 'products.id');
        if( $request->filled('filter_user_id'))
        $products_and_views = $products_and_views->where('product_user.user_id' , '=' , $request->input('filter_user_id') );
        if( $request->filled('filter_from_date'))
        $products_and_views = $products_and_views->where('product_user.created_at' , '>=' , $request->input('filter_from_date') );
        if( $request->filled('filter_to_date'))
        $products_and_views = $products_and_views->where('product_user.created_at' , '<=' , $request->input('filter_to_date') );
        $products_and_views = $products_and_views
        ->where('products.user_id' , $request->input('user_id') )
        ->select('products.id')
        ->distinct()
        ->get();
        }
        catch (QueryException $exception) {
            throw new DbErrorException($exception->errorInfo);
        }
        if( $request->filled('filter_user_id'))
        $params['filter_user_id'] = $request->input('filter_user_id');
        if( $request->filled('filter_from_date'))
        $params['filter_from_date'] = $request->input('filter_from_date');
        if( $request->filled('filter_to_date'))
        $params['filter_to_date'] = $request->input('filter_to_date');



        return [
            'status' => true,
            'result' => new ViewProductCountCollection ($products_and_views , $params)
        ];

    }
}
