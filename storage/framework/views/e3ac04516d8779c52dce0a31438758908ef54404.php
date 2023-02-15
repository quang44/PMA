<?php $__env->startSection('content'); ?>
    <style>
        .remove-attachment {
            display: none;
        }
    </style>
    <div class="row">
        <div class="col-lg-12 mx-auto">
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0 h6"><?php echo e(translate('Info Warranty Card')); ?> </h5>
                </div>
                <div class="card-body align-content-center">
                    <div class="form-group row">
                        <label class="col-sm-3 col-from-label" for="name">Người tạo :</label>
                        <div class="col-sm-9">
                            <span>
                                <?php if($warranty_card->user): ?>
                                    <?php echo e(strtoupper($warranty_card->user->name)); ?>

                                <?php else: ?>
                                    người dùng không tồn tại
                                    <?php endif; ?>
                               </span>
                        </div>
                    </div>

                    <div class="form-group row">
                        <label class="col-sm-3 col-from-label" for="name"><?php echo e(translate('Customer')); ?> :</label>
                        <div class="col-sm-9">
                            <span><?php echo e(strtoupper($warranty_card->user_name)); ?></span>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-sm-3 col-from-label" for="Seri"><?php echo e(translate('Phone')); ?> :</label>
                        <div class="col-sm-9">
                            <span><?php echo e($warranty_card->phone); ?></span>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-sm-3 col-from-label" for="email"><?php echo e(translate('email')); ?> :</label>
                        <div class="col-sm-9">
                            <span><?php echo e($warranty_card->email??'Chưa có email'); ?></span>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-sm-3 col-from-label" for="address"><?php echo e(translate('Address')); ?> :</label>
                        <div class="col-sm-9">
                            <span><?php echo e($warranty_card->address); ?>, <?php echo e($warranty_card->ward->name); ?>, <?php echo e($warranty_card->district->name); ?>, <?php echo e($warranty_card->province->name); ?></span>

                        </div>
                    </div>

                    <div class="form-group row">
                        <label class="col-sm-3 col-from-label" for="Seri"><?php echo e(translate('Created_at')); ?> :</label>
                        <div class="col-sm-9">
                            <span><?php echo e(convertTime($warranty_card->create_time)); ?></span>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-sm-3 col-from-label" for="Seri"><?php echo e(translate('Active time')); ?> / Hủy
                            :</label>
                        <div class="col-sm-9">
                                <span class="text-danger"><?php if($warranty_card->active_time>0): ?>
                                        <?php echo e(convertTime($warranty_card->active_time)); ?>

                                    <?php else: ?>
                                        --
                                    <?php endif; ?>
                                </span>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-sm-3 col-from-label" for="Seri"><?php echo e(translate('Warranty code')); ?>:</label>
                        <div class="col-sm-9">
                         <?php echo e($warranty_card->warranty_code); ?>

                        </div>
                    </div>

                    <div class="form-group row">
                        <label class="col-sm-3 col-from-label" for="package_id"><?php echo e(translate('Accept by')); ?>

                            :</label>
                        <div class="col-sm-9">
                            <span>
                                        <?php if($warranty_card->accept_by!=null): ?>
                                    <?php if($warranty_card->active_user_id!=null && $warranty_card->active_user_id->user_type='admin'): ?>
                                        <span class="badge badge-inline badge-success">Admin</span>
                                    <?php else: ?>
                                        <span class="badge badge-inline badge-success">CTV</span>
                                    <?php endif; ?>
                                <?php endif; ?>
                            </span>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-sm-3 col-from-label" for="package_id"><?php echo e(translate('Status')); ?> :</label>
                        <div class="col-sm-9">

                            <?php if($warranty_card->status==0): ?>
                                <span class="badge badge-inline badge-secondary">
                                    <?php echo e(\App\Utility\WarrantyCardUtility::$aryStatus[\App\Utility\WarrantyCardUtility::STATUS_NEW]); ?>

                                        </span>
                            <?php elseif($warranty_card->status==1): ?>
                                <span class="badge badge-inline badge-success">
                                        <?php echo e(\App\Utility\WarrantyCardUtility::$aryStatus[\App\Utility\WarrantyCardUtility::STATUS_SUCCESS]); ?>

                                </span>
                            <?php else: ?>
                                <span class="badge badge-inline badge-danger">
                                        <?php echo e(\App\Utility\WarrantyCardUtility::$aryStatus[\App\Utility\WarrantyCardUtility::STATUS_CANCEL]); ?>

                                             </span> / lý do :  <?php echo e($warranty_card->reason); ?>

                            <?php endif; ?>


                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-sm-3 col-from-label" for="package_id"><?php echo e(translate('Note')); ?>

                            :</label>
                        <div class="col-sm-9">
                            <span><?php echo e($warranty_card->note); ?></span>
                        </div>
                    </div>

                    <div class="  row">
                        <div class="card-body">
                            <div class="table-responsive">
                        <table class="table aiz-table mb-0">
                            <thead>
                            <tr>
                                <th><?php echo e(translate('Product')); ?> </th>
                                <th data-breakpoints="lg"><?php echo e(translate('Image')); ?></th>
                                <th data-breakpoints="lg"><?php echo e(translate('Video')); ?></th>
                                <th data-breakpoints="lg"><?php echo e(translate('Color')); ?></th>
                                <th data-breakpoints="lg"><?php echo e(translate('Quantity')); ?></th>
                            </tr>
                            </thead>
                            <tbody>
                           <?php $__currentLoopData = $warranty_card->cardDetail; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key=> $detail): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <tr>
                                <td><?php echo e($detail->product!=null?$detail->product->name:"sản phẩm không tồn tại"); ?></td>
                                <td>
                                    <div class="row" id="gallery<?php echo e($key); ?>">
                                    <a class="a-key" data-key="<?php echo e($key); ?>" href="<?php echo e(static_asset($detail->image)); ?>" >
                                    <img src="<?php echo e(static_asset($detail->image)); ?>" alt="" class="h-60px image" >
                                    </a>
                                    </div>
                                </td>
                                <td>
                                    <div class="row" id="video<?php echo e($key); ?>">
                                        <a  >
                                            <video width="200" height="300px" controls>
                                                <source src="<?php echo e(static_asset($detail->video)); ?>" type="video/mp4">
                                            </video>
                                        </a>
                                    </div>
                                </td>
                                <td>
                                    <?php if(!$detail->color): ?>
                                        <span class='size-25px d-inline-block mr-2 bg-danger '>not found</span>
                                        <?php else: ?>
                                        <span class='size-25px d-inline-block mr-2 rounded border'
                                              style='background:<?php echo e($detail->color?$detail->color->code:''); ?>'></span>
                                        <p>Thời gian bảo hành (<?php echo e(timeWarranty($detail->warranty_duration)); ?>)</p>
                                        <?php endif; ?>

                                </td>
                                <td><?php echo e($detail->qty); ?></td>

                            </tr>
                           <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            </tbody>
                        </table>
                    </div>
                        </div>
                    </div>

                    <?php if($warranty_card->status==0 ): ?>
                        <a href="javascript:void(0)"
                           class="btn btn-soft-info btn-icon btn-circle btn-sm"
                           onclick="updateCard('<?php echo e(route('warranty_card.ban', encrypt($warranty_card->id))); ?>',1);"
                           title="<?php echo e(translate('Activate Cards')); ?>">
                            <i class="las la-credit-card"></i>
                        </a>
                        <a href="javascript:void(0)"
                           class="btn btn-soft-danger btn-icon btn-circle btn-sm"
                           onclick="confirm_ban('<?php echo e(route('warranty_card.ban', encrypt($warranty_card->id))); ?>' ,2);"
                           title="<?php echo e(translate('Cancel the card')); ?>">
                            <i class="las la-credit-card"></i>
                        </a>
                    <?php endif; ?>
                </div>

            </div>
        </div>
    </div>



