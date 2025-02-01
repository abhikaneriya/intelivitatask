<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;

class Controller extends BaseController
{
    use AuthorizesRequests, ValidatesRequests;
    public function DTFilters($request)
    {
        $filters = array(
            'draw' => $request['draw'],
            'offset' => $request['start'],
            'limit' => $request['length'],
            'sort_column' => $request['columns'][$request['order'][0]['column']]['data'],
            'sort_order' => $request['order'][0]['dir'],
            'search' => $request['search']['value']
        );
        return $filters;
    }
}
