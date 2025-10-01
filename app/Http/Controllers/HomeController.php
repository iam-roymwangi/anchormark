<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Inertia\Inertia;

class HomeController extends Controller
{
    public function products()
    {
        return Inertia::render('Products');
    }

    public function about()
    {
        return Inertia::render('About');
    }

    public function showProductDetails()
    {
        return Inertia::render('ProductDetails');
    }
}
