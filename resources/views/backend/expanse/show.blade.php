@extends('layouts.backend.app')
@section('title','Diets')

@push('head')
    <link rel="stylesheet" href="{{ asset('assets/backend/plugins/DataTables/DataTables-1.10.20/css/dataTables.bootstrap4.min.css') }}">

    <style>
        #user_diets_nutritions_chartdiv {
            width: 100%;
            height: 500px;
        }
    </style>
@endpush

@section('content')
    <div class="container-fluid">
        <div class="page-header">
            <div class="row align-items-end">
                <div class="col-lg-8">
                    <div class="page-header-title">
                        <i class="ik ik-award bg-blue"></i>
                        <div class="d-inline">
                            <h5>{{ $diet->name  }} for {{$diet->user->name}}</h5>
{{--                            <span>{{ __('All diets')}}</span>--}}
                        </div>
                    </div>
                </div>
                <div class="col-lg-4">
                    <nav class="breadcrumb-container" aria-label="breadcrumb">
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item">
                                <a href="{{route('dashboard')}}"><i class="ik ik-home"></i></a>
                            </li>
                            <li class="breadcrumb-item">
                                <a href="#">@yield('title','')</a>
                            </li>
                        </ol>
                    </nav>

                </div>
            </div>
        </div>


        <!-- only those have manage_diet permission will get access -->
        @canany(['manage_diet', 'show_diet'])
    <div class="row">
            <div class="col-lg-3 col-md-2 col-sm-12">
                <div class="widget bg-primary">
                    <div class="widget-body">
                        <div class="d-flex justify-content-between align-items-center">
                            <div class="state">
                                <h6>Total Calories</h6>
                                <h2>{{$morning_all['calories'] + $noon_all['calories']  + $night_all['calories']}}</h2>
                            </div>
                            <div class="icon">
                                <i class="ik ik-box"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-lg-3 col-md-2 col-sm-12">
                <div class="widget bg-success">
                    <div class="widget-body">
                        <div class="d-flex justify-content-between align-items-center">
                            <div class="state">
                                <h6>Total Fat</h6>
                                <h2>{{$morning_all['fat'] + $noon_all['fat']  + $night_all['fat']}}%</h2>
                            </div>
                            <div class="icon">
                                <i class="ik ik-shopping-cart"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-lg-3 col-md-2 col-sm-12">
                <div class="widget bg-warning">
                    <div class="widget-body">
                        <div class="d-flex justify-content-between align-items-center">
                            <div class="state">
                                <h6>Total Protein</h6>
                                <h2>{{$morning_all['protein'] + $noon_all['protein'] + $night_all['protein']}}g</h2>
                            </div>
                            <div class="icon">
                                <i class="ik ik-inbox"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-lg-3 col-md-2 col-sm-12">
                <div class="widget bg-danger">
                    <div class="widget-body">
                        <div class="d-flex justify-content-between align-items-center">
                            <div class="state">
                                <h6>Total Carbs</h6>
                                <h2>{{$morning_all['carbohydrate'] + $noon_all['carbohydrate']  + $night_all['carbohydrate']}}g</h2>
                            </div>
                            <div class="icon">
                                <i class="ik ik-users"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-lg-3 col-md-2 col-sm-12">
            <div class="widget bg-danger">
                <div class="widget-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div class="state">
                            <h6>Total Sugars</h6>
                            <h2>{{$morning_all['sugars'] + $noon_all['sugars']  + $night_all['sugars']}}g</h2>
                        </div>
                        <div class="icon">
                            <i class="ik ik-users"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

         <div class="col-lg-8 col-md-8 col-sm-12">
                    <div id="user_diets_nutritions_chartdiv" class="chart-shadow" style="overflow: hidden; text-align: left;">
                    </div>
            {{--                <div id="user_weight_chartdiv" class="chart-shadow" style="overflow: hidden; text-align: left;"></div>--}}
        </div>

    </div>



    <div class="row">
        <div class="col-md-4">
            <div class="main-card mb-3 card">
                <div class="card-body"><h5 class="card-title">Morning {{$morning_all['calories']}}</h5>
                    <div class="table-responsive">
                        <table class="table table-hover">
                        <thead>
                        <tr>
                            <th>Food name</th>
                            <th>Calories</th>
                            <th>Fat</th>
                            <th>Protien</th>
                            <th>Carbohydrate</th>
                            <th>Time</th>
                        </tr>
                        </thead>
                        <tbody>
                        @foreach($morning as $roww)
                            @php
                            $food = new \App\Food();
                                $row=  $food->where('id',$roww->food_id)->first();
                            @endphp
                            <tr>
                                <td>
                                    @if ($row->id)
                                        <span class="">{{ $row->name }}</span>
                                    @else
                                        <div class="badge badge-danger">Inactive</div>
                                    @endif
                                </td>
                                <td>
                                    @if ($row->id)
                                        <span class="">{{ $row->calories }}</span>
                                    @else
                                        <div class="badge badge-danger">Inactive</div>
                                    @endif
                                </td>
                                <td>
                                    @if ($row->id)
                                        <span class="">{{ $row->fat }}</span>
                                    @else
                                        <div class="badge badge-danger">Inactive</div>
                                    @endif
                                </td>
                                <td>
                                    @if ($row->id)
                                        <span class="">{{ $row->protein }}</span>
                                    @else
                                        <div class="badge badge-danger">Inactive</div>
                                    @endif
                                </td>
                                <td>
                                    @if ($row->id)
                                        <span class="">{{ $row->carbohydrate }}</span>
                                    @else
                                        <div class="badge badge-danger">Inactive</div>
                                    @endif
                                </td>
                                <td>
                                    @if ($row->id)
                                        <span class="">{{ $roww->time }}</span>
                                    @else
                                        <div class="badge badge-danger">Inactive</div>
                                    @endif
                                </td>
                            </tr>
                        </tbody>
                        @endforeach
                    </table>
                </div>
            </div>
        </div>
    </div>
    </div>

    </div>
    @endcan
@endsection

@push('script')

    <script type="text/javascript">
        var API_URL = "{{ route('frontend.home') }}";
            var globalVariable = {
            diet_id: {{$diet->id}}
        };

        //             // Set up data source
        //             $.get(API_URL + '/fitness/weight').then(function (response) {
        //                 console.log(response);
        // // Display the array elements
        //                 chart.data = response;


    </script>


            <script src="{{ asset('assets/backend/plugins/DataTables/DataTables-1.10.20/js/jquery.dataTables.min.js') }}"></script>
            <script src="{{ asset('assets/backend/plugins/DataTables/DataTables-1.10.20/js/dataTables.bootstrap4.js') }}"></script>
            <script src="{{ asset('assets/backend/js/tables.js') }}"></script>


            {{--        for new amcharts--}}
            <script src="https://cdn.amcharts.com/lib/4/core.js"></script>
            <script src="https://cdn.amcharts.com/lib/4/charts.js"></script>
            <script src="https://cdn.amcharts.com/lib/4/themes/animated.js"></script>


            <script src="{{ asset('assets/backend/js/amcharts/pie-chart.js') }}"></script>
@endpush
