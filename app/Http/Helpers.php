<?php

    use App\Http\Controllers\AffiliateController;
    use App\Http\Controllers\ClubPointController;
    use App\Http\Controllers\CommissionController;
    use App\Models\Addon;
    use App\Models\Address;
    use App\Models\BusinessSetting;
    use App\Models\Cart;
    use App\Models\City;
    use App\Models\CombinedOrder;
    use App\Models\Coupon;
    use App\Models\CouponUsage;
    use App\Models\Currency;
    use App\Models\CustomerPackage;
    use App\Models\Log;
    use App\Models\Notification as NotificationCustomer;
    use App\Models\Product;
    use App\Models\ProductStock;
    use App\Models\Province;
    use App\Models\Shop;
    use App\Models\Translation;
    use App\Models\Upload;
    use App\Models\User;
    use App\Models\Wallet;
    use App\Utility\CategoryUtility;
    use App\Utility\CustomerBillUtility;
    use App\Utility\NotificationUtility;
    use App\Utility\SendSMSUtility;
    use Carbon\Carbon;

//sensSMS function for OTP
    if (!function_exists('sendSMS')) {
        function sendSMS($to, $from, $text, $template_id)
        {
            return SendSMSUtility::sendSMS($to, $from, $text, $template_id);
        }
    }

    //sensFireBase function for OTP
    if (!function_exists('sendFireBase')) {
        function sendFireBase($user,$title,$text,$type,$amountFirst=null,$amountLast=null,$accept_by=null,$data=null)
        {
           $result= checkType($type);

           if($result==0 || $result==1){
               $db = [
                   'type' => $result,
                   'data' => $text,
                   'user_id' =>$user->id,
                   'amount_first' => $amountFirst,
                   'amount_later' =>$amountLast,
                   'accept_by' => $accept_by,
                   'item_id'=>$data->id,
                   'notifiable_type' => CustomerBillUtility::TYPE_NOTIFICATION_USER,
               ];

               sendNotification($db);
           }


//            if (!empty($user->device_token)) {

                $req = new \stdClass();
                $req->device_token = $user->device_token;
                $req->title = $title;
                $req->text = $text;
                $req->type = $type;
                $req->id = $user->id;


                $req->data=$data;
                $req->amountFirst = $amountFirst;
                $req->amountLast = $amountLast;
                $req->accept_by = $accept_by;

                $result = NotificationUtility::sendFirebaseNotification($req);
                return response(['result' => true, 'data' => $result]);
//            } else {
//                return response(['result' => false]);
//            }
        }
    }

    function sendNotification($data){
            $Notification = new NotificationCustomer;
           return $Notification->newQuery()->create($data);
    }



    //return file uploaded via uploader
    if (!function_exists('image_asset_by_object')) {
        function image_asset_by_object($id)
        {
            $dataImage = [];
            $asset = \App\Models\Upload::where('object_id', $id)->get();
            foreach ($asset as $img) {
                $dataImage[] = $img->file_name ? my_asset($img->file_name) : null;
            }
            return $dataImage;
        }
    }

    function checkType($type){
        $result=null;
        if ($type == 'warranty') {
            $result = CustomerBillUtility::TYPE_NOTIFICATION_WARRANTY;
        }
        if ($type == 'gift') {
            $result = CustomerBillUtility::TYPE_NOTIFICATION_GIFT;
        }
        if ($type == 'maintain'){
            $result = CustomerBillUtility::TYPE_NOTIFICATION_MAINTAIN;
        }
        if ($type == 'event') {
            $result = CustomerBillUtility::TYPE_NOTIFICATION_EVENT;
        }
        return $result;
    }

//highlights the selected navigation on admin panel
    if (!function_exists('areActiveRoutes')) {
        function areActiveRoutes(array $routes, $output = "active")
        {
            foreach ($routes as $route) {
                if (Route::currentRouteName() == $route) return $output;
            }
        }
    }

//highlights the selected navigation on frontend
    if (!function_exists('areActiveRoutesHome')) {
        function areActiveRoutesHome(array $routes, $output = "active")
        {
            foreach ($routes as $route) {
                if (Route::currentRouteName() == $route) return $output;
            }
        }
    }


//highlights the selected navigation on frontend
    if (!function_exists('default_language')) {
        function default_language()
        {
            return env("DEFAULT_LANGUAGE");
        }
    }

//    new notification
    if (!function_exists('NewNotification')) {
        function NewNotification($data)
        {
            $notification=new NotificationCustomer();
            return  $notification->newQuery()->create($data);

        }
    }

// new log
    if (!function_exists('log_history')) {
        function log_history($data)
        {
            $historyLog=new Log();
            return $historyLog->newQuery()->create($data);
        }
    }
    /**
     * Save JSON File
     * @return Response
     */
    if (!function_exists('convert_to_usd')) {
        function convert_to_usd($amount)
        {
            $currency = Currency::find(get_setting('system_default_currency'));
            return (floatval($amount) / floatval($currency->exchange_rate)) * Currency::where('code', 'USD')->first()->exchange_rate;
        }
    }

    if (!function_exists('convert_to_kes')) {
        function convert_to_kes($amount)
        {
            $currency = Currency::find(get_setting('system_default_currency'));
            return (floatval($amount) / floatval($currency->exchange_rate)) * Currency::where('code', 'KES')->first()->exchange_rate;
        }
    }

//filter products based on vendor activation system
    if (!function_exists('filter_products')) {
        function filter_products($products)
        {
            $verified_sellers = verified_sellers_id();
            if (get_setting('vendor_system_activation') == 1) {
                return $products->where('approved', '1')->where('published', '1')->where('auction_product', 0)->orderBy('created_at', 'desc')->where(function ($p) use ($verified_sellers) {
                    $p->where('added_by', 'admin')->orWhere(function ($q) use ($verified_sellers) {
                        $q->whereIn('user_id', $verified_sellers);
                    });
                });
            } else {
                return $products->where('published', '1')->where('auction_product', 0)->where('added_by', 'admin');
            }
        }
    }

