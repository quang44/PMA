<?php

use App\Http\Controllers\AddonController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\AizUploadController;
use App\Http\Controllers\AttributeController;
use App\Http\Controllers\BlogCategoryController;
use App\Http\Controllers\BlogController;
use App\Http\Controllers\BrandController;
use App\Http\Controllers\BusinessSettingsController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\CityController;
use App\Http\Controllers\CommissionController;
use App\Http\Controllers\ConversationController;
use App\Http\Controllers\CountryController;
use App\Http\Controllers\CouponController;
use App\Http\Controllers\CurrencyController;
use App\Http\Controllers\CustomerController;
use App\Http\Controllers\CustomerPackageController;
use App\Http\Controllers\CustomerProductController;
use App\Http\Controllers\DigitalProductController;
use App\Http\Controllers\FlashDealController;
use App\Http\Controllers\LanguageController;
use App\Http\Controllers\NewsletterController;
use App\Http\Controllers\NotificationController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\PageController;
use App\Http\Controllers\PaymentController;
use App\Http\Controllers\PickupPointController;
use App\Http\Controllers\ProductBulkUploadController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\ReportController;
use App\Http\Controllers\ReviewController;
use App\Http\Controllers\RoleController;
use App\Http\Controllers\SellerController;
use App\Http\Controllers\SellerWithdrawRequestController;
use App\Http\Controllers\StaffController;
use App\Http\Controllers\StateController;
use App\Http\Controllers\SubscriberController;
use App\Http\Controllers\SupportTicketController;
use App\Http\Controllers\TaxController;
use App\Http\Controllers\UpdateController;
use App\Http\Controllers\WebsiteController;
use App\Http\Controllers\BannerController;
use App\Http\Controllers\OrderDeliveryController;
use App\Http\Controllers\CustomerBillController;
use App\Http\Controllers\PartnerBillController;
use App\Http\Controllers\BankController;

use App\Http\Controllers\WarrantyCodeController;
use \App\Http\Controllers\WarrantyCardController;

use App\Http\Controllers\CustomerGroupController;
use App\Http\Controllers\CommonConfigController;

use App\Http\Controllers\GiftController;
 use Illuminate\Http\Request;
/*
  |--------------------------------------------------------------------------
  | Admin Routes
  |--------------------------------------------------------------------------
  |
  | Here is where you can register admin routes for your application. These
  | routes are loaded by the RouteServiceProvider within a group which
  | contains the "web" middleware group. Now create something great!
  |
 */
//Update Routes
Route::controller(UpdateController::class)->group(function () {
    Route::post('/update', 'step0')->name('update');
    Route::get('/update/step1', 'step1')->name('update.step1');
    Route::get('/update/step2', 'step2')->name('update.step2');
});


