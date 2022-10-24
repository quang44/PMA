<?php

namespace App\Http\Controllers;

use App\Models\AffiliatePayment;
use App\Models\Wallet;
use App\Models\WarrantyCard;
use Illuminate\Http\Request;
use App\Models\Category;
use App\Models\Product;
use Artisan;
use Cache;
use CoreComponentRepository;

class AdminController extends Controller
{
    /**
     * Show the admin dashboard.
     *
     * @return \Illuminate\Http\Response
     */
    public function admin_dashboard(Request $request)
    {
        CoreComponentRepository::initializeCache();
        $root_categories = Category::where('level', 0)->get();

        $cached_graph_data = Cache::remember('cached_graph_data', 86400, function() use ($root_categories){
            $num_of_sale_data = null;
            $qty_data = null;
            foreach ($root_categories as $key => $category){
                $category_ids = \App\Utility\CategoryUtility::children_ids($category->id);
                $category_ids[] = $category->id;

                $products = Product::with('stocks')->whereIn('category_id', $category_ids)->get();
                $qty = 0;
                $sale = 0;
                foreach ($products as $key => $product) {
                    $sale += $product->num_of_sale;
                    foreach ($product->stocks as $key => $stock) {
                        $qty += $stock->qty;
                    }
                }
                $qty_data .= $qty.',';
                $num_of_sale_data .= $sale.',';
            }
            $item['num_of_sale_data'] = $num_of_sale_data;
            $item['qty_data'] = $qty_data;

            return $item;
        });

        $history_withdraws = AffiliatePayment::where('status', 2)->get();
        $wallet = Wallet::where('id', '>', 0)->get();
        $total_withdraw = 0;
        $total_not_withdraw = 0;
        $total_active = WarrantyCard::where('status', 1)->count();
        foreach ($history_withdraws as $key => $history_withdraw){
            $total_withdraw += $history_withdraw->amount;
        }

        foreach ($wallet as $key => $not_withdraw){
            $total_not_withdraw += (int)config_base64_decode($not_withdraw->amount);
        }

        return view('backend.dashboard', compact('root_categories', 'cached_graph_data', 'total_withdraw', 'total_not_withdraw', 'total_active'));
    }

    function clearCache(Request $request)
    {
        Artisan::call('cache:clear');
        flash(translate('Cache cleared successfully'))->success();
        return back();
    }
}