//cache products based on category
    if (!function_exists('get_cached_products')) {
        function get_cached_products($category_id = null)
        {
            $products = \App\Models\Product::where('published', 1)->where('approved', '1')->where('auction_product', 0);
            $verified_sellers = verified_sellers_id();
            if (get_setting('vendor_system_activation') == 1) {
                $products = $products->where(function ($p) use ($verified_sellers) {
                    $p->where('added_by', 'admin')->orWhere(function ($q) use ($verified_sellers) {
                        $q->whereIn('user_id', $verified_sellers);
                    });
                });
            } else {
                $products = $products->where('added_by', 'admin');
            }

            if ($category_id != null) {
                return Cache::remember('products-category-' . $category_id, 86400, function () use ($category_id, $products) {
                    $category_ids = CategoryUtility::children_ids($category_id);
                    $category_ids[] = $category_id;
                    return $products->whereIn('category_id', $category_ids)->latest()->take(12)->get();
                });
            } else {
                return Cache::remember('products', 86400, function () use ($products) {
                    return $products->latest()->take(12)->get();
                });
            }
        }
    }

    if (!function_exists('verified_sellers_id')) {
        function verified_sellers_id()
        {
            return Cache::rememberForever('verified_sellers_id', function () {
                return App\Models\Shop::where('verification_status', 1)->pluck('user_id')->toArray();
            });
        }
    }

    if (!function_exists('get_system_default_currency')) {
        function get_system_default_currency()
        {
            return Cache::remember('system_default_currency', 86400, function () {
                return Currency::findOrFail(get_setting('system_default_currency'));
            });
        }
    }

//converts currency to home default currency
    if (!function_exists('convert_price')) {
        function convert_price($price)
        {
            if (Session::has('currency_code') && (Session::get('currency_code') != get_system_default_currency()->code)) {
                $price = floatval($price) / floatval(get_system_default_currency()->exchange_rate);
                $price = floatval($price) * floatval(Session::get('currency_exchange_rate'));
            }
            return $price;
        }
    }

//gets currency symbol
    if (!function_exists('currency_symbol')) {
        function currency_symbol()
        {
            if (Session::has('currency_symbol')) {
                return Session::get('currency_symbol');
            }
            return get_system_default_currency()->symbol;
        }
    }

//formats currency
    if (!function_exists('format_price')) {
        function format_price($price)
        {
            if (get_setting('decimal_separator') == 1) {
                $fomated_price = number_format($price, get_setting('no_of_decimals'));
            } else {
                $fomated_price = number_format($price, get_setting('no_of_decimals'), ',', '.');
            }

            if (get_setting('symbol_format') == 1) {
                return currency_symbol() . $fomated_price;
            } else if (get_setting('symbol_format') == 3) {
                return currency_symbol() . ' ' . $fomated_price;
            } else if (get_setting('symbol_format') == 4) {
                return $fomated_price . ' ' . currency_symbol();
            }
            return $fomated_price ."". currency_symbol();
        }
    }

//formats price to home default price with convertion
    if (!function_exists('single_price')) {
        function single_price($price)
        {
            return format_price(convert_price($price));
        }
    }

    if (!function_exists('discount_in_percentage')) {
        function discount_in_percentage($product)
        {
            $base = home_base_price($product, false);
            $reduced = home_discounted_base_price($product, false);
            $discount = $base - $reduced;
            $dp = ($discount * 100) / ($base > 0 ? $base : 1);
            return round($dp);
        }
    }

//Shows Price on page based on carts
    if (!function_exists('cart_product_price')) {
        function cart_product_price($cart_product, $product, $formatted = true, $tax = true)
        {
            $str = '';
            if ($cart_product['variation'] != null) {
                $str = $cart_product['variation'];
            }
            $price = 0;
            $product_stock = $product->stocks->where('variant', $str)->first();
            if ($product_stock) {
                $price = $product_stock->price;
            }


            //discount calculation
            $discount_applicable = false;

            if ($product->discount_start_date == null) {
                $discount_applicable = true;
            } elseif (strtotime(date('d-m-Y H:i:s')) >= $product->discount_start_date &&
                strtotime(date('d-m-Y H:i:s')) <= $product->discount_end_date) {
                $discount_applicable = true;
            }

            if ($discount_applicable) {
                if ($product->discount_type == 'percent') {
                    $price -= ($price * $product->discount) / 100;
                } elseif ($product->discount_type == 'amount') {
                    $price -= $product->discount;
                }
            }

            //calculation of taxes
            if ($tax) {
                $taxAmount = 0;
                foreach ($product->taxes as $product_tax) {
                    if ($product_tax->tax_type == 'percent') {
                        $taxAmount += ($price * $product_tax->tax) / 100;
                    } elseif ($product_tax->tax_type == 'amount') {
                        $taxAmount += $product_tax->tax;
                    }
                }
                $price += $taxAmount;
            }

            if ($formatted) {
                return format_price(convert_price($price));
            } else {
                return $price;
            }

        }
    }

    if (!function_exists('cart_product_tax')) {
        function cart_product_tax($cart_product, $product, $formatted = true)
        {
            $str = '';
            if ($cart_product['variation'] != null) {
                $str = $cart_product['variation'];
            }
            $product_stock = $product->stocks->where('variant', $str)->first();
            $price = $product_stock->price;

            //discount calculation
            $discount_applicable = false;

            if ($product->discount_start_date == null) {
                $discount_applicable = true;
            } elseif (strtotime(date('d-m-Y H:i:s')) >= $product->discount_start_date &&
                strtotime(date('d-m-Y H:i:s')) <= $product->discount_end_date) {
                $discount_applicable = true;
            }

            if ($discount_applicable) {
                if ($product->discount_type == 'percent') {
                    $price -= ($price * $product->discount) / 100;
                } elseif ($product->discount_type == 'amount') {
                    $price -= $product->discount;
                }
            }

            //calculation of taxes
            $tax = 0;
            foreach ($product->taxes as $product_tax) {
                if ($product_tax->tax_type == 'percent') {
                    $tax += ($price * $product_tax->tax) / 100;
                } elseif ($product_tax->tax_type == 'amount') {
                    $tax += $product_tax->tax;
                }
            }

            if ($formatted) {
                return format_price(convert_price($tax));
            } else {
                return $tax;
            }

        }
    }

    if (!function_exists('cart_product_discount')) {
        function cart_product_discount($cart_product, $product, $formatted = false)
        {
            $str = '';
            if ($cart_product['variation'] != null) {
                $str = $cart_product['variation'];
            }
            $product_stock = $product->stocks->where('variant', $str)->first();
            $price = $product_stock->price;

            //discount calculation
            $discount_applicable = false;
            $discount = 0;

            if ($product->discount_start_date == null) {
                $discount_applicable = true;
            } elseif (strtotime(date('d-m-Y H:i:s')) >= $product->discount_start_date &&
                strtotime(date('d-m-Y H:i:s')) <= $product->discount_end_date) {
                $discount_applicable = true;
            }

            if ($discount_applicable) {
                if ($product->discount_type == 'percent') {
                    $discount = ($price * $product->discount) / 100;
                } elseif ($product->discount_type == 'amount') {
                    $discount = $product->discount;
                }
            }

            if ($formatted) {
                return format_price(convert_price($discount));
            } else {
                return $discount;
            }

        }
    }

