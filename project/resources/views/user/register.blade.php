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

                <form>
                    <div class="form-group">
                        <input class="form-control" name="name" id="name" placeholder="{{__('USER NAME')}}" type="text" required>
                    </div>

                    <div class="form-group">
                        <input class="form-control" name="email" id="email" placeholder="{{__('EMAIL')}}" type="email" required>
                    </div>
                    
                    <div class="form-group">
                        <input class="form-control" name="password" id="password" placeholder="PASSWORD" type="password" required>
                    </div>
                    <div class="form-group">
                        <button class="btn btn-primary" type="submit">create account</button>
                        <p class="text-center">already have an account ? <a href="login.html">Login</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection

@push('js')
    
@endpush