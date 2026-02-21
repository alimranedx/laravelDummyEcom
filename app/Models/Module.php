<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Module extends Model
{
    protected $fillable = ['name', 'icon', 'sequence', 'display_name'];

    public function subModules()
    {
        return $this->hasMany(SubModule::class);
    }

    public function pages()
    {
        return $this->hasMany(Page::class);
    }
}
