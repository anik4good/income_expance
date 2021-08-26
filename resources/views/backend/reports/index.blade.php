@extends('layouts.backend.app')
@section('title','Reports')


@section('content')

    <!-- push external head elements to head -->
    @push('head')
        <link rel="stylesheet"
              href="{{ asset('assets/backend/plugins/DataTables/DataTables-1.10.20/css/dataTables.bootstrap4.min.css') }}">
        <link rel="stylesheet" href="{{ asset('assets/backend/plugins/DataTables/datatables.min.css') }}">
        {{--    sweet alert--}}
        <link rel="stylesheet"
              href="https://cdn.jsdelivr.net/npm/@sweetalert2/themes@3.2.0/wordpress-admin/wordpress-admin.css">
    @endpush



    <div class="container-fluid">
        <div class="page-header">
            <div class="row align-items-end">
                <div class="col-lg-8">
                    <div class="page-header-title">
                        <i class="ik ik-award bg-blue"></i>
                        <div class="d-inline">
                            <h5>{{ __('Reports')}}</h5>
                            <span>{{$request->dateFilter}}</span>
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
                    <div class="page-title-actions">
                        <div class="d-inline-block">


                            <a href="{{ route('report.download',$request->all()) }}"
                               class="btn-shadow btn btn-info">


                        <span class="btn-icon-wrapper pr-2 opacity-7">
                            <i class="ik ik-download"></i>
                        </span>
                                {{ __('Download') }}
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Search bar -->
        <div class="row">
            <form method="GET" action="{{route('report.index')}}">
                <div class="col-md-12">
                    <div class="main-card mb-3 card">
                        <div class="card-body"><h5 class="card-title">Search</h5>
                            <div class="position-relative form-group">
                                <div>
                                    <div class="custom-checkbox custom-control custom-control-inline">
                                        <input type="number" placeholder="Tracking ID" name="tracking_id"
                                               class="form-control">
                                    </div>


                                    <div class="custom-checkbox custom-control custom-control-inline">
                                        <select type="select" id="exampleCustomSelect" name="dateFilter"
                                                class="custom-select">
                                            <option value="">Select Date</option>
                                            <option value="today">Today</option>
                                            <option value="yesterday">Yesterday</option>

                                            <option value="thisweek">This Week</option>
                                            <option value="lastweek">Last Week</option>
                                            <option value="10days">Last 10 days</option>
                                            <option value="thismonth">This Month</option>
                                            <option value="lastmonth">Last Month</option>
                                            <option value="last3month">Last 90 Days</option>
                                        </select>
                                    </div>

                                    {{--                                    'today' => 'Today',--}}
                                    {{--                                    'yesterday' => 'Yesterday',--}}
                                    {{--                                    'thisweek' => 'This Week',--}}
                                    {{--                                    'lastweek' => 'Last Week',--}}
                                    {{--                                    '10days' => 'Last 10 days',--}}
                                    {{--                                    'thismonth' => 'This Month',--}}
                                    {{--                                    'lastmonth' => 'Last Month',--}}
                                    <div class="custom-checkbox custom-control custom-control-inline">
                                        <button class="btn btn-outline-success btn-lg btn-block">Filter
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </form>
        </div>


        <!-- Summary bar -->
        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header d-block">
                        <h5> {{$request->dateFilter}}'s Income Expanse Summary</h5>

                    </div>

                    <div class="card-body p-0 table-border-style">
                        <div class="table-responsive">
                            <table class="table">
                                <thead>
                                <tr>
                                    <th class="">Total Income</th>
                                    <th class="">Total Expanse</th>
                                    <th class="">Cash</th>
                                    <th class="">Previous Cash</th>
                                </tr>
                                </thead>
                                <tbody>

                                <tr>
                                    <td>
                                        {{$total_income}}
                                    </td>

                                    <td>
                                        {{$total_expanse}}
                                    </td>

                                    <td>
                                        {{$cash}}
                                    </td>

                                    <td>
                                        {{$previousCash}}
                                    </td>
                                </tr>


                                </tbody>
                            </table>

                        </div>
                    </div>
                </div>
            </div>
            <hr>
        </div>


        <div class="row">
            <div class="col-md-12">
                <div class="card table-card">
                    <div class="card-header d-block bg-green">
                        <h3>{{$request->dateFilter}}'s Income Summary</h3>
                    </div>
                    <div class="table-responsive">
                        <table class="table table-hover table-bordered">
                            <thead>
                            <tr>
                                <th class="">ID</th>
                                <th class="">Tracking ID</th>
                                <th class="">Category</th>
                                <th class="">CN Amount</th>
                                <th class="">CN Charge</th>
                                <th class="">Booking Charge</th>
                                <th class="">Labour Charge</th>
                                <th class="">Other Amount</th>
                                <th class="">Previous Cash</th>
                                <th class="">Notes</th>
                                <th class="">Created at</th>
                                <th class="">Actions</th>
                            </tr>
                            </thead>
                            <tbody>

                            </tbody>
                            <tfoot>
                            <th class="">
                                {{ $incomes->sum('condition_amount') }}
                            </th>

                            <th class="">
                                {{ $incomes->sum('condition_charge') }}
                            </th>
                            <th class="">
                                {{ $incomes->sum('booking_charge') }}
                            </th>

                            <th class="">
                                {{ $incomes->sum('labour_charge') }}
                            </th>
                            <th class="">
                                {{ $incomes->sum('other_amount') }}
                            </th>

                            <th class="">
                                {{ $incomes->sum('condition_amount') + $incomes->sum('condition_charge') + $incomes->sum('booking_charge')+ $incomes->sum('labour_charge') + $incomes->sum('other_amount') }}
                            </th>
                            </tfoot>
                        </table>
                    </div>
                </div>
            </div>

        </div>
    </div>





    <div class="row">
        <div class="col-md-12">
            <div class="card table-card">
                <div class="card-header d-block bg-warning">
                    <h3>{{$request->dateFilter}}'s Expanse Summary</h3>
                </div>
                <div class="table-responsive">
                    <table class="table table-hover table-bordered">
                        <thead>
                        <tr>
                            <th class="">ID</th>
                            <th class="">Tracking ID</th>
                            <th class="">Category</th>
                            <th class="">{{ $expanses->sum('condition_delivery') }}</th>
                            <th class="">CN Advance Payment</th>
                            <th class="">T.T Delivery</th>
                            <th class="">D.D Delivery</th>
                            <th class="">H/O Payment</th>
                            <th class="">Advance R/N</th>
                            <th class="">Loan R/N</th>
                            <th class="">Commission</th>
                            <th class="">Other Amount</th>
                            <th class="">Previous Cash</th>
                            <th class="">Notes</th>
                            <th class="">Actions</th>
                        </tr>
                        </thead>
                        <tbody>


                        </tbody>
                        <tfoot>
                        <th class="">ID</th>
                        <th class="">Tracking ID</th>
                        <th class="">Category</th>
                        <th class="">CN Delivery</th>
                        <th class="">CN Advance Payment</th>
                        <th class="">T.T Delivery</th>
                        <th class="">D.D Delivery</th>
                        <th class="">H/O Payment</th>
                        <th class="">Advance R/N</th>
                        <th class="">Loan R/N</th>
                        <th class="">Commission</th>
                        <th class="">Other Amount</th>
                        <th class="">Previous Cash</th>
                        <th class="">Notes</th>
                        <th class="">Actions</th>
                        </tfoot>
                    </table>
                </div>
            </div>
        </div>

    </div>

    <!-- push external js -->
    @push('script')


        <script src="{{ asset('assets/backend/js/tables.js') }}"></script>

        <script src="{{ asset('assets/backend/plugins/DataTables/datatables.min.js') }}"></script>
        <script src="{{ asset('assets/backend/js/datatables.js') }}"></script>






        {{--        <script type="text/javascript">--}}
        {{--            // Foods data table--}}
        {{--            $(function () {--}}

        {{--                'use strict';--}}

        {{--                var dTable = $('#Reports_table').DataTable({--}}
        {{--                    order: [],--}}
        {{--                    lengthMenu: [[10, 25, 50, 100, -1], [10, 25, 50, 100, "All"]],--}}
        {{--                    processing: true,--}}
        {{--                    responsive: false,--}}
        {{--                    serverSide: true,--}}
        {{--                    language: {--}}
        {{--                        processing: '<i class="ace-icon fa fa-spinner fa-spin orange bigger-500" style="font-size:60px;margin-top:50px;"></i>'--}}
        {{--                    },--}}
        {{--                    scroller: {--}}
        {{--                        loadingIndicator: false--}}
        {{--                    },--}}
        {{--                    pagingType: "full_numbers",--}}
        {{--                    dom: "<'row'<'col-sm-2'l><'col-sm-7 text-center'B><'col-sm-3'f>>tipr",--}}
        {{--                    ajax: "{{ route('Reportss.datatable.get') }}",--}}
        {{--                    columns: [--}}
        {{--                        {data: 'tracking_id', name: 'tracking_id'},--}}

        {{--                    ],--}}

        {{--                });--}}
        {{--            });--}}
        {{--        </script>--}}
    @endpush


@endsection



