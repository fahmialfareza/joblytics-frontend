<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;

class JobDetailController extends Controller
{   
    public function index()
    {
        return view('job_detail.index');
    }
}