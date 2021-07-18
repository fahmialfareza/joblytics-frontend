<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;

class DashboardController extends Controller
{
    private $USER_SESSION;
    

    public function index()
    {
        return view('dashboard.index');
    }
    public function skill()
    {
        return view('dashboard.index');
    }
    public function industry()
    {
        return view('dashboard.index');
    }
    public function needs()
    {
        return view('dashboard.index');
    }
}
