@extends('layouts.front')

@push('css')
    
@endpush

@section('contents')
<div id="main-slide" class="carousel slide carousel-fade" data-ride="carousel">
    <ol class="carousel-indicators visible-lg visible-md">
        @foreach ($sliders as $key=>$slider)
            <li data-target="#main-slide" data-slide-to="{{$key}}" class="{{$loop->first ? 'active':''}}"></li>
        @endforeach
    </ol>
    <div class="carousel-inner">
        @foreach ($sliders as $key=>$slider)
            <div class="item bg-parallax {{$loop->first ? 'active':''}}" style="background: url({{ $slider->photo ? asset('assets/images/'.$slider->photo):asset('assets/images/noimage.png') }});">
                <div class="slider-content">
                    <div class="container">
                        <div class="slider-text text-center">
                            <h3 class="slide-title">{{$slider->title_text}}</h3>
                            <h3 class="slide-title">{{$slider->subtitle_text}}</h3>
                            <p>
                                <a href="{{$slider->link}}" class="slider btn btn-primary">{{__('Learn more')}}</a>
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        @endforeach
    </div>
    <a class="left carousel-control" href="index.html#main-slide" data-slide="prev">
        <span><i class="fa fa-angle-left"></i></span>
    </a>
    <a class="right carousel-control" href="index.html#main-slide" data-slide="next">
        <span><i class="fa fa-angle-right"></i></span>
    </a>
</div>

<section class="features">
    <div class="container">
        <div class="row features-row">
            @foreach ($features as $key=>$data)
                <div class="feature-box col-md-4 col-sm-12">
                    <span class="feature-icon">
                        <img src="{{asset('assets/images/'.$data->photo)}}" alt="download bitcoin">
                    </span>
                    <div class="feature-box-content">
                        <h3>{{$data->title}}</h3>
                        <p>{{$data->details}}</p>
                    </div>
                </div>
            @endforeach
        </div>
    </div>
</section>

<section class="about-us">
    <div class="container">
        <div class="row text-center">
            <h2 class="title-head">About <span>Us</span></h2>
            <div class="title-head-subtitle">
                <p>a commercial website that lists wallets, exchanges and other bitcoin related info</p>
            </div>
        </div>
        <div class="row about-content">
            <!-- Image Starts -->
            <div class="col-sm-12 col-md-5 col-lg-6 text-center">
                <img class="img-responsive img-about-us" src="images/about-us.png" alt="about us">
            </div>
            <!-- Image Ends -->
            <!-- Content Starts -->
            <div class="col-sm-12 col-md-7 col-lg-6">
                <h3 class="title-about">WE ARE BAYYA</h3>
                <p class="about-text">A place for everyone who wants to simply buy and sell Bitcoins. Deposit funds using your Visa/MasterCard or bank transfer. Instant buy/sell of Bitcoins at fair price is guaranteed. Nothing extra. Join over 700,000 users from all over the world satisfied with our services.</p>
                <ul class="nav nav-tabs">
                    <li class="active"><a data-toggle="tab" href="#menu1">Our Mission</a></li>
                    <li><a data-toggle="tab" href="#menu2">Our advantages</a></li>
                    <li><a data-toggle="tab" href="#menu3">Our guarantees</a></li>
                </ul>
                <div class="tab-content">
                    <div id="menu1" class="tab-pane fade in active">
                        <p>Bitcoin is based on a protocol known as the blockchain, which allows to create, transfer and verify ultra-secure financial data without interference of third parties.</p>
                    </div>
                    <div id="menu2" class="tab-pane fade">
                        <p>Our mission as an official partner of Bitcoin Foundation is to help you enter and better understand the world of #1 cryptocurrency and avoid any issues you may encounter.</p>
                    </div>
                    <div id="menu3" class="tab-pane fade">
                        <p>We are here because we are passionate about open, transparent markets and aim to be a major driving force in widespread adoption, we are the first and the best in cryptocurrency. </p>
                    </div>
                </div>
                <a class="btn btn-primary" href="about.html">Read More</a>
            </div>
            <!-- Content Ends -->
        </div>
        <!-- Section Content Ends -->
    </div>
</section>

<section class="image-block">
    <div class="container-fluid">
        <div class="row">
            <!-- Features Starts -->
            <div class="col-md-8 ts-padding img-block-left">
                <div class="gap-20"></div>
                <div class="row">
                    <!-- Feature Starts -->
                    <div class="col-sm-6 col-md-6 col-xs-12">
                        <div class="feature text-center">
                            <span class="feature-icon">
                                <img src="images/icons/orange/strong-security.png" alt="strong security"/>
                            </span>
                            <h3 class="feature-title">Strong Security</h3>
                            <p>Protection against DDoS attacks, <br>full data encryption</p>
                        </div>
                    </div>

                    <!-- Feature Ends -->
                    <div class="gap-20-mobile"></div>
                    <!-- Feature Starts -->
                    <div class="col-sm-6 col-md-6 col-xs-12">
                        <div class="feature text-center">
                            <span class="feature-icon">
                                <img src="images/icons/orange/world-coverage.png" alt="world coverage"/>
                            </span>
                            <h3 class="feature-title">World Coverage</h3>
                            <p>Providing services in 99% countries<br> around all the globe</p>
                        </div>
                    </div>
                    <!-- Feature Ends -->
                </div>
            </div>
            <!-- Features Ends -->
            <!-- Video Starts -->
            <div class="col-md-4 ts-padding bg-image-1">
                <div>
                    <div class="text-center">
                        <a class="button-video mfp-youtube" href="https://www.youtube.com/watch?v=0gv7OC9L2s8"></a>
                    </div>
                </div>
            </div>
            <!-- Video Ends -->
        </div>
    </div>
