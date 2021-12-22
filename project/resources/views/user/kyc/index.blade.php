@extends('layouts.user')

@section('styles')

@endsection

@section('content')

        <div class="col-lg-9">

          <div class="transaction-area">
            <div class="heading-area">
              <h3 class="title">
                KYC Form
              </h3>
            </div>
            <form id="userform" class="px-4" action="{{route('kyc-submit')}}">

                <div class="gocover" style="background: url({{ asset('assets/images/'.$gs->loader) }}) no-repeat scroll center center rgba(45, 45, 45, 0.5);"></div>
            
                {{ csrf_field() }}
                @include('includes.admin.form-both') 
                <div class="row">
                    <div class="col-lg-12">
                        <div class="form-group bmd-form-group">
                            <label for="details" class="bmd-label-floating">@lang('Give Your info')*</label>
                            <input type="text" class="form-control" id="details" name="details" value="{{auth()->user()->details}}" required="">
                            <span class="bmd-help">@lang('Give Your info')</span>
                        </div>
                    </div>

                    <div class="col-lg-12">
                        <button type="submit" class="btn btn-primary btn-round">{{ $langg->lang276 }}</button>
                    </div>
                </div>
            </form>
          </div>
        </div>

@endsection

@section('scripts')

@endsection
