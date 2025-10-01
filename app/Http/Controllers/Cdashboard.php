<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class Cdashboard extends Controller
{
    public function index()
    {
        return view('welcome'); // atau view('dashboard') sesuai file blade yang Anda punya
    }
}