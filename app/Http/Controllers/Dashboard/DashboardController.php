<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Models\User;

class DashboardController extends Controller
{
    public function __construct()
    {
        $this->middleware('permission:view_dashboard')->only(['index']);
    }
    public function index()
    {
        // Example: passing variables to the view
        $user = auth()->user();
        $users = User::whereDoesntHave('roles', function ($query) {
            $query->where('name', 'Super Admin');
        })->count();
        return view('dashboard.home', compact('user', 'users'));
    }
}
