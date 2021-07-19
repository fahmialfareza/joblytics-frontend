<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;

class OverviewController extends Controller
{
    private $USER_SESSION;
    
    public function index()
    {
        return view('overview.index');
    }
    public function skill()
    {
        return view('overview.index');
    }
    public function industry()
    {
        return view('overview.index');
    }
    public function needs()
    {
        return view('overview.index');
    }
}