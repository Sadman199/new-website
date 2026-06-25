<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Front\HomeController;
use App\Http\Controllers\Front\AboutController;
use App\Http\Controllers\Front\ContactController;
use App\Http\Controllers\Front\FaqController;
use App\Http\Controllers\Front\TermsController;
use App\Http\Controllers\Front\PrivacyController;
use App\Http\Controllers\Front\DisclaimerController;
use App\Http\Controllers\Front\LoginController;
use App\Http\Controllers\Front\PostController;
use App\Http\Controllers\Front\SubCategoryController;
use App\Http\Controllers\Front\PhotoController;
use App\Http\Controllers\Front\VideoController;
use App\Http\Controllers\Front\SubscriberController;
use App\Http\Controllers\Front\PollController;
use App\Http\Controllers\Front\ArchiveController;
use App\Http\Controllers\Front\TagController;
use App\Http\Controllers\Front\LanguageController;
use App\Http\Controllers\Front\BrokerController;
use App\Http\Controllers\Front\BonusController;
use App\Http\Controllers\Front\BrokerTypeController;
use App\Http\Controllers\Front\BrokerCountryController;
use App\Http\Controllers\Front\BrokerAccountTypeController;
use App\Http\Controllers\Front\BrokerComparisonController;
use App\Http\Controllers\Front\AllBrokerController;
use App\Http\Controllers\Front\ForexCalculatorController;

use App\Http\Controllers\Admin\AdminHomeController;
use App\Http\Controllers\Admin\AdminLoginController;
use App\Http\Controllers\Admin\AdminProfileController;
use App\Http\Controllers\Admin\AdminAdvertisementController;
use App\Http\Controllers\Admin\AdminCategoryController;
use App\Http\Controllers\Admin\AdminSubCategoryController;
use App\Http\Controllers\Admin\AdminPostController;
use App\Http\Controllers\Admin\AdminSettingController;
use App\Http\Controllers\Admin\AdminPhotoController;
use App\Http\Controllers\Admin\AdminVideoController;
use App\Http\Controllers\Admin\AdminPageController;
use App\Http\Controllers\Admin\AdminFaqController;
use App\Http\Controllers\Admin\AdminSubscriberController;
use App\Http\Controllers\Admin\AdminLiveChannelController;
use App\Http\Controllers\Admin\AdminOnlinePollController;
use App\Http\Controllers\Admin\AdminSocialItemController;
use App\Http\Controllers\Admin\AdminAuthorController;
use App\Http\Controllers\Admin\AdminLanguageController;
use App\Http\Controllers\Admin\AdminForexBonusController;
use App\Http\Controllers\Admin\AdminBrokerController;
use App\Http\Controllers\Admin\ReviewController;
use App\Http\Controllers\Admin\AccountOptionController;



use App\Http\Controllers\Author\AuthorHomeController;
use App\Http\Controllers\Author\AuthorProfileController;
use App\Http\Controllers\Author\AuthorPostController;
use App\Http\Controllers\Front\BrokerFilterController;  
use App\Http\Controllers\Front\NewsController;  


use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use App\Models\Broker;


use App\Http\Controllers\Front\AwardController;



Route::get('/broker-live-search', [BrokerController::class, 'liveSearch'])
    ->name('broker.live.search');

Route::get('/awards', [AwardController::class, 'index'])->name('awards.index');

Route::get('/brokers/award/{award}', [\App\Http\Controllers\Front\BrokerController::class, 'byAward'])->name('brokers.byAward');


Route::post('/admin/broker/store', [BrokerController::class, 'store'])->name('admin_broker_store');



Route::get('/best-brokers/{slug}', 
    [BrokerController::class, 'bestBrokers']
)->name('brokers.best');


/* Front End */
Route::get('/', [HomeController::class, 'index'])->name('home');
Route::post('/language/switch', [LanguageController::class, 'switch_language'])->name('front_language');
Route::get('/subcategory-by-category/{id}', [HomeController::class, 'get_subcategory_by_category'])->name('subcategory-by-category');
Route::get('/subcategory/{slug}', [SubCategoryController::class, 'index'])->name('category');
Route::get('/brokers/high-leverage', [BrokerFilterController::class, 'highLeverageBrokers'])->name('brokers.high.leverage');
Route::get('/brokers/platform/{slug}', [BrokerFilterController::class, 'filterByPlatform'])->name('brokers.by.platform');
Route::get('/brokers/regulation/{slug}', [BrokerFilterController::class, 'filterByRegulation'])->name('brokers.by.regulation');


