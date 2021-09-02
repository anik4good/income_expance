@extends('layouts.backend.app')
@section('title','Cash in')
@section('content')

    <!-- push external head elements to head -->
    @push('head')
        <link rel="stylesheet" href="{{ asset('assets/backend/plugins/DataTables/datatables.min.css') }}">

        {{--    sweet alert--}}
        <link rel="stylesheet"
              href="https://cdn.jsdelivr.net/npm/@sweetalert2/themes@3.2.0/wordpress-admin/wordpress-admin.css">


        <!--Dropify css-->
        <link rel="stylesheet" href="{{ asset('assets/backend/plugins/dropify/css/dropify.min.css') }}">

    @endpush


    <div class="container-fluid">
        <div class="page-header">
            <div class="row align-items-end">
                <div class="col-lg-8">
                    <div class="page-header-title">
                        <i class="ik ik-award bg-blue"></i>
                        <div class="d-inline">
                            <h5>{{ __('Cash in')}}</h5>
                            <span>{{ __('All Cash in')}}</span>
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


        <div class="row">
            <div class="col-lg-12 col-md-12">
                <div class="card table-card">
                    <div class="card-header"><h3>Add Your Cash in Today</h3></div>
                    <div class="card-body">
                        <form method="POST" action="{{ route('income.store') }}" class="form-horizontal"
                              enctype="multipart/form-data">
                            @csrf

                            <div class="row">
                                <div class="col-md-2">
                                    <div class="form-group">
                                        <label for="exampleInputUsername1">Select Category</label>
                                        <select type="select" id="exampleCustomSelect" name="income_category"
                                                class="custom-select">
                                            @foreach($categories as $row)
                                                <option value="{{$row->id}}">{{$row->name}}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>

                                <div class="col-md-2">
                                    <div class="form-group">
                                        <label for="exampleInputEmail1">Tracking ID</label>
                                        <input type="number"
                                               class="form-control @error('tracking_id') is-invalid @enderror"
                                               placeholder="Tracking ID"
                                               name="tracking_id" value="{{ old('tracking_id') }}" autofocus>
                                        @error('tracking_id')
                                        <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                        @enderror
                                    </div>
                                </div>

                                <div class="col-md-2">
                                    <div class="form-group">
                                        <label for="booking_date">Booking Date</label>
                                        <input type="date" class="form-control" placeholder="Booking Date"
                                               name="booking_date">
                                    </div>
                                </div>

                                <div class="col-md-2">
                                    <div class="form-group">
                                        <label for="Conditionamount">Condition Amount</label>
                                        <input type="number" class="form-control" placeholder="Condition Amount"
                                               name="condition_amount">
                                    </div>
                                </div>

                                <div class="col-md-2">
                                    <div class="form-group">
                                        <label for="Condition">Condition Charge</label>
                                        <input type="number" class="form-control" placeholder="Condition Charge"
                                               name="condition_charge">
                                    </div>
                                </div>

                                <div class="col-md-2">
                                    <div class="form-group">
                                        <label for="Booking">Booking Charge</label>
                                        <input type="number" class="form-control" placeholder="Booking Charge"
                                               name="booking_charge">
                                    </div>
                                </div>

                                <div class="col-md-2">
                                    <div class="form-group">
                                        <label for="Labour">Labour Charge</label>
                                        <input type="number" class="form-control" placeholder="Labour Charge"
                                               name="labour_charge">
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-2">
                                    <div class="form-group">
                                        <label for="Amounts">Other Amounts</label>
                                        <input type="number" class="form-control" placeholder="Other Amounts"
                                               name="other_amount">
                                    </div>
                                </div>

                                <div class="col-md-2">
                                    <div class="form-group">
                                        <label for="amount">Quantity</label>
                                        <input type="number" class="form-control" placeholder="Quantity"
                                               name="quantity">
                                    </div>
                                </div>

                                <div class="col-md-2">
                                    <div class="form-group">
                                        <label for="notes">Notes</label>
                                        {{--                                        <input type="text" id="notes"--}}
                                        {{--                                               class="form-control @error('notes') is-invalid @enderror"--}}
                                        {{--                                               placeholder="Notes"--}}
                                        {{--                                               name="notes" value="{{ old('notes') }}">--}}
                                        <textarea name="notes" id="notes" rows="5"
                                                  class="form-control @error('notes') is-invalid @enderror">{{ old('notes') }}</textarea>

                                        @error('notes')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                        @enderror
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <div class="card">
                                            <div class="card-body">
                                                <input type="file" name="receipt" id="receipt"
                                                       class="dropify">
                                            </div>
                                            <!-- /.card-body -->
                                        </div>
                                        <!-- /.card -->
                                    </div>
                                </div>
                            </div>

                            <button type="submit" class="btn btn-info btn-block"><i class="ik ik-clipboard"></i>Submit</button>
                        </form>
                    </div>
                </div>
            </div>

        </div>


        <div class="row">
            <div class="col-md-12">
                <div class="card table-card">
                    <div class="table-responsive">
                        <table id="simpletable" class="table table-hover table-bordered">
                            <thead>
                            <tr>
                                <th class="">ID</th>
                                <th class="">Tracking ID</th>
                                <th class="">Category</th>
                                <th class="">Booking Date</th>
                                <th class="">image</th>
                                <th class="">CN Amount</th>
                                <th class="">CN Charge</th>
                                <th class="">Booking Charge</th>
                                <th class="">Labour Charge</th>
                                <th class="">Other Amount</th>
                                <th class="">Quantity</th>
                                <th class="">Notes</th>
                                <th class="">Created at</th>
                                <th class="">Actions</th>
                            </tr>
                            </thead>
                            <tbody>
                            {{--                                    @foreach($categories as $category)--}}
                            {{--                                        @foreach($category->incomes as $key=>$row)--}}
                            @foreach($incomes as $key=>$row)
                                <tr>

                                    <td class="">
                                        {{ $key + 1 }}
                                    </td>

                                    <td class="">
                                        {{ $row->tracking_id }}
                                    </td>

                                    <td class="">
                                        {{ $row->category->name }}
                                    </td>

                                    <td class="">
                                        {{date('d-M h:i:a', strtotime($row->booking_date)) }}
                                    </td>


                                    <td class="">
                                        <img class="avatar"
                                             src="{{ $row->getFirstMediaUrl('receipts') != null ? $row->getFirstMediaUrl('receipts') : ''}}"
                                        >

                                    </td>
                                    <td class="">
                                        {{ $row->condition_amount }}
                                    </td>

                                    <td class="">
                                        {{ $row->condition_charge }}
                                    </td>

                                    <td class="">
                                        {{ $row->booking_charge }}
                                    </td>

                                    <td class="">
                                        {{ $row->labour_charge }}
                                    </td>

                                    <td class="">
                                        {{ $row->other_amount }}
                                    </td>

                                    <td class="">
                                        {{ $row->quantity }}
                                    </td>

                                    <td class="">
                                        {{ $row->notes }}
                                    </td>

                                    <td class="">
                                        {{date('d-M h:i:a', strtotime($row->created_at)) }}
                                    </td>

                                    <td class="">
                                        <button type="submit"
                                                onclick="deleteData({{ $row->id }})"><i class=" ik ik-trash-2 f-16
                                           text-red"></i></button>


                                        <form id="delete-form-{{ $row->id }}"
                                              action="{{ route('income.destroy',$row->id) }}" method="POST"
                                              style="display: none;">
                                            @csrf()
                                            @method('DELETE')
                                        </form>


                                    </td>


                                </tr>

                            @endforeach
                            <tfoot>
                            <th class="">ID</th>
                            <th class="">Tracking ID</th>
                            <th class="">Category</th>
                            <th class="">Booking Date</th>
                            <th class="">image</th>
                            <th class="">CN Amount</th>
                            <th class="">CN Charge</th>
                            <th class="">Booking Charge</th>
                            <th class="">Labour Charge</th>
                            <th class="">Other Amount</th>
                            <th class="">Total Amount</th>
                            <th class="">Notes</th>
                            <th class="">Created at</th>
                            <th class="">Actions</th>
                            </tfoot>
                        </table>
                    </div>
                </div>
            </div>


        </div>

    </div>



    @push('script')

        <script src="{{ asset('assets/backend/plugins/DataTables/datatables.min.js') }}"></script>
        <script src="{{ asset('assets/backend/js/datatables.js') }}"></script>
        <script src="{{ asset('assets/backend/js/form-components.js') }}"></script>

        <!--Dropify script-->
        <script src="{{ asset('assets/backend/plugins/dropify/js/dropify.min.js') }}"></script>
        <script>
            $(document).ready(function () {
                // Dropify
                $('.dropify').dropify();

            });
        </script>




        {{--    sweet alert--}}
        <script src="https://cdn.jsdelivr.net/npm/sweetalert2@9.17.0/dist/sweetalert2.all.min.js"></script>


    @endpush


@endsection


