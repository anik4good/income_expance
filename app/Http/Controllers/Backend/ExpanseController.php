<?php

namespace App\Http\Controllers\Backend;

use App\Category;
use App\Expanse;
use App\Http\Controllers\Controller;


use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class ExpanseController extends Controller
{

    public function index(Request $request)
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

        $total_expanse = $expanses->sum('total');
//            $expanses = Cache::remember('expanse-all', 60 * 60 * 24, function () use ($value) {
//                return $value;
//        });






        $request->dateFilter = empty($request->dateFilter) ? 'Today' : $request->dateFilter;

        return view('backend.expanse.index', compact('expanses', 'request'));

        //  return expanse::LastMonth()->get();


    }




    public function create()
    {
        $categories = Category::where('type', 'expanse')->get();

        //  $categories = Category::where('type', 'expanse')->with('expanses')->get();
        //return $categories;
        $expanses = Expanse::Today()->with('category')->orderByDesc('created_at')->get();

        // $expanses = expanse::Today()->with('category',fn($query)=> $query->select('id','name'))->get();

        return view('backend.expanse.create', compact('categories', 'expanses'));
    }


    public function store(Request $request)
    {


        // insert expanse
        $validator = Validator::make($request->all(), [
            'tracking_id' => 'required|numeric',
            'amount' => 'numeric',
            'receipt' => 'nullable|image',
            'notes' => 'string|max:255',
        ]);

        if ( $validator->fails() )
        {

            return redirect()->back()->withInput()->withErrors($validator->errors());

        }

        try
        {
            // store user information
            $expanse = Expanse::create([
                'category_id' => $request->expanse_category,
                'tracking_id' => $request->tracking_id,
                'booking_date' => $request->booking_date,
                'condition_delivery' => $request->condition_delivery,
                'amount' => $request->amount,
                'quantity' => $request->quantity,
                'notes' => $request->notes,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ]);


            // upload images
            if ( $request->hasFile('receipt') )
            {
                $expanse->addMedia($request->receipt)->toMediaCollection('receipts');
            }

            if ( $expanse )
            {
                // return with success msg
                notify()->success('expanse Successfully Added.', 'Added');

                return back();
            }
            else
            {
                // return with success msg
                notify()->success('expanse not added.', 'Added');

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


        $expanse = Expanse::find($id);
        if ( $expanse )
        {
            $expanse->delete();
            notify()->warning("Expanse Successfully Deleted", "Deleted");

        }
        else
        {
            notify()->warning("Expanse not Found", "Status");
        }

        return back();
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


        $columns = array('Tracking ID', 'Category', 'Condition Amount','created_at');


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
