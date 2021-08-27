<?php

    namespace App\Http\Controllers\Backend;

    use App\Category;
    use App\Http\Controllers\Controller;
    use App\Income;
    use App\User;
    use Carbon\Carbon;
    use Illuminate\Http\Request;
    use Illuminate\Support\Facades\Auth;
    use Illuminate\Support\Facades\Cache;
    use Illuminate\Support\Facades\Hash;
    use Illuminate\Support\Facades\Validator;
    use Yajra\DataTables\DataTables;

    class IncomeController extends Controller {

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



                   else if ( $request->dateFilter == 'today' )
                    {

                        $today = Carbon::now();
                        $q->whereBetween('created_at', [$today->startOfDay()->format('Y-m-d H:i:s'), $today->endOfDay()->format('Y-m-d H:i:s')]);

                    }


                    elseif ( $request->dateFilter == 'yesterday' )
                    {

                        $yesterday = Carbon::now()->subDay();
                        $q->whereBetween('created_at', [$yesterday->startOfDay()->format('Y-m-d H:i:s'), $yesterday->endOfDay()->format('Y-m-d H:i:s')]);

                    }

                    elseif ( $request->dateFilter  == 'porsu' )
                    {

                        $yesterday = Carbon::now()->subDays(2);
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


//            $income_cache = Cache::remember('income-all', 60 * 60 * 24, function () use ($incomes) {
//                return $incomes;
//            });


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

            return view('backend.income.index', compact('incomes', 'request'));

            //  return Income::LastMonth()->get();


        }


        public function getIncome(Request $request)
        {


            $data = Income::all();

            //   return Datatables::of($data)
            //      ->make(true);

        }


        public function create()
        {

            $categories = Category::where('type', 'income')->get();

            //  $categories = Category::where('type', 'income')->with('incomes')->get();
            //return $categories;

//            $incomes = Cache::rememberForever('incomes.today', function () {
//                return Income::Today()->with('category')->get();
//            });

            $incomes = Income::Today()->with('category')->orderByDesc('created_at')->get();

            // $incomes = Income::Today()->with('category',fn($query)=> $query->select('id','name'))->get();

            return view('backend.income.create', compact('categories', 'incomes'));
        }


        public function store(Request $request)
        {


            // insert Income
            $validator = Validator::make($request->all(), [
                'tracking_id' => 'required|numeric',
                'condition_amount' => 'numeric',
                'condition_charge' => 'numeric',
                'booking_charge' => 'numeric',
                'labour_charge' => 'numeric',
                'other_amount' => 'numeric',
                'receipt' => 'nullable|image',
                'notes' => 'string|max:255',
            ]);

            if ( $validator->fails() )
            {
                // return with error msg
                //    notify()->error($validator->messages()->first(), 'Error');
                //   return redirect()->back()->withInput()->with('error', $validator->messages()->first());
                return redirect()->back()->withInput()->withErrors($validator->errors());

            }

            try
            {
                // store user information
                $income = Income::create([
                    'category_id' => $request->income_category,
                    'tracking_id' => $request->tracking_id,
                    'condition_amount' => $request->condition_amount,
                    'condition_charge' => $request->condition_charge,
                    'booking_charge' => $request->booking_charge,
                    'labour_charge' => $request->labour_charge,
                    'other_amount' => $request->other_amount,
                    'total_amount' => $request->total_amount,
                    'notes' => $request->notes,
                    'created_at' => Carbon::now(),
                    'updated_at' => Carbon::now(),
                ]);


                // upload images
                if ( $request->hasFile('receipt') )
                {
                    $income->addMedia($request->receipt)->toMediaCollection('receipts');
                }

                if ( $income )
                {
                    // return with success msg
                    notify()->success('Income Successfully Added.', 'Added');

                    return back();
                }
                else
                {
                    // return with success msg
                    notify()->error('No data in Inserted', 'Error');

                    return back();
                }


            } catch (\Exception $e)
            {
                $bug = $e->getMessage();
                notify()->error($bug, 'Error');

                return back();
            }


        }


        public function show($id)
        {
            //
        }


        public function edit($id)
        {
            //
        }


        public function update(Request $request, $id)
        {
            //
        }


        public function destroy($id)
        {


            $income = Income::find($id);
            if ( $income )
            {
                $income->delete();
                notify()->warning("Income Successfully Deleted", "Deleted");

            }
            else
            {
                notify()->warning("Income not Found", "Status");
            }

            return back();
        }


        public function download(Request $request)
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
                "Content-Disposition" => "attachment; filename=incomeReport-$date.csv",
                "Pragma" => "no-cache",
                "Cache-Control" => "must-revalidate, post-check=0, pre-check=0",
                "Expires" => "0"
            );


            $columns = array('Tracking ID', 'Category', 'Condition Amount', 'created_at');


            $callback = function () use ($incomes, $columns) {
                $file = fopen('php://output', 'w');
                fputcsv($file, $columns);

                foreach ( $incomes as $income )
                {

                    fputcsv($file, [
                        $income->tracking_id,
                        $income->category->name,
                        $income->condition_amount,
                        date('d-M h:i:a', strtotime($income->created_at)),

                    ]);
                }
                fclose($file);
            };

            return response()->stream($callback, 200, $headers);

        }
    }
