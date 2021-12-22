@extends('layouts.front')
@section('content')


  <!-- Breadcrumb Area Start -->
  <div class="breadcrumb-area" style="background: url({{ $gs->breadcumb_banner ? asset('assets/images/'.$gs->breadcumb_banner):asset('assets/images/noimage.png') }});">
    <div class="container">
      <div class="row">
        <div class="col-lg-12">
          <h1 class="pagetitle">
            @lang('Google Authentication')
          </h1>

          <ul class="pages">
              <li>
                <a href="{{ route('front.index') }}">
                  {{ $langg->lang2 }}
                </a>
              </li>
              <li>
                <a href="{{ route('user.login') }}">
                    @lang('OTP')
                </a>
              </li>

          </ul>
        </div>
      </div>
    </div>
  </div>
  <!-- Breadcrumb Area End -->

<!-- login-signup Area Start -->
<section class="login-signup">
    <div class="container">
      <div class="row justify-content-center">
        <div class="col-lg-8">
            <div class="login-area signup-area">  
                <div class="login-form signup-form">
                  <form action="{{ route('user.otp.submit') }}" method="POST">
                    @include('includes.admin.form-login')
                    {{ csrf_field() }}
                    <div class="row">
                      <div class="col-lg-12">
                        <div class="form-input">
                          <input type="text" name="otp" placeholder="@lang('Type Your otp')" required="">
                          <i class="fa fa-key"></i>
                        </div>
                      </div>
                    </div>
                    <input id="authdata" type="hidden" value="{{ $langg->lang177 }}">
                    <button type="submit" class="submit-btn">@lang('Submit')</button>

                  </form>
                </div>
              </div>
      </div>
    </div>
  </div>
</section>

<!-- login-signup Area Ends -->
@endsection

@section('scripts')
<script src="{{asset('assets/user/js/sweetalert2@9.js')}}"></script>

@if($errors->any())
    @foreach ($errors->all() as $error)
        <script>
            const Toast = Swal.mixin({
            toast: true,
            position: 'top-end',
            showConfirmButton: false,
            timer: 3000,
            timerProgressBar: true,
            onOpen: (toast) => {
                toast.addEventListener('mouseenter', Swal.stopTimer)
                toast.addEventListener('mouseleave', Swal.resumeTimer)
        }
        })
            Toast.fire({
            icon: 'error',
            title: '{{ $error }}'
            })
        </script>
    @endforeach
@endif
@endsection