// View All - Latest News
Route::get('/news/latest', [NewsController::class, 'latestNews'])->name('news_latest');
// View All - Popular News
Route::get('/news/popular', [NewsController::class, 'popularNews'])->name('news_popular');


Route::get('/about', [AboutController::class, 'index'])->name('about');
Route::get('/contact', [ContactController::class, 'index'])->name('contact');
Route::post('/contact/send-email', [ContactController::class, 'send_email'])->name('contact_form_submit');
Route::get('/terms-and-conditions', [TermsController::class, 'index'])->name('terms');
Route::get('/privacy-policy', [PrivacyController::class, 'index'])->name('privacy');
Route::get('/disclaimer', [DisclaimerController::class, 'index'])->name('disclaimer');
Route::get('/insights/{subcategory_slug}/{post_slug}', [PostController::class, 'detail'])->name('news_detail');

Route::get('/broker/search', [BrokerController::class, 'search'])->name('brokers.search');


Route::get('/forex-calculator', [ForexCalculatorController::class, 'index'])->name('forex.calculator');


// Route for viewing a specific broker's details
Route::get('/insights/{slug}', [SubCategoryController::class, 'index'])->name('category');
Route::get('/broker-reviews/{slug}', [BrokerController::class, 'detail'])
    ->name('broker_detail');

// Route::get('/photo-gallery', [PhotoController::class, 'index'])->name('photo_gallery');
Route::get('/video-gallery', [VideoController::class, 'index'])->name('video_gallery');

Route::post('/archive/show', [ArchiveController::class, 'show'])->name('archive_show');
Route::get('/archive/{year}/{month}', [ArchiveController::class, 'detail'])->name('archive_detail');
Route::get('/tag/{tag_name}', [TagController::class, 'show'])->name('tag_posts_show');


// Front bonus route
Route::get('/forex-deposit-bonus', [BonusController::class, 'forexDepositBonus'])->name('forex_deposit_bonus');
Route::get('/forex-no-deposit-bonus', [BonusController::class, 'forexNoDepositBonus'])->name('forex_no_deposit_bonus');
Route::get('/forex-live-contest', [BonusController::class, 'forexLiveContest'])->name('forex_live_contest');
Route::get('/forex-demo-contest', [BonusController::class, 'forexDemoContest'])->name('forex_demo_contest');
Route::get('/forex-cashback-rebate', [BonusController::class, 'forexCashbackRebate'])->name('forex_cashback_rebate');
Route::get('/crypto-bonus-promotion', [BonusController::class, 'cryptoBonusPromotion'])->name('crypto_bonus_promotion');

Route::get('deposit-bonuses/{slug}', [BonusController::class, 'bonusDetail'])->name('deposit-bonuses.detail');
Route::get('no-deposit-bonuses/{slug}', [BonusController::class, 'bonusDetail'])->name('no-deposit-bonuses.detail');
Route::get('live-contests/{slug}', [BonusController::class, 'bonusDetail'])->name('live-contests.detail');
Route::get('demo-contests/{slug}', [BonusController::class, 'bonusDetail'])->name('demo-contests.detail');
Route::get('cashback-rebates/{slug}', [BonusController::class, 'bonusDetail'])->name('cashback-rebates.detail');
Route::get('crypto-bonuses/{slug}', [BonusController::class, 'bonusDetail'])->name('crypto-bonuses.detail');
Route::get('/bonuses/{type}', [BonusController::class, 'showBonusByType'])->name('bonuses.type');


/* Author */
Route::get('/author/home', [AuthorHomeController::class, 'index'])->name('author_home')->middleware('author:author');
Route::get('/author/edit-profile', [AuthorProfileController::class, 'index'])->name('author_profile')->middleware('author:author');
Route::post('/author/edit-profile-submit', [AuthorProfileController::class, 'profile_submit'])->name('author_profile_submit');

