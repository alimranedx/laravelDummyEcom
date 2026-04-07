<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Pagination\LengthAwarePaginator;

class Product extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = ['category_id', 'brand_id', 'name', 'slug', 'description', 'price', 'stock', 'image_path'];

    public function getImageUrlAttribute()
    {
        if ($this->image_path) {
            return asset('storage/'.$this->image_path);
        }

        return asset('images/placeholder-product.png');
    }

    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function brand()
    {
        return $this->belongsTo(Brand::class);
    }

    public function getByFilters(array $filters = []): Collection|LengthAwarePaginator
    {
        $query = self::query();

        $query->with(['category', 'brand']);
        if (! empty($filters['date_range']) && ! empty($filters['from_date']) && ! empty($filters['to_date'])) {
            $query->whereBetween('created_at', [$filters['from_date'], $filters['to_date']]);
        }

        if (! empty($filters['category_id'])) {
            if (is_array($filters['category_id'])) {
                $query->whereIn('category_id', $filters['category_id']);
            } else {
                $query->where('category_id', $filters['category_id']);
            }
        }
        if (! empty($filters['brand_id'])) {
            if (is_array($filters['brand_id'])) {
                $query->whereIn('brand_id', $filters['brand_id']);
            } else {
                $query->where('brand_id', $filters['brand_id']);
            }
        }
        if (! empty($filters['q'])) {
            $query->where(function ($q) use ($filters) {
                $q->where('name', 'like', '%'.$filters['q'].'%')
                    ->orWhere('brand', 'like', '%'.$filters['q'].'%')
                    ->orWhere('category', 'like', '%'.$filters['q'].'%');
            });
        }
        if (! empty($filters['per_page'])) {

            return $query->paginate($filters['per_page']);
        }

        return $query->get();
    }
}
