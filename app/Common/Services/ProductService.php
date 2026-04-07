<?php

namespace App\Common\Services;

use App\Common\Services\Utility\DateTime;
use App\Models\Product;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Pagination\LengthAwarePaginator;

class ProductService
{
    /**
     * Create a new class instance.
     */
    public function __construct()
    {
        //
    }

    public function prepareFilters(array $inputData): array
    {
        $filters = [];
        if (! empty($inputData['date_range'])) {
            $filters['date_range'] = $inputData['date_range'];
            [$filters['from_date'], $filters['to_date']] = (new DateTime)->gateFormatedDateFromDateRange($inputData['date_range']);
        }
        if (! empty($inputData['q'])) {
            $filters['q'] = $inputData['q'];
        }
        if (! empty($inputData['category_id'])) {
            $filters['category_id'] = $inputData['category_id'];
        }
        if (! empty($inputData['brand_id'])) {
            $filters['brand_id'] = $inputData['brand_id'];
        }
        $filters['per_page'] = $inputData['per_page'] ?? 10;

        return $filters;
    }

    public function getByFilters(array $filters = []): LengthAwarePaginator|Collection
    {
        $products = (new Product)->getByFilters($filters);

        return $products;
    }
}