Route::get('/author/post/show', [AuthorPostController::class, 'show'])->name('author_post_show')->middleware('author:author');
Route::get('/author/post/create', [AuthorPostController::class, 'create'])->name('author_post_create')->middleware('author:author');
Route::post('/author/post/store', [AuthorPostController::class, 'store'])->name('author_post_store');
Route::get('/author/post/edit/{id}', [AuthorPostController::class, 'edit'])->name('author_post_edit')->middleware('author:author');
Route::post('/author/post/update/{id}', [AuthorPostController::class, 'update'])->name('author_post_update');
Route::get('/author/post/delete/{id}', [AuthorPostController::class, 'delete'])->name('author_post_delete')->middleware('author:author');
Route::get('/author/post/tag/delete/{id}/{id1}', [AuthorPostController::class, 'delete_tag'])->name('author_post_delete_tag')->middleware('author:author');


/* Admin */
Route::get('/admin/home', [AdminHomeController::class, 'index'])->name('admin_home')->middleware('admin:admin');
Route::get('/admin/login', [AdminLoginController::class, 'index'])->name('admin_login');
Route::post('/admin/login-submit', [AdminLoginController::class, 'login_submit'])->name('admin_login_submit');
Route::get('/admin/logout', [AdminLoginController::class, 'logout'])->name('admin_logout');
// Route::get('/admin/forget-password', [AdminLoginController::class, 'forget_password'])->name('admin_forget_password');
// Route::post('/admin/forget-password-submit', [AdminLoginController::class, 'forget_password_submit'])->name('admin_forget_password_submit');
// Route::get('/admin/reset-password/{token}/{email}', [AdminLoginController::class, 'reset_password'])->name('admin_reset_password');
// Route::post('/admin/reset-password-submit', [AdminLoginController::class, 'reset_password_submit'])->name('admin_reset_password_submit');

Route::get('/admin/edit-profile', [AdminProfileController::class, 'index'])->name('admin_profile')->middleware('admin:admin');
Route::post('/admin/edit-profile-submit', [AdminProfileController::class, 'profile_submit'])->name('admin_profile_submit');

Route::get('/admin/home-advertisement', [AdminAdvertisementController::class, 'home_ad_show'])->name('admin_home_ad_show')->middleware('admin:admin');
Route::post('/admin/home-advertisement-update', [AdminAdvertisementController::class, 'home_ad_update'])->name('admin_home_ad_update');

Route::get('/admin/top-advertisement', [AdminAdvertisementController::class, 'top_ad_show'])->name('admin_top_ad_show')->middleware('admin:admin');
Route::post('/admin/top-advertisement-update', [AdminAdvertisementController::class, 'top_ad_update'])->name('admin_top_ad_update');


Route::get('/admin/sidebar-advertisement-view', [AdminAdvertisementController::class, 'sidebar_ad_show'])->name('admin_sidebar_ad_show')->middleware('admin:admin');
Route::get('/admin/sidebar-advertisement-create', [AdminAdvertisementController::class, 'sidebar_ad_create'])->name('admin_sidebar_ad_create')->middleware('admin:admin');
Route::post('/admin/sidebar-advertisement-store', [AdminAdvertisementController::class, 'sidebar_ad_store'])->name('admin_sidebar_ad_store');

Route::get('/admin/sidebar-advertisement-edit/{id}', [AdminAdvertisementController::class, 'sidebar_ad_edit'])->name('admin_sidebar_ad_edit')->middleware('admin:admin');
Route::post('/admin/sidebar-advertisement-update/{id}', [AdminAdvertisementController::class, 'sidebar_ad_update'])->name('admin_sidebar_ad_update');

Route::get('/admin/sidebar-advertisement-delete/{id}', [AdminAdvertisementController::class, 'sidebar_ad_delete'])->name('admin_sidebar_ad_delete')->middleware('admin:admin');

// ==== Forex Category Start ====
Route::group(['prefix' => 'admin/category', 'middleware' => 'admin:admin'], function () {
    Route::get('/show', [AdminCategoryController::class, 'show'])->name('admin_category_show');
    Route::get('/create', [AdminCategoryController::class, 'create'])->name('admin_category_create');
    Route::post('/store', [AdminCategoryController::class, 'store'])->name('admin_category_store');
    Route::get('/edit/{id}', [AdminCategoryController::class, 'edit'])->name('admin_category_edit');
    Route::put('/update/{id}', [AdminCategoryController::class, 'update'])->name('admin_category_update');
    Route::delete('/delete/{id}', [AdminCategoryController::class, 'delete'])->name('admin_category_delete');

});
// ==== Forex Category Start ====

