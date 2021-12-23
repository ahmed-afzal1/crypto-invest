<nav class="navbar navigation navbar-expand-lg new-nav " id="site-navigation">
    <button class="navbar-toggler ml-auto" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
        <span class="navbar-toggler-icon"><i class="fa fa-bars"></i></span>
      </button>
  
    <div class="collapse navbar-collapse" id="navbarSupportedContent">
      <ul class="navbar-nav mx-auto text-center">
        <li class="active"><a href="{{route('front.index')}}">{{__('Home')}}</a></li>
        <li><a href="{{route('front.services')}}">{{__('Services')}}</a></li>
        <li><a href="{{route('front.pricing')}}">{{__('Invest plan')}}</a></li>
        <li><a href="{{route('front.blog')}}">{{__('Blog')}}</a></li>
        @if (count($pages) > 0)
            <li class="dropdown">
                <a href="#" class="" data-toggle="dropdown">{{__('pages')}} <i class="fa fa-angle-down"></i></a>
                <ul class="dropdown-menu" role="menu">
                    @foreach ($pages as $key=>$data)
                        <li><a href="{{route('front.page',$data->slug)}}">{{$data->title}}</a></li>
                    @endforeach
                </ul>
            </li>
        @endif
        <li><a href="{{route('front.contact')}}">{{(__('Contact'))}}</a></li>
        <li class="search"><button class="fa fa-search"></button></li>
      </ul>
      
    </div>
    <div class="site-search">
        <div class="container">
            <input type="text" placeholder="type your keyword and hit enter ...">
            <span class="close">×</span>
        </div>
    </div>
</nav>