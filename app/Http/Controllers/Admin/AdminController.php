<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;


class AdminController extends Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->middleware(['auth:sanctum', 'role:admin']);
    }

    public function index()
    {
        return response()->json(['message' => 'Admin dashboard']);
    }
}
