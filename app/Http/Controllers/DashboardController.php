<?php

namespace App\Http\Controllers;

use App\Models\Bantuan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function index()
    {

        $sql = Bantuan::select('*');

        if (Auth::user()->level == 2) {
            $sql->where('user_id', Auth::user()->id);
        }
        $bantuan = $sql->count();
        $pageTitle = 'Dashboard';
        return view('v_dashboard', compact('pageTitle', 'bantuan'));
    }
}
