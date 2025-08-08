<?php

namespace App\Http\Controllers\LandingPage;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class HalamanAwalController extends Controller
{
    public function index()
    {
        return view('landing-page.halaman_awal');
    }
}
