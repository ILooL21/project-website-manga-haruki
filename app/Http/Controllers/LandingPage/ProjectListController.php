<?php

namespace App\Http\Controllers\LandingPage;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class ProjectListController extends Controller
{
    public function index() 
    {
        return view('landing-page.project_list');
    }
}
