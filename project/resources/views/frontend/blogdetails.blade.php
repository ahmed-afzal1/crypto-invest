@extends('layouts.front')

@push('css')
    
@endpush

@section('contents')
		<!-- Banner Area Starts -->
		<section class="banner-area" style="background: url({{ $gs->breadcumb_banner ? asset('assets/images/'.$gs->breadcumb_banner):asset('assets/images/noimage.png') }});">
			<div class="banner-overlay">
				<div class="banner-text text-center">
					<div class="container">
						<div class="text-center">
							<div class="col-xs-12">
								<h2 class="title-head banner-post-title">{{$data->title}}</h2>
							</div>
						</div>
					</div>
				</div>
			</div>
		</section>

        <section class="container blog-page">
			<div class="row">
				<div class="content col-xs-12 col-md-8">
					<article>
						<figure class="blog-figure">
							<img class="img-responsive" src="{{asset('assets/images/'.$data->photo)}}" alt="">
						</figure>
						<p class="content-article">
							@php
								echo $data->details;
							@endphp
						</p>

						<div class="meta second-font">
							<span class="date"><i class="fa fa-calendar"></i> {{$data->created_at->format('d M Y')}}</span>
							<span><i class="fa fa-tags"></i> {{$data->tags}}</span>
						</div>

						<div class="comments">
							<h3 class="comments-heading uppercase">3 Comments</h3>
							<ul class="comments-list">
								<li>
									<!-- Comment Starts -->
									<div class="comment">
										<img class="comment-avatar pull-left" alt="" src="images/blog/user1.jpg">
										<div class="comment-body">
											<div class="meta-data">
												<span class="comment-author">Lina Marzouki</span>
												<span class="comment-date pull-right second-font">January 17, 2017</span>
											</div>
											<p class="comment-content">
											Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat. Duis aute irure dolor in reprehen.</p>
											<div>
												<a class="comment-reply" href="#">Reply</a>
											</div>	
										</div>
									</div>
									<!-- Comment Ends -->
									<ul class="comments-reply">
										<li>
											<!-- Comment Starts -->
											<div class="comment">
												<img class="comment-avatar pull-left" alt="" src="images/blog/user2.jpg">
												<div class="comment-body">
													<div class="meta-data">
														<span class="comment-author">Rawia Chniti</span>
														<span class="comment-date pull-right">March 29, 2017</span>
													</div>
													<p class="comment-content">
													Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat. Duis aute irure dolor in reprehen.</p>
													<div>
														<a class="comment-reply" href="#">Reply</a>
													</div>	
												</div>
											</div>
											<!-- Comment Ends -->
										</li>
									</ul>
									<!-- Comment Starts -->
									<div class="comment">
										<img class="comment-avatar pull-left" alt="" src="images/blog/user3.jpg">
										<div class="comment-body">
											<div class="meta-data">
												<span class="comment-author">Slim Hamdi</span>
												<span class="comment-date pull-right">January 17, 2017</span>
											</div>
											<p class="comment-content">
											Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat. Duis aute irure dolor in reprehen.</p>
											<div>
												<a class="comment-reply" href="#">Reply</a>
											</div>	
										</div>
									</div>
									<!-- Comment Ends -->
								</li>
							</ul>
							<h3 class="comments-heading add-comment">Add a comment</h3>
							<!-- Comments Form Starts -->
							<div class="comments-form">
								<form>
									<!-- Input Field Starts -->
									<div class="form-group">
										<input class="form-control" name="name" id="name" placeholder="NAME" type="text" required>
									</div>
									<!-- Input Field Ends -->
									<!-- Input Field Starts -->
									<div class="form-group">
										<input class="form-control" name="email" id="email" placeholder="EMAIL" type="email" required>
									</div>
									<!-- Input Field Ends -->
									<!-- Input Field Starts -->
									<div class="form-group">
										<textarea class="form-control" id="comment" name="comment" placeholder="COMMENT"  required></textarea>
									</div>
									<!-- Input Field Ends -->
									<!-- Submit Form Button Starts -->
									<div class="form-group">
										<button class="btn btn-primary" type="submit">submit comment</button>
									</div>
									<!-- Submit Form Button Ends -->
								</form>
							</div>
							<!-- Comments Form Ends -->
						</div>
					</article>
				</div>
				<!-- Sidebar Starts -->
				<div class="sidebar col-xs-12 col-md-4">
					<div class="widget">
						<h3 class="widget-title">{{__('Categories')}}</h3>
						<ul class="arrow nav nav-tabs second-font uppercase">
							@foreach ($bcats as $key=>$data)
								<li><a href="{{ route('front.blogcategory',$data->slug) }}">{{$data->name}}</a></li>
							@endforeach
						</ul>
					</div>

					<div class="widget">
						<h3 class="widget-title">{{__('Archives')}}</h3>
						<ul class="arrow nav nav-tabs second-font uppercase">
							@foreach ($archives as $key=>$data)
								<li><a href="{{ route('front.blogarchive',$key) }}">{{$key}}</a></li>
							@endforeach
						</ul>
					</div>

					<div class="widget recent-posts">
						<h3 class="widget-title">{{__('Recent Posts')}}</h3>
						<ul class="unstyled clearfix">
							@foreach ($rblogs as $key=>$data)
								<li>
									<div class="posts-thumb pull-left"> 
										<a href="{{route('blog.details',$data->slug)}}">
											<img alt="img" src="{{asset('assets/images/'.$data->photo)}}">
										</a>
									</div>
									<div class="post-info">
										<h4 class="entry-title">
											<a href="{{route('blog.details',$data->slug)}}">{{Str::limit($data->title,50)}}</a>
										</h4>
										<p class="post-meta">
											<span class="post-date"><i class="fa fa-clock-o"></i> {{$data->created_at->format('M d, Y')}}</span>
										</p>
									</div>
									<div class="clearfix"></div>
								</li>
							@endforeach
						<!-- Recent Post Widget Ends -->
						</ul>
					</div>

					<div class="widget widget-tags">
						<h3 class="widget-title">{{__('Popular Tags')}} </h3>
						<ul class="unstyled clearfix">
							@foreach($tags as $tag)
								@if(!empty($tag))
								<li>
									<a class="{{ isset($slug) ? ($slug == $tag ? 'active' : '') : '' }}" href="{{ route('front.blogtags',$tag) }}">{{ $tag }} </a>
								</li>
								@endif
							@endforeach
			            </ul>
					</div>
				</div>
			</div>
		</section>

		@includeIf('partials.front.footer_top')

@endsection

@push('js')
    
@endpush