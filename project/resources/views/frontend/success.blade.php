@extends('layouts.front')

@push('css')
    
@endpush

@section('contents')
<section class="banner-area" style="background: url({{ $gs->breadcumb_banner ? asset('assets/images/'.$gs->breadcumb_banner):asset('assets/images/noimage.png') }});">
    <div class="banner-overlay">
        <div class="banner-text text-center">
            <div class="container">
                <div class="text-center">
                    <div class="col-xs-12">
                        <h2 class="title-head"><span>{{__('Success')}}</span></h2>
                        <hr>

                        <ul class="breadcrumbb">
                            <li><a href="{{route('front.index')}}"> {{__('home')}}</a></li>
                            <li>{{__('success')}}</li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<section class="thankyou">
    <div class="container">
      <div class="row justify-content-center">
        <div class="col-lg-8 col-md-10 col-11">
          <div class="content">
            <div class="icon">
                <i class="far fa-check-circle"></i>
            </div>
            <h4 class="heading">
                  {{__('THANK YOU FOR YOUR INVEST.')}}
            </h4>
            <p class="text">
                  {{__("We'll email you an order confirmation with details and tracking info.")}}
            </p>
            <a href="{{route('front.index')}}" class="link">{{__('Get Back To Our Homepage')}}</a>
          </div>
        </div>
      </div>
    </div>
  </section>
@endsection

@push('js')
    
@endpush