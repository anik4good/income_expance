@extends('layouts.backend.app')

@section('title','General Settings')

@section('content')

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
            <form id="settingsFrom" autocomplete="off" role="form" method="POST" action="{{ route('settings.update') }}">
                @csrf
                @method('PATCH')
                <!-- general form elements -->
                <div class="main-card mb-3 card">
                    <div class="card-body">
                       <div class="form-group">
                            <label for="site_title">Site Title <code>{ key: site_title }</code></label>
                            <input type="text" name="site_title" id="site_title"
                                   class="form-control @error('site_title') is-invalid @enderror"
                                   value="{{ setting('site_title') ?? old('site_title') }}"
                                   placeholder="Site Title">
                            @error('site_title')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                            @enderror
                        </div>
                         <div class="form-group">
                             <label for="site_description">Site Description <code>{ key: site_description }</code></label>
                             <textarea name="site_description" id="site_description"
                                       class="form-control @error('site_description') is-invalid @enderror">{{ setting('site_description') ?? old('site_description') }}</textarea>
                             @error('site_description')
                             <span class="invalid-feedback" role="alert">
                                 <strong>{{ $message }}</strong>
                             </span>
                             @enderror
                         </div>

                         <div class="form-group">
                             <label for="site_address">Site Address <code>{ key: site_address }</code></label>
                             <textarea name="site_address" id="site_address"
                                       class="form-control @error('site_address') is-invalid @enderror">{{ setting('site_address') ?? old('site_address') }}</textarea>
                             @error('site_address')
                             <span class="invalid-feedback" role="alert">
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
@endsection