// all discount
    if (!function_exists('carts_product_discount')) {
        function carts_product_discount($cart_products, $formatted = false)
        {
            $discount = 0;
            foreach ($cart_products as $key => $cart_product) {
                $str = '';
                $product = \App\Models\Product::find($cart_product['product_id']);
                if ($cart_product['variation'] != null) {
                    $str = $cart_product['variation'];
                }
                $product_stock = $product->stocks->where('variant', $str)->first();
                $price = $product_stock->price;

                //discount calculation
                $discount_applicable = false;

                if ($product->discount_start_date == null) {
                    $discount_applicable = true;
                } elseif (strtotime(date('d-m-Y H:i:s')) >= $product->discount_start_date &&
                    strtotime(date('d-m-Y H:i:s')) <= $product->discount_end_date) {
                    $discount_applicable = true;
                }

                if ($discount_applicable) {
                    if ($product->discount_type == 'percent') {
                        $discount += ($price * $product->discount) / 100;
                    } elseif ($product->discount_type == 'amount') {
                        $discount += $product->discount;
                    }
                }
            }

            if ($formatted) {
                return format_price(convert_price($discount));
            } else {
                return $discount;
            }

        }
    }

    if (!function_exists('carts_coupon_discount')) {
        function carts_coupon_discount($code, $formatted = false)
        {
            $coupon = Coupon::where('code', $code)->first();
            $coupon_discount = 0;
            if ($coupon != null) {
                if (strtotime(date('d-m-Y')) >= $coupon->start_date && strtotime(date('d-m-Y')) <= $coupon->end_date) {
                    if (CouponUsage::where('user_id', Auth::user()->id)->where('coupon_id', $coupon->id)->first() == null) {
                        $coupon_details = json_decode($coupon->details);

                        $carts = Cart::where('user_id', Auth::user()->id)
                            ->where('owner_id', $coupon->user_id)
                            ->get();

                        if ($coupon->type == 'cart_base') {
                            $subtotal = 0;
                            $tax = 0;
                            $shipping = 0;
                            foreach ($carts as $key => $cartItem) {
                                $product = Product::find($cartItem['product_id']);
                                $subtotal += cart_product_price($cartItem, $product, false, false) * $cartItem['quantity'];
                                $tax += cart_product_tax($cartItem, $product, false) * $cartItem['quantity'];
                                $shipping += $cartItem['shipping_cost'];
                            }
                            $sum = $subtotal + $tax + $shipping;

                            if ($sum >= $coupon_details->min_buy) {
                                if ($coupon->discount_type == 'percent') {
                                    $coupon_discount = ($sum * $coupon->discount) / 100;
                                    if ($coupon_discount > $coupon_details->max_discount) {
                                        $coupon_discount = $coupon_details->max_discount;
                                    }
                                } elseif ($coupon->discount_type == 'amount') {
                                    $coupon_discount = $coupon->discount;
                                }

                            }
                        } elseif ($coupon->type == 'product_base') {
                            foreach ($carts as $key => $cartItem) {
                                $product = Product::find($cartItem['product_id']);
                                foreach ($coupon_details as $key => $coupon_detail) {
                                    if ($coupon_detail->product_id == $cartItem['product_id']) {
                                        if ($coupon->discount_type == 'percent') {
                                            $coupon_discount += (cart_product_price($cartItem, $product, false, false) * $coupon->discount / 100) * $cartItem['quantity'];
                                        } elseif ($coupon->discount_type == 'amount') {
                                            $coupon_discount += $coupon->discount * $cartItem['quantity'];
                                        }
                                    }
                                }
                            }
                        }

                    }
                }

                if ($coupon_discount > 0) {
                    Cart::where('user_id', Auth::user()->id)
                        ->where('owner_id', $coupon->user_id)
                        ->update(
                            [
                                'discount' => $coupon_discount / count($carts),
                            ]
                        );
                } else {
                    Cart::where('user_id', Auth::user()->id)
                        ->where('owner_id', $coupon->user_id)
                        ->update(
                            [
                                'discount' => 0,
                                'coupon_code' => null,
                            ]
                        );
                }
            }

            if ($formatted) {
                return format_price(convert_price($coupon_discount));
            } else {
                return $coupon_discount;
            }
        }
    }

