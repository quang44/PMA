<div class="aiz-sidebar-wrap">
    <div class="aiz-sidebar left c-scrollbar">
        <div class="aiz-side-nav-logo-wrap">
            <a href="<?php echo e(route('admin.dashboard')); ?>" class="d-block text-center">
                
                
                
                <img class="w-130px" style="height: 120px!important;" src="<?php echo e(static_asset('assets/img/photo_2022-12-05_10-35-43.jpg')); ?>"
                     class="brand-icon" alt="<?php echo e(get_setting('site_name')); ?>">
                
            </a>
        </div>
        <div class="aiz-side-nav-wrap">
            <div class="px-20px mb-3">
                <input class="form-control bg-soft-secondary border-0 form-control-sm text-white" type="text" name=""
                       placeholder="<?php echo e(translate('Search in menu')); ?>" id="menu-search" onkeyup="menuSearch()">
            </div>
            <ul class="aiz-side-nav-list" id="search-menu">
            </ul>
            <ul class="aiz-side-nav-list" id="main-menu" data-toggle="aiz-side-menu">
                <li class="aiz-side-nav-item">
                    <a href="<?php echo e(route('admin.dashboard')); ?>" class="aiz-side-nav-link">
                        <i class="las la-home aiz-side-nav-icon"></i>
                        <span class="aiz-side-nav-text"><?php echo e(translate('Dashboard')); ?></span>
                    </a>
                </li>

                <!-- POS Addon-->
                

                <?php if(Auth::user()->user_type == 'admin' || in_array('9', json_decode(Auth::user()->staff->role->permissions))): ?>
                    <li class="aiz-side-nav-item ">
                        <a href="<?php echo e(route('warranty_card.index')); ?>"
                           class="aiz-side-nav-link <?php echo e(areActiveRoutes(['warranty_card.index', 'warranty_card.show'])); ?>">
                            <i class="las la-credit-card aiz-side-nav-icon"></i>
                            <span class="aiz-side-nav-text">Danh sách bảo hành</span>
                            <span class="badge badge-info"
                                  style="margin-right: 3px"><?php echo e(\App\Models\WarrantyCard::count()); ?></span>
                        </a>
                    </li>
                <?php endif; ?>

            <!-- Customers -->
                <?php if(Auth::user()->user_type == 'admin' || in_array('8', json_decode(Auth::user()->staff->role->permissions))): ?>
                    <li class="aiz-side-nav-item">
                        <a href="#" class="aiz-side-nav-link">
                            <i class="las la-user-friends aiz-side-nav-icon"></i>
                            <span class="aiz-side-nav-text"><?php echo e(translate('Management')); ?> <?php echo e(translate('Customers')); ?></span>
                            <span class="badge badge-info"
                                  style="margin-right: 3px"><?php echo e(\App\Models\User::where('user_type','customer')->count()); ?></span>
                            <span class="aiz-side-nav-arrow"></span>
                        </a>
                        <ul class="aiz-side-nav-list level-2">
                            <li class="aiz-side-nav-item">
                                <a href="<?php echo e(route('customers.index')); ?>" class="aiz-side-nav-link">
                                    <span class="aiz-side-nav-text"><?php echo e(translate('Customer Account')); ?></span>
                                </a>
                            </li>
                            
                            
                            
                            
                            
                            
                        </ul>
                    </li>
                <?php endif; ?>

            <!-- Customers -->
                <?php if(Auth::user()->user_type == 'admin' || in_array('24', json_decode(Auth::user()->staff->role->permissions))): ?>
                    <li class="aiz-side-nav-item">
                        <a href="#" class="aiz-side-nav-link">
                            <i class="las la-gift aiz-side-nav-icon"></i>
                            <span class="aiz-side-nav-text"><?php echo e(translate('Management')); ?> <?php echo e(translate('Gift')); ?></span>
                            <span class="badge badge-info" style="margin-right: 3px"><?php echo e(\App\Models\GiftRequest::count()); ?></span>
                            <span class="aiz-side-nav-arrow"></span>
                        </a>
                        <ul class="aiz-side-nav-list level-2">
                            <li class="aiz-side-nav-item">
                                <a href="<?php echo e(route('gift.index')); ?>" class="aiz-side-nav-link">
                                    <span class="aiz-side-nav-text"><?php echo e(translate('List of gift')); ?></span>
                                </a>
                            </li>
                            <li class="aiz-side-nav-item">
                                <a href="<?php echo e(route('gift.giftRequest')); ?>"
                                   class="aiz-side-nav-link <?php echo e(areActiveRoutes(['gift.giftRequest'])); ?>">
                                    <span class="aiz-side-nav-text"><?php echo e(translate('Request gift exchange')); ?></span>
                                    <span class="badge badge-info" style="margin-right: 3px"><?php echo e(\App\Models\GiftRequest::count()); ?></span>

                                </a>
                            </li>

                        </ul>
                    </li>
                <?php endif; ?>

                <?php if(Auth::user()->user_type == 'admin' || in_array('9', json_decode(Auth::user()->staff->role->permissions))): ?>
                    <li class="aiz-side-nav-item ">
                        <a href="#"
                           class="aiz-side-nav-link <?php echo e(areActiveRoutes(['warranty_codes.index','warranty_codes.edit','warranty_codes.create', 'product_warranty.index','product_warranty.create','product_warranty.edit','attributes.index','attributes.create','attributes.edit'])); ?>">
                            <i class="las la-credit-card aiz-side-nav-icon"></i>
                            <span class="aiz-side-nav-text">Cấu hình bảo hành</span>
                            <span class="aiz-side-nav-arrow"></span>
                        </a>
                        <ul class="aiz-side-nav-list level-2">
                            <li class="aiz-side-nav-item">
                                <a href="<?php echo e(route('warranty_codes.index')); ?>"
                                   class="aiz-side-nav-link <?php echo e(areActiveRoutes(['warranty_codes.index'])); ?>">
                                    <span class="aiz-side-nav-text">Quản lý mã bảo hành</span>
                                    <span class="badge badge-info" style="margin-right: 3px"></span>
                                </a>
                            </li>
                            <li class="aiz-side-nav-item">
                                <a href="<?php echo e(route('product_warranty.index')); ?>"
                                   class="aiz-side-nav-link <?php echo e(areActiveRoutes(['product_warranty.index','product_warranty.create','product_warranty.edit'])); ?>">
                                    <span class="aiz-side-nav-text">Quản lý cửa bảo hành</span>
                                </a>
                            </li>
                            <li class="aiz-side-nav-item">
                                <a href="<?php echo e(route('colors')); ?>"
                                   class="aiz-side-nav-link <?php echo e(areActiveRoutes(['attributes.index','attributes.create','attributes.edit'])); ?>">
                                    <span class="aiz-side-nav-text">Quản lý màu sắc</span>
                                </a>
                            </li>
                        </ul>
                    </li>
                <?php endif; ?>




            <!-- Product -->
                <?php if(Auth::user()->user_type == 'admin' || in_array('2', json_decode(Auth::user()->staff->role->permissions))): ?>
                    <li class="aiz-side-nav-item">






                        <a href="<?php echo e(route('products.all')); ?>" class="aiz-side-nav-link">
                            <i class="las la-procedures aiz-side-nav-icon"></i>
                            <span class="aiz-side-nav-text">Quản lý sản phẩm</span>
                        </a>

                        <!--Submenu-->

                            






















































                            





                    </li>
                <?php endif; ?>

                <!-- Auction Product -->
                

                <!-- Wholesale Product -->
                <!-- Wholesale Product -->
                

                <!-- Sale -->


















































                <!-- Deliver Boy Addon-->
                <?php if(addon_is_activated('delivery_boy')): ?>
                    <?php if(Auth::user()->user_type == 'admin' || in_array('1', json_decode(Auth::user()->staff->role->permissions))): ?>
                        <li class="aiz-side-nav-item">
                            <a href="#" class="aiz-side-nav-link">
                                <i class="las la-truck aiz-side-nav-icon"></i>
                                <span class="aiz-side-nav-text"><?php echo e(translate('Delivery Boy')); ?></span>
                                <?php if(env("DEMO_MODE") == "On"): ?>
                                    <span class="badge badge-inline badge-danger">Addon</span>
                                <?php endif; ?>
                                <span class="aiz-side-nav-arrow"></span>
                            </a>
                            <ul class="aiz-side-nav-list level-2">
                                <li class="aiz-side-nav-item">
                                    <a href="<?php echo e(route('delivery-boys.index')); ?>" class="aiz-side-nav-link">
                                        <span class="aiz-side-nav-text"><?php echo e(translate('All Delivery Boy')); ?></span>
                                    </a>
                                </li>
                                <li class="aiz-side-nav-item">
                                    <a href="<?php echo e(route('delivery-boys.create')); ?>" class="aiz-side-nav-link">
                                        <span class="aiz-side-nav-text"><?php echo e(translate('Add Delivery Boy')); ?></span>
                                    </a>
                                </li>
                                <li class="aiz-side-nav-item">
                                    <a href="<?php echo e(route('delivery-boys-payment-histories')); ?>" class="aiz-side-nav-link">
                                        <span class="aiz-side-nav-text"><?php echo e(translate('Payment Histories')); ?></span>
                                    </a>
                                </li>
                                <li class="aiz-side-nav-item">
                                    <a href="<?php echo e(route('delivery-boys-collection-histories')); ?>" class="aiz-side-nav-link">
                                        <span class="aiz-side-nav-text"><?php echo e(translate('Collected Histories')); ?></span>
                                    </a>
                                </li>
                                <li class="aiz-side-nav-item">
                                    <a href="<?php echo e(route('delivery-boy.cancel-request')); ?>" class="aiz-side-nav-link">
                                        <span class="aiz-side-nav-text"><?php echo e(translate('Cancel Request')); ?></span>
                                    </a>
                                </li>
                                <li class="aiz-side-nav-item">
                                    <a href="<?php echo e(route('delivery-boy-configuration')); ?>" class="aiz-side-nav-link">
                                        <span class="aiz-side-nav-text"><?php echo e(translate('Configuration')); ?></span>
                                    </a>
                                </li>
                            </ul>
                        </li>
                    <?php endif; ?>
                <?php endif; ?>

                <!-- Refund addon -->
                



                <?php if(Auth::user()->user_type == 'admin' || in_array('29', json_decode(Auth::user()->staff->role->permissions))): ?>
                    <li class="aiz-side-nav-item">
                        <a href="#" class="aiz-side-nav-link">
                            <i class="las la-link aiz-side-nav-icon"></i>
                            <span class="aiz-side-nav-text"><?php echo e(translate('depot')); ?> - <?php echo e(translate('Agent')); ?> </span>
                            <span class="aiz-side-nav-arrow"></span>
                        </a>
                        <ul class="aiz-side-nav-list level-2">
                            
                            
                            
                            
                            
                            <li class="aiz-side-nav-item">
                                <a href="<?php echo e(route('affiliate.depot.index')); ?>"
                                   class="aiz-side-nav-link <?php echo e(areActiveRoutes(['affiliate.depot.index', 'affiliate.depot.create', 'affiliate.depot.edit'])); ?>">
                                    <span class="aiz-side-nav-text"><?php echo e(translate('depot')); ?></span>
                                </a>
                            </li>
                            <li class="aiz-side-nav-item">
                                <a href="<?php echo e(route('affiliate.employee.index')); ?>"
                                   class="aiz-side-nav-link <?php echo e(areActiveRoutes(['affiliate.employee.index', 'affiliate.employee.create', 'affiliate.employee.edit'])); ?>">
                                    <span class="aiz-side-nav-text"><?php echo e(translate('Agent')); ?></span>
                                </a>
                            </li>
                            
                            
                            
                            
                            
                            
                            
                            
                            

                            
                            
                            
                            
                            
                            
                            
                        </ul>
                    </li>
                <?php endif; ?>




                <?php if(Auth::user()->user_type == 'admin' || in_array('28', json_decode(Auth::user()->staff->role->permissions))): ?>
                    <li class="aiz-side-nav-item">
                        <a href="<?php echo e(route('news.index')); ?>"
                           class="aiz-side-nav-link <?php echo e(areActiveRoutes(['news.index', 'news.create', 'news.edit'])); ?>">
                            <i class="las la-newspaper aiz-side-nav-icon"></i>
                            <span class="aiz-side-nav-text"><?php echo e(translate('Management')); ?> <?php echo e(translate('News')); ?></span>
                        </a>
                    </li>
                <?php endif; ?>










                <!--Accounting-->
























































                <!-- Sellers -->
                
                <?php if(Auth::user()->user_type == 'admin' || in_array('22', json_decode(Auth::user()->staff->role->permissions))): ?>
                    <li class="aiz-side-nav-item">
                        <a href="<?php echo e(route('uploaded-files.index')); ?>"
                           class="aiz-side-nav-link <?php echo e(areActiveRoutes(['uploaded-files.create'])); ?>">
                            <i class="las la-folder-open aiz-side-nav-icon"></i>
                            <span class="aiz-side-nav-text"><?php echo e(translate('Uploaded Files')); ?></span>
                        </a>
                    </li>
                <?php endif; ?>

            <!-- Reports -->
                
                
                
                
                
                
                
                
                
                
                
                
                
                
                
































                <!--Blog System-->
                

            <!-- marketing -->
                
                
                
                
                
                
                
                
                
                
                
                
                
                
                
                <?php if(Auth::user()->user_type == 'admin' || in_array('11', json_decode(Auth::user()->staff->role->permissions))): ?>
                    <li class="aiz-side-nav-item">

                        <a href="<?php echo e(route('banner.index')); ?>"
                           class="aiz-side-nav-link <?php echo e(areActiveRoutes(['banner.index', 'banner.create', 'banner.edit'])); ?>">
                            <i class="las la-image aiz-side-nav-icon"></i>
                            <span class="aiz-side-nav-text"><?php echo e(translate('Management')); ?> <?php echo e(translate('Banners')); ?></span>
                        </a>
                    </li>
                    
                    
                    
                    
                    
                    
                    
                    
                    
                    
                    
                    
                    
                    
                    
                    
                    
                    
                    
                    

















                <?php endif; ?>

                <!-- Support -->
                <?php
                    $support_ticket = DB::table('tickets')
                                ->where('viewed', 0)
                                ->select('id')
                                ->count();
                ?>










                <!-- Affiliate Addon -->































































































                <!-- Offline Payment Addon-->
                

                <!-- Paytm Addon -->
                

                <!-- Club Point Addon-->
                

                <!--OTP addon -->
                

                

                <!-- Website Setup -->
                <?php if(Auth::user()->user_type == 'admin' || in_array('14', json_decode(Auth::user()->staff->role->permissions))): ?>
                    <li class="aiz-side-nav-item">
                        <a href="<?php echo e(route('common_configs.index')); ?>" class="aiz-side-nav-link" >
                            <i class="las la-desktop aiz-side-nav-icon"></i>
                            <span class="aiz-side-nav-text"><?php echo e(translate('Common Config')); ?></span>
                        </a>



























                    </li>
                <?php endif; ?>

            <!-- Setup & Configurations -->
                
                
                
                
                
                
                
                
                
                
                
                
                
                
                
                
                
                
                
                
                
                
                

                
                
                
                
                
                
                
                
                
                
                
                
                
                
                
                
                
                
                







































































































                <!-- Staffs -->
                <?php if(Auth::user()->user_type == 'admin' || in_array('20', json_decode(Auth::user()->staff->role->permissions))): ?>
                    <li class="aiz-side-nav-item">
                        <a href="#" class="aiz-side-nav-link">
                            <i class="las la-user-tie aiz-side-nav-icon"></i>
                            <span class="aiz-side-nav-text"><?php echo e(translate('Management')); ?>  <?php echo e(translate('Employee')); ?></span>
                            <span class="aiz-side-nav-arrow"></span>
                        </a>
                        <ul class="aiz-side-nav-list level-2">
                            <li class="aiz-side-nav-item">
                                <a href="<?php echo e(route('staffs.index')); ?>"
                                   class="aiz-side-nav-link <?php echo e(areActiveRoutes(['staffs.index', 'staffs.create', 'staffs.edit'])); ?>">
                                    <span class="aiz-side-nav-text"><?php echo e(translate('Employee')); ?></span>
                                </a>
                            </li>
                            <li class="aiz-side-nav-item">
                                <a href="<?php echo e(route('roles.index')); ?>"
                                   class="aiz-side-nav-link <?php echo e(areActiveRoutes(['roles.index', 'roles.create', 'roles.edit'])); ?>">
                                    <span class="aiz-side-nav-text">Nhóm quyền</span>
                                </a>
                            </li>
                        </ul>
                    </li>
                <?php endif; ?>

                










                
                <?php if(Auth::user()->user_type == 'admin' || in_array('27', json_decode(Auth::user()->staff->role->permissions))): ?>
                    <li class="aiz-side-nav-item">
                        <a href="<?php echo e(route('website.pages')); ?>"
                           class="aiz-side-nav-link <?php echo e(areActiveRoutes(['website.pages'])); ?>">
                            <i class="las la-pager aiz-side-nav-icon"></i>
                            <span class="aiz-side-nav-text"><?php echo e(translate('All pages')); ?></span>
                        </a>
                    </li>
                <?php endif; ?>

                



                <?php if(Auth::user()->user_type == 'admin' || in_array('30', json_decode(Auth::user()->staff->role->permissions))): ?>
                    <li class="aiz-side-nav-item">
                        <a href="<?php echo e(route('admin.all-notification')); ?>"
                           class="aiz-side-nav-link <?php echo e(areActiveRoutes(['admin.all-notification'])); ?>">
                            <i class="las la-newspaper aiz-side-nav-icon"></i>
                            <span class="aiz-side-nav-text"><?php echo e(translate('Management')); ?> <?php echo e(translate('Notifications')); ?></span>
                        </a>
                    </li>
                <?php endif; ?>
                
                
                
                
                
                
                
                
                
                
                
                


                

                <!-- Addon Manager -->
                
            </ul><!-- .aiz-side-nav -->
        </div><!-- .aiz-side-nav-wrap -->
    </div><!-- .aiz-sidebar -->
    <div class="aiz-sidebar-overlay"></div>
</div><!-- .aiz-sidebar -->
<?php /**PATH F:\PHP\PMA\resources\views/backend/inc/admin_sidenav.blade.php ENDPATH**/ ?>