<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class PresidentController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('presidente');
    }


}