</section>

<section class="pricing">
    <div class="container">
        <!-- Section Title Starts -->
        <div class="row text-center">
            <h2 class="title-head">affordable <span>packages</span></h2>
            <div class="title-head-subtitle">
                <p>Purchase Bitcoin using a credit card or with your linked bank account</p>
            </div>
        </div>
        <!-- Section Title Ends -->
        <!-- Section Content Starts -->
        <div class="row">
            @foreach($plans as $$key=>$data)

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
                                    <a href="javascript:;" data-url="" data-href="" class="mybtn1 checkout-btn">{{ __('INVEST NOW') }} </a>
                                @else 
                                    <a href="javascript:;" data-checkoutRoute="" data-url="" data-href="" class="mybtn1 checkout-btn">{{ __('INVEST NOW') }} </a>
                                @endif
                            </div>
                        </div>
                    </div>

            @endforeach
        </div>
    </div>
</section>


<section class="team">
    <div class="container">
        <!-- Section Title Starts -->
        <div class="row text-center">
            <h2 class="title-head">our <span>experts</span></h2>
            <div class="title-head-subtitle">
                <p> A talented team of Cryptocurrency experts based in London</p>
            </div>
        </div>

        <div class="row team-content team-members">
            <div class="col-xs-6 col-sm-3 col-md-3 col-lg-3">
                <div class="team-member">
                    <img src="images/team/member1.jpg" class="img-responsive" alt="team member">
                    <div class="team-member-caption social-icons">
                        <h4>Lina Marzouki</h4>
                        <p>Ceo Founder</p>
                        <ul class="list list-inline social">
                            <li>
                                <a href="#" class="fa fa-facebook"></a>
                            </li>
                            <li>
                                <a href="#" class="fa fa-twitter"></a>
                            </li>
                            <li>
                                <a href="#" class="fa fa-google-plus"></a>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>

            <div class="col-xs-6 col-sm-3 col-md-3 col-lg-3">
                <div class="team-member">
                    <img src="images/team/member2.jpg" class="img-responsive" alt="team member">
                    <div class="team-member-caption social-icons">
                        <h4>Marco Verratti</h4>
                        <p>Director</p>
                        <ul class="list list-inline social">
                            <li>
                                <a href="#" class="fa fa-facebook"></a>
                            </li>
                            <li>
                                <a href="#" class="fa fa-twitter"></a>
                            </li>
                            <li>
                                <a href="#" class="fa fa-google-plus"></a>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>

            <div class="col-xs-6 col-sm-3 col-md-3 col-lg-3">
                <div class="team-member">
                    <img src="images/team/member3.jpg" class="img-responsive" alt="team member">
                    <div class="team-member-caption social-icons">
                        <h4>Emilia Bella</h4>
                        <p>Bitcoin Consultant</p>
                        <ul class="list list-inline social">
                            <li>
                                <a href="#" class="fa fa-facebook"></a>
                            </li>
                            <li>
                                <a href="#" class="fa fa-twitter"></a>
                            </li>
                            <li>
                                <a href="#" class="fa fa-google-plus"></a>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>

            <div class="col-xs-6 col-sm-3 col-md-3 col-lg-3">
                <div class="team-member">
                    <img src="images/team/member4.jpg" class="img-responsive" alt="team member">
                    <div class="team-member-caption social-icons">
                        <h4>Antonio Conte</h4>
                        <p>Bitcoin Developer</p>
                        <ul class="list list-inline social">
                            <li>
                                <a href="#" class="fa fa-facebook"></a>
                            </li>
                            <li>
                                <a href="#" class="fa fa-twitter"></a>
                            </li>
                            <li>
                                <a href="#" class="fa fa-google-plus"></a>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>


<section class="blog">
    <div class="container">
        <div class="row text-center">
            <h2 class="title-head">Bitcoin <span>News</span></h2>
            <div class="title-head-subtitle">
                <p>Discover latest news about Bitcoin on our blog</p>
            </div>
        </div>

        <div class="row latest-posts-content">
            @foreach ($blogs->get() as $key=>$blog)
                <div class="col-sm-4 col-md-4 col-xs-12">
                    <div class="latest-post">
                        <a href="{{route('blog.details',$blog->slug)}}"><img class="img-responsive" src="{{asset('assets/images/'.$blog->photo)}}" alt="img"></a>
                        <div class="post-body">
                            <h4 class="post-title">
                                <a href="{{route('blog.details',$blog->slug)}}">{{Str::limit($blog->title,50)}}</a>
                            </h4>
                            <div class="post-text">
                                <?php echo substr($blog->details,0,100) ?>
                            </div>
                        </div>
                        <div class="post-date">
                            <span>{{$blog->created_at->format('d')}}</span>
                            <span>{{$blog->created_at->format('M')}}</span>
                        </div>
                        <a href="{{route('blog.details',$blog->slug)}}" class="btn btn-primary">{{__('read more')}}</a>
                    </div>
                </div>
            @endforeach
        </div>

    </div>
</section>
    @includeIf('partials.footer_top')
@endsection

@push('js')
    
@endpush