@extends('layouts.backend.app')
@section('title','expanse')
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
                            <h5>{{ __('expanses')}}</h5>
                            <span>{{ __('All expanses')}}</span>
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
                    <div class="card-header"><h3>Add Your expanse Today</h3></div>
                    <div class="card-body">
                        <form method="POST" action="{{ route('expanse.store') }}" class="form-horizontal"
                              enctype="multipart/form-data">
                            @csrf

                            <div class="row">
                                <div class="col-md-2">
                                    <div class="form-group">
                                        <label for="exampleInputUsername1">Select Category</label>
                                        <select type="select" id="exampleCustomSelect" name="expanse_category"
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
                                        <label for="condition_delivery">Condition Delivery</label>
                                        <input type="number" class="form-control" placeholder="Condition Delivery"
                                               name="condition_delivery">
                                    </div>
                                </div>

                                <div class="col-md-2">
                                    <div class="form-group">
                                        <label for="condition_advance_payment">Condition Advance Payment</label>
                                        <input type="number" class="form-control" placeholder="Condition Advance Payment"
                                               name="condition_advance_payment">
                                    </div>
                                </div>

                                <div class="col-md-2">
                                    <div class="form-group">
                                        <label for="tt_delivery">TT Delivery</label>
                                        <input type="number" class="form-control" placeholder="TT Delivery"
                                               name="tt_delivery">
                                    </div>
                                </div>

                                <div class="col-md-2">
                                    <div class="form-group">
                                        <label for="dd_delivery">DD Delivery</label>
                                        <input type="number" class="form-control" placeholder="DD Delivery"
                                               name="dd_delivery">
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-2">
                                    <div class="form-group">
                                        <label for="ho_payment">H/O Payment</label>
                                        <input type="number" class="form-control" placeholder="H/O Payment"
                                               name="ho_payment">
                                    </div>
                                </div>

                                <div class="col-md-2">
                                    <div class="form-group">
                                        <label for="advance_rn">Advance R/N</label>
                                        <input type="number" class="form-control" placeholder="Advance R/N"
                                               name="advance_rn">
                                    </div>
                                </div>

                                <div class="col-md-2">
                                    <div class="form-group">
                                        <label for="loan_rn">Loan R/N</label>
                                        <input type="number" class="form-control" placeholder="Loan R/N"
                                               name="loan_rn">
                                    </div>
                                </div>

                                <div class="col-md-2">
                                    <div class="form-group">
                                        <label for="commission">Commission</label>
                                        <input type="number" class="form-control" placeholder="Commission"
                                               name="commission">
                                    </div>
                                </div>

                                <div class="col-md-2">
                                    <div class="form-group">
                                        <label for="other_amount">Other Amount</label>
                                        <input type="number" class="form-control" placeholder="Other Amount"
                                               name="other_amount">
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
                                <th class="">image</th>
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
                            </tr>
                            </thead>
                            <tbody>
                            {{--                                    @foreach($categories as $category)--}}
                            {{--                                        @foreach($category->expanses as $key=>$row)--}}
                            @foreach($expanses as $key=>$row)
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
                                        <img class="avatar"
                                             src="{{ $row->getFirstMediaUrl('receipts') != null ? $row->getFirstMediaUrl('receipts') : ''}}"
                                        >

                                    </td>

                                    <td class="">
                                        {{ $row->condition_delivery }}
                                    </td>

                                    <td class="">
                                        {{ $row->condition_advance_payment }}
                                    </td>

                                    <td class="">
                                        {{ $row->tt_delivery }}
                                    </td>

                                    <td class="">
                                        {{ $row->dd_delivery }}
                                    </td>

                                    <td class="">
                                        {{ $row->ho_payment }}
                                    </td>

                                    <td class="">
                                        {{ $row->advance_rn }}
                                    </td>

                                    <td class="">
                                        {{ $row->loan_rn }}
                                    </td>

                                    <td class="">
                                        {{ $row->commission }}
                                    </td>

                                    <td class="">
                                        {{ $row->other_amount }}
                                    </td>


                                    <td class="">
                                        {{ $row->previous_cash }}
                                    </td>

                                    <td class="">
                                        {{ $row->notes }}
                                    </td>

                                    <td class="">
                                        <button type="submit"
                                                onclick="deleteData({{ $row->id }})"><i class=" ik ik-trash-2 f-16
                                           text-red"></i></button>


                                        <form id="delete-form-{{ $row->id }}"
                                              action="{{ route('expanse.destroy',$row->id) }}" method="POST"
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
                            <th class="">image</th>
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