Route::get('/admin', [AdminController::class, 'admin_dashboard'])->name('admin.dashboard')->middleware(['auth', 'admin' ]);
Route::group(['prefix' => 'admin', 'middleware' => ['auth', 'admin']], function() {
    // category
    Route::resource('categories', CategoryController::class);
    Route::controller(CategoryController::class)->group(function () {
        Route::get('/categories/edit/{id}', 'edit')->name('categories.edit');
        Route::get('/categories/destroy/{id}', 'destroy')->name('categories.destroy');
        Route::post('/categories/featured', 'updateFeatured')->name('categories.featured');

    });

    // Brand
    Route::resource('brands', BrandController::class);
    Route::controller(BrandController::class)->group(function () {
        Route::get('/brands/edit/{id}', 'edit')->name(  'brands.edit');
        Route::get('/brands/destroy/{id}', 'destroy')->name('brands.destroy');
        Route::post('/brands/update_status', 'updateStatus')->name('brands.update_status');
    });

    Route::resource('product_warranty',\App\Http\Controllers\ProductWarrantyController::class);
    Route::get('product_warranty/delete/{id}',[\App\Http\Controllers\ProductWarrantyController::class,'destroy'])->name('product_warranty.destroy');
    // Products
    Route::controller(ProductController::class)->group(function () {
        Route::get('/products/admin', 'admin_products')->name('products.admin');
        Route::get('/products/seller', 'seller_products')->name('products.seller');
        Route::get('/products/all', 'all_products')->name('products.all');
        Route::get('/products/create', 'create')->name('products.create');
        Route::post('/products/store/', 'store')->name('products.store');
        Route::get('/products/admin/{id}/edit', 'admin_product_edit')->name('products.admin.edit');
        Route::get('/products/seller/{id}/edit', 'seller_product_edit')->name('products.seller.edit');
        Route::post('/products/update/{product}', 'update')->name('products.update');
        Route::post('/products/update_status', 'updateStatus')->name('products.update_status');
        Route::post('/products/todays_deal', 'updateTodaysDeal')->name('products.todays_deal');
        Route::post('/products/featured', 'updateFeatured')->name('products.featured');
        Route::post('/products/published', 'updatePublished')->name('products.published');
        Route::post('/products/approved', 'updateProductApproval')->name('products.approved');
        Route::post('/products/get_products_by_subcategory', 'get_products_by_subcategory')->name('products.get_products_by_subcategory');
        Route::get('/products/duplicate/{id}', 'duplicate')->name('products.duplicate');
        Route::get('/products/destroy/{id}', 'destroy')->name('products.destroy');
        Route::post('/bulk-product-delete', 'bulk_product_delete')->name('bulk-product-delete');

        Route::post('/products/sku_combination', 'sku_combination')->name('products.sku_combination');
        Route::post('/products/sku_combination_edit', 'sku_combination_edit')->name('products.sku_combination_edit');
        Route::post('/products/add-more-choice-option', 'add_more_choice_option')->name('products.add-more-choice-option');

    });

    // Digital Product
//    Route::resource('digitalproducts', DigitalProductController::class);
//    Route::controller(DigitalProductController::class)->group(function () {
//        Route::get('/digitalproducts/edit/{id}', 'edit')->name('digitalproducts.edit');
//        Route::get('/digitalproducts/destroy/{id}', 'destroy')->name('digitalproducts.destroy');
//        Route::get('/digitalproducts/download/{id}', 'download')->name('digitalproducts.download');
//    });

    Route::controller(ProductBulkUploadController::class)->group(function () {
        //Product Export
        Route::get('/product-bulk-export', 'export')->name('product_bulk_export.index');

        //Product Bulk Upload
        Route::get('/product-bulk-upload/index', 'index')->name('product_bulk_upload.index');
        Route::post('/bulk-product-upload', 'bulk_upload')->name('bulk_product_upload');
        Route::get('/product-csv-download/{type}', 'import_product')->name('product_csv.download');
        Route::get('/vendor-product-csv-download/{id}', 'import_vendor_product')->name('import_vendor_product.download');
        Route::group(['prefix' => 'bulk-upload/download'], function () {
            Route::get('/category', 'pdf_download_category')->name('pdf.download_category');
            Route::get('/brand', 'pdf_download_brand')->name('pdf.download_brand');
            Route::get('/seller', 'pdf_download_seller')->name('pdf.download_seller');
        });
    });

    // Seller
//    Route::resource('sellers', SellerController::class);
//    Route::controller(SellerController::class)->group(function () {
//        Route::get('sellers_ban/{id}', 'ban')->name('sellers.ban');
//        Route::get('/sellers/destroy/{id}', 'destroy')->name('sellers.destroy');
//        Route::post('/bulk-seller-delete', 'bulk_seller_delete')->name('bulk-seller-delete');
//        Route::get('/sellers/view/{id}/verification', 'show_verification_request')->name('sellers.show_verification_request');
//        Route::get('/sellers/approve/{id}', 'approve_seller')->name('sellers.approve');
//        Route::get('/sellers/reject/{id}', 'reject_seller')->name('sellers.reject');
//        Route::get('/sellers/login/{id}', 'login')->name('sellers.login');
//        Route::post('/sellers/payment_modal', 'payment_modal')->name('sellers.payment_modal');
//        Route::post('/sellers/profile_modal', 'profile_modal')->name('sellers.profile_modal');
//        Route::post('/sellers/approved', 'updateApproved')->name('sellers.approved');
//    });

    // Seller Payment
//    Route::controller(PaymentController::class)->group(function () {
//        Route::get('/seller/payments', 'payment_histories')->name('sellers.payment_histories');
//        Route::get('/seller/payments/show/{id}', 'show')->name('sellers.payment_history');
//    });

    // Seller Withdraw Request
//    Route::resource('/withdraw_requests', SellerWithdrawRequestController::class);
//    Route::controller(SellerWithdrawRequestController::class)->group(function () {
//        Route::get('/withdraw_requests_all', 'index')->name('withdraw_requests_all');
//        Route::post('/withdraw_request/payment_modal', 'payment_modal')->name('withdraw_request.payment_modal');
//        Route::post('/withdraw_request/message_modal', 'message_modal')->name('withdraw_request.message_modal');
//    });

    // Customer
    Route::resource('customers', CustomerController::class)->only('create','update','edit','store','delete','show','index');
    Route::controller(CustomerController::class)->group(function () {
        Route::post('customers_store', 'addNewCustomer')->name('customers.addNew');
        Route::get('customers_ban/{customer}', 'ban')->name('customers.ban');
        Route::get('customers_update_bank/{customer}', 'bank')->name('customers.bank');
        Route::post('update-package', 'updatePackage')->name('customers.update_package');
        Route::get('/customers/login/{id}', 'login')->name('customers.login');
        Route::get('/customers/destroy/{id}', 'destroy')->name('customers.destroy');
        Route::get('/customers/updateToAgent/{id}', 'updateToAgent')->name('customers.updateToAgent');
        Route::get('/customers/updateToDepot/{id}', 'updateToAgent')->name('customers.updateToDepot');

        //        Route::get('customer_pay', 'customerPay')->name('customers.customer_pay');
        Route::post('/bulk-customer-delete', 'bulk_customer_delete')->name('bulk-customer-delete');
        Route::get('/historyPayment/{id}', 'historyPayment')->name('customers.historyPayment');
    });

    Route::resource('banks', BankController::class);
    Route::controller(BankController::class)->group(function () {
        Route::get('/banks/destroy/{id}', 'destroy')->name('banks.destroy');
    });

//    Route::controller(CustomerBillController::class)->group(function () {
//        Route::get('customer_bill', 'index')->name('customer_bill.index');
//        Route::get('customer_bill/{id}', 'show')->name('customer_bill.show');
//        Route::get('customer_bill/create/{user_id}', 'create')->name('customer_bill.create');
//        Route::post('customer_bill/create/{user_id}', 'store')->name('customer_bill.create');
//        Route::post('customer_bill/update-payment/{id}', 'updatePayment')->name('customer_bill.update_payment');
//        Route::post('customer_bill/cancel/{id}', 'cancel')->name('customer_bill.cancel');
//    });

//    Route::controller(PartnerBillController::class)->group(function () {
//        Route::get('partner_bill', 'index')->name('partner_bill.index');
//        Route::get('partner_bill/create', 'create')->name('partner_bill.create');
//        Route::post('partner_bill/create', 'store')->name('partner_bill.create');
//        Route::post('partner_bill/cancel/{id}', 'cancel')->name('partner_bill.cancel');
//        Route::get('partner_bill/{id}', 'show')->name('partner_bill.show');
//
//
//    });

    //       Warranty code

    Route::resource('warranty_codes',WarrantyCodeController::class)->only('index','create','store','edit','update');
    Route::group(['prefix'=>'warranty_codes'],function (){
    Route::controller(WarrantyCodeController::class)->group(function () {
     Route::get('/destroy/{id}','destroy')->name('warranty_codes.destroy');
     Route::post('/bulk-delete','bulk_warranty_code_delete')->name('warranty_codes.bulk-delete');
     Route::post('/upload', 'warrantyCodeUpload')->name('warranty_codes.upload');
     Route::get('/bulk-import', 'importWarrantyCode')->name('warranty_codes.bulk-warranty-code');
    });
    });

    //       Warranty card
    Route::get('cc', function (Request $request){
        $combinations=$request->combination;
        $products=\App\Models\Product::all();
        return view('backend.customer.warranty_cards.combinations',compact('combinations','products'));
    })->name('warranty_card.cc');

    Route::resource('warranty_card',WarrantyCardController::class);
    Route::group(['prefix'=>'warranty_card'],function (){
        Route::controller(WarrantyCardController::class)->group(function () {
            Route::post('card_combinations','card_combination')->name('warranty_card.combinations');
            Route::post('card_combinations_edit','card_combination_edit')->name('warranty_card.combinations_edit');
            Route::get('.ban/{id}', 'ban')->name('warranty_card.ban');
            Route::get('/destroy/{id}', 'destroy')->name('warranty_card.destroy');
        });
    });


//    gift
    Route::resource('gift', GiftController::class);
    Route::group(['prefix' => 'gift'], function () {
        Route::controller( GiftController::class)->group(function () {
            Route::get('ban/{id}', 'giftBan')->name('gift_ban');
            Route::get('gifts/Request', 'giftRequest')->name('gift.giftRequest');
            Route::post('updateStatus',  'updateStatus')->name('gift.update_status');
            Route::post('bulk-delete', 'bulk_delete')->name('gift.bulk-delete');
            Route::get('destroy/{id}', 'destroy')->name('gift.destroy');

        });
    });



    // Newsletter
    Route::controller(NewsletterController::class)->group(function () {
        Route::get('/newsletter', 'index')->name('newsletters.index');
        Route::post('/newsletter/send', 'send')->name('newsletters.send');
        Route::post('/newsletter/test/smtp', 'testEmail')->name('test.smtp');
    });

    Route::resource('profile', ProfileController::class);

    // Business Settings
    Route::controller(BusinessSettingsController::class)->group(function () {
        Route::post('/business-settings/update', 'update')->name('business_settings.update');
        Route::post('/business-settings/update/activation', 'updateActivationSettings')->name('business_settings.update.activation');
        Route::get('/general-setting', 'general_setting')->name('general_setting.index');
        Route::get('/activation', 'activation')->name('activation.index');
        Route::get('/payment-method', 'payment_method')->name('payment_method.index');
        Route::get('/file_system', 'file_system')->name('file_system.index');
        Route::get('/social-login', 'social_login')->name('social_login.index');
        Route::get('/smtp-settings', 'smtp_settings')->name('smtp_settings.index');
        Route::get('/google-analytics', 'google_analytics')->name('google_analytics.index');
        Route::get('/google-recaptcha', 'google_recaptcha')->name('google_recaptcha.index');
        Route::get('/google-map', 'google_map')->name('google-map.index');
        Route::get('/google-firebase', 'google_firebase')->name('google-firebase.index');

        //Facebook Settings
        Route::get('/facebook-chat', 'facebook_chat')->name('facebook_chat.index');
        Route::post('/facebook_chat', 'facebook_chat_update')->name('facebook_chat.update');
        Route::get('/facebook-comment', 'facebook_comment')->name('facebook-comment');
        Route::post('/facebook-comment', 'facebook_comment_update')->name('facebook-comment.update');
        Route::post('/facebook_pixel', 'facebook_pixel_update')->name('facebook_pixel.update');

        Route::post('/env_key_update', 'env_key_update')->name('env_key_update.update');
        Route::post('/payment_method_update', 'payment_method_update')->name('payment_method.update');
        Route::post('/google_analytics', 'google_analytics_update')->name('google_analytics.update');
        Route::post('/google_recaptcha', 'google_recaptcha_update')->name('google_recaptcha.update');
        Route::post('/google-map', 'google_map_update')->name('google-map.update');
        Route::post('/google-firebase', 'google_firebase_update')->name('google-firebase.update');

        Route::get('/verification/form', 'seller_verification_form')->name('seller_verification_form.index');
        Route::post('/verification/form', 'seller_verification_form_update')->name('seller_verification_form.update');
        Route::get('/vendor_commission', 'vendor_commission')->name('business_settings.vendor_commission');
        Route::post('/vendor_commission_update', 'vendor_commission_update')->name('business_settings.vendor_commission.update');

        //Shipping Configuration
//        Route::get('/shipping_configuration', 'shipping_configuration')->name('shipping_configuration.index');
//        Route::post('/shipping_configuration/update', 'shipping_configuration_update')->name('shipping_configuration.update');

        // Order Configuration
//        Route::get('/order-configuration', 'order_configuration')->name('order_configuration.index');
    });


    Route::resource('common_configs', CommonConfigController::class);
    Route::controller(CommonConfigController::class)->group(function () {
        Route::get('/common_configs/edit/{id}', 'edit')->name('common_configs.edit');
        Route::get('/common_configs/create', 'create')->name('common_configs.create');
    });

    //Currency
//    Route::controller(CurrencyController::class)->group(function () {
//        Route::get('/currency', 'currency')->name('currency.index');
//        Route::post('/currency/update', 'updateCurrency')->name('currency.update');
//        Route::post('/your-currency/update', 'updateYourCurrency')->name('your_currency.update');
//        Route::get('/currency/create', 'create')->name('currency.create');
//        Route::post('/currency/store', 'store')->name('currency.store');
//        Route::post('/currency/currency_edit', 'edit')->name('currency.edit');
//        Route::post('/currency/update_status', 'update_status')->name('currency.update_status');
//    });

    //Tax
    Route::resource('tax', TaxController::class);
    Route::controller(TaxController::class)->group(function () {
        Route::get('/tax/edit/{id}', 'edit')->name('tax.edit');
        Route::get('/tax/destroy/{id}', 'destroy')->name('tax.destroy');
        Route::post('tax-status', 'change_tax_status')->name('taxes.tax-status');
    });

    // Language
    Route::resource('/languages', LanguageController::class);
    Route::controller(LanguageController::class)->group(function () {
        Route::post('/languages/{id}/update', 'update')->name('languages.update');
        Route::get('/languages/destroy/{id}', 'destroy')->name('languages.destroy');
        Route::post('/languages/update_rtl_status', 'update_rtl_status')->name('languages.update_rtl_status');
        Route::post('/languages/update-status', 'update_status')->name('languages.update-status');
        Route::post('/languages/key_value_store', 'key_value_store')->name('languages.key_value_store');

        //App Trasnlation
        Route::post('/languages/app-translations/import', 'importEnglishFile')->name('app-translations.import');
        Route::get('/languages/app-translations/show/{id}', 'showAppTranlsationView')->name('app-translations.show');
        Route::post('/languages/app-translations/key_value_store', 'storeAppTranlsation')->name('app-translations.store');
        Route::get('/languages/app-translations/export/{id}', 'exportARBFile')->name('app-translations.export');
    });


    // website setting
    Route::group(['prefix' => 'website'], function () {
        Route::controller(WebsiteController::class)->group(function () {
            Route::get('/footer', 'footer')->name('website.footer');
            Route::get('/header', 'header')->name('website.header');
            Route::get('/appearance', 'appearance')->name('website.appearance');
            Route::get('/pages', 'pages')->name('website.pages');
        });

        // Custom Page
        Route::resource('custom-pages', PageController::class);
        Route::controller(PageController::class)->group(function () {
            Route::get('/custom-pages/edit/{id}', 'edit')->name('custom-pages.edit');
            Route::get('/custom-pages/destroy/{id}', 'destroy')->name('custom-pages.destroy');
        });
    });

    Route::resource('news', \App\Http\Controllers\NewsController::class);
    Route::controller(\App\Http\Controllers\NewsController::class)->group(function () {
        Route::post('/news/update_status', 'update_status')->name('news.update_status');
        Route::get('/news/edit/{id}', 'edit')->name('news.edit');
        Route::get('/news/destroy/{id}', 'destroy')->name('news.destroy');
    });

    Route::resource('questions', \App\Http\Controllers\QuestionsController::class);
    Route::controller(\App\Http\Controllers\QuestionsController::class)->group(function () {
        Route::get('/questions/edit/{id}', 'edit')->name('questions.edit');
        Route::get('/questions/destroy/{id}', 'destroy')->name('questions.destroy');
    });

    Route::resource('user_manual', \App\Http\Controllers\UserManualController::class);
    Route::controller(\App\Http\Controllers\UserManualController::class)->group(function () {
        Route::get('/user_manual/edit/{id}', 'edit')->name('user_manual.edit');
        Route::get('/user_manual/destroy/{id}', 'destroy')->name('user_manual.destroy');
    });

    // Staff Roles
    Route::resource('roles', RoleController::class);
    Route::controller(RoleController::class)->group(function () {
        Route::get('/roles/edit/{id}', 'edit')->name('roles.edit');
        Route::get('/roles/destroy/{id}', 'destroy')->name('roles.destroy');
    });
    // Staff
    Route::resource('staffs', StaffController::class);
    Route::get('/staffs/destroy/{id}', [StaffController::class, 'destroy'])->name('staffs.destroy');

    // Flash Deal
//    Route::resource('flash_deals', FlashDealController::class);
//    Route::controller(FlashDealController::class)->group(function () {
//        Route::get('/flash_deals/edit/{id}', 'edit')->name('flash_deals.edit');
//        Route::get('/flash_deals/destroy/{id}', 'destroy')->name('flash_deals.destroy');
//        Route::post('/flash_deals/update_status', 'update_status')->name('flash_deals.update_status');
//        Route::post('/flash_deals/update_featured', 'update_featured')->name('flash_deals.update_featured');
//        Route::post('/flash_deals/product_discount', 'product_discount')->name('flash_deals.product_discount');
//        Route::post('/flash_deals/product_discount_edit', 'product_discount_edit')->name('flash_deals.product_discount_edit');
//    });

    //Subscribers
//    Route::controller(SubscriberController::class)->group(function () {
//        Route::get('/subscribers', 'index')->name('subscribers.index');
//        Route::get('/subscribers/destroy/{id}', 'destroy')->name('subscriber.destroy');
//    });

    // Order
//    Route::resource('orders', OrderController::class);
//    Route::controller(OrderController::class)->group(function () {
//        // All Orders
//        Route::get('/all_orders', 'all_orders')->name('all_orders.index');
//        Route::get('/all_orders/{id}/show', 'all_orders_show')->name('all_orders.show');
//
//        // Inhouse Orders
//        Route::get('/inhouse-orders', 'admin_orders')->name('inhouse_orders.index');
//        Route::get('/inhouse-orders/{id}/show', 'show')->name('inhouse_orders.show');
//
//        // Seller Orders
//        Route::get('/seller_orders', 'seller_orders')->name('seller_orders.index');
//        Route::get('/seller_orders/{id}/show', 'seller_orders_show')->name('seller_orders.show');
//
//        Route::post('/bulk-order-status', 'bulk_order_status')->name('bulk-order-status');
//
//        // Pickup point orders
//        Route::get('orders_by_pickup_point', 'pickup_point_order_index')->name('pick_up_point.order_index');
//        Route::get('/orders_by_pickup_point/{id}/show', 'pickup_point_order_sales_show')->name('pick_up_point.order_show');
//
//        Route::get('/orders/destroy/{id}', 'destroy')->name('orders.destroy');
//        Route::post('/bulk-order-delete', 'bulk_order_delete')->name('bulk-order-delete');
//
//        Route::get('/orders/destroy/{id}', 'destroy')->name('orders.destroy');
//        Route::post('/orders/details', 'order_details')->name('orders.details');
//        Route::post('/orders/update_delivery_status', 'update_delivery_status')->name('orders.update_delivery_status');
//        Route::post('/orders/update_payment_status', 'update_payment_status')->name('orders.update_payment_status');
//        Route::post('/orders/update_tracking_code', 'update_tracking_code')->name('orders.update_tracking_code');
//
//        //Delivery Boy Assign
//        Route::post('/orders/delivery-boy-assign', 'assign_delivery_boy')->name('orders.delivery-boy-assign');
//    });

    Route::controller(OrderDeliveryController::class)->group(function () {
        Route::get('/order_delivery', 'index')->name('order_delivery.index');
        Route::get('/order_delivery/{id}/show', 'show')->name('order_delivery.show');
    });
//
//    Route::post('/pay_to_seller', [CommissionController::class, 'pay_to_seller'])->name('commissions.pay_to_seller');

    //Reports
    Route::controller(ReportController::class)->group(function () {
        Route::get('/stock_report', 'stock_report')->name('stock_report.index');
        Route::get('/in_house_sale_report', 'in_house_sale_report')->name('in_house_sale_report.index');
        Route::get('/seller_sale_report', 'seller_sale_report')->name('seller_sale_report.index');
        Route::get('/wish_report', 'wish_report')->name('wish_report.index');
//        Route::get('/user_search_report', 'user_search_report')->name('user_search_report.index');
        Route::get('/wallet-history', 'wallet_transaction_history')->name('wallet-history.index');
        Route::get('/wallet_balance_history/{id}', 'wallet_balance_history')->name('wallet-balance.balance');
//        Route::get('/commission-log', 'commission_history')->name('commission-log.index');
//        Route::get('/', 'commission_history')->name('commission-log.index');

    });

    //Blog Section
    //Blog cateory
//    Route::resource('blog-category', BlogCategoryController::class);
//    Route::get('/blog-category/destroy/{id}', [BlogCategoryController::class, 'destroy'])->name('blog-category.destroy');

    // Blog
//    Route::resource('blog', BlogController::class);
//    Route::controller(BlogController::class)->group(function () {
//        Route::get('/blog/destroy/{id}', 'destroy')->name('blog.destroy');
//        Route::post('/blog/change-status', 'change_status')->name('blog.change-status');
//    });

    Route::resource('banner', BannerController::class);
    Route::controller(BannerController::class)->group(function () {
        Route::get('/banner/destroy/{id}', 'destroy')->name('banner.destroy');
        Route::post('/banner/change-status', 'change_status')->name('banner.change-status');
    });

//    Route::resource('popup', \App\Http\Controllers\PopupController::class);
//    Route::controller(\App\Http\Controllers\PopupController::class)->group(function () {
//        Route::get('/popup/destroy/{id}', 'destroy')->name('popup.destroy');
//        Route::post('/popup/change-status', 'change_status')->name('popup.change-status');
//    });


    //Coupons
//    Route::resource('coupon', CouponController::class);
//    Route::controller(CouponController::class)->group(function () {
//        Route::get('/coupon/destroy/{id}', 'destroy')->name('coupon.destroy');
//
//        //Coupon Form
//        Route::post('/coupon/get_form', 'get_coupon_form')->name('coupon.get_coupon_form');
//        Route::post('/coupon/get_form_edit', 'get_coupon_form_edit')->name('coupon.get_coupon_form_edit');
//    });

    //Reviews
    Route::controller(ReviewController::class)->group(function () {
        Route::get('/reviews', 'index')->name('reviews.index');
        Route::post('/reviews/published', 'updatePublished')->name('reviews.published');
    });

//    Support_Ticket
//    Route::controller(SupportTicketController::class)->group(function () {
//        Route::get('support_ticket/', 'admin_index')->name('support_ticket.admin_index');
//        Route::get('support_ticket/{id}/show', 'admin_show')->name('support_ticket.admin_show');
//        Route::post('support_ticket/reply', 'admin_store')->name('support_ticket.admin_store');
//    });

    //Pickup_Points
//    Route::resource('pick_up_points', PickupPointController::class);
//    Route::controller(PickupPointController::class)->group(function () {
//        Route::get('/pick_up_points/edit/{id}', 'edit')->name('pick_up_points.edit');
//        Route::get('/pick_up_points/destroy/{id}', 'destroy')->name('pick_up_points.destroy');
//    });

    //conversation of seller customer
//    Route::controller(ConversationController::class)->group(function () {
//        Route::get('conversations', 'admin_index')->name('conversations.admin_index');
//        Route::get('conversations/{id}/show', 'admin_show')->name('conversations.admin_show');
//    });

    // Product Attribute
    Route::resource('attributes', AttributeController::class);
    Route::controller(AttributeController::class)->group(function () {
        Route::get('/attributes/edit/{id}', 'edit')->name('attributes.edit');
        Route::get('/attributes/destroy/{id}', 'destroy')->name('attributes.destroy');

        //Attribute Value
        Route::post('/store-attribute-value', 'store_attribute_value')->name('store-attribute-value');
        Route::get('/edit-attribute-value/{id}', 'edit_attribute_value')->name('edit-attribute-value');
        Route::post('/update-attribute-value/{id}', 'update_attribute_value')->name('update-attribute-value');
        Route::get('/destroy-attribute-value/{id}', 'destroy_attribute_value')->name('destroy-attribute-value');

        //Colors
        Route::get('/colors', 'colors')->name('colors');
        Route::post('/colors/store', 'store_color')->name('colors.store');
        Route::get('/colors/edit/{id}', 'edit_color')->name('colors.edit');
        Route::post('/colors/update/{id}', 'update_color')->name('colors.update');
        Route::get('/colors/destroy/{id}', 'destroy_color')->name('colors.destroy');
    });

    // Addon
    Route::resource('addons', AddonController::class);
    Route::post('/addons/activation', [AddonController::class, 'activation'])->name('addons.activation');

    //Customer Package
    Route::resource('customer_packages', CustomerPackageController::class);
    Route::controller(CustomerPackageController::class)->group(function () {
        Route::get('/customer_packages/edit/{id}', 'edit')->name('customer_packages.edit');
        Route::get('/customer_packages/create', 'create')->name('customer_packages.create');
        Route::get('/customer_packages/destroy/{id}', 'destroy')->name('customer_packages.destroy');
        Route::post('/customer_packages/setup_hidden', 'setup_hidden')->name('customer_packages.setup_hidden');
        Route::post('/customer_packages/setup_default', 'setup_default')->name('customer_packages.setup_default');
    });

    //Classified Products
//    Route::controller(CustomerProductController::class)->group(function () {
//        Route::get('/classified_products', 'customer_product_index')->name('classified_products');
//        Route::post('/classified_products/published', 'updatePublished')->name('classified_products.published');
//    });

    // Countries
    Route::resource('countries', CountryController::class);
    Route::post('/countries/status', [CountryController::class, 'updateStatus'])->name('countries.status');

    // States
    Route::resource('states', StateController::class);
    Route::post('/states/status', [StateController::class, 'updateStatus'])->name('states.status');

    Route::resource('cities', CityController::class);
    Route::controller(CityController::class)->group(function () {
        Route::get('/cities/edit/{id}', 'edit')->name('cities.edit');
        Route::get('/cities/destroy/{id}', 'destroy')->name('cities.destroy');
        Route::post('/cities/status', 'updateStatus')->name('cities.status');
    });

    Route::view('/system/update', 'backend.system.update')->name('system_update');
    Route::view('/system/server-status', 'backend.system.server_status')->name('system_server');

    // uploaded files
    Route::resource('/uploaded-files', AizUploadController::class);
    Route::controller(AizUploadController::class)->group(function () {
        Route::any('/uploaded-files/file-info', 'file_info')->name('uploaded-files.info');
        Route::get('/uploaded-files/destroy/{id}', 'destroy')->name('uploaded-files.destroy');
    });

    Route::group(['prefix'=>'notification'],function (){
        Route::get('/all-notification', [NotificationController::class, 'index'])->name('admin.all-notification');
        Route::get('/notification-create', [NotificationController::class, 'create'])->name('notification.create');
        Route::post('/notification-store', [NotificationController::class, 'store'])->name('notification.store');
        Route::get('/notification-edit/{id}', [NotificationController::class, 'edit'])->name('notification.edit');
        Route::put('/notification-update/{id}', [NotificationController::class, 'update'])->name('notification.update');
        Route::get('/notification-delete/{id}', [NotificationController::class, 'destroy'])->name('notification.destroy');
    });


    Route::get('/clear-cache', [AdminController::class, 'clearCache'])->name('cache.clear');
});
