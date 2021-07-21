<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;

class ComparisonController extends Controller
{   
    public function index()
    {
        return view('comparison.index');
    }
}