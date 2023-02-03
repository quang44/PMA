<?php $__env->startSection('content'); ?>
    <div class="aiz-titlebar text-left mt-2 mb-3 row">
        <div class=" col-md-6 align-items-center">
            <h1 class="h3"><?php echo e(translate('List of Warranty')); ?></h1>
        </div>
        
        
        
        
        
    </div>

    <div class="card">
        <form class="" id="sort_Card" action="" method="GET">
            <div class="card-header row gutters-5">
                <div class="col">
                    <h5 class="mb-0 h6"><?php echo e(translate('List of Warranty')); ?></h5>
                </div>
                <div class="col-md-3">
                    <div class="form-group mb-0">
                        <select name="sort_customer" id="sort_selectCart" class="form-control aiz-selectpicker"
                                data-selected-text-format="count"
                                data-live-search="true"
                        >
                            <option value="-1">Người tạo</option>
                            <?php $__currentLoopData = $customers; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $customer): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <option  <?php if(request('sort_customer',-1)==$customer->id): ?> selected <?php endif; ?>  value="<?php echo e($customer->id); ?>"><?php echo e($customer->name); ?></option>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </select>
                    </div>
                </div>

                <div class="col-md-3">
                    <div class="form-group mb-0">
                        <select name="sort_status" id="sort_selectCart" class="form-control aiz-selectpicker"
                                data-selected-text-format="count"
                                data-live-search="true"
                        >
                            <option value="-1">Trạng thái của thẻ....</option>
                            <option value="0"
                                    <?php if(request('sort_status',-1)==0): ?> selected <?php endif; ?>>
                                <?php echo e(\App\Utility\WarrantyCardUtility::$aryStatus[\App\Utility\WarrantyCardUtility::STATUS_NEW]); ?>

                            </option>
                            <option value="1"
                                    <?php if(request('sort_status',-1)==1): ?> selected <?php endif; ?>>
                                <?php echo e(\App\Utility\WarrantyCardUtility::$aryStatus[\App\Utility\WarrantyCardUtility::STATUS_SUCCESS]); ?>

                            </option>
                            <option value="2"
                                    <?php if(request('sort_status',-1)==2): ?> selected <?php endif; ?>>
                                <?php echo e(\App\Utility\WarrantyCardUtility::$aryStatus[\App\Utility\WarrantyCardUtility::STATUS_CANCEL]); ?>

                            </option>
                        </select>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="form-group mb-0">

                        <input type="text" class="form-control aiz-selectpicker " id="search" name="search"
                               <?php if(isset($search)): ?> value="<?php echo e($search); ?>"
                               <?php endif; ?> placeholder="<?php echo e(translate('enter customer name or phone number or address')); ?>">
                    </div>
                </div>
            </div>

            <div class="card-body">
                <div class="table-responsive">
                    <table class="table aiz-table mb-0">
                        <thead>
                        <tr>
                            <th data-breakpoints="md">Người bảo hành</th>
                            <th data-breakpoints="md"><?php echo e(translate('Customer info')); ?></th>
                            <th data-breakpoints="md">Cửa bảo hành</th>
                            <th data-breakpoints="md"><?php echo e(translate('Warranty code')); ?></th>
                            <th data-breakpoints="md"><?php echo e(translate('Created_at')); ?></th>
                            <th data-breakpoints="md"><?php echo e(translate('Active time')); ?> / Hủy</th>
                            <th data-breakpoints="md">Người xác nhận</th>
                            <th data-breakpoints="md"><?php echo e(translate('Status')); ?></th>
                            <th class="text-right"><?php echo e(translate('Options')); ?></th>
                        </tr>

                        </thead>
                        <tbody>
                        <?php $__currentLoopData = $warranty_cards; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key => $warranty_card): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <?php if($warranty_card != null): ?>
                                <tr>
                                    <td>
                                        <?php if($warranty_card->user): ?>
                                            <?php echo e($warranty_card->user->name); ?>

                                        <?php else: ?>
                                            người dùng không tồn tại
                                            <?php endif; ?>
                                        </td>
                                    <td>
                                        <?php echo e(translate('Customer')); ?> : <?php echo e(ucfirst($warranty_card->user_name)); ?> <br>
                                        <?php echo e(translate('Address')); ?> : <?php echo e(ucfirst($warranty_card->address)); ?>, <?php echo e(ucfirst($warranty_card->ward->name)); ?>, <?php echo e(ucfirst($warranty_card->district->name)); ?>, <?php echo e(ucfirst($warranty_card->province->name)); ?> <br>
                                        <?php echo e(translate('phone')); ?> : <?php echo e(ucfirst($warranty_card->phone)); ?>


                                    </td>
                                    <td>
                                        <?php $__currentLoopData = $warranty_card->cardDetail; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $pr): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                           - <?php echo e(!$pr->product?'not found':$pr->product->name); ?> <br>
                                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                    </td>
                                    <td>
                                        <?php echo e($warranty_card->warranty_code); ?>

                                    </td>
                                    <td>
                                        <?php echo e(convertTime($warranty_card->create_time)); ?>

                                    </td>
                                    <td> <?php if($warranty_card->active_time>0): ?>
                                            <?php echo e(convertTime($warranty_card->active_time)); ?>

                                        <?php else: ?>
                                            --
                                        <?php endif; ?>
                                    </td>

                                    <td>
                                        <?php if($warranty_card->accept_by!=null): ?>
                                            <?php if($warranty_card->active_user_id!=null && $warranty_card->active_user_id->user_type='admin'): ?>
                                                <span class="badge badge-inline badge-success">Admin</span>
                                            <?php else: ?>
                                                <span class="badge badge-inline badge-success">CTV</span>
                                            <?php endif; ?>
                                        <?php endif; ?>
                                    </td>

                                    <td>
                                        <?php if($warranty_card->status == 0): ?>
                                            <span class="badge badge-inline badge-secondary">
                                                <?php echo e(\App\Utility\WarrantyCardUtility::$aryStatus[\App\Utility\WarrantyCardUtility::STATUS_NEW]); ?></span>
                                        <?php else: ?>
                                            <?php if($warranty_card->status == 1): ?>
                                                <span class="badge badge-inline badge-success">
                                                    <?php echo e(\App\Utility\WarrantyCardUtility::$aryStatus[\App\Utility\WarrantyCardUtility::STATUS_SUCCESS]); ?></span>
                                            <?php else: ?>
                                                <span class="badge badge-inline badge-danger">
                                                    <?php echo e(\App\Utility\WarrantyCardUtility::$aryStatus[\App\Utility\WarrantyCardUtility::STATUS_CANCEL]); ?></span>
                                            <?php endif; ?>
                                        <?php endif; ?>
                                    </td>
                                    <td class="text-right">
                                        <?php if($warranty_card->status==0 ): ?>
                                            <a href="javascript:void(0)"
                                               class="btn btn-soft-info btn-icon btn-circle btn-sm"
                                               onclick="updateCard('<?php echo e(route('warranty_card.ban', encrypt($warranty_card->id))); ?>',1);"
                                               title="<?php echo e(translate('Kích hoạt thẻ')); ?>">
                                                <i class="las la-credit-card"></i>
                                            </a>

                                            <a href="javascript:void(0)"
                                               class="btn btn-soft-danger btn-icon btn-circle btn-sm"
                                               onclick="confirm_ban('<?php echo e(route('warranty_card.ban', encrypt($warranty_card->id))); ?>' ,2);"
                                               title="<?php echo e(translate('Hủy thẻ')); ?>">
                                                <i class="las la-credit-card"></i>
                                            </a>
                                        <?php endif; ?>
                                            <a class="btn btn-soft-primary btn-icon btn-circle btn-sm"
                                               href="<?php echo e(route('warranty_card.show',[encrypt($warranty_card->id)])); ?>"
                                               title="View">
                                                <i class="las la-eye"></i>
                                            </a>

                                        
                                        
                                        
                                        
                                        
                                        
                                        
                                        
                                    </td>

                                </tr>
                            <?php endif; ?>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </tbody>
                    </table>
                </div>

                                <div class="aiz-pagination">
                                    <?php echo e($warranty_cards->appends(request()->input())->links()); ?>

                                </div>
            </div>
        </form>
    </div>



