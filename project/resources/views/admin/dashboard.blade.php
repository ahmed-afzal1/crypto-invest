@extends('layouts.admin')

@section('content')

<div class="d-sm-flex align-items-center justify-content-between mb-4">
    <h1 class="h3 mb-0 text-gray-800">{{ __('Dashboard') }}</h1>
    <ol class="breadcrumb">
      <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">{{ __('Dashboard') }}</a></li>
    </ol>
  </div>
  @if(Session::has('cache'))

  <div class="alert alert-success validation">
      <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span
              aria-hidden="true">×</span></button>
      <h3 class="text-center">{{ Session::get("cache") }}</h3>
  </div>


@endif

  <div class="row mb-3">

    <div class="col-xl-3 col-md-6 mb-4">
      <div class="card h-100">
        <div class="card-body">
          <div class="row align-items-center">
            <div class="col mr-2">
              <div class="text-xs font-weight-bold text-uppercase mb-1">{{ __('Active Users') }}</div>
              <div class="h5 mb-0 font-weight-bold text-gray-800">1</div>
            </div>
            <div class="col-auto">
              <i class="fas fa-user fa-2x text-success"></i>
            </div>
          </div>
        </div>
      </div>
    </div>
    <div class="col-xl-3 col-md-6 mb-4">
        <div class="card h-100">
          <div class="card-body">
            <div class="row align-items-center">
              <div class="col mr-2">
                <div class="text-xs font-weight-bold text-uppercase mb-1">{{ __('Blocked Users') }}</div>
                <div class="h5 mb-0 font-weight-bold text-gray-800">0</div>
              </div>
              <div class="col-auto">
                <i class="fas fa-user fa-2x text-danger"></i>
              </div>
            </div>
          </div>
        </div>
      </div>
      <div class="col-xl-3 col-md-6 mb-4">
        <div class="card h-100">
          <div class="card-body">
            <div class="row align-items-center">
              <div class="col mr-2">
                <div class="text-xs font-weight-bold text-uppercase mb-1">{{ __('Total Authors') }}</div>
                <div class="h5 mb-0 font-weight-bold text-gray-800">3</div>
              </div>
              <div class="col-auto">
                <i class="fas fa-user-tie fa-2x text-primary"></i>
              </div>
            </div>
          </div>
        </div>
      </div>
      <div class="col-xl-3 col-md-6 mb-4">
        <div class="card h-100">
          <div class="card-body">
            <div class="row align-items-center">
              <div class="col mr-2">
                <div class="text-xs font-weight-bold text-uppercase mb-1">{{ __('Active Products') }}</div>
                <div class="h5 mb-0 font-weight-bold text-gray-800">5</div>
              </div>
              <div class="col-auto">
                <i class="fas fa-file fa-2x text-success"></i>
              </div>
            </div>
          </div>
        </div>
      </div>
      <div class="col-xl-3 col-md-6 mb-4">
        <div class="card h-100">
          <div class="card-body">
            <div class="row align-items-center">
              <div class="col mr-2">
                <div class="text-xs font-weight-bold text-uppercase mb-1">{{ __('Approval Pending') }}</div>
                <div class="h5 mb-0 font-weight-bold text-gray-800">0</div>
              </div>
              <div class="col-auto">
                <i class="fas fa-file fa-2x text-primary"></i>
              </div>
            </div>
          </div>
        </div>
      </div>
      <div class="col-xl-3 col-md-6 mb-4">
        <div class="card h-100">
          <div class="card-body">
            <div class="row align-items-center">
              <div class="col mr-2">
                <div class="text-xs font-weight-bold text-uppercase mb-1">{{ __('Update Approval Pending') }}</div>
                <div class="h5 mb-0 font-weight-bold text-gray-800">3</div>
              </div>
              <div class="col-auto">
                <i class="fas fa-file fa-2x text-warning"></i>
              </div>
            </div>
          </div>
        </div>
      </div>
      <div class="col-xl-3 col-md-6 mb-4">
        <div class="card h-100">
          <div class="card-body">
            <div class="row align-items-center">
              <div class="col mr-2">
                <div class="text-xs font-weight-bold text-uppercase mb-1">{{ __('Total Sale') }}</div>
                <div class="h5 mb-0 font-weight-bold text-gray-800">4</div>
              </div>
              <div class="col-auto">
                <i class="fas fa-shopping-cart fa-2x text-primary"></i>
              </div>
            </div>
          </div>
        </div>
      </div>
      <div class="col-xl-3 col-md-6 mb-4">
        <div class="card h-100">
          <div class="card-body">
            <div class="row align-items-center">
              <div class="col mr-2">
                <div class="text-xs font-weight-bold text-uppercase mb-1">{{ __('Total Sale profit') }}</div>
                <div class="h6 mb-0 mt-2 font-weight-bold text-gray-800"><i class='fas fa-dollar-sign'></i> </div>
              </div>
              <div class="col-auto">
                <i class="fas fa-money-check-alt fa-2x text-success"></i>
              </div>
            </div>
          </div>
        </div>
      </div>
  </div>
  <!--Row-->

@endsection

@section('scripts')





@endsection
