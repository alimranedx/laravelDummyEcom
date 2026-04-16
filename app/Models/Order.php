<?php

namespace App\Models;

use App\Enums\OrderStatus;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Collection\Collection;

class Order extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = ['user_id', 'guest_phone', 'total_price', 'status', 'payment_status', 'payment_method', 'shipping_address'];


    protected $casts = [
        'status' => OrderStatus::class,
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function items()
    {
        return $this->hasMany(OrderItem::class);
    }
    public function getByFilters(array $filters) : Collection
    {
        $query = self::query();

        if(isset($filters['date_range']) && !empty($filters['date_range'])){
            $dates = explode(' to ', $filters['date_range']);
            if(count($dates) === 2){
                $query->whereDate('created_at', '>=', $dates[0])
                      ->whereDate('created_at', '<=', $dates[1]);
            }else{
                $query->whereDate('created_at', $dates[0]);
            }
        }

        if(!empty($filters['status'])){
            if(is_array($filters['status'])){
                $query->whereIn('status', $filters['status']);
            }else{
                $query->where('status', $filters['status']);
            }
        }

        return $query->get();
    }
}