// ==== Forex Subcategory Start ====
Route::group(['prefix' => 'admin/sub-category', 'middleware' => 'admin:admin'], function () {
    Route::get('/show', [AdminSubCategoryController::class, 'show'])->name('admin_sub_category_show');
    Route::get('/create', [AdminSubCategoryController::class, 'create'])->name('admin_sub_category_create');
    Route::post('/store', [AdminSubCategoryController::class, 'store'])->name('admin_sub_category_store');
    Route::get('/edit/{id}', [AdminSubCategoryController::class, 'edit'])->name('admin_sub_category_edit');
    Route::put('/update/{id}', [AdminSubCategoryController::class, 'update'])->name('admin_sub_category_update');
    Route::delete('/delete/{id}', [AdminSubCategoryController::class, 'delete'])->name('admin_sub_category_delete');
});
// ==== Forex Subcategory End ====

// ==== Forex Bonus Start ====
Route::group(['prefix' => 'admin/forex-bonus', 'middleware' => 'admin:admin'], function () {
    Route::get('/show', [AdminForexBonusController::class, 'show'])->name('admin_forex_bonus_show');
    Route::get('/create', [AdminForexBonusController::class, 'create'])->name('admin_forex_bonus_create');
    Route::post('/store', [AdminForexBonusController::class, 'store'])->name('admin_forex_bonus_store');
    Route::get('/edit/{id}', [AdminForexBonusController::class, 'edit'])->name('admin_forex_bonus_edit');
    Route::put('/update/{id}', [AdminForexBonusController::class, 'update'])->name('admin_forex_bonus_update');
    Route::delete('/delete/{id}', [AdminForexBonusController::class, 'delete'])->name('admin_forex_bonus_delete');
});

// ==== Forex Bonus End ====

// ==== Broker Management Start ====
Route::group(['prefix' => 'admin/broker', 'middleware' => 'admin:admin'], function () {
    Route::get('/show', [AdminBrokerController::class, 'show'])->name('admin_broker_show');
    Route::get('/create', [AdminBrokerController::class, 'create'])->name('admin_broker_create');
    Route::post('/store', [AdminBrokerController::class, 'store'])->name('admin_broker_store');
    Route::get('/edit/{id}', [AdminBrokerController::class, 'edit'])->name('admin_broker_edit');
    Route::put('/update/{id}', [AdminBrokerController::class, 'update'])->name('admin_broker_update');
    Route::delete('/delete/{id}', [AdminBrokerController::class, 'delete'])->name('admin_broker_delete');

});
// ==== Broker Management End ====

Route::group(['prefix' => 'admin/broker', 'middleware' => 'admin:admin'], function () {
    Route::get('/{broker_id}/account-options', [AccountOptionController::class, 'index'])->name('admin_account_options_index');
    Route::get('/{broker_id}/account-options/create', [AccountOptionController::class, 'create'])->name('admin_account_options_create');
    Route::post('/{broker_id}/account-options/store', [AccountOptionController::class, 'store'])->name('admin_account_options_store');
    Route::get('/{broker_id}/account-options/edit/{id}', [AccountOptionController::class, 'edit'])->name('admin_account_options_edit');
    Route::put('/{broker_id}/account-options/update/{id}', [AccountOptionController::class, 'update'])->name('admin_account_options_update');
    Route::delete('/{broker_id}/account-options/delete/{id}', [AccountOptionController::class, 'delete'])->name('admin_account_options_delete');
});

Route::group(['prefix' => 'admin/subscribers', 'middleware' => 'admin:admin'], function () {
    Route::patch('{subscriber}/accept', [AdminSubscriberController::class, 'accept'])->name('subscriber.accept');
    Route::patch('{subscriber}/decline', [AdminSubscriberController::class, 'decline'])->name('subscriber.decline');
    Route::delete('{subscriber}/delete', [AdminSubscriberController::class, 'delete'])->name('subscriber.delete');
});




