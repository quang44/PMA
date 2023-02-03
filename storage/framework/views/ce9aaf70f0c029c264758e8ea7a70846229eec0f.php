
<div class="aiz-topbar px-15px px-lg-25px d-flex align-items-stretch justify-content-between">
    <div class="d-flex">
        <div class="aiz-topbar-nav-toggler d-flex align-items-center justify-content-start mr-2 mr-md-3 ml-0" data-toggle="aiz-mobile-nav">
            <button class="aiz-mobile-toggler">
                <span></span>
            </button>
        </div>
    </div>
    <div class="d-flex justify-content-between align-items-stretch flex-grow-xl-1">
        <div class="d-flex justify-content-around align-items-center align-items-stretch">
            <div class="d-flex justify-content-around align-items-center align-items-stretch">
                <div class="aiz-topbar-item">
                    <div class="d-flex align-items-center">
                        <a class="btn btn-icon btn-circle btn-light" href="<?php echo e(route('home')); ?>" target="_blank" title="<?php echo e(translate('Browse Website')); ?>">
                            <i class="las la-globe"></i>
                        </a>
                    </div>
                </div>
            </div>
            <?php if(addon_is_activated('pos_system')): ?>
                <div class="d-flex justify-content-around align-items-center align-items-stretch ml-3">
                    <div class="aiz-topbar-item">
                        <div class="d-flex align-items-center">
                            <a class="btn btn-icon btn-circle btn-light" href="<?php echo e(route('poin-of-sales.index')); ?>" target="_blank" title="<?php echo e(translate('POS')); ?>">
                                <i class="las la-print"></i>
                            </a>
                        </div>
                    </div>
                </div>
            <?php endif; ?>
            <div class="d-flex justify-content-around align-items-center align-items-stretch ml-3">
                <div class="aiz-topbar-item">
                    <div class="d-flex align-items-center">
                        <a class="btn btn-soft-danger btn-sm d-flex align-items-center" href="<?php echo e(route('cache.clear')); ?>">
                            <i class="las la-hdd fs-20"></i>
                            <span class="fw-500 ml-1 mr-0 d-none d-md-block"><?php echo e(translate('Clear Cache')); ?></span>
                        </a>
                    </div>
                </div>
            </div>
        </div>
        <div class="d-flex justify-content-around align-items-center align-items-stretch">




















































            






















            <div class="aiz-topbar-item ml-2">
                <div class="align-items-stretch d-flex dropdown">
                    <a class="dropdown-toggle no-arrow text-dark" data-toggle="dropdown" href="javascript:void(0);" role="button" aria-haspopup="false" aria-expanded="false">
                        <span class="d-flex align-items-center">
                            <span class="avatar avatar-sm mr-md-2">
                                <img
                                    src="<?php echo e(uploaded_asset(Auth::user()->avatar_original)); ?>"
                                    onerror="this.onerror=null;this.src='<?php echo e(static_asset('assets/img/avatar-place.png')); ?>';"
                                >
                            </span>
                            <span class="d-none d-md-block">
                                <span class="d-block fw-500"><?php echo e(Auth::user()->name); ?></span>
                                <span class="d-block small opacity-60"><?php echo e(Auth::user()->user_type); ?></span>
                            </span>
                        </span>
                    </a>
                    <div class="dropdown-menu dropdown-menu-right dropdown-menu-animated dropdown-menu-md">
                        <a href="<?php echo e(route('profile.index')); ?>" class="dropdown-item">
                            <i class="las la-user-circle"></i>
                            <span><?php echo e(translate('Profile')); ?></span>
                        </a>

                        <a href="<?php echo e(route('logout')); ?>" class="dropdown-item">
                            <i class="las la-sign-out-alt"></i>
                            <span><?php echo e(translate('Logout')); ?></span>
                        </a>
                    </div>
                </div>
            </div><!-- .aiz-topbar-item -->
        </div>
    </div>
</div><!-- .aiz-topbar -->
<?php /**PATH F:\PHP\PMA\resources\views/backend/inc/admin_nav.blade.php ENDPATH**/ ?>