<?php $__env->startSection('modal'); ?>
    <?php echo $__env->make('modals.confirm_modal', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
<?php $__env->stopSection(); ?>

<?php $__env->stopSection(); ?>

<!-- CSS only -->
<?php $__env->startSection('script'); ?>


    <script>
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

        $(document).on('focus', '.a-video', function (e) {
            let key = $(this).attr('data-key')
            $(`div#video` + key).magnificPopup({
                delegate: 'a',
                gallery: {
                    enabled: true
                }
            })
        })
        // $(`div#gallery`).magnificPopup({
        //     delegate: 'a',
        //     type: 'image',
        //     gallery: {
        //         enabled: true
        //     }
        // })


        $('.image').on('click', function () {
            let img = $(this).attr('src');
            $('#image').attr('src', img)
        })

        function confirm_ban(url, status) {
            $('#confirm-ban').modal('show', {backdrop: 'static'});
            document.getElementById('confirmation').setAttribute('action', url + '?status=' + status);
        }


        function updateCard(url, status) {
            $('#confirm-update-bank').modal('show', {backdrop: 'static'});
            document.getElementById('updateCard').setAttribute('href', url + '?status=' + status);
        }

    </script>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('backend.layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH F:\PHP\PMA\resources\views/backend/customer/warranty_cards/show.blade.php ENDPATH**/ ?>