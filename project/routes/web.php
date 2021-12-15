<?php

use App\Http\Controllers;
use App\Http\Controllers\Admin\AdminLanguageController;
use App\Http\Controllers\Admin\AttributeController;
use App\Http\Controllers\Admin\AuthorBadgeController;
use App\Http\Controllers\Admin\AuthorLevelController;
use App\Http\Controllers\Admin\AuthorTrendingController;
use App\Http\Controllers\Admin\BlogCategoryController;
use App\Http\Controllers\Admin\BlogController;
use App\Http\Controllers\Admin\CategoryController;
use App\Http\Controllers\Admin\ChildCategoryController;
use App\Http\Controllers\Admin\CouponController;
use App\Http\Controllers\Admin\CurrencyController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\EmailController;
use App\Http\Controllers\Admin\FaqController;
use App\Http\Controllers\Admin\FeatureController;
use App\Http\Controllers\Admin\FontController;
use App\Http\Controllers\Admin\FormController;
use App\Http\Controllers\Admin\GeneralSettingController;
use App\Http\Controllers\Admin\LanguageController;
use App\Http\Controllers\Admin\LoginController;
use App\Http\Controllers\Admin\OrderController;
use App\Http\Controllers\Admin\PageController;
use App\Http\Controllers\Admin\PageSettingController;
use App\Http\Controllers\Admin\PaymentGatewayController;
use App\Http\Controllers\Admin\PlanController;
use App\Http\Controllers\Admin\RoleController;
use App\Http\Controllers\Admin\SeoToolController;
use App\Http\Controllers\Admin\SocialSettingController;
use App\Http\Controllers\Admin\StaffController;

use App\Http\Controllers\Admin\RefundController;
use App\Http\Controllers\Admin\ServiceController;
use App\Http\Controllers\Admin\SitemapController;
use App\Http\Controllers\Admin\SliderController;
use App\Http\Controllers\Admin\SubCategoryController;
use App\Http\Controllers\Admin\SubscriberController;
use App\Http\Controllers\User\DashboardController as AppDashboardController;
use App\Models\Childcategory;
use Illuminate\Support\Facades\Route;


