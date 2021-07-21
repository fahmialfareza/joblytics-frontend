<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;

class FutureJobController extends Controller
{   
    public function index()
    {
        return view('future_job.index');
    }
}