<?php

namespace App\Http\Resources\v1;

use App\Models\Product_user;
use Illuminate\Http\Resources\Json\ResourceCollection;
use Illuminate\Support\Facades\DB;

class ViewProductCountCollection extends ResourceCollection
{
    protected $params;

    public function __construct($resource, $params)
    {
        $this->params = $params;
        parent::__construct($resource);
    }

    /**
     * Transform the resource collection into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {

        return
            $this->collection->map(function ($item) {
                $product = DB::table('products')->find($item->id);
                $views = DB::table('product_user');
                $views = $views->where('product_id', $item->id);
                if (isset($this->params['filter_user_id']))
                    $views =  $views->where('user_id', $this->params['filter_user_id']);
                if (isset($this->params['filter_from_date']))
                    $views =  $views->where('created_at', '>=', $this->params['filter_from_date']);
                if (isset($this->params['filter_to_date']))
                    $views =  $views->where('created_at', '<=', $this->params['filter_to_date']);
                $views = $views->get();

                return [
                    'id' => $product->id,
                    'title' => $product->title,
                    'url' => $product->url,
                    'image' => url('public/images/' . $product->image),
                    'description' => $product->description,
                    'label' => (!empty($product->deleted_at) ? 'deleted' : ''),
                    'views' =>  new ViewCountCollection($views)
                ];
            });
    }
}
