<?php

namespace App\Http\Controllers;

use App\Expanse;
use App\Income;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Artisan;

class HomeController extends Controller
{


    public function index()
    {
        return view('home');
    }

    public function dashboard()
    {

        $incomes = Income::with('category')
            ->orderByDesc('created_at')
            ->whereBetween('created_at', [Carbon::now()->startOfDay()->format('Y-m-d H:i:s'), Carbon::now()->endOfDay()->format('Y-m-d H:i:s')])
            ->get();


        $expanses = Expanse::with('category')
            ->orderByDesc('created_at')
            ->whereBetween('created_at', [Carbon::now()->startOfDay()->format('Y-m-d H:i:s'), Carbon::now()->endOfDay()->format('Y-m-d H:i:s')])
            ->get();

        $total_income = $incomes->sum('condition_amount') + $incomes->sum('condition_charge') + $incomes->sum('booking_charge') + $incomes->sum('labour_charge') + $incomes->sum('other_amount');
        $total_expanse = $expanses->sum('amount');
        $cash = $total_income - $total_expanse;
        $previousCash = 0;

        return view('backend.dashboard', compact('incomes', 'expanses', 'total_income', 'total_expanse', 'cash', 'previousCash'));
    }


    public function clearCache()
    {
        Artisan::call('cache:clear');
        Artisan::call('route:clear');
        Artisan::call('config:clear');
        notify()->success('All cache Successfully Cleared.', 'Success');
        return redirect()->back();
    }
}
