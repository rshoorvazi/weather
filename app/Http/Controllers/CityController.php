<?php

namespace App\Http\Controllers;

use App\Http\Resources\CityResource;
use App\Services\CityService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class CityController extends Controller
{
    protected CityService $cityService;

    public function __construct(CityService $cityService)
    {
        $this->cityService = $cityService;
    }

    public function index(Request $request): JsonResponse
    {
//        dump($request->input()) ;
        $perPage = $request->input('per_page', 10);
        $page = $request->input('page', 1);
        $search = $request->input('search');

        $cities = $this->cityService->getCities($search, $perPage, $page);

        return response()->json([
            'results' => CityResource::collection($cities),
            'pagination' => ['more' => $cities->hasMorePages()]
        ]);
    }


}