//Shows Price on page based on low to high
    if (!function_exists('home_price')) {
        function home_price($product, $formatted = true)
        {
            $lowest_price = $product->unit_price;
            $highest_price = $product->unit_price;

            if ($product->variant_product) {
                foreach ($product->stocks as $key => $stock) {
                    if ($lowest_price > $stock->price) {
                        $lowest_price = $stock->price;
                    }
                    if ($highest_price < $stock->price) {
                        $highest_price = $stock->price;
                    }
                }
            }

            foreach ($product->taxes as $product_tax) {
                if ($product_tax->tax_type == 'percent') {
                    $lowest_price += ($lowest_price * $product_tax->tax) / 100;
                    $highest_price += ($highest_price * $product_tax->tax) / 100;
                } elseif ($product_tax->tax_type == 'amount') {
                    $lowest_price += $product_tax->tax;
                    $highest_price += $product_tax->tax;
                }
            }

            if ($formatted) {
                if ($lowest_price == $highest_price) {
                    return format_price(convert_price($lowest_price));
                } else {
                    return format_price(convert_price($lowest_price)) . ' - ' . format_price(convert_price($highest_price));
                }
            } else {
                return $lowest_price . ' - ' . $highest_price;
            }
        }
    }

//Shows Price on page based on low to high with discount
    if (!function_exists('home_discounted_price')) {
        function home_discounted_price($product, $formatted = true)
        {
            $lowest_price = $product->unit_price;
            $highest_price = $product->unit_price;

            if ($product->variant_product) {
                foreach ($product->stocks as $key => $stock) {
                    if ($lowest_price > $stock->price) {
                        $lowest_price = $stock->price;
                    }
                    if ($highest_price < $stock->price) {
                        $highest_price = $stock->price;
                    }
                }
            }

            $discount_applicable = false;

            if ($product->discount_start_date == null) {
                $discount_applicable = true;
            } elseif (
                strtotime(date('d-m-Y H:i:s')) >= $product->discount_start_date &&
                strtotime(date('d-m-Y H:i:s')) <= $product->discount_end_date
            ) {
                $discount_applicable = true;
            }

            if ($discount_applicable) {
                if ($product->discount_type == 'percent') {
                    $lowest_price -= ($lowest_price * $product->discount) / 100;
                    $highest_price -= ($highest_price * $product->discount) / 100;
                } elseif ($product->discount_type == 'amount') {
                    $lowest_price -= $product->discount;
                    $highest_price -= $product->discount;
                }
            }

            foreach ($product->taxes as $product_tax) {
                if ($product_tax->tax_type == 'percent') {
                    $lowest_price += ($lowest_price * $product_tax->tax) / 100;
                    $highest_price += ($highest_price * $product_tax->tax) / 100;
                } elseif ($product_tax->tax_type == 'amount') {
                    $lowest_price += $product_tax->tax;
                    $highest_price += $product_tax->tax;
                }
            }

            if ($formatted) {
                if ($lowest_price == $highest_price) {
                    return format_price(convert_price($lowest_price));
                } else {
                    return format_price(convert_price($lowest_price)) . ' - ' . format_price(convert_price($highest_price));
                }
            } else {
                return $lowest_price . ' - ' . $highest_price;
            }
        }
    }

//Shows Base Price
    if (!function_exists('home_base_price_by_stock_id')) {
        function home_base_price_by_stock_id($id)
        {
            $product_stock = ProductStock::findOrFail($id);
            $price = $product_stock->price;
            $tax = 0;

            foreach ($product_stock->product->taxes as $product_tax) {
                if ($product_tax->tax_type == 'percent') {
                    $tax += ($price * $product_tax->tax) / 100;
                } elseif ($product_tax->tax_type == 'amount') {
                    $tax += $product_tax->tax;
                }
            }
            $price += $tax;
            return format_price(convert_price($price));
        }
    }
    if (!function_exists('home_base_price')) {
        function home_base_price($product, $formatted = true)
        {
            $price = $product->unit_price;
            $tax = 0;

            foreach ($product->taxes as $product_tax) {
                if ($product_tax->tax_type == 'percent') {
                    $tax += ($price * $product_tax->tax) / 100;
                } elseif ($product_tax->tax_type == 'amount') {
                    $tax += $product_tax->tax;
                }
            }
            $price += $tax;
            return $formatted ? format_price(convert_price($price)) : $price;
        }
    }

//Shows Base Price with discount
    if (!function_exists('home_discounted_base_price_by_stock_id')) {
        function home_discounted_base_price_by_stock_id($id)
        {
            $product_stock = ProductStock::findOrFail($id);
            $product = $product_stock->product;
            $price = $product_stock->price;
            $tax = 0;

            $discount_applicable = false;

            if ($product->discount_start_date == null) {
                $discount_applicable = true;
            } elseif (
                strtotime(date('d-m-Y H:i:s')) >= $product->discount_start_date &&
                strtotime(date('d-m-Y H:i:s')) <= $product->discount_end_date
            ) {
                $discount_applicable = true;
            }

            if ($discount_applicable) {
                if ($product->discount_type == 'percent') {
                    $price -= ($price * $product->discount) / 100;
                } elseif ($product->discount_type == 'amount') {
                    $price -= $product->discount;
                }
            }

            foreach ($product->taxes as $product_tax) {
                if ($product_tax->tax_type == 'percent') {
                    $tax += ($price * $product_tax->tax) / 100;
                } elseif ($product_tax->tax_type == 'amount') {
                    $tax += $product_tax->tax;
                }
            }
            $price += $tax;

            return format_price(convert_price($price));
        }
    }