Route::group(['middleware' => 'admin:admin'], function () {

    // Post-related routes
    Route::get('/admin/post/show', [AdminPostController::class, 'show'])->name('admin_post_show');
    Route::get('/admin/post/create', [AdminPostController::class, 'create'])->name('admin_post_create');
    Route::post('/admin/post/store', [AdminPostController::class, 'store'])->name('admin_post_store');
    Route::get('/admin/post/edit/{id}', [AdminPostController::class, 'edit'])->name('admin_post_edit');
    Route::match(['post', 'put'], '/admin/post/update/{id}', [AdminPostController::class, 'update'])->name('admin_post_update');


    Route::get('/admin/post/delete/{id}', [AdminPostController::class, 'delete'])->name('admin_post_delete');
    Route::get('/admin/post/tag/delete/{id}/{id1}', [AdminPostController::class, 'delete_tag'])->name('admin_post_delete_tag');

    // Setting-related routes
    Route::get('/admin/setting', [AdminSettingController::class, 'index'])->name('admin_setting');
    Route::post('/admin/setting/update', [AdminSettingController::class, 'update'])->name('admin_setting_update');

    // Photo-related routes
    Route::get('/admin/photo/show', [AdminPhotoController::class, 'show'])->name('admin_photo_show');
    Route::get('/admin/photo/create', [AdminPhotoController::class, 'create'])->name('admin_photo_create');
    Route::post('/admin/photo/store', [AdminPhotoController::class, 'store'])->name('admin_photo_store');
    Route::get('/admin/photo/edit/{id}', [AdminPhotoController::class, 'edit'])->name('admin_photo_edit');
    Route::post('/admin/photo/update/{id}', [AdminPhotoController::class, 'update'])->name('admin_photo_update');
    Route::get('/admin/photo/delete/{id}', [AdminPhotoController::class, 'delete'])->name('admin_photo_delete');

    // Video-related routes
    Route::get('/admin/video/show', [AdminVideoController::class, 'show'])->name('admin_video_show');
    Route::get('/admin/video/create', [AdminVideoController::class, 'create'])->name('admin_video_create');
    Route::post('/admin/video/store', [AdminVideoController::class, 'store'])->name('admin_video_store');
    Route::get('/admin/video/edit/{id}', [AdminVideoController::class, 'edit'])->name('admin_video_edit');
    Route::post('/admin/video/update/{id}', [AdminVideoController::class, 'update'])->name('admin_video_update');
    Route::get('/admin/video/delete/{id}', [AdminVideoController::class, 'delete'])->name('admin_video_delete');

    // Page-related routes
    Route::get('/admin/page/about', [AdminPageController::class, 'about'])->name('admin_page_about');
    Route::post('/admin/page/about/update', [AdminPageController::class, 'about_update'])->name('admin_page_about_update');

});


Route::group(['middleware' => 'admin:admin'], function () {

    // FAQ page routes
    Route::get('/admin/page/faq', [AdminPageController::class, 'faq'])->name('admin_page_faq');
    Route::post('/admin/page/faq/update', [AdminPageController::class, 'faq_update'])->name('admin_page_faq_update');

    // Terms page routes
    Route::get('/admin/page/terms', [AdminPageController::class, 'terms'])->name('admin_page_terms');
    Route::post('/admin/page/terms/update', [AdminPageController::class, 'terms_update'])->name('admin_page_terms_update');

    // Privacy page routes
    Route::get('/admin/page/privacy', [AdminPageController::class, 'privacy'])->name('admin_page_privacy');
    Route::post('/admin/page/privacy/update', [AdminPageController::class, 'privacy_update'])->name('admin_page_privacy_update');

    // Disclaimer page routes
    Route::get('/admin/page/disclaimer', [AdminPageController::class, 'disclaimer'])->name('admin_page_disclaimer');
    Route::post('/admin/page/disclaimer/update', [AdminPageController::class, 'disclaimer_update'])->name('admin_page_disclaimer_update');

    // Login page routes
    Route::get('/admin/page/login', [AdminPageController::class, 'login'])->name('admin_page_login');
    Route::post('/admin/page/login/update', [AdminPageController::class, 'login_update'])->name('admin_page_login_update');

    // Contact page routes
    Route::get('/admin/page/contact', [AdminPageController::class, 'contact'])->name('admin_page_contact');
    Route::post('/admin/page/contact/update', [AdminPageController::class, 'contact_update'])->name('admin_page_contact_update');

});


