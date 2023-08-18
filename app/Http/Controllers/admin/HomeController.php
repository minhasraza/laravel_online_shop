<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class HomeController extends Controller
{
    public function index(){
        $admin = Auth::guard('admin')->user();
        print_r("Welcome". $admin->name . '<a href=" '.route('admin.logout').'">Logout </a>');
        exit();
    }

    public function logout(){
        Auth::guard('admin')->logout();
        return redirect()->route('admin.login');
    }
}
