<?php

namespace App\Services;

use App\Models\City;
use Illuminate\Pagination\LengthAwarePaginator;

class CityService
{
    public function getCities(string $search = null, int $perPage = 10, int $page = 1): LengthAwarePaginator
    {
        $query = City::query();

        if ($search) {
            $query->where('city', 'like', "%{$search}%");
        }

        return $query->paginate($perPage, ['*'], 'page', $page);
    }
}
