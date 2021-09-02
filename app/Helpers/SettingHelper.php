<?php

    use App\Expanse;
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
                        $start_date = Carbon::now()->subDays(365);
                        $today = Carbon::now();
                        $q->whereBetween('created_at', [$start_date->startOfDay()->format('Y-m-d H:i:s'), $today->startOfDay()->format('Y-m-d H:i:s')]);

                    }


                    elseif ( $request == 'yesterday' )
                    {

                        $start_date = Carbon::now()->subDays(365);
                        $yesterday = Carbon::now()->subDays(1);
                        $q->whereBetween('created_at', [$start_date->startOfDay()->format('Y-m-d H:i:s'), $yesterday->startOfDay()->format('Y-m-d H:i:s')]);

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
                        $start_date = Carbon::now()->subDays(365)->startOfDay()->format('Y-m-d H:i:s');

                        $start = Carbon::now()->subDays(10)->endOfDay()->format('Y-m-d H:i:s');

                        $q->whereBetween('created_at', [$start_date, $start]);

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


            $expanses = Expanse::with('category')
                ->orderByDesc('created_at')
                ->where(function ($q) use ($request) {


                    if ( $request == 'today' )
                    {
                        $start_date = Carbon::now()->subDays(365);
                        $today = Carbon::now();
                        $q->whereBetween('created_at', [$start_date->startOfDay()->format('Y-m-d H:i:s'), $today->startOfDay()->format('Y-m-d H:i:s')]);

                    }


                    elseif ( $request == 'yesterday' )
                    {

                        $start_date = Carbon::now()->subDays(365);
                        $yesterday = Carbon::now()->subDays(1);
                        $q->whereBetween('created_at', [$start_date->startOfDay()->format('Y-m-d H:i:s'), $yesterday->startOfDay()->format('Y-m-d H:i:s')]);

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
                        $start_date = Carbon::now()->subDays(365)->startOfDay()->format('Y-m-d H:i:s');

                        $start = Carbon::now()->subDays(10)->endOfDay()->format('Y-m-d H:i:s');


                        $q->whereBetween('created_at', [$start_date, $start]);


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


            $total_income = $incomes->sum('condition_amount') + $incomes->sum('condition_charge') + $incomes->sum('booking_charge') + $incomes->sum('labour_charge') + $incomes->sum('other_amount');

            $total_expanse = $expanses->sum('amount');

            return $total_income - $total_expanse;
        }


        function previousCashWithCustomDate($request)
        {
            $incomes = Income::with('category')
                ->orderByDesc('created_at')
                ->where(function ($q) use ($request) {

                    if ( $request )
                    {
                        $start_date = Carbon::now()->subDays(365)->startOfDay()->format('Y-m-d H:i:s');
                        $old_date = $request;
                        $date = Carbon::createFromFormat('Y-m-d', $old_date)->subDays()->endOfDay()->format('Y-m-d H:i:s');

                        $q->whereBetween('created_at', [$start_date, $date]);
                    }

                })
                ->get();


            $expanses = Expanse::with('category')
                ->orderByDesc('created_at')
                ->where(function ($q) use ($request) {


                    if ( $request )
                    {
                        $start_date = Carbon::now()->subDays(365)->startOfDay()->format('Y-m-d H:i:s');
                        $old_date = $request;
                        $date = Carbon::createFromFormat('Y-m-d', $old_date)->subDays()->endOfDay()->format('Y-m-d H:i:s');
                        $q->whereBetween('created_at', [$start_date, $date]);

                    }

                })
                ->get();


            $total_income = $incomes->sum('condition_amount') + $incomes->sum('condition_charge') + $incomes->sum('booking_charge') + $incomes->sum('labour_charge') + $incomes->sum('other_amount');

            $total_expanse = $expanses->sum('amount');

            return $total_income - $total_expanse;
        }

    }




