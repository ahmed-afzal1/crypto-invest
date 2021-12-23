@extends('layouts.front')

@push('css')
    
@endpush

@section('contents')
<div class="container-fluid user-auth">
    <div class="hidden-xs col-sm-4 col-md-4 col-lg-4">

    </div>
    <div class="col-xs-12 col-sm-12 col-md-8 col-lg-12">
        <div class="form-container">
            <div>
                <div class="text-center">
                    <h2 class="title-head hidden-xs">{{__('get')}} <span>{{__('started')}}</span></h2>
                     <p class="info-form">{{__('Open account for free and start trading Bitcoins now!')}}</p>
                </div>

                <form id="registerform" action="{{route('user.register.submit')}}" method="POST">
                    @csrf
                    @include('includes.admin.form-login')
                    <div class="form-group">
                        <input class="form-control" name="name" id="name" placeholder="{{__('Full Name')}}" type="text" required>
                    </div>

                    <div class="form-group">
                        <input class="form-control" name="email" id="email" placeholder="{{__('Email Address')}}" type="email" required>
                    </div>

                    <div class="form-group">
                        <input class="form-control" name="phone" id="phone" placeholder="{{__('Phone')}}" type="tel" required>
                    </div>

                    <div class="form-group">
                        <input class="form-control" name="address" id="address" placeholder="{{__('Address')}}" type="text" required>
                    </div>

                    <div class="form-group">
                        <input class="form-control" name="password" id="password" placeholder="{{__('Password')}}" type="password" required>
                    </div>

                    <div class="form-group">
                        <input class="form-control" name="password_confirmation" id="cpassword" placeholder="{{__('Confirm Password')}}" type="password" required>
                    </div>

                    <div class="form-group">
                        <button class="btn btn-primary" type="submit">{{__('create account')}}</button>
                        <p class="text-center">{{__('already have an account ?')}} <a href="{{route('user.login')}}"> {{__('Login')}}</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection

@push('js')
    
@endpush