Route::get('/admin/faq/show', [AdminFaqController::class, 'show'])->name('admin_faq_show')->middleware('admin:admin');
Route::get('/admin/faq/create', [AdminFaqController::class, 'create'])->name('admin_faq_create')->middleware('admin:admin');
Route::post('/admin/faq/store', [AdminFaqController::class, 'store'])->name('admin_faq_store');
Route::get('/admin/faq/edit/{id}', [AdminFaqController::class, 'edit'])->name('admin_faq_edit')->middleware('admin:admin');
Route::post('/admin/faq/update/{id}', [AdminFaqController::class, 'update'])->name('admin_faq_update');
Route::get('/admin/faq/delete/{id}', [AdminFaqController::class, 'delete'])->name('admin_faq_delete')->middleware('admin:admin');

Route::get('/admin/subscriber/all', [AdminSubscriberController::class, 'show_all'])->name('admin_subscribers')->middleware('admin:admin');
Route::get('/admin/subscriber/send-email', [AdminSubscriberController::class, 'send_email'])->name('admin_subscriber_send_email')->middleware('admin:admin');
Route::post('/admin/subscriber/send-email-submit', [AdminSubscriberController::class, 'send_email_submit'])->name('admin_subscriber_send_email_submit');


Route::get('/admin/live-channel/show', [AdminLiveChannelController::class, 'show'])->name('admin_live_channel_show')->middleware('admin:admin');
Route::get('/admin/live-channel/create', [AdminLiveChannelController::class, 'create'])->name('admin_live_channel_create')->middleware('admin:admin');
Route::post('/admin/live-channel/store', [AdminLiveChannelController::class, 'store'])->name('admin_live_channel_store');
Route::get('/admin/live-channel/edit/{id}', [AdminLiveChannelController::class, 'edit'])->name('admin_live_channel_edit')->middleware('admin:admin');
Route::post('/admin/live-channel/update/{id}', [AdminLiveChannelController::class, 'update'])->name('admin_live_channel_update');
Route::get('/admin/live-channel/delete/{id}', [AdminLiveChannelController::class, 'delete'])->name('admin_live_channel_delete')->middleware('admin:admin');


Route::get('/admin/online-poll/show', [AdminOnlinePollController::class, 'show'])->name('admin_online_poll_show')->middleware('admin:admin');
Route::get('/admin/online-poll/create', [AdminOnlinePollController::class, 'create'])->name('admin_online_poll_create')->middleware('admin:admin');
Route::post('/admin/online-poll/store', [AdminOnlinePollController::class, 'store'])->name('admin_online_poll_store');
Route::get('/admin/online-poll/edit/{id}', [AdminOnlinePollController::class, 'edit'])->name('admin_online_poll_edit')->middleware('admin:admin');
Route::post('/admin/online-poll/update/{id}', [AdminOnlinePollController::class, 'update'])->name('admin_online_poll_update');
Route::get('/admin/online-poll/delete/{id}', [AdminOnlinePollController::class, 'delete'])->name('admin_online_poll_delete')->middleware('admin:admin');

Route::get('/admin/social-item/show', [AdminSocialItemController::class, 'show'])->name('admin_social_item_show')->middleware('admin:admin');
Route::get('/admin/social-item/create', [AdminSocialItemController::class, 'create'])->name('admin_social_item_create')->middleware('admin:admin');
Route::post('/admin/social-item/store', [AdminSocialItemController::class, 'store'])->name('admin_social_item_store');
Route::get('/admin/social-item/edit/{id}', [AdminSocialItemController::class, 'edit'])->name('admin_social_item_edit')->middleware('admin:admin');
Route::post('/admin/social-item/update/{id}', [AdminSocialItemController::class, 'update'])->name('admin_social_item_update');
Route::get('/admin/social-item/delete/{id}', [AdminSocialItemController::class, 'delete'])->name('admin_social_item_delete')->middleware('admin:admin');


Route::get('/admin/author/show', [AdminAuthorController::class, 'show'])->name('admin_author_show')->middleware('admin:admin');
Route::get('/admin/author/create', [AdminAuthorController::class, 'create'])->name('admin_author_create')->middleware('admin:admin');
Route::post('/admin/author/store', [AdminAuthorController::class, 'store'])->name('admin_author_store');
Route::get('/admin/author/edit/{id}', [AdminAuthorController::class, 'edit'])->name('admin_author_edit')->middleware('admin:admin');
Route::post('/admin/author/update/{id}', [AdminAuthorController::class, 'update'])->name('admin_author_update');
Route::get('/admin/author/delete/{id}', [AdminAuthorController::class, 'delete'])->name('admin_author_delete')->middleware('admin:admin');


