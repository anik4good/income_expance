<?php

    use App\Income;
    use Carbon\Carbon;
    use Illuminate\Http\Request;

    if ( !function_exists('setting') )
    {


        function setting($key, $default = null)
        {
            return \App\Setting::get($key, $default);
        }


        function previousCash($request)
        {
            $incomes = Income::with('category')
                ->orderByDesc('created_at')
                ->where(function ($q) use ($request) {

                    if ( $request == 'today' )
                    {

                        $yesterday = Carbon::now()->subDays(1);
                        $q->whereBetween('created_at', [$yesterday->startOfDay()->format('Y-m-d H:i:s'), $yesterday->endOfDay()->format('Y-m-d H:i:s')]);

                    }


                    elseif ( $request == 'yesterday' )
                    {

                        $yesterday = Carbon::now()->subDays(2);
                        $q->whereBetween('created_at', [$yesterday->startOfDay()->format('Y-m-d H:i:s'), $yesterday->endOfDay()->format('Y-m-d H:i:s')]);

                    }


                    elseif ( $request == 'porsudin' )
                    {

                        $yesterday = Carbon::now()->subDays(3);
                        $q->whereBetween('created_at', [$yesterday->startOfDay()->format('Y-m-d H:i:s'), $yesterday->endOfDay()->format('Y-m-d H:i:s')]);

                    }


                    elseif ( $request == 'thisweek' )
                    {

                        $start = Carbon::now()->subDays(1)->startOfWeek(Carbon::SATURDAY)->startOfDay()->format('Y-m-d H:i:s');
                        $end = Carbon::now()->format('Y-m-d H:i:s');
                        $q->whereBetween('created_at', [$start, $end]);

                    }
                    elseif ( $request == 'lastweek' )
                    {

                        $carbon = Carbon::now()->subWeek();
                        $q->whereBetween('created_at', [$carbon->startOfWeek(Carbon::SATURDAY)->endOfDay()->format('Y-m-d H:i:s'), $carbon->endOfWeek(Carbon::FRIDAY)->startOfDay()->format('Y-m-d H:i:s')]);

                    }
                    elseif ( $request == '10days' )
                    {

                        $start = Carbon::now()->subDays(10)->startOfDay()->format('Y-m-d H:i:s');
                        $end = Carbon::now()->format('Y-m-d H:i:s');
                        $q->whereBetween('created_at', [$start, $end]);


                    }

                    elseif ( $request == 'thismonth' )
                    {

                        $today = Carbon::now();
                        $q->whereBetween('created_at', [$today->startOfMonth()->startOfDay()->format('Y-m-d H:i:s'), $today->endOfMonth()->endOfDay()->format('Y-m-d H:i:s')]);

                    }
                    elseif ( $request == 'lastmonth' )
                    {

                        $start = Carbon::now()->subMonth()->startOfMonth()->startOfMonth()->startOfDay()->format('Y-m-d H:i:s');
                        $end = Carbon::now()->subMonth()->startOfMonth()->endOfMonth()->endOfDay()->format('Y-m-d H:i:s');
                        $q->whereBetween('created_at', [$start, $end]);

                    }

                    elseif ( $request == 'last3month' )
                    {

                        $start = Carbon::now()->subDays(90)->startOfDay()->format('Y-m-d H:i:s');
                        $end = Carbon::now()->format('Y-m-d H:i:s');
                        $q->whereBetween('created_at', [$start, $end]);

                    }
                    else
                    {
                        $today = Carbon::now();
                        $q->whereBetween('created_at', [$today->startOfDay()->format('Y-m-d H:i:s'), $today->endOfDay()->format('Y-m-d H:i:s')]);
                    }
                })
                ->get();


         return   $total_income = $incomes->sum('condition_amount') + $incomes->sum('condition_charge') + $incomes->sum('booking_charge') + $incomes->sum('labour_charge') + $incomes->sum('other_amount');

        }

    }