Route::prefix('admin')->group(function(){

//-----------------------------Clear Cache--------------------
Route::get('/cache/clear', function() {
    Artisan::call('cache:clear');
    Artisan::call('config:clear');
    Artisan::call('route:clear');
    Artisan::call('view:clear');
    return redirect()->route('admin.dashboard')->with('cache','System Cache Has Been Removed.');
  })->name('admin.cache.clear');
//-----------------------------Clear cache end----------------

  Route::get('/login', [LoginController::class,'showLoginForm'])->name('admin.login');
  Route::post('/login', [LoginController::class,'login'])->name('admin.login.submit');
  Route::get('/forgot', [LoginController::class,'showForgotForm'])->name('admin.forgot');
  Route::post('/forgot-submit', [LoginController::class,'forgot'])->name('admin.forgot.submit');
  Route::get('/change-password/{token}', [LoginController::class,'showChangePassForm'])->name('admin.change.token');
  Route::post('/change-password', [LoginController::class,'changepass'])->name('admin.change.password');
  Route::get('/logout', [LoginController::class,'logout'])->name('admin.logout');

  Route::get('/profile', [DashboardController::class,'profile'])->name('admin.profile');
  Route::post('/profile/update', [DashboardController::class,'profileupdate'])->name('admin.profile.update');

  Route::get('/',[DashboardController::class,'index'])->name('admin.dashboard');
  Route::get('/password', [DashboardController::class,'passwordreset'])->name('admin.password');
  Route::post('/password/update', [DashboardController::class,'changepass'])->name('admin.password.update');

  //------------Order Area Start----------------------
  Route::get('/orders/datatables', [OrderController::class,'datatables'])->name('admin.order.datatables');
  Route::get('/orders', [OrderController::class,'index'])->name('admin.order.index');
  Route::get('/order/{id}/show', [OrderController::class,'show'])->name('admin.order.show');
  Route::get('/order/{id}/invoice', [OrderController::class,'invoice'])->name('admin.order.invoice');
  Route::get('/order/{id}/print', [OrderController::class,'printpage'])->name('admin.order.print');
  Route::post('/order/email/', [OrderController::class,'emailsub'])->name('admin.order.emailsub');
  Route::post('/order/{id}/license', [OrderController::class,'license'])->name('admin.order.license');
  //------------Order Area End------------------------


  //------------------------------Plan PAGE----------------------
  Route::get('/plan/datatables', [PlanController::class,'datatables'])->name('admin.plan.datatables');
  Route::get('/plans', [PlanController::class,'index'])->name('admin.plan.index');
  Route::get('/plan/create', [PlanController::class,'create'])->name('admin.plan.create');
  Route::post('/plan/store', [PlanController::class,'store'])->name('admin.plan.store');
  Route::get('/plan/edit/{id}', [PlanController::class,'edit'])->name('admin.plan.edit');
  Route::post('/plan/update/{id}', [PlanController::class,'update'])->name('admin.plan.update');
  Route::get('/plan/delete/{id}', [PlanController::class,'destroy'])->name('admin.plan.delete');
  // ---------------------------Plan page end---------------------

  // ------------------------Refund Order Start--------------------
  Route::get('/refund-orders/datatables', [RefundController::class,'datatables'])->name('admin.refund.datatables');
  Route::get('/refund-orders', [RefundController::class,'index'])->name('admin.refund.index');
  Route::get('/refund/{id}/show', [RefundController::class,'show'])->name('admin.refund.details');


  // ------------------------Refund Order End--------------------


  //------------ ADMIN GENERAL SETTINGS SECTION ------------
  Route::get('/general-settings/logo', [GeneralSettingController::class,'logo'])->name('admin.gs.logo');
  Route::get('/general-settings/favicon', [GeneralSettingController::class,'fav'])->name('admin.gs.fav');
  Route::get('/general-settings/loader', [GeneralSettingController::class,'load'])->name('admin.gs.load');
  Route::post('/general-settings/update/all', [GeneralSettingController::class,'generalupdate'])->name('admin.gs.update');
  Route::get('/general-settings/contents', [GeneralSettingController::class,'contents'])->name('admin.gs.contents');
  Route::get('/general-settings/theme', [GeneralSettingController::class,'theme'])->name('admin.gs.theme');

  Route::get('/general-settings/breadcumb', [GeneralSettingController::class,'breadcumb'])->name('admin.gs.breadcumb');
  Route::get('/general-settings/menu-builder', [GeneralSettingController::class,'menubuilder'])->name('admin.gs.menubuilder');
  Route::get('/general-settings/status/{field}/{status}', [GeneralSettingController::class,'status'])->name('admin.gs.status');
  Route::get('/general-settings/footer', [GeneralSettingController::class,'footer'])->name('admin.gs.footer');
  Route::get('/general-settings/affilate', [GeneralSettingController::class,'affilate'])->name('admin.gs.affilate');
  Route::get('/general-settings/error-banner', [GeneralSettingController::class,'errorbanner'])->name('admin.gs.error.banner');
  Route::get('/general-settings/popup', [GeneralSettingController::class,'popup'])->name('admin.gs.popup');
  Route::get('/general-settings/maintenance', [GeneralSettingController::class,'maintain'])->name('admin.gs.maintenance');
  //------------ ADMIN GENERAL SETTINGS JSON SECTION ENDS------------



  //------------ ADMIN EMAIL SETTINGS SECTION ------------
  Route::get('/email-templates/datatables', [EmailController::class,'datatables'])->name('admin.mail.datatables');
  Route::get('/email-templates', [EmailController::class,'index'])->name('admin.mail.index');
  Route::get('/email-templates/{id}', [EmailController::class,'edit'])->name('admin.mail.edit');
  Route::post('/email-templates/{id}', [EmailController::class,'update'])->name('admin.mail.update');
  Route::get('/email-config', [EmailController::class,'config'])->name('admin.mail.config');
  Route::get('/groupemail', [EmailController::class,'groupemail'])->name('admin.group.show');
  Route::post('/groupemailpost', [EmailController::class,'groupemailpost'])->name('admin.group.submit');
  //------------ ADMIN EMAIL SETTINGS SECTION ENDS ------------

  Route::get('/check/movescript', 'Admin\DashboardController@movescript')->name('admin-move-script');
  Route::get('/generate/backup', 'Admin\DashboardController@generate_bkup')->name('admin-generate-backup');
  Route::get('/activation', 'Admin\DashboardController@activation')->name('admin-activation-form');
  Route::post('/activation', 'Admin\DashboardController@activation_submit')->name('admin-activate-purchase');
  Route::get('/clear/backup', 'Admin\DashboardController@clear_bkup')->name('admin-clear-backup');


  // ------------ ROLE SECTION ----------------------
  Route::get('/role/datatables', [RoleController::class,'datatables'])->name('admin.role.datatables');
  Route::get('/role', [RoleController::class,'index'])->name('admin.role.index');
  Route::get('/role/create', [RoleController::class,'create'])->name('admin.role.create');
  Route::post('/role/create', [RoleController::class,'store'])->name('admin.role.store');
  Route::get('/role/edit/{id}', [RoleController::class,'edit'])->name('admin.role.edit');
  Route::post('/role/edit/{id}', [RoleController::class,'update'])->name('admin.role.update');
  Route::get('/role/delete/{id}', [RoleController::class,'destroy'])->name('admin.role.delete');
  // ------------ ROLE SECTION ENDS ----------------------


  //------------ ADMIN BLOG SECTION ------------
  Route::get('/blog/datatables', [BlogController::class,'datatables'])->name('admin.blog.datatables'); //JSON REQUEST
  Route::get('/blog', [BlogController::class,'index'])->name('admin.blog.index');
  Route::get('/blog/create', [BlogController::class,'create'])->name('admin.blog.create');
  Route::post('/blog/create', [BlogController::class,'store'])->name('admin.blog.store');
  Route::get('/blog/edit/{id}', [BlogController::class,'edit'])->name('admin.blog.edit');
  Route::post('/blog/edit/{id}', [BlogController::class,'update'])->name('admin.blog.update');
  Route::get('/blog/delete/{id}', [BlogController::class,'destroy'])->name('admin.blog.delete');

  Route::get('/blog/category/datatables', [BlogCategoryController::class,'datatables'])->name('admin.cblog.datatables'); //JSON REQUEST
  Route::get('/blog/category', [BlogCategoryController::class,'index'])->name('admin.cblog.index');
  Route::get('/blog/category/create', [BlogCategoryController::class,'create'])->name('admin.cblog.create');
  Route::post('/blog/category/create', [BlogCategoryController::class,'store'])->name('admin.cblog.store');
  Route::get('/blog/category/edit/{id}', [BlogCategoryController::class,'edit'])->name('admin.cblog.edit');
  Route::post('/blog/category/edit/{id}', [BlogCategoryController::class,'update'])->name('admin.cblog.update');
  Route::get('/blog/category/delete/{id}', [BlogCategoryController::class,'destroy'])->name('admin.cblog.delete');
  //------------ ADMIN BLOG SECTION ENDS ------------

  //------------ ADMIN SLIDEER SECTION ------------
  Route::get('/slider/datatables', [SliderController::class,'datatables'])->name('admin.slider.datatables'); //JSON REQUEST
  Route::get('/slider', [SliderController::class,'index'])->name('admin.slider.index');
  Route::get('/slider/create', [SliderController::class,'create'])->name('admin.slider.create');
  Route::post('/slider/create', [SliderController::class,'store'])->name('admin.slider.store');
  Route::get('/slider/edit/{id}', [SliderController::class,'edit'])->name('admin.slider.edit');
  Route::post('/slider/update/{id}', [SliderController::class,'update'])->name('admin.slider.update');
  Route::get('/slider/delete/{id}', [SliderController::class,'destroy'])->name('admin.slider.delete');
  //------------ ADMIN SLIDEER SECTION ENDS ------------
    
  //------------ ADMIN FEATURE SECTION ------------
  Route::get('/feature/datatables', [FeatureController::class,'datatables'])->name('admin.feature.datatables'); //JSON REQUEST
  Route::get('/feature', [FeatureController::class,'index'])->name('admin.feature.index');
  Route::get('/feature/create', [FeatureController::class,'create'])->name('admin.feature.create');
  Route::post('/feature/create', [FeatureController::class,'store'])->name('admin.feature.store');
  Route::get('/feature/edit/{id}', [FeatureController::class,'edit'])->name('admin.feature.edit');
  Route::post('/feature/update/{id}', [FeatureController::class,'update'])->name('admin.feature.update');
  Route::get('/feature/delete/{id}', [FeatureController::class,'destroy'])->name('admin.feature.delete');
  //------------ ADMIN FEATURE SECTION ENDS ------------


  //------------ ADMIN SERVICE SECTION ------------
  Route::get('/service/datatables', [ServiceController::class,'datatables'])->name('admin.service.datatables'); //JSON REQUEST
  Route::get('/service', [ServiceController::class,'index'])->name('admin.service.index');
  Route::get('/service/create', [ServiceController::class,'create'])->name('admin.service.create');
  Route::post('/service/store', [ServiceController::class,'store'])->name('admin.service.store');
  Route::get('/service/edit/{id}', [ServiceController::class,'edit'])->name('admin.service.edit');
  Route::post('/service/edit/{id}', [ServiceController::class,'update'])->name('admin.service.update');  
  Route::get('/service/delete/{id}', [ServiceController::class,'destroy'])->name('admin.service.delete'); 
  //------------ ADMIN SERVICE SECTION ENDS ------------


  //------------ ADMIN MENU PAGE SETTINGS SECTION ------------
  Route::get('/page-settings/contact', [PageSettingController::class,'contact'])->name('admin.ps.contact');
  Route::get('/page-settings/about', [PageSettingController::class,'about'])->name('admin.ps.about');
  Route::get('/page-settings/top/service', [PageSettingController::class,'topservice'])->name('admin.ps.topservice');
  Route::get('/page-settings/top/footer', [PageSettingController::class,'footertop'])->name('admin.ps.footertop');
  Route::post('/page-settings/contact/update', [PageSettingController::class,'contactupdate'])->name('admin.ps.contactupdate');
  Route::post('/page-settings/update/all', [PageSettingController::class,'update'])->name('admin.ps.update');
  Route::get('/homepage-settings/hero-section', [PageSettingController::class,'herosection'])->name('admin.ps.hero');
  Route::get('/homepage-settings/recent-theme-section', [PageSettingController::class,'checkouttheme'])->name('admin.ps.recent');
  Route::get('/homepage-settings/featured-theme-section', [PageSettingController::class,'featuredtheme'])->name('admin.ps.featured');
  Route::get('/homepage-settings/blog-section', [PageSettingController::class,'blogsection'])->name('admin.ps.blog');
  Route::get('/homepage-settings/newsletter-section', [PageSettingController::class,'newsletter'])->name('admin.ps.newsletter');
  //------------ ADMIN PAGE SECTION ------------


  Route::get('/page/datatables', [PageController::class,'datatables'])->name('admin.page.datatables'); //JSON REQUEST
  Route::get('/page', [PageController::class,'index'])->name('admin.page.index');
  Route::get('/page/create', [PageController::class,'create'])->name('admin.page.create');
  Route::post('/page/create', [PageController::class,'store'])->name('admin.page.store');
  Route::get('/page/edit/{id}', [PageController::class,'edit'])->name('admin.page.edit');
  Route::post('/page/update/{id}', [PageController::class,'update'])->name('admin.page.update');
  Route::get('/page/delete/{id}', [PageController::class,'destroy'])->name('admin.page.delete');
  Route::get('/page/status/{id1}/{id2}', [PageController::class,'status'])->name('admin.page.status');


  //------------------------------FAQ PAGE----------------------
  Route::get('/faq/datatables', [FaqController::class,'datatables'])->name('admin.faq.datatables');
  Route::get('/admin-faq', [FaqController::class,'index'])->name('admin.faq.index');
  Route::get('/faq/create', [FaqController::class,'create'])->name('admin.faq.create');
  Route::get('/faq/edit/{id}', [FaqController::class,'edit'])->name('admin.faq.edit');
  Route::get('/faq/delete/{id}', [FaqController::class,'destroy'])->name('admin.faq.delete');
  Route::post('/faq/update/{id}', [FaqController::class,'update'])->name('admin.faq.update');
  Route::post('/faq/create', [FaqController::class,'store'])->name('admin.faq.store');
  // ---------------------------Faq page end---------------------


  //------------ ADMIN SOCIAL SETTINGS SECTION ------------
  Route::get('/social', [SocialSettingController::class,'index'])->name('admin.social.index');
  Route::post('/social/update', [SocialSettingController::class,'socialupdate'])->name('admin.social.update');
  Route::post('/social/update/all', [SocialSettingController::class,'socialupdateall'])->name('admin.social.update.all');
  Route::get('/social/facebook', [SocialSettingController::class,'facebook'])->name('admin.social.facebook');
  Route::get('/social/google', [SocialSettingController::class,'google'])->name('admin.social.google');
  Route::get('/social/facebook/{status}', [SocialSettingController::class,'facebookup'])->name('admin.social.facebookup');
  Route::get('/social/google/{status}', [SocialSettingController::class,'googleup'])->name('admin.social.googleup');
  //------------ ADMIN SOCIAL SETTINGS SECTION ENDS------------


  //------------ ADMIN SEOTOOL SETTINGS SECTION ------------
  Route::get('/seotools/analytics', [SeoToolController::class,'analytics'])->name('admin.seotool.analytics');
  Route::post('/seotools/analytics/update', [SeoToolController::class,'analyticsupdate'])->name('admin.seotool.analytics.update');
  Route::get('/seotools/keywords', [SeoToolController::class,'keywords'])->name('admin.seotool.keywords');
  Route::post('/seotools/keywords/update', [SeoToolController::class,'keywordsupdate'])->name('admin.seotool.keywords.update');
  Route::get('/products/popular/{id}',[SeoToolController::class,'popular'])->name('admin.prod.popular');
  //------------ ADMIN SEOTOOL SETTINGS SECTION ------------

  //---------------Category Section--------------
  Route::get('/category/datatables',[CategoryController::class,'datatables'])->name('admin.cat.datatables');
  Route::get('/category',[CategoryController::class,'index'])->name('admin.cat.index');
  Route::get('/category/create', [CategoryController::class,'create'])->name('admin.cat.create');
  Route::post('/category/store', [CategoryController::class,'store'])->name('admin.cat.store');
  Route::get('/category/edit/{id}',[CategoryController::class,'edit'])->name('admin.cat.edit');
  Route::post('/category/update/{id}',[CategoryController::class,'update'])->name('admin.cat.update');
  Route::get('/category/delete/{id}',[CategoryController::class,'destroy'])->name('admin.cat.delete');
  Route::get('/category/status/{id1}/{id2}',[CategoryController::class,'status'])->name('admin.cat.status');
  //---------------Category Section End--------------



   //-----------------------------Paymentys Information----------------------------

   Route::post('/general-settings/update/all',[ GeneralSettingController::class,'generalupdate'])->name('admin.gs.update');
   Route::get('/payment/information', [PaymentGatewayController::class,'paymentinfo'])->name('admin.payment.info');
   Route::get('/paymentgateway/datatables', [PaymentGatewayController::class,'datatables'])->name('admin.payment.datatables'); //JSON REQUEST
   Route::get('/paymentgateway', [PaymentGatewayController::class,'index'])->name('admin.payment.index');
   Route::get('/paymentgateway/create', [PaymentGatewayController::class,'create'])->name('admin.payment.create');
   Route::post('/paymentgateway/create', [PaymentGatewayController::class,'store'])->name('admin.payment.store');
   Route::get('/paymentgateway/edit/{id}', [PaymentGatewayController::class,'edit'])->name('admin.payment.edit');
   Route::post('/paymentgateway/update/{id}', [PaymentGatewayController::class,'update'])->name('admin.payment.update');
   Route::delete('/paymentgateway/delete/{id}', [PaymentGatewayController::class,'destroy'])->name('admin.payment.delete');
   Route::get('/paymentgateway/status/{id1}/{id2}', [PaymentGatewayController::class,'status'])->name('admin.payment.status');



  Route::get('/general-settings/currency/{status}', [GeneralSettingController::class,'currency'])->name('admin.gs.iscurrency');
  Route::get('/currency/datatables', [CurrencyController::class,'datatables'])->name('admin.currency.datatables'); //JSON REQUEST
  Route::get('/currency',[ CurrencyController::class,'index'])->name('admin.currency.index');
  Route::get('/currency/create', [CurrencyController::class,'create'])->name('admin.currency.create');
  Route::post('/currency/create', [CurrencyController::class,'store'])->name('admin.currency.store');
  Route::get('/currency/edit/{id}', [CurrencyController::class,'edit'])->name('admin.currency.edit');
  Route::post('/currency/update/{id}', [CurrencyController::class,'update'])->name('admin.currency.update');
  Route::get('/currency/delete/{id}', [CurrencyController::class,'destroy'])->name('admin.currency.delete');
  Route::get('/currency/status/{id1}/{id2}', [CurrencyController::class,'status'])->name('admin.currency.status');
   //-----------------------------Payment Informations End-------------------------

  //------------ ADMIN STAFF SECTION ------------
  Route::get('/staff/datatables', [StaffController::class,'datatables'])->name('admin.staff.datatables');
  Route::get('/staff', [StaffController::class,'index'])->name('admin.staff.index');
  Route::get('/staff/create', [StaffController::class,'create'])->name('admin.staff.create');
  Route::post('/staff/create', [StaffController::class,'store'])->name('admin.staff.store');
  Route::get('/staff/edit/{id}', [StaffController::class,'edit'])->name('admin.staff.edit');
  Route::post('/staff/update/{id}', [StaffController::class,'update'])->name('admin.staff.update');
  Route::get('/staff/delete/{id}', [StaffController::class,'destroy'])->name('admin.staff.delete');
  //------------ ADMIN STAFF SECTION ENDS------------




  Route::get('/general-settings/language/{status}',[ GeneralSettingController::class,'language'])->name('admin.gs.islanguage');


  Route::get('/languages/datatables', [LanguageController::class,'datatables'])->name('admin.lang.datatables');
  Route::get('/languages', [LanguageController::class,'index'])->name('admin.lang.index');
  Route::get('/languages/create', [LanguageController::class,'create'])->name('admin.lang.create');
  Route::get('/languages/edit/{id}', [LanguageController::class,'edit'])->name('admin.lang.edit');
  Route::post('/languages/create', [LanguageController::class,'store'])->name('admin.lang.store');
  Route::post('/languages/edit/{id}', [LanguageController::class,'update'])->name('admin.lang.update');
  Route::get('/languages/status/{id1}/{id2}', [LanguageController::class,'status'])->name('admin.lang.st');
  Route::get('/languages/delete/{id}',[ LanguageController::class,'destroy'])->name('admin.lang.delete');


  //------------ ADMIN PANEL LANGUAGE SETTINGS SECTION ------------
  Route::get('/adminlanguages/datatables', [AdminLanguageController::class,'datatables'])->name('admin.tlang.datatables');
  Route::get('/adminlanguages', [AdminLanguageController::class,'index'])->name('admin.tlang.index');
  Route::get('/adminlanguages/create', [AdminLanguageController::class,'create'])->name('admin.tlang.create');
  Route::get('/adminlanguages/edit/{id}', [AdminLanguageController::class,'edit'])->name('admin.tlang.edit');
  Route::post('/adminlanguages/create', [AdminLanguageController::class,'store'])->name('admin.tlang.store');
  Route::post('/adminlanguages/edit/{id}', [AdminLanguageController::class,'update'])->name('admin.tlang.update');
  Route::get('/adminlanguages/status/{id1}/{id2}', [AdminLanguageController::class,'status'])->name('admin.tlang.st');
  Route::get('/adminlanguages/delete/{id}', [AdminLanguageController::class,'destroy'])->name('admin.tlang.delete');
  //------------ ADMIN PANEL LANGUAGE SETTINGS SECTION ENDS ------------



  //------------Sitemap Section---------------------
  Route::get('/sitemap/datatables', [SitemapController::class,'datatables'])->name('admin.sitemap.datatables');
  Route::get('/sitemap',[SitemapController::class,'index'])->name('admin.sitemap.index');
  Route::get('/sitemap/create',[SitemapController::class,'create'])->name('admin.sitemap.create');
  Route::post('/sitemap/store', [SitemapController::class,'store'])->name('admin.sitemap.store');
  Route::get('/sitemap/{id}/update',[SitemapController::class,'update'])->name('admin.sitemap.update');
  Route::get('/sitemap/{id}/delete', [SitemapController::class,'delete'])->name('admin.sitemap.delete');
  Route::post('/sitemap/download', [SitemapController::class,'download'])->name('admin.sitemap.download');
  //------------Sitemap Section End---------------------


  //------------ ADMIN FONT SECTION ------------
  Route::get('/fonts/datatables', [FontController::class,'datatables'])->name('admin.font.datatables');
  Route::get('/fonts', [FontController::class,'index'])->name('admin.font.index');
  Route::get('/font/create', [FontController::class,'create'])->name('admin.font.create');
  Route::post('/font/store', [FontController::class,'store'])->name('admin.font.store');
  Route::get('/font/edit/{id}', [FontController::class,'edit'])->name('admin.font.edit');
  Route::post('/font/update/{id}', [FontController::class,'update'])->name('admin.font.update');
  Route::get('/font/status/{id1}/{id2}', [FontController::class,'status'])->name('admin.font.status');
  Route::get('/font/delete/{id}', [FontController::class,'destroy'])->name('admin.font.delete');
  //------------ ADMIN FONT SECTION ENDS------------


  //------------ ADMIN SUBSCRIBERS SECTION ------------
  Route::get('/subscribers/datatables', [SubscriberController::class,'datatables'])->name('admin.subs.datatables'); //JSON REQUEST
  Route::get('/subscribers', [SubscriberController::class,'index'])->name('admin.subs.index');
  Route::get('/subscribers/download', [SubscriberController::class,'download'])->name('admin.subs.download');
  //------------ ADMIN SUBSCRIBERS ENDS --------------


  //------------ ADMIN USER SECTION ------------
  Route::get('/users/bonus', 'Admin\BonusController@index')->name('admin.user.bonus');
  Route::post('/users/edit/', 'Admin\BonusController@update')->name('admin.bonus.update');
  Route::get('/users/datatables', 'Admin\UserController@datatables')->name('admin-user-datatables'); //JSON REQUEST
  Route::get('/users', 'Admin\UserController@index')->name('admin.user.index');
  Route::get('/users/withdraws', 'Admin\UserController@withdraws')->name('admin.withdraw.index');
  Route::get('/users/withdraws/datatables', 'Admin\UserController@withdrawdatatables')->name('admin.withdraw.datatables');
  Route::get('/users/withdraw/{id}/show', 'Admin\UserController@withdrawdetails')->name('admin.withdraw.show');
  Route::get('/users/withdraws/accept/{id}', 'Admin\UserController@accept')->name('admin-withdraw-accept');
  Route::get('/users/withdraws/reject/{id}', 'Admin\UserController@reject')->name('admin-withdraw-reject');
  Route::get('/users/edit/{id}', 'Admin\UserController@edit')->name('admin-user-edit');
  Route::post('/users/edit/{id}', 'Admin\UserController@update')->name('admin-user-update');
  Route::get('/users/delete/{id}', 'Admin\UserController@destroy')->name('admin-user-delete');
  Route::get('/user/{id}/show', 'Admin\UserController@show')->name('admin-user-show');
  Route::get('/users/ban/{id1}/{id2}', 'Admin\UserController@ban')->name('admin-user-ban');
  Route::get('/user/default/image', 'Admin\UserController@image')->name('admin-user-image');
  Route::get('/users/deposit/{id}', 'Admin\UserController@deposit')->name('admin-user-deposit');
  Route::post('/user/deposit/{id}', 'Admin\UserController@depositUpdate')->name('admin-user-deposit-update');



  Route::post('/user/send/message', 'Admin\MessageController@usercontact')->name('admin-send-message');
  Route::get('/user/ticket','Admin\MessageController@index')->name('admin.user.message');
  Route::get('/messages/datatables/', 'Admin\MessageController@datatables')->name('admin.message.datatables');
  Route::get('/message/{id}', 'Admin\MessageController@message')->name('admin.message.show');
  Route::get('/message/{id}/delete', 'Admin\MessageController@messagedelete')->name('admin.message.delete');
  Route::post('/message/post', 'Admin\MessageController@postmessage')->name('admin.message.store');
  Route::get('/message/load/{id}', 'Admin\MessageController@messageshow')->name('admin-message-load');
//------------ ADMIN USER SECTION ENDS ------------

});

