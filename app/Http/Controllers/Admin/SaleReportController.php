<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Spatie\Permission\Models\Role;

class SaleReportController extends Controller
{
    public function index()
    {
        return "Hello Sale Report page";
    }

    
}
