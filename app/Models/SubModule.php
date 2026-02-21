<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SubModule extends Model
{
    protected $fillable = [
        'module_id',
        'name',
        'controller_name',
        'icon',
        'sequence',
        'default_method',
        'display_name'
    ];

    public function module()
    {
        return $this->belongsTo(Module::class);
    }

    public function pages()
    {
        return $this->hasMany(Page::class);
    }
}
