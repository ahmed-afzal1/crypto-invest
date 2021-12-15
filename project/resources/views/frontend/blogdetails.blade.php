@extends('layouts.front')

@push('css')
    
@endpush

@section('contents')
		<!-- Banner Area Starts -->
		<section class="banner-area" style="background: url({{ $gs->breadcumb_banner ? asset('assets/images/'.$gs->breadcumb_banner):asset('assets/images/noimage.png') }});">
			<div class="banner-overlay">
				<div class="banner-text text-center">
					<div class="container">
						<div class="row text-center">
							<div class="col-xs-12">
								<h2 class="title-head banner-post-title">{{$data->title}}</h2>
							</div>
						</div>
					</div>
				</div>
			</div>
		</section>
		<!-- Banner Area Ends -->
		<!-- Section Content Starts -->
        <section class="container blog-page">
			<div class="row">
				<div class="content col-xs-12 col-md-8">
					<!-- Article Starts -->
					<article>
						<!-- Figure Starts -->
						<figure class="blog-figure">
							<img class="img-responsive" src="images/blog/blog-post-1.jpg" alt="">
						</figure>
						<!-- Figure Ends -->
						<!-- Content Starts -->
						<p class="content-article">Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur. <br><br>Excepteur sint occaecat cupidatat non proident, sunt in culpa qui officia deserunt mollit anim id est laborum.Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur. Excepteur sint occaecat cupidatat non proident, sunt in culpa qui officia deserunt mollit anim id est laborum.

Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur. <br><br>Excepteur sint occaecat cupidatat non proident, sunt in culpa qui officia deserunt mollit anim id est laborum.Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur. Excepteur sint occaecat cupidatat non proident, sunt in culpa qui officia deserunt mollit anim id est laborum.</p>
						<!-- Content Ends -->
						<!-- Meta Starts -->
						<div class="meta second-font">
							<span><i class="fa fa-user"></i> <a href="#">admin</a></span>
							<span class="date"><i class="fa fa-calendar"></i> 9 November 2017</span>
							<span><i class="fa fa-commenting"></i> <a href="blog-post.html">18 comments</a></span>
							<span><i class="fa fa-tags"></i> market, cryptocurrency, trading</span>
						</div>
						<!-- Meta Ends -->
						<!-- Comments Starts -->
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
					<!-- Article Ends -->
				</div>
				<!-- Sidebar Starts -->
				<div class="sidebar col-xs-12 col-md-4">
					<!-- Categories Widget Starts -->
					<div class="widget">
						<h3 class="widget-title">Categories</h3>
						<ul class="arrow nav nav-tabs second-font uppercase">
							<li><a href="#">Insurance</a></li>
							<li><a href="#">Announcement</a></li>
							<li><a href="#">Banking</a></li>
							<li><a href="#">Security</a></li>
							<li><a href="#">Regulation</a></li>
							<li><a href="#">Market Updates</a></li>
						</ul>
					</div>
					<!-- Categories Widget Ends -->
					<!-- Archives Widget Starts -->
					<div class="widget">
						<h3 class="widget-title">Archives</h3>
						<ul class="arrow nav nav-tabs second-font uppercase">
							<li><a href="#">January 2018</a></li>
							<li><a href="#">December 2017</a></li>
							<li><a href="#">November 2017</a></li>
							<li><a href="#">October 2017</a></li>
							<li><a href="#">September 2017</a></li>
						</ul>
					</div>
					<!-- Archives Widget Ends -->
					<!-- Latest Posts Widget Ends -->
					<div class="widget recent-posts">
						<h3 class="widget-title">Recent Posts</h3>
						<ul class="unstyled clearfix">
						<!-- Recent Post Widget Starts -->
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
									<span class="post-date"><i class="fa fa-clock-o"></i> January 19, 2017</span>
								</p>
							</div>
							<div class="clearfix"></div>
						</li>
						<!-- Recent Post Widget Ends -->
						<!-- Recent Post Widget Starts -->
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
									<span class="post-date"> August 03, 2017</span>
								</p>
							</div>
							<div class="clearfix"></div>
						</li>
						<!-- Recent Post Widget Ends -->
						<!-- Recent Post Widget Starts -->
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
									<span class="post-date"> March 27, 2017</span>
								</p>
							</div>
							<div class="clearfix"></div>
						</li>
						<!-- Recent Post Widget Ends -->
						</ul>
					</div>
					<!-- Latest Posts Widget Ends -->
					<!-- Tags Widget Starts -->
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
					<!-- Tags Widget Ends -->
				</div>
				<!-- Sidebar Ends -->
			</div>
		</section>
		<!-- Section Content Ends -->

@endsection

@push('js')
    
@endpush