//Shows Base Price with discount
    if (!function_exists('home_discounted_base_price')) {
        function home_discounted_base_price($product, $formatted = true)
        {
            $price = $product->unit_price;
            $tax = 0;

            $discount_applicable = false;

            if ($product->discount_start_date == null) {
                $discount_applicable = true;
            } elseif (
                strtotime(date('d-m-Y H:i:s')) >= $product->discount_start_date &&
                strtotime(date('d-m-Y H:i:s')) <= $product->discount_end_date
            ) {
                $discount_applicable = true;
            }

            if ($discount_applicable) {
                if ($product->discount_type == 'percent') {
                    $price -= ($price * $product->discount) / 100;
                } elseif ($product->discount_type == 'amount') {
                    $price -= $product->discount;
                }
            }

            foreach ($product->taxes as $product_tax) {
                if ($product_tax->tax_type == 'percent') {
                    $tax += ($price * $product_tax->tax) / 100;
                } elseif ($product_tax->tax_type == 'amount') {
                    $tax += $product_tax->tax;
                }
            }
            $price += $tax;

            return $formatted ? format_price(convert_price($price)) : $price;
        }
    }

    if (!function_exists('renderStarRating')) {
        function renderStarRating($rating, $maxRating = 5)
        {
            $fullStar = "<i class = 'las la-star active'></i>";
            $halfStar = "<i class = 'las la-star half'></i>";
            $emptyStar = "<i class = 'las la-star'></i>";
            $rating = $rating <= $maxRating ? $rating : $maxRating;

            $fullStarCount = (int)$rating;
            $halfStarCount = ceil($rating) - $fullStarCount;
            $emptyStarCount = $maxRating - $fullStarCount - $halfStarCount;

            $html = str_repeat($fullStar, $fullStarCount);
            $html .= str_repeat($halfStar, $halfStarCount);
            $html .= str_repeat($emptyStar, $emptyStarCount);
            echo $html;
        }
    }

    function translate($key, $lang = null, $addslashes = false)
    {
        if ($lang == null) {
            $lang = App::getLocale();
        }

        $lang_key = preg_replace('/[^A-Za-z0-9\_]/', '', str_replace(' ', '_', strtolower($key)));

        $translations_en = Cache::rememberForever('translations-en', function () {
            return Translation::where('lang', 'en')->pluck('lang_value', 'lang_key')->toArray();
        });

        if (!isset($translations_en[$lang_key])) {
            $translation_def = new Translation;
            $translation_def->lang = 'en';
            $translation_def->lang_key = $lang_key;
            $translation_def->lang_value = str_replace(array("\r", "\n", "\r\n"), "", $key);
            $translation_def->save();
            Cache::forget('translations-en');
        }

        // return user session lang
        $translation_locale = Cache::rememberForever("translations-{$lang}", function () use ($lang) {
            return Translation::where('lang', $lang)->pluck('lang_value', 'lang_key')->toArray();
        });
        if (isset($translation_locale[$lang_key])) {
            return $addslashes ? addslashes(trim($translation_locale[$lang_key])) : trim($translation_locale[$lang_key]);
        }

        // return default lang if session lang not found
        $translations_default = Cache::rememberForever('translations-' . env('DEFAULT_LANGUAGE', 'en'), function () {
            return Translation::where('lang', env('DEFAULT_LANGUAGE', 'en'))->pluck('lang_value', 'lang_key')->toArray();
        });
        if (isset($translations_default[$lang_key])) {
            return $addslashes ? addslashes(trim($translations_default[$lang_key])) : trim($translations_default[$lang_key]);
        }

        // fallback to en lang
        if (!isset($translations_en[$lang_key])) {
            return trim($key);
        }
        return $addslashes ? addslashes(trim($translations_en[$lang_key])) : trim($translations_en[$lang_key]);
    }

    function remove_invalid_charcaters($str)
    {
        $str = str_ireplace(array("\\"), '', $str);
        return str_ireplace(array('"'), '\"', $str);
    }

    function getShippingCost($carts, $index)
    {
        $admin_products = array();
        $seller_products = array();

        $cartItem = $carts[$index];
        $product = Product::find($cartItem['product_id']);

        if ($product->digital == 1) {
            return 0;
        }

        foreach ($carts as $key => $cart_item) {
            $item_product = Product::find($cart_item['product_id']);
            if ($item_product->added_by == 'admin') {
                array_push($admin_products, $cart_item['product_id']);
            } else {
                $product_ids = array();
                if (isset($seller_products[$item_product->user_id])) {
                    $product_ids = $seller_products[$item_product->user_id];
                }
                array_push($product_ids, $cart_item['product_id']);
                $seller_products[$item_product->user_id] = $product_ids;
            }
        }

        if (get_setting('shipping_type') == 'flat_rate') {
            return get_setting('flat_rate_shipping_cost') / count($carts);
        } elseif (get_setting('shipping_type') == 'seller_wise_shipping') {
            if ($product->added_by == 'admin') {
                return get_setting('shipping_cost_admin') / count($admin_products);
            } else {
                return Shop::where('user_id', $product->user_id)->first()->shipping_cost / count($seller_products[$product->user_id]);
            }
        } elseif (get_setting('shipping_type') == 'area_wise_shipping') {
            $shipping_info = Address::where('id', $carts[0]['address_id'])->first();
            $city = City::where('id', $shipping_info->city_id)->first();
            if ($city != null) {
                if ($product->added_by == 'admin') {
                    return $city->cost / count($admin_products);
                } else {
                    return $city->cost / count($seller_products[$product->user_id]);
                }
            }
            return 0;
        } else {
            if ($product->is_quantity_multiplied && get_setting('shipping_type') == 'product_wise_shipping') {
                return $product->shipping_cost * $cartItem['quantity'];
            }
            return $product->shipping_cost;
        }
    }

    function timezones()
    {
        return Timezones::timezonesToArray();
    }

    if (!function_exists('app_timezone')) {
        function app_timezone()
        {
            return config('app.timezone');
        }
    }

//return file uploaded via uploader
    if (!function_exists('uploaded_asset')) {
        function uploaded_asset($id)
        {
            if (($asset = \App\Models\Upload::find($id)) != null) {
                return $asset->external_link == null ? my_asset($asset->file_name) : $asset->external_link;
            }
            return null;
        }
    }

//return file uploaded via uploader
    if (!function_exists('get_image_asset')) {
        function get_image_asset($id, $object_id)
        {
            if (($asset = \App\Models\Upload::where('id', $id)->where('object_id', $object_id)->first()) != null) {
                return $asset->external_link == null ? my_asset($asset->file_name) : $asset->external_link;
            }
            return null;
        }
    }


//return file uploaded via uploader
    if (!function_exists('image_asset_by_object')) {
        function image_asset_by_object($id)
        {
            $dataImage = [];
            if (($asset = \App\Models\Upload::where('object_id', $id)->get()) != null) {
                foreach ($asset as $img) {
                    $dataImage[] = $img->external_link == null ? my_asset($img->file_name) : $img->external_link;
                }
                return $dataImage;
            }
            return null;
        }
    }


    if (!function_exists('my_asset')) {
        /**
         * Generate an asset path for the application.
         *
         * @param string $path
         * @param bool|null $secure
         * @return string
         */
        function my_asset($path, $secure = null)
        {
            if (env('FILESYSTEM_DRIVER') == 's3') {
                return Storage::disk('s3')->url($path);
            } else {
                return app('url')->asset('public/' . $path, $secure);
            }
        }
    }

    if (!function_exists('static_asset')) {
        /**
         * Generate an asset path for the application.
         *
         * @param string $path
         * @param bool|null $secure
         * @return string
         */
        function static_asset($path, $secure = null)
        {
            return app('url')->asset('public/' . $path, $secure);
        }
    }


// if (!function_exists('isHttps')) {
//     function isHttps()
//     {
//         return !empty($_SERVER['HTTPS']) && ('on' == $_SERVER['HTTPS']);
//     }
// }

    if (!function_exists('getBaseURL')) {
        function getBaseURL()
        {
            $root = '//' . $_SERVER['HTTP_HOST'];
            $root .= str_replace(basename($_SERVER['SCRIPT_NAME']), '', $_SERVER['SCRIPT_NAME']);

            return $root;
        }
    }


    if (!function_exists('getFileBaseURL')) {
        function getFileBaseURL()
        {
            if (env('FILESYSTEM_DRIVER') == 's3') {
                return env('AWS_URL') . '/';
            } else {
                return getBaseURL() . 'public/';
            }
        }
    }

    if (!function_exists('uploadMultipleImage')) {
        function uploadMultipleImage($imageName, $id = null, $path)
        {
            foreach ($imageName as $key => $image) {
                $img = $image->getClientOriginalName();
                $name = substr($img, 0, strpos($img, '.'));
                $realImage = $image->hashName();
                $extension = $image->getClientOriginalExtension();
                $size = $image->getSize();
                $newPath = $path . "/$realImage";
                 $image->store($path, 'local');
                $upload = new Upload;
                $upload->file_original_name = $name;
                $upload->extension = $extension;
                $upload->file_name = $newPath;
                $upload->type = 'image';
                $upload->file_size = $size;
                $upload->object_id = $id;
                $upload->save();
            }
        }
    }


//    if (!function_exists('uploadImageURL')) {
//        function uploadMultipleImage($imageName, $id = null, $path)
//        {
//            foreach ($imageName as $key => $image) {
//                $upload = new Upload;
//                $img = $image->getClientOriginalName();
//                $name = substr($img, 0, strpos($img, '.'));
//                $realImage = $image->hashName();
//                $extension = $image->getClientOriginalExtension();
//                $size = $image->getSize();
//                $newPath = $path . "/$realImage";
//                $image->store($path, 'local');
//                $upload->file_original_name = $name;
//                $upload->extension = $extension;
//                $upload->file_name = $newPath;
//                $upload->type = 'image';
//                $upload->file_size = $size;
//                $upload->object_id = $id;
//                $upload->save();
//            }
//        }
//    }


    if (!function_exists('uploadFile')) {
        function uploadFile($imageName, $path){
            $image = $imageName;  // ảnh chuyền vào
            $name=$image->hashName();  // hash name ảnh unique

            $processedImagePath = public_path($path).'/'.$name;  // đường dẫn lưu vào thư mục
//            dường dẫn lưu vào database
            $newPath = $path . "/".$name;

//            tiến hành lưu
            $image->store($path, 'local');

//            $saveImage = Image::make($processedImagePath);
//            $saveImage->sharpen(5);
//            $saveImage->rotate(-90);
//            $saveImage->save($processedImagePath);

//dd($processedImagePath);
            return $newPath;
        }
    }

    if (!function_exists('sharpenImage')) {
        function sharpenImage($imagePath)
        {
            dd($imagePath);

            // Tạo đối tượng ảnh từ đường dẫn
            $image = imagecreatefromjpeg($imagePath);
            // Tạo bộ lọc làm nét
            $sharpenMatrix = [
                [-1, -1, -1],
                [-1, 16, -1],
                [-1, -1, -1]
            ];
            // Lọc ảnh bằng bộ lọc làm nét
            imageconvolution($image, $sharpenMatrix, 8, 0);
            // Lưu ảnh đã làm nét
            imagejpeg($image, $imagePath);
            // Giải phóng bộ nhớ
            imagedestroy($image);
        }
    }






    if (!function_exists('isUnique')) {
        /**
         * Generate an asset path for the application.
         *
         * @param string $path
         * @param bool|null $secure
         * @return string
         */
        function isUnique($email)
        {
            $user = \App\Models\User::where('email', $email)->first();

            if ($user == null) {
                return '1'; // $user = null means we did not get any match with the email provided by the user inside the database
            } else {
                return '0';
            }
        }
    }

    if (!function_exists('get_setting')) {
        function get_setting($key, $default = null, $lang = false)
        {
            $settings = Cache::remember('business_settings', 86400, function () {
                return BusinessSetting::all();
            });

            if ($lang == false) {
                $setting = $settings->where('type', $key)->first();
            } else {
                $setting = $settings->where('type', $key)->where('lang', $lang)->first();
                $setting = !$setting ? $settings->where('type', $key)->first() : $setting;
            }
            return $setting == null ? $default : $setting->value;
        }
    }

    function hex2rgba($color, $opacity = false)
    {
        return Colorcodeconverter::convertHexToRgba($color, $opacity);
    }

    if (!function_exists('isAdmin')) {
        function isAdmin()
        {
            if (Auth::check() && (Auth::user()->user_type == 'admin' || Auth::user()->user_type == 'staff')) {
                return true;
            }
            return false;
        }
    }

    if (!function_exists('isSeller')) {
        function isSeller()
        {
            if (Auth::check() && Auth::user()->user_type == 'seller') {
                return true;
            }
            return false;
        }
    }

    if (!function_exists('isCustomer')) {
        function isCustomer()
        {
            if (Auth::check() && Auth::user()->user_type == 'customer') {
                return true;
            }
            return false;
        }
    }

    if (!function_exists('formatBytes')) {
        function formatBytes($bytes, $precision = 2)
        {
            $units = array('B', 'KB', 'MB', 'GB', 'TB');

            $bytes = max($bytes, 0);
            $pow = floor(($bytes ? log($bytes) : 0) / log(1024));
            $pow = min($pow, count($units) - 1);

            // Uncomment one of the following alternatives
            $bytes /= pow(1024, $pow);
            // $bytes /= (1 << (10 * $pow));

            return round($bytes, $precision) . ' ' . $units[$pow];
        }
    }

// duplicates m$ excel's ceiling function
    if (!function_exists('ceiling')) {
        function ceiling($number, $significance = 1)
        {
            return (is_numeric($number) && is_numeric($significance)) ? (ceil($number / $significance) * $significance) : false;
        }
    }

//for api
    if (!function_exists('get_images_path')) {
        function get_images_path($given_ids, $with_trashed = false)
        {
            $paths = [];
            foreach (explode(',', $given_ids) as $id) {
                $paths[] = uploaded_asset($id);
            }

            return $paths;
        }
    }

//for api
    if (!function_exists('checkout_done')) {
        function checkout_done($combined_order_id, $payment)
        {
            $combined_order = CombinedOrder::find($combined_order_id);

            foreach ($combined_order->orders as $key => $order) {
                $order->payment_status = 'paid';
                $order->payment_details = $payment;
                $order->save();

                try {
                    NotificationUtility::sendOrderPlacedNotification($order);
                    calculateCommissionAffilationClubPoint($order);
                } catch (\Exception $e) {
                }
            }
        }
    }

//for api
    if (!function_exists('wallet_payment_done')) {
        function wallet_payment_done($user_id, $amount, $payment_method, $payment_details)
        {
            $user = \App\Models\User::find($user_id);
            $user->balance = $user->balance + $amount;
            $user->save();

            $wallet = new Wallet;
            $wallet->user_id = $user->id;
            $wallet->amount = $amount;
            $wallet->payment_method = $payment_method;
            $wallet->payment_details = $payment_details;
            $wallet->save();
        }
    }

    if (!function_exists('purchase_payment_done')) {
        function purchase_payment_done($user_id, $package_id)
        {
            $user = User::findOrFail($user_id);
            $user->customer_package_id = $package_id;
            $customer_package = CustomerPackage::findOrFail($package_id);
            $user->remaining_uploads += $customer_package->product_upload;
            $user->save();

            return 'success';
        }
    }

    if (!function_exists('product_restock')) {
        function product_restock($orderDetail)
        {
            $variant = $orderDetail->variation;
            if ($orderDetail->variation == null) {
                $variant = '';
            }

            $product_stock = ProductStock::where('product_id', $orderDetail->product_id)
                ->where('variant', $variant)
                ->first();

            if ($product_stock != null) {
                $product_stock->qty += $orderDetail->quantity;
                $product_stock->save();
            }
        }
    }

//Commission Calculation
    if (!function_exists('calculateCommissionAffilationClubPoint')) {
        function calculateCommissionAffilationClubPoint($order)
        {
            (new CommissionController)->calculateCommission($order);

            if (addon_is_activated('affiliate_system')) {
                (new AffiliateController)->processAffiliatePoints($order);
            }

            if (addon_is_activated('club_point')) {
                if ($order->user != null) {
                    (new ClubPointController)->processClubPoints($order);
                }
            }

            $order->commission_calculated = 1;
            $order->save();
        }
    }

// Addon Activation Check
    if (!function_exists('addon_is_activated')) {
        function addon_is_activated($identifier, $default = null)
        {
            $addons = Cache::remember('addons', 86400, function () {
                return Addon::all();
            });

            $activation = $addons->where('unique_identifier', $identifier)->where('activated', 1)->first();
            return $activation == null ? false : true;
        }
    }

// Addon Activation Check
    if (!function_exists('seller_package_validity_check')) {
        function seller_package_validity_check($user_id = null)
        {
            $user = $user_id == null ? \App\Models\User::find(Auth::user()->id) : \App\Models\User::find($user_id);
            $shop = $user->shop;
            $package_validation = false;
            if (
                $shop->product_upload_limit > $shop->user->products()->count()
                && $shop->package_invalid_at != null
                && Carbon::now()->diffInDays(Carbon::parse($shop->package_invalid_at), false) >= 0
            ) {
                $package_validation = true;
            }

            return $package_validation;
            // Ture = Seller package is valid and seller has the product upload limit
            // False = Seller package is invalid or seller product upload limit exists.
        }
    }

// Get URL params
    if (!function_exists('get_url_params')) {
        function get_url_params($url, $key)
        {
            $query_str = parse_url($url, PHP_URL_QUERY);
            parse_str($query_str, $query_params);

            return $query_params[$key] ?? '';
        }
    }
// encode md5
    if (!function_exists('config_base64_encode')) {
        function config_base64_encode($text)
        {
            $data = strtoupper(md5(rand(0, 1000)) . "bdh") . base64_encode($text);
            return $data;
        }
    }

// decode md5
    if (!function_exists('config_base64_decode')) {
        function config_base64_decode($text)
        {
            $result = substr($text, 35);

            return (int)base64_decode($result);


        }
    }

    if (!function_exists('hidePhone')) {
    function hidePhone($string, $start, $total)
    {
        if (!is_string($string)) {
            return '';
        }
        $text = '*****';

        $new_string = substr_replace($string, $text, $start, $total);
        return $new_string;
    }
    }

    if (!function_exists('available_balances')) {

    function available_balances($user_id)
    {

        $wallet_user = Wallet::where('user_id', $user_id)->first();
        if(!$wallet_user){
            $wallet_user=Wallet::create([
                'amount'=>config_base64_encode(0),
                'user_id'=>$user_id,
            ]);
        }
        $GiftWaiting=\App\Models\GiftRequest::query()
            ->with('gift')
            ->where('status',0)
            ->where('user_id',$user_id)->get();
        $waiting_point = 0;
        if (count($GiftWaiting) > 0 ) {
            foreach ($GiftWaiting as $key => $item) {
                $waiting_point += $item->gift->point;
            }
        }

            $wallet_point = config_base64_decode($wallet_user->amount);


      $result= $wallet_point - $waiting_point;
        return $result;
    }


    }

    if (!function_exists('update_customer_package')) {
        function update_customer_package($user_id){
            $balance=available_balances($user_id);
            $user= User::with('customer_package')->findOrFail($user_id);

            $package_id=$user->customer_package_id;
            $package_point=$user->customer_package->point;
            $bonus_package=$user->customer_package->bonus;
            $packages=CustomerPackage::query()
                ->orderBy('point','asc')
                ->whereBetween('point',[0,$balance])->get();
            $itemsGroup=null;
            foreach ( $packages as $item){
                $group =$item;
            }
            $user->customer_package_id=$group->id;
            $user->save();
            if($package_id!=$user->customer_package_id && $group->bonus > $bonus_package){
                $wallet=Wallet::query()->where('user_id',$user->id)->first();
                $wallet->amount=config_base64_encode(config_base64_decode($wallet->amount)+$group->bonus);
                $wallet->save();
                sendFireBase($user,'Thông báo nâng cấp ',"Bạn đã được nâng cập lên nhóm $group->name, Bạn đã được cộng thêm  điểm $group->bonus vào ví",'upgrade');
            }
//           }
            return $user;
        }
    }


    if (!function_exists('makeSlug')) {
        function makeSlug($string)
        {
            $data = preg_replace('/[^A-Za-z0-9\-]/', '', str_replace(' ', '-', $string));
            return $data;
        }
    }
    if (!function_exists('timeWarranty')) {
        function timeWarranty($time)
        {

            if($time<=12 ){
                $numberTime=$time .' tháng';
            }else if($time==12){
                $numberTime=$time/12 .' năm';
            }else{
                $numberTime=$time/12;
                if(is_int($numberTime)){
                    $numberTime=$time/12 .' năm';
                }else{
                    $year=floor($time/12) .' năm';
                    $month=$time%12 .' tháng';
                    $numberTime=$year .' '.$month;
                }
            }

//            dd($numberTime);
//            $explode=explode(',',$numberTime);
//
//               $text=str_replace($numberTime, ',',"$numberTime năm");
//               dd($text);
            return is_null($time)?null:$numberTime;
        }
    }

    if (!function_exists('convertTime')) {
        function convertTime($strtotime)
        {

            if (is_numeric($strtotime)==true) {
                $strtotime=  date('H:i:s d-m-Y',$strtotime);
            }else{
                $strtotime=  date('H:i:s d-m-Y',strtotime($strtotime));
            }
           return $strtotime;
        }
    }


    if (!function_exists('removeImg')) {
        function removeImg($filename)
        {
            if (file_exists(base_path('public/') . $filename)) {
                unlink(base_path('public/') . $filename);
            }
        }
    }

    if (!function_exists('addWallet')) {
        function addWallet($user_id,$amount=null)
        {
            $wallet = Wallet::where('user_id', $user_id)->first();
            if(!$wallet){
                $wallet=new Wallet;
                $wallet->user_id=$user_id;
                $wallet->amount=$amount!=null?config_base64_encode($amount):config_base64_encode(0);
                $wallet->save();
            }else{
                if($amount!=null){
                    $wallet->amount =config_base64_encode($amount+config_base64_decode($wallet->amount));
                    $wallet->save();
                }
            }
            return $wallet;
        }
    }


    if (!function_exists('provinceMultiple')) {
        function provinceMultiple($data)
        {
            $province = Province::all()->pluck('name', 'id');
            $arr = [];
            foreach ($data as $val) {
                $arr[] = $province[$val];
            }
            return implode(',', $arr);
        }
    }


    if (!function_exists('convertAmount')) {
        function convertAmount($amount){
            $config=\App\Models\CommonConfig::first();
            $result=$amount/$config->exchange;
            return (int)$result.' '.'điểm';
        }
    }

    if (!function_exists('number_convert')) {
        function number_convert($amount){
            $result=$amount;
            return number_format($result,0,'.','.');
        }
    }

    if (!function_exists('revert_amount')) {
        function revert_amount($amount){
            $config=\App\Models\CommonConfig::first();
            $result=$amount*$config->exchange;
            return single_price($result);
        }
    }