Route::get('/admin/language/show', [AdminLanguageController::class, 'show'])->name('admin_language_show')->middleware('admin:admin');
Route::get('/admin/language/create', [AdminLanguageController::class, 'create'])->name('admin_language_create')->middleware('admin:admin');
Route::post('/admin/language/store', [AdminLanguageController::class, 'store'])->name('admin_language_store');
Route::get('/admin/language/edit/{id}', [AdminLanguageController::class, 'edit'])->name('admin_language_edit')->middleware('admin:admin');
Route::post('/admin/language/update/{id}', [AdminLanguageController::class, 'update'])->name('admin_language_update');
Route::get('/admin/language/delete/{id}', [AdminLanguageController::class, 'delete'])->name('admin_language_delete')->middleware('admin:admin');

Route::get('/admin/language/update-detail/{id}', [AdminLanguageController::class, 'update_detail'])->name('admin_language_update_detail')->middleware('admin:admin');
Route::post('/admin/language/update-detail-submit/{id}', [AdminLanguageController::class, 'update_detail_submit'])->name('admin_language_update_detail_submit');



Route::get('/country/{country}', [BrokerCountryController::class, 'showBrokersByCountry'])->name('broker_by_country');

Route::get('/account-type/{type}', [BrokerAccountTypeController::class, 'showByAccountType'])->name('brokers.byAccountType');



// Broker Comparison Routes
Route::get('/brokers/compare', [BrokerComparisonController::class, 'showBrokerComparison'])->name('broker.comparison');
Route::post('/brokers/compare', [BrokerComparisonController::class, 'getComparison'])->name('brokers.getComparison');

// Main comparison route with slug support
Route::get('/brokers/compare/{broker1_slug}-vs-{broker2_slug}', 
    [BrokerComparisonController::class, 'compare'])
    ->where([
        'broker1_slug' => '[a-zA-Z0-9\-]+',
        'broker2_slug' => '[a-zA-Z0-9\-]+'
    ])
    ->name('brokers.compare');

// Alternative simple comparison route
Route::get('/compare/{broker1_slug}/{broker2_slug}', 
    [BrokerComparisonController::class, 'compare'])
    ->where([
        'broker1_slug' => '[a-zA-Z0-9\-]+',
        'broker2_slug' => '[a-zA-Z0-9\-]+'
    ])
    ->name('compare');
Route::get('/brokers', [AllBrokerController::class, 'index'])->name('all_brokers');
Route::get('/brokers/filter', [AllBrokerController::class, 'filterBrokers'])->name('all_brokers_filter');


// Admin routes protected by admin middleware
Route::prefix('admin')->middleware('admin:admin')->group(function () {
    Route::get('/language/show', [AdminLanguageController::class, 'show'])->name('admin_language_show');
    Route::get('/reviews/pending', [ReviewController::class, 'pending'])->name('reviews.pending');
    Route::post('/reviews/{review}/approve', [ReviewController::class, 'approve'])->name('reviews.approve');
    Route::post('/reviews/{review}/decline', [ReviewController::class, 'decline'])->name('reviews.decline');
});

// Public route to submit review, no admin middleware
Route::post('/brokers/{broker}/reviews', [ReviewController::class, 'store'])->name('reviews.store');


Route::post('/contact', [ContactController::class, 'submitForm'])->name('contact_form_submit');
Route::get('/regulated-brokers', [BrokerTypeController::class, 'showRegulatedBrokers'])->name('regulated_brokers');
Route::get('/non-regulated-brokers', [BrokerTypeController::class, 'showNonRegulatedBrokers'])->name('non_regulated_brokers');

// Frontend Routes
Route::post('/subscribe', [SubscriberController::class, 'subscribe'])->name('subscribe');
Route::get('/verify-subscription/{token}/{email}', [SubscriberController::class, 'verify'])->name('subscriber_verify');
Route::get('/brokers/comparison', [HomeController::class, 'showComparisonDropdown'])->name('brokers.comparison.dropdown');