// user controller start

Route::prefix('user')->group(function(){

  Route::get('/forgot', 'User\ForgotController@showforgotform')->name('user.forgot');
  Route::post('/forgot', 'User\ForgotController@forgot')->name('user.forgot.submit');

  Route::group(['middleware' => 'guest'], function() {
    Route::get('/loginform','User\LoginController@loginform')->name('user.login');
    Route::post('/login','User\LoginController@login')->name('user.login.submit');
    Route::get('/registerform','User\RegisterController@registerform')->name('user.register');
    Route::post('/register','User\RegisterController@register')->name('user.register.submit');
    Route::get('/register/verify/{token}', 'User\RegisterController@token')->name('user.register.token');

  });

  Route::get('/wishlist/add/{slug}','User\WishlistController@addwish')->name('user.wishlist.add')->middleware('banuser');

  Route::group(['middleware' => 'auth'], function() {
    Route::get('/Dashboard','User\DashboardController@index')->name('user.dashboard')->middleware('banuser');;
    Route::post('dashboard/update/{id}', 'User\DashboardController@dashboard')->name('user.dashboard.update');

    Route::post('/contact',[AppDashboardController::class,'contact'])->name('user.contact');

    Route::post('/social-setting/{id}','User\DashboardController@social')->name('user.social');


    // Ticke Route User
    Route::post('admin/user/send/message', 'User\MessageController@adminusercontact')->name('user.send.message');
    Route::get('/ticket', 'User\MessageController@adminmessages')->name('user.ticket');
    Route::get('admin/message/{id}', 'User\MessageController@adminmessage')->name('user.message.show');
    Route::get('admin/message/{id}/delete', 'User\MessageController@adminmessagedelete')->name('user.message.delete1');
    Route::get('admin/message/load/{id}', 'User\MessageController@messageload')->name('user.message.load');
    Route::post('admin/message/post', 'User\MessageController@adminpostmessage')->name('user.message.store');
    // Ticket route user end

    Route::post('/changepassword', 'User\DashboardController@changepassword')->name('user.change.password');
    Route::get('/wishlists', 'User\WishlistController@wishlists')->name('user.wishlists');
    Route::get('/wishlist/remove/{id}','User\WishlistController@removewish')->name('user.wishlist.remove');
    //User Dashboard
    Route::get('/logout','User\LoginController@logout')->name('user.logout.submit');
    Route::get('/followers','User\DashboardController@followers')->name('user.followers');
    Route::get('/following','User\DashboardController@following')->name('user.following');
    Route::get('/settings','User\DashboardController@setting')->name('user.setting');
    Route::get('/downloads','User\DashboardController@download')->name('user.download');
    Route::post('/download', 'User\DashboardController@getfile')->name('user.file');
    Route::get('/hidden-items','User\DashboardController@hiddenitem')->name('user.hidden.items');
    Route::get('/reviews','User\DashboardController@reviews')->name('user.reviews');

    //User Product Purchase History Start
    Route::get('/purchase/history','User\DashboardController@purchasehistory')->name('user.history');
    Route::get('/purchase/details/{id}','User\DashboardController@purchasedetails')->name('user.purchase.details');
    Route::post('/refund/{id}','User\DashboardController@refund')->name('user.refund');
    //User Product Purchase History

    //User Refund Request Start

    Route::get('/refunds','User\DashboardController@refundlist')->name('user.allrefund');
    Route::get('/refund/details/{id}','User\DashboardController@refunddetails')->name('user.refund.details');
    Route::get('/refund/approve/{id}','User\DashboardController@refund_approve')->name('user.refund.approve');
    Route::get('/refund/decline/{id}','User\DashboardController@refund_decline')->name('user.refund.decline');
    Route::post('/refund-reply/{id}','User\DashboardController@refund_reply')->name('user.refund.reply');
    //User Refund Request End
    Route::get('/withdraw','User\WithdrawController@index')->name('user.withdraw');
    Route::get('/withdraw/request', 'User\WithdrawController@request')->name('user.withdraw.request');
    Route::post('/withdraw/request/send', 'User\WithdrawController@sendRequest')->name('user.withdraw.request.send');
    Route::get('/statements','User\DashboardController@statements')->name('user.statements');
    Route::get('/order/{id}/invoice', 'User\DashboardController@invoice')->name('user.invoice');
    Route::get('/order/{id}/print','User\DashboardController@printpage')->name('user.invoice.print');
    Route::post('/update/{id}', 'User\DashboardController@update')->name('user.profile.update');


    Route::get('/become/author','User\DashboardController@become_author')->name('user.become.author');
    Route::post('/author/store/', 'User\DashboardController@author')->name('user.author.submit');

    Route::get('/checkout', 'Frontend\CheckoutController@index')->name('front.checkout.index')->middleware('banuser');

    Route::get('/checkout/payment/{slug1}/{slug2}','Frontend\CheckoutController@loadpayment')->name('front.load.payment');
    Route::get('/checkout/payment/return', 'Frontend\CheckoutController@payreturn')->name('front.payment.return');
    Route::get('/checkout/payment/cancle', 'Frontend\CheckoutController@paycancle')->name('front.payment.cancle');

    Route::post('/checkout/payment/paypal-submit', 'Checkout\PaypalController@store')->name('front.paypal.submit');
    Route::get('/checkout/payment/paypal-notify', 'Checkout\PaypalController@notify')->name('front.paypal.notify');


    Route::post('/checkout/payment/instamojo-submit', 'Checkout\InstamojoController@store')->name('front.instamojo.submit');
    Route::get('/checkout/payment/instamojo-notify', 'Checkout\InstamojoController@notify')->name('front.instamojo.notify');

    Route::post('/checkout/payment/stripe-submit', 'Checkout\StripeController@store')->name('front.stripe.submit');

  });

});

    // ----------------- In Last Of Admin


    // ------------------ MID ----------------------
    Route::post('the/genius/ocean/2441139', 'Frontend\FrontendController@subscription');
    Route::get('finalize', 'Frontend\FrontendController@finalize');


    Route::get('/', 'Frontend\FrontendController@index')->name('front.index');
   
    Route::get('blogs', 'Frontend\FrontendController@blog')->name('front.blog');
    Route::get('blog/{slug}', 'Frontend\FrontendController@blogdetails')->name('blog.details');
    Route::get('/blog-search','Frontend\FrontendController@blogsearch')->name('front.blogsearch');
    Route::get('/blog/category/{slug}','Frontend\FrontendController@blogcategory')->name('front.blogcategory');
    Route::get('/blog/tag/{slug}','Frontend\FrontendController@blogtags')->name('front.blogtags');
    Route::get('/blog/archive/{slug}','Frontend\FrontendController@blogarchive')->name('front.blogarchive');


    Route::get('/pricing-plan','Frontend\FrontendController@plan')->name('front.pricing');
    Route::get('/services','Frontend\FrontendController@services')->name('front.services');


    Route::get('/user/portfolio/{slug}','Frontend\FrontendController@portfolio')->name('user.portfolio');
    Route::get('/contact', 'Frontend\FrontendController@contact')->name('front.contact');
    Route::post('/contact','Frontend\FrontendController@contactemail')->name('front.contact.submit');
    Route::get('/faq', 'Frontend\FrontendController@faq')->name('front.faq');
    Route::get('/{slug}','Frontend\FrontendController@page')->name('front.page');
 

    Route::get('/currency/{id}', 'Frontend\FrontendController@currency')->name('front.currency');
    Route::get('/language/{id}', 'Frontend\FrontendController@language')->name('front.language');

