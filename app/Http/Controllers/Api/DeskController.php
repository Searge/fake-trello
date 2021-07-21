<?php

namespace App\Http\Controllers\Api;

use App\Models\Desk;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class DeskController extends Controller
{
    public function index()
    {
        return Desk::all();
    }
}