<?php $__env->startSection('modal'); ?>
    <?php echo $__env->make('modals.confirm_modal', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
<?php $__env->stopSection(); ?>


<?php $__env->stopSection(); ?>

<?php $__env->startSection('modal'); ?>
    <?php echo $__env->make('modals.delete_modal', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('script'); ?>
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

        $('#search').change(function () {
            $('#sort_Card').submit();
        })

        $(document).on('change','#sort_selectCart' ,function () {
            $('#sort_Card').submit();
        })

        function confirm_ban(url, status) {
            $('#confirm-ban').modal('show', {backdrop: 'static'});
            document.getElementById('confirmation').setAttribute('action', url + '?status=' + status);
        }

        function updateCard(url, status) {
            $('#confirm-update-bank').modal('show', {backdrop: 'static'});
            document.getElementById('updateCard').setAttribute('href', url + '?status=' + status);
        }


        function bulk_delete() {
            var data = new FormData($('#sort_Card')[0]);
            $.ajax({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                url: "<?php echo e(route('bulk-customer-delete')); ?>",
                type: 'POST',
                data: data,
                cache: false,
                contentType: false,
                processData: false,
                success: function (response) {
                    if (response == 1) {
                        location.reload();
                    }
                }
            });
        }
    </script>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('backend.layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH F:\PHP\PMA\resources\views/backend/customer/warranty_cards/index.blade.php ENDPATH**/ ?>