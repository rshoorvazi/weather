<?php

namespace App\Http\Controllers;

use App\Http\Resources\CityResource;
use App\Models\City;
use Illuminate\Http\Request;

class CityController extends Controller
{
    public function index(Request $request)
    {
        $perPage = $request->input('per_page', 10);
        $page = $request->input('page', 1);

        $cities = City::paginate($perPage);

        return response()->json([
            'results' =>CityResource::collection($cities),
            'pagination' => [
                'more' => $cities->currentPage() < $cities->lastPage()
            ]
        ]);
    }


}
