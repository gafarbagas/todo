<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        $tasks = auth()->user()->tasks()->orderBy('priority', 'asc')->get();
        return view('dashboard.index', [
            'tasks' => $tasks,
        ]);
    }
}
