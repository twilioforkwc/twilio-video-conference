<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class ErrorsController extends Controller
{
    public function index($page)
    {
        return view("errors.{$page}");
    }
}
