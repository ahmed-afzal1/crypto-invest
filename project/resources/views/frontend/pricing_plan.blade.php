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
								<h2 class="title-head">{{__('Our')}} <span>{{__('Prices')}}</span></h2>
								<hr>
								<ul class="breadcrumbb">
									<li><a href="{{route('front.index')}}"> {{__('home')}}</a></li>
									<li>{{__('pricing')}}</li>
								</ul>
							</div>
						</div>
					</div>
				</div>
			</div>
		</section>

        <section class="pricing">
            <div class="container">
				<h3 class="text-center">Buy Bitcoins</h3>
				<p class="text-center">Buy bitcoins with your credit card or cash here! Register to Bayya and get your bitcoins today.</p>
                <div class="row">
                    @if (count($plans) == 0)
                        <div class="card">
                            <div class="card-body">
                                <p>{{__('No Plan Found')}}</p>
                            </div>
                        </div>
                    @endif
                    @foreach($plans as $key=>$data)
                            <div class="col-lg-4 col-md-6">
                                <div class="single-pricebox">
                                    <p class="plan-title">
                                        {{ $data->title }}
                                    </p>
                                    <div class="bonus">
                                        <i class="fas fa-dollar-sign"></i>
                                        <p class="persent">{{ $data->percentage }}%</p>
                                        <p class="time">{{ __('Payout ') }} {{ $data->days }} {{ __('day(s)') }} </p>
                                    </div>
                                    <div class="price-range-area">
                                        <div class="invest-count">
                                            <div class="left">
                                                {{ __('Minimum Invest') }} 
                                            </div>
                                            <div class="right">
                                                {{ $gs->currency_format == 0 ? $gs->currency_sign.$data->min_price : $data->min_price .$gs->currency_sign }}<i class="fa fa-btc"></i>
                                            </div>
                                        </div>
                                        <div class="invest-range-slider">
                                            <div class="range-slider">
                                                <input class="range-slider__range" type="range" value="{{ $data->min_price }}" min="{{ $data->min_price }}" max="{{ $data->max_price }}" style="background: linear-gradient(90deg, rgb(31, 113, 212) 26.4%, rgba(31, 113, 212, 0.125) 26.5%);">
                                            </div> 
                                        </div>
                                        <div class="invest-get">
                                            <div class="left">
                                                {{ __('Invest')}}  <br>
                                                <input type="hidden" value="{{ $data->min_price }}" class="invest-min-price" />
                                                <input type="hidden" value="{{ round($data->max_price ) }}" class="invest-max-price" />
                                                <input type="number" min="{{ $data->min_price }}" max="{{ round($data->max_price ) }}"  class="payprice" value="{{ $data->min_price }}">
                                                <span style="display:none;" class="range-slider__value ck">{{ $data->min_price }}</span>
                                            </div>
                                            <input type="hidden" class="dbl" value="{{ $data->interest() }}">
                                            <div class="right">
                                                {{ __('Get') }}  <span class="dk">{{ round($data->min_price * $data->interest()) }}</span>
                                                <input type="hidden" class="prodid" value="{{ $data->id }}">
                                                <input type="hidden" class="getprice" value="{{ round($data->min_price * $data->interest()) }}">
                                            </div>
                                        </div>
                                    </div>
                                    <div class="invest-button">
                                        @if (auth()->user())
                                            <a href="javascript:;" data-url="{{ route('front.setdata') }}" data-href="{{ route('front.checkout',$data->id) }}" class="mybtn1 checkout-btn btn btn-primary">{{ __('INVEST NOW') }} </a>
                                        @else 
                                            <a href="javascript:;" data-checkoutRoute="{{ route('front.checkout', $data->id) }}" data-url="{{ route('front.setdata') }}" data-href="{{ route('front.checkout',$data->id) }}" class="mybtn1 checkout-btn btn btn-primary">{{ __('INVEST NOW') }} </a>
                                        @endif
                                    </div>
                                </div>
                            </div>
        
                    @endforeach
                </div>
            </div>
        </section>

    @includeIf('partials.front.footer_top')
@endsection

@push('js')
    
@endpush