@extends('layouts.backend.app')
@section('title','Appearance Settings')
@section('content')
    <!-- push external head elements to head -->
    @push('head')
        <!--Dropify css-->
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/Dropify/0.2.2/css/dropify.min.css"
              integrity="sha512-EZSUkJWTjzDlspOoPSpUFR0o0Xy7jdzW//6qhUkoZ9c4StFkVsp9fbbd0O06p9ELS3H486m4wmrCELjza4JEog=="
              crossorigin="anonymous" referrerpolicy="no-referrer"/>
    @endpush


    <div class="container-fluid">

        <div class="page-header">
            <div class="row align-items-end">
                <div class="col-lg-8">
                    <div class="page-header-title">
                        <i class="ik ik-settings bg-blue"></i>
                        <div class="d-inline">
                            <h5>{{ __('Settings')}}</h5>
                            <span>{{ __('Define settings of Application')}}</span>
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
                                <a href="#">{{ __('Settings')}}</a>
                            </li>
                        </ol>
                    </nav>
                </div>
            </div>
        </div>

            <div class="row">
                <div class="col-md-3">
                    @include('backend.settings.sidebar')
                </div>
                <!-- left column -->
                <div class="col-md-9">
                    {{-- how to use callout --}}
                    <div class="main-card mb-3 card">
                        <div class="card-body">
                            <h5 class="card-title">How To Use:</h5>
                            <p>You can get the value of each setting anywhere on your site by calling <code>setting('key')</code></p>
                        </div>
                    </div>
                    <!-- form start -->
                    <form id="settingsFrom" autocomplete="off" role="form" method="POST" action="{{ route('settings.appearance.update') }}"
                          enctype="multipart/form-data">
                    @csrf
                    @method('PATCH')
                    <!-- general form elements -->
                        <div class="main-card mb-3 card">
                            <div class="card-body">

                                <div class="form-group">
                                    <label for="site_logo">Logo (Only Image are allowed) <code>{ key: site_logo }</code></label>
                                    <input type="file" name="site_logo" id="site_logo"
                                           class="dropify @error('site_logo') is-invalid @enderror"
                                           data-default-file="{{ setting('site_logo') != null ?  asset('storage/'.setting('site_logo')) : '' }}">
                                    @error('site_logo')
                                    <span class="text-danger" role="alert">
                                     <strong>{{ $message }}</strong>
                                 </span>
                                    @enderror
                                </div>

                                <div class="form-group">
                                    <label for="site_favicon">Favicon (Only Image are allowed, Size: 33 X 33) <code>{ key: site_favicon }</code></label>
                                    <input type="file" name="site_favicon" id="site_favicon"
                                           class="dropify @error('site_favicon') is-invalid @enderror"
                                           data-default-file="{{ setting('site_favicon') != null ?  asset('storage/'.setting('site_favicon')) : '' }}">
                                    @error('site_favicon')
                                    <span class="text-danger" role="alert">
                                     <strong>{{ $message }}</strong>
                                 </span>
                                    @enderror
                                </div>

                                <button type="button" class="btn btn-danger" onClick="resetForm('settingsFrom')">
                                    <i class="fas fa-redo"></i>
                                    <span>Reset</span>
                                </button>

                                <button type="submit" class="btn btn-primary">
                                    <i class="fas fa-arrow-circle-up"></i>
                                    <span>Update</span>
                                </button>

                            </div>
                        </div>
                        <!-- /.card -->
                    </form>
                </div>
            </div>
            <!-- /.row -->
    </div>


    <!-- push external js -->
    @push('script')
        <!--Dropify script-->
        <script src="https://cdnjs.cloudflare.com/ajax/libs/Dropify/0.2.2/js/dropify.min.js"
                integrity="sha512-8QFTrG0oeOiyWo/VM9Y8kgxdlCryqhIxVeRpWSezdRRAvarxVtwLnGroJgnVW9/XBRduxO/z1GblzPrMQoeuew=="
                crossorigin="anonymous" referrerpolicy="no-referrer"></script>
        <script>
            $(document).ready(function () {
                // Dropify
                $('.dropify').dropify();

            });
        </script>
    @endpush
@endsection
