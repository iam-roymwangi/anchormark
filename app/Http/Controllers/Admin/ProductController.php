<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Inertia\Inertia;

class ProductController extends Controller
{
    public function index()
    {
        return Inertia::render('admin/products/Index');
    }

    public function create()
    {
        return Inertia::render('admin/products/Create');
    }
}
