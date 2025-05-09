<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;

class SupplyController extends Controller
{
    public function index()
    {
        return view('pages.admin.supply.index');
    }
}
