@extends('layouts.user')


@section('content')
        <div class="col-lg-8 col-md-8">

          <div class="transaction-area">
            <div class="heading-area">
              <h3 class="title">
                {{ __('Balance Transfer') }} ({{auth()->user()->income}})<a href="{{route('user-trans')}}" class="btn btn-primary btn-round ml-2">{{ __('Back') }}</a>
              </h3>
            </div>


            <div class="gocover" style="background: url({{ asset('assets/images/'.$gs->loader) }}) no-repeat scroll center center rgba(45, 45, 45, 0.5);"></div>
              <form id="userform" class="form-horizontal px-4" action="{{route('balance.transfer.store')}}" method="POST" enctype="multipart/form-data">
                  {{ csrf_field() }}

                  @include('includes.admin.form-both')

                  <div class="row">
                      <div class="col-lg-12 mb-3">
                          <label for="email" class="bmd-label-floating">{{ ('Receiver Email') }}*</label>
                          <input name="email" id="email" class="form-control" autocomplete="off"  type="email" value="{{ old('email') }}" required>
                      </div>

                      <div class="col-lg-12 mt-3">
                          <label for="amount" class="bmd-label-floating">{{ __('Amount') }}*</label>
                          <input name="amount" id="amount" class="form-control" autocomplete="off"  type="text" value="{{ old('amount') }}" required>
                      </div>

                      <div class="col-lg-12 mt-3">
                          <button type="submit" class="btn btn-primary btn-round">{{ __('Submit') }}</button>
                      </div>
                  </div>
              </form>
          </div>
        </div>

@endsection

@push('js')


@endpush