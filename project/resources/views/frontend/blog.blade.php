@extends('layouts.front')

@push('css')
    
@endpush

@section('contents')
		<section class="banner-area">
			<div class="banner-overlay">
				<div class="banner-text text-center">
					<div class="container">
						<div class="row text-center">
							<div class="col-xs-12">
								<h2 class="title-head">{{__('Blog')}} <span>{{__('Posts')}}</span></h2>
								<hr>
								<ul class="breadcrumb">
									<li><a href="index.html"> {{__('home')}}</a></li>
									<li>{{__('Blog')}}</li>
								</ul>
							</div>
						</div>
					</div>
				</div>
			</div>
		</section>

        <section class="container blog-page">
			<div class="row">
				<div class="sidebar col-xs-12 col-md-4">
					<div class="widget">
						<h3 class="widget-title">Categories</h3>
						<ul class="nav nav-tabs">
							<li><a href="#">Insurance</a></li>
							<li><a href="#">Announcement</a></li>
							<li><a href="#">Banking</a></li>
							<li><a href="#">Security</a></li>
							<li><a href="#">Regulation</a></li>
							<li><a href="#">Market Updates</a></li>
						</ul>
					</div>
					<div class="widget">
						<h3 class="widget-title">Archives</h3>
						<ul class="arrow nav nav-tabs">
							<li><a href="#">January 2018</a></li>
							<li><a href="#">December 2017</a></li>
							<li><a href="#">November 2017</a></li>
							<li><a href="#">October 2017</a></li>
							<li><a href="#">September 2017</a></li>
						</ul>
					</div>
					<div class="widget recent-posts">
						<h3 class="widget-title">Recent Posts</h3>
						<ul class="unstyled clearfix">
						<li>
							<div class="posts-thumb pull-left"> 
								<a href="blog-post.html">
									<img alt="img" src="images/blog/blog-post-small-1.jpg">
								</a>
							</div>
							<div class="post-info">
								<h4 class="entry-title">
									<a href="blog-post.html">Risks & Rewards Of Investing In Bitcoin</a>
								</h4>
								<p class="post-meta">
									<span class="post-date"><i class="fa fa-clock-o"></i> January 19, 2018</span>
								</p>
							</div>
							<div class="clearfix"></div>
						</li>

						<li>
							<div class="posts-thumb pull-left"> 
								<a href="blog-post-light.html">
									<img alt="img" src="images/blog/blog-post-small-2.jpg">
								</a>
							</div>
							<div class="post-info">
								<h4 class="entry-title">
									<a href="blog-post-light.html">Cryptocurrency - Who Are Involved With It?</a>
								</h4>
								<p class="post-meta">
									<span class="post-date"><i class="fa fa-clock-o"></i>  August 03, 2017</span>
								</p>
							</div>
							<div class="clearfix"></div>
						</li>

						<li>
							<div class="posts-thumb pull-left"> 
								<a href="blog-post-light.html">
									<img alt="img" src="images/blog/blog-post-small-3.jpg">
								</a>
							</div>
							<div class="post-info">
								<h4 class="entry-title">
									<a href="blog-post-light.html">How Cryptocurrency Begun and Its Impact</a>
								</h4>
								<p class="post-meta">
									<span class="post-date"><i class="fa fa-clock-o"></i>  March 27, 2017</span>
								</p>
							</div>
							<div class="clearfix"></div>
						</li>
						</ul>
					</div>

					<div class="widget widget-tags">
						<h3 class="widget-title">Popular Tags </h3>
						<ul class="unstyled clearfix">
							<li><a href="#">currency</a></li>
							<li><a href="#">crypto</a></li>
							<li><a href="#">trading</a></li>
							<li><a href="#">wallet</a></li>
							<li><a href="#">mining</a></li>
							<li><a href="#">transaction</a></li>
							<li><a href="#">financial</a></li>
							<li><a href="#">security</a></li>
			            </ul>
					</div>
				</div>

				<div class="content col-xs-12 col-md-8">
                    @foreach ($blogs as $key=>$blog)
                        <article>
                            <a href="blog-post.html"><h4>{{Str::limit($blog->title,50)}}</h4></a>
                            <figure>
                                <a href="blog-post.html">
                                    <img class="img-responsive" src="{{asset('assets/images/'.$blog->photo)}}" alt="img">
                                </a>
                            </figure>
                            <p class="excerpt">
                                @php
                                    echo substr($blog->details,0,100)
                                @endphp
                            </p>

                            <a href="blog-post.html" class="btn btn-primary btn-readmore">
                                {{__('Read more')}}
                            </a>
                            <div class="meta">
                                <span><i class="fa fa-user"></i> <a href="#">admin</a></span>
                                <span><i class="fa fa-calendar"></i> 9 November 2017</span>
                                <span><i class="fa fa-commenting"></i> <a href="blog-post.html">18 comments</a></span>
                                <span><i class="fa fa-tags"></i> market, cryptocurrency, trading</span>
                                <span><i class="fa fa-link"></i> <a href="blog-post.html">permalink</a></span>
                            </div>
                        </article>
                    @endforeach

					<nav class="col-xs-12 text-center" aria-label="Page navigation">
					  <ul class="pagination">
						<li>
						  <a href="#" aria-label="Previous">
							<span aria-hidden="true"><i class="fa fa-angle-double-left"></i></span>
						  </a>
						</li>
						<li><a href="#">1</a></li>
						<li class="active"><a href="#">2</a></li>
						<li><a href="#">3</a></li>
						<li><a href="#">4</a></li>
						<li>
						  <a href="#" aria-label="Next">
							<span aria-hidden="true"><i class="fa fa-angle-double-right"></i></span>
						  </a>
						</li>
					  </ul>
					</nav>
				</div>
			</div>
		</section>

        <section class="call-action-all">
			<div class="call-action-all-overlay">
				<div class="container">
					<div class="row">
						<div class="col-xs-12">
							<div class="action-text">
								<h2>Get Started Today With Bitcoin</h2>
								<p class="lead">Open account for free and start trading Bitcoins!</p>
							</div>
							<p class="action-btn"><a class="btn btn-primary" href="register.html">Register Now</a></p>
						</div>
					</div>
				</div>
			</div>
        </section>

@endsection

@push('js')
    
@endpush