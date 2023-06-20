

<?php $__env->startSection('content'); ?>

    <div class="aiz-titlebar text-left mt-2 mb-3 row">
        <div class=" col-md-6 align-items-center">
            <h1 class="h3"><?php echo e(translate('List of gift request')); ?></h1>
        </div>
        
        
        
        
        
    </div>

    <div class="card">
        <form class="" id="sort_Gift" action="" method="GET">
            <div class="card-header row gutters-5">
                <div class="col">
                    <h5 class="mb-0 h6"><?php echo e(translate('Gift request')); ?></h5>
                </div>
                
                
                
                
                
                
                
                

                <div class="col-md-3">
                    <div class="form-group mb-0">
                        <select name="sort_status" id="sort_selectGift" class="form-control aiz-selectpicker"
                                data-selected-text-format="count"
                                data-live-search="true"
                        >
                            <option value="-1"><?php echo e(translate('gift status')); ?></option>
                            <option value="0"
                                    <?php if(request('sort_status',-1)==0): ?> selected <?php endif; ?>><?php echo e(translate('Not approved yet')); ?></option>
                            <option value="1"
                                    <?php if(request('sort_status',-1)==1): ?> selected <?php endif; ?>><?php echo e(translate('Approved')); ?></option>
                            <option value="2"
                                    <?php if(request('sort_status',-1)==2): ?> selected <?php endif; ?>><?php echo e(translate('Cancelled')); ?></option>
                        </select>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="form-group mb-0">

                        <input type="text" class="form-control" id="search" name="search"
                               <?php if(isset($search)): ?> value="<?php echo e($search); ?>"
                               <?php endif; ?> placeholder="<?php echo e(translate('Search')); ?>">
                    </div>
                </div>
            </div>

            <div class="card-body">
                <div class="table-responsive">
                    <table class="table aiz-table mb-0">
                        <thead>
                        <tr>
                            <th data-breakpoints="md">#</th>
                            <th data-breakpoints="md"><?php echo e(translate('Customer')); ?></th>
                            <th data-breakpoints="md"><?php echo e(translate('Info')); ?></th>
                            <th data-breakpoints="md"><?php echo e(translate('Status')); ?></th>
                            <th data-breakpoints="md"><?php echo e(translate('Created_at')); ?></th>
                            <th data-breakpoints="md"><?php echo e(translate('Active Time')); ?> / Hủy</th>
                            <th data-breakpoints="md">Người duyệt</th>
                            <th class="text-right"><?php echo e(translate('Options')); ?></th>
                        </tr>
                        </thead>
                        <tbody>
                        <?php $__currentLoopData = $giftRequest; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key => $gift): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <?php if($gift != null): ?>
                                <tr>

                                    <td>
                                        <?php echo e($key+1); ?>

                                    </td>

                                    <td>
                                        <?php if($gift->user!=null): ?>
                                            <?php echo e($gift->user->name); ?>  <br>
                                            <?php echo e($gift->user->phone); ?> <br>
                                            <?php echo e($gift->user->address); ?>

                                        <?php else: ?>
                                            người dùng không tồn tại

                                        <?php endif; ?>

                                    </td>
                                    <td>
                                        <div class="row" id="gallery<?php echo e($key); ?>">
                                            <a class="a-key" data-key="<?php echo e($key); ?>" href="<?php echo e(uploaded_asset($gift->gift->image)); ?>" >
                                             <img width="80px" src="<?php echo e(uploaded_asset($gift->gift->image)); ?>">
                                            </a>
                                        </div>
                                           <br>
                                        Tên quà: <?php echo e($gift->gift->name); ?> <br>
                                        Điểm quà: <?php echo e($gift->gift->point); ?>


                                    </td>
                                    <td>
                                        <?php if($gift->status == 0): ?>
                                            <span class="badge badge-inline badge-secondary">
                                                <?php echo e(\App\Utility\WarrantyCardUtility::$aryStatus[\App\Utility\WarrantyCardUtility::STATUS_NEW]); ?></span>
                                        <?php else: ?>
                                            <?php if($gift->status == 1): ?>
                                                <span class="badge badge-inline badge-success">
                                                    <?php echo e(\App\Utility\WarrantyCardUtility::$aryStatus[\App\Utility\WarrantyCardUtility::STATUS_SUCCESS]); ?>

</span>
                                            <?php else: ?>
                                                <span class="badge badge-inline badge-danger">
                                                    <?php echo e(\App\Utility\WarrantyCardUtility::$aryStatus[\App\Utility\WarrantyCardUtility::STATUS_CANCEL]); ?>

</span>
                                            <?php endif; ?>
                                        <?php endif; ?>
                                    </td>

                                    <td><?php echo e(convertTime($gift->created_time)); ?></td>
                                    <td><?php echo e($gift->active_time!=null?convertTime($gift->active_time):'--'); ?></td>
                                    <td><?php echo e($gift->accept_by!=null?$gift->accept->name:''); ?></td>
                                    <td class="text-right">

                                        <a class="btn btn-soft-primary btn-icon btn-circle btn-sm"
                                           href="<?php echo e(route('gift.show',[encrypt($gift->id)])); ?>"
                                           title="View">
                                            <i class="las la-eye"></i>
                                        </a>
                                        <?php if($gift->status==0 ): ?>
                                            <a href="javascript:void(0)"
                                               class="btn btn-soft-info btn-icon btn-circle btn-sm"
                                               onclick="updateCard('<?php echo e(route('gift_ban', [encrypt($gift->id)])); ?>',1);"
                                               title="xác nhận yêu cầu">
                                                <i class="las la-gifts"></i>
                                            </a>


                                            <a href="javascript:void(0)"
                                               class="btn btn-soft-danger btn-icon btn-circle btn-sm"
                                               onclick="confirm_ban('<?php echo e(route('gift_ban', [encrypt($gift->id)])); ?>' ,2);"
                                               title="hủy yêu cầu">
                                                <i class="las la-gifts"></i>
                                            </a>
                                        <?php endif; ?>

                                    </td>
                                </tr>
                            <?php endif; ?>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </tbody>
                    </table>
                </div>

                <div class="aiz-pagination">
                    <?php echo e($giftRequest->appends(request()->input())->links()); ?>

                </div>
            </div>
        </form>
    </div>




<?php $__env->stopSection(); ?>


<?php $__env->startSection('modal'); ?>
    <?php echo $__env->make('modals.confirm_modal', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
    <?php echo $__env->make('modals.delete_modal', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('script'); ?>
    <script src="<?php echo e(asset('public/assets/js/sweetalert2@11.js')); ?>"></script>
    <script type="text/javascript">

        window.onload = function () {
            $(document).on('focus', '.a-key', function (e) {
                let key = $(this).attr('data-key')
                $(`div#gallery` + key).magnificPopup({
                    delegate: 'a',
                    type: 'image',
                    gallery: {
                        enabled: true
                    }
                })
            })
        }

        $('#search').on('change',function () {
            $('#sort_Gift').submit();
        })

        function confirm_ban(url, status) {
            $('#confirm-ban').modal('show', {backdrop: 'static'});
            document.getElementById('confirmation').setAttribute('action', url + '?status=' + status);
        }

        function updateCard(url, status) {
            $('#confirm-update-bank').modal('show', {backdrop: 'static'});
            document.getElementById('updateCard').setAttribute('href', url + '?status=' + status);
        }


        $('#sort_selectGift').on('change', function () {
            $('#sort_Gift').submit();
        })


        $(document).on("change", ".check-all", function () {
            if (this.checked) {
                // Iterate each checkbox
                $('.check-one:checkbox').each(function () {
                    this.checked = true;
                });
            } else {
                $('.check-one:checkbox').each(function () {
                    this.checked = false;
                });
            }

        });


    </script>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('backend.layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH F:\PHP\PMA\resources\views/backend/marketing/gift/request.blade.php ENDPATH**/ ?>