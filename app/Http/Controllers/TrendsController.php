<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;

class TrendsController extends Controller
{   
    public function index()
    {
        return view('trends.index');
    }
}