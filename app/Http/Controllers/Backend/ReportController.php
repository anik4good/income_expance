<?php

    namespace App\Http\Controllers\Backend;

    use App\Expanse;
    use App\Http\Controllers\Controller;
    use App\Income;
    use Carbon\Carbon;
    use Illuminate\Http\Request;

    class ReportController extends Controller {


        public function index(Request $request)
        {

            $incomes = Income::with('category')
                ->orderByDesc('created_at')
                ->where(function ($q) use ($request) {

                    if ( $request->tracking_id )
                    {
                        $q->where('tracking_id', $request->tracking_id);


                    }

                })
                ->where(function ($q) use ($request) {


                    if ( $request->custom_date )
                    {

                        $old_date = $request->custom_date;
                        $date = Carbon::createFromFormat('Y-m-d', $old_date);
                        $q->whereBetween('created_at', [$date->startOfDay()->format('Y-m-d H:i:s'), $date->endOfDay()->format('Y-m-d H:i:s')]);

                    }


                    elseif ( $request->dateFilter == 'today' )
                    {

                        $today = Carbon::now();
                        $q->whereBetween('created_at', [$today->startOfDay()->format('Y-m-d H:i:s'), $today->endOfDay()->format('Y-m-d H:i:s')]);

                    }


                    elseif ( $request->dateFilter == 'yesterday' )
                    {

                        $yesterday = Carbon::now()->subDay();
                        $q->whereBetween('created_at', [$yesterday->startOfDay()->format('Y-m-d H:i:s'), $yesterday->endOfDay()->format('Y-m-d H:i:s')]);

                    }
                    elseif ( $request->dateFilter == 'thisweek' )
                    {

                        $start = Carbon::now()->startOfWeek(Carbon::SATURDAY)->startOfDay()->format('Y-m-d H:i:s');
                        $end = Carbon::now()->format('Y-m-d H:i:s');
                        $q->whereBetween('created_at', [$start, $end]);

                    }
                    elseif ( $request->dateFilter == 'lastweek' )
                    {

                        $carbon = Carbon::now()->subWeek();
                        $q->whereBetween('created_at', [$carbon->startOfWeek(Carbon::SATURDAY)->endOfDay()->format('Y-m-d H:i:s'), $carbon->endOfWeek(Carbon::FRIDAY)->startOfDay()->format('Y-m-d H:i:s')]);

                    }
                    elseif ( $request->dateFilter == '10days' )
                    {

                        $start = Carbon::now()->subDays(10)->startOfDay()->format('Y-m-d H:i:s');
                        $end = Carbon::now()->format('Y-m-d H:i:s');
                        $q->whereBetween('created_at', [$start, $end]);


                    }

                    elseif ( $request->dateFilter == 'thismonth' )
                    {

                        $today = Carbon::now();
                        $q->whereBetween('created_at', [$today->startOfMonth()->startOfDay()->format('Y-m-d H:i:s'), $today->endOfMonth()->endOfDay()->format('Y-m-d H:i:s')]);

                    }
                    elseif ( $request->dateFilter == 'lastmonth' )
                    {

                        $start = Carbon::now()->subMonth()->startOfMonth()->startOfMonth()->startOfDay()->format('Y-m-d H:i:s');
                        $end = Carbon::now()->subMonth()->startOfMonth()->endOfMonth()->endOfDay()->format('Y-m-d H:i:s');
                        $q->whereBetween('created_at', [$start, $end]);

                    }

                    elseif ( $request->dateFilter == 'last3month' )
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

                    if ( $request->tracking_id )
                    {
                        $q->where('tracking_id', $request->tracking_id);
                    }
                })
                ->where(function ($q) use ($request) {

                    if ( $request->dateFilter == 'today' )
                    {

                        $today = Carbon::now();
                        $q->whereBetween('created_at', [$today->startOfDay()->format('Y-m-d H:i:s'), $today->endOfDay()->format('Y-m-d H:i:s')]);

                    }


                    elseif ( $request->dateFilter == 'yesterday' )
                    {

                        $yesterday = Carbon::now()->subDay();
                        $q->whereBetween('created_at', [$yesterday->startOfDay()->format('Y-m-d H:i:s'), $yesterday->endOfDay()->format('Y-m-d H:i:s')]);

                    }
                    elseif ( $request->dateFilter == 'thisweek' )
                    {

                        $start = Carbon::now()->startOfWeek(Carbon::SATURDAY)->startOfDay()->format('Y-m-d H:i:s');
                        $end = Carbon::now()->format('Y-m-d H:i:s');
                        $q->whereBetween('created_at', [$start, $end]);

                    }
                    elseif ( $request->dateFilter == 'lastweek' )
                    {

                        $carbon = Carbon::now()->subWeek();
                        $q->whereBetween('created_at', [$carbon->startOfWeek(Carbon::SATURDAY)->endOfDay()->format('Y-m-d H:i:s'), $carbon->endOfWeek(Carbon::FRIDAY)->startOfDay()->format('Y-m-d H:i:s')]);

                    }
                    elseif ( $request->dateFilter == '10days' )
                    {

                        $start = Carbon::now()->subDays(10)->startOfDay()->format('Y-m-d H:i:s');
                        $end = Carbon::now()->format('Y-m-d H:i:s');
                        $q->whereBetween('created_at', [$start, $end]);


                    }

                    elseif ( $request->dateFilter == 'thismonth' )
                    {

                        $today = Carbon::now();
                        $q->whereBetween('created_at', [$today->startOfMonth()->startOfDay()->format('Y-m-d H:i:s'), $today->endOfMonth()->endOfDay()->format('Y-m-d H:i:s')]);

                    }
                    elseif ( $request->dateFilter == 'lastmonth' )
                    {

                        $start = Carbon::now()->subMonth()->startOfMonth()->startOfMonth()->startOfDay()->format('Y-m-d H:i:s');
                        $end = Carbon::now()->subMonth()->startOfMonth()->endOfMonth()->endOfDay()->format('Y-m-d H:i:s');
                        $q->whereBetween('created_at', [$start, $end]);

                    }

                    elseif ( $request->dateFilter == 'last3month' )
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
            $total_expanse = $expanses->sum('condition_delivery') + $expanses->sum('condition_advance_payment') + $expanses->sum('tt_delivery') + $expanses->sum('dd_delivery') + $expanses->sum('ho_payment');
            $cash = $total_income - $total_expanse;




$previousCash =0;
            if ($request->dateFilter)
            {
                $previousCash = previousCash($request->dateFilter);
                $request->dateFilter = empty($request->dateFilter) ? 'Today' : $request->dateFilter;
            }


            if ($request->custom_date)
            {
                $previousCash = previousCash($request->dateFilter);
                $request->custom_date = empty($request->custom_date) ? 'Today' : $request->custom_date;
            }


            return view('backend.reports.index', compact('incomes', 'expanses', 'total_income', 'total_expanse', 'cash', 'previousCash', 'request'));
        }


        public function download(Request $request)
        {

            $expanses = Expanse::with('category')
                ->orderByDesc('created_at')
                ->where(function ($q) use ($request) {

                    if ( $request->tracking_id )
                    {
                        $q->where('tracking_id', $request->tracking_id);
                    }
                })
                ->where(function ($q) use ($request) {

                    if ( $request->dateFilter == 'today' )
                    {

                        $today = Carbon::now();
                        $q->whereBetween('created_at', [$today->startOfDay()->format('Y-m-d H:i:s'), $today->endOfDay()->format('Y-m-d H:i:s')]);

                    }


                    elseif ( $request->dateFilter == 'yesterday' )
                    {

                        $yesterday = Carbon::now()->subDay();
                        $q->whereBetween('created_at', [$yesterday->startOfDay()->format('Y-m-d H:i:s'), $yesterday->endOfDay()->format('Y-m-d H:i:s')]);

                    }
                    elseif ( $request->dateFilter == 'thisweek' )
                    {

                        $start = Carbon::now()->startOfWeek(Carbon::SATURDAY)->startOfDay()->format('Y-m-d H:i:s');
                        $end = Carbon::now()->format('Y-m-d H:i:s');
                        $q->whereBetween('created_at', [$start, $end]);

                    }
                    elseif ( $request->dateFilter == 'lastweek' )
                    {

                        $carbon = Carbon::now()->subWeek();
                        $q->whereBetween('created_at', [$carbon->startOfWeek(Carbon::SATURDAY)->endOfDay()->format('Y-m-d H:i:s'), $carbon->endOfWeek(Carbon::FRIDAY)->startOfDay()->format('Y-m-d H:i:s')]);

                    }
                    elseif ( $request->dateFilter == '10days' )
                    {

                        $start = Carbon::now()->subDays(10)->startOfDay()->format('Y-m-d H:i:s');
                        $end = Carbon::now()->format('Y-m-d H:i:s');
                        $q->whereBetween('created_at', [$start, $end]);


                    }

                    elseif ( $request->dateFilter == 'thismonth' )
                    {

                        $today = Carbon::now();
                        $q->whereBetween('created_at', [$today->startOfMonth()->startOfDay()->format('Y-m-d H:i:s'), $today->endOfMonth()->endOfDay()->format('Y-m-d H:i:s')]);

                    }
                    elseif ( $request->dateFilter == 'lastmonth' )
                    {

                        $start = Carbon::now()->subMonth()->startOfMonth()->startOfMonth()->startOfDay()->format('Y-m-d H:i:s');
                        $end = Carbon::now()->subMonth()->startOfMonth()->endOfMonth()->endOfDay()->format('Y-m-d H:i:s');
                        $q->whereBetween('created_at', [$start, $end]);

                    }

                    elseif ( $request->dateFilter == 'last3month' )
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
                //  ->take(100)
                ->get();
            $date = date('d-M h:i:a', strtotime(Carbon::now()));
            $headers = array(
                "Content-type" => "text/csv",
                "Content-Disposition" => "attachment; filename=expanseReport-$date.csv",
                "Pragma" => "no-cache",
                "Cache-Control" => "must-revalidate, post-check=0, pre-check=0",
                "Expires" => "0"
            );


            $columns = array('Tracking ID', 'Category', 'Condition Amount', 'created_at');


            $callback = function () use ($expanses, $columns) {
                $file = fopen('php://output', 'w');
                fputcsv($file, $columns);

                foreach ( $expanses as $expanse )
                {

                    fputcsv($file, [
                        $expanse->tracking_id,
                        $expanse->category->name,
                        $expanse->condition_amount,
                        date('d-M h:i:a', strtotime($expanse->created_at)),

                    ]);
                }
                fclose($file);
            };

            return response()->stream($callback, 200, $headers);

        }


    }
