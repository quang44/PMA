

<?php $__env->startSection('content'); ?>

    <div class="aiz-titlebar text-left mt-2 mb-3 row">
        <div class=" col-md-6 align-items-center">
            <h1 class="h3"><?php echo e(translate('List of gift')); ?></h1>
        </div>
        <div class="col-md-6 text-md-right">
            <a href="<?php echo e(route('gift.create')); ?>" class="btn btn-circle btn-info">
                <span><?php echo e(translate('Add New Gift')); ?></span>
            </a>
        </div>
    </div>

    <div class="card">
        <form class="" id="sort_Gift" action="" method="GET">
            <div class="card-header row gutters-5">
                <div class="col">
                    <h5 class="mb-0 h6"><?php echo e(translate('Gift')); ?></h5>
                </div>
                <div class="dropdown mb-2 mb-md-0">
                    <button class="btn border dropdown-toggle" type="button" data-toggle="dropdown">
                        <?php echo e(translate('Bulk Action')); ?>

                    </button>
                    <div class="dropdown-menu dropdown-menu-right">
                        <a class="dropdown-item" href="#" onclick="bulk_delete()"> <?php echo e(translate('Delete selection')); ?></a>
                    </div>
                </div>

                <div class="col-md-3">
                    <div class="form-group mb-0">
                        <select name="sort_status" id="sort_selectGift" class="form-control aiz-selectpicker"
                                data-selected-text-format="count"
                                data-live-search="true"
                        >
                            <option value="-1"><?php echo e(translate('gift status')); ?></option>
                            <option value="0"
                                    <?php if(request('sort_status',-1)==0): ?> selected <?php endif; ?>><?php echo e(translate('Show')); ?></option>
                            <option value="1"
                                    <?php if(request('sort_status',-1)==1): ?> selected <?php endif; ?>><?php echo e(translate('Hidden')); ?></option>

                        </select>
                    </div>
                </div>
                                <div class="col-md-3">
                                    <div class="form-group mb-0">
                                        <input type="text" class="form-control form-control-sm" id="search" name="search" <?php if(isset($search)): ?> value="<?php echo e($search); ?>" <?php endif; ?> placeholder="Tìm kiếm quà tặng">
                                    </div>
                                </div>
            </div>

            <div class="card-body">
                <div class="table-responsive">
                    <table class="table aiz-table mb-0">
                        <thead>
                        <tr>
                            <th data-breakpoints="lg"><div class="form-group">
                                    <div class="aiz-checkbox-inline">
                                        <label class="aiz-checkbox">
                                            <input type="checkbox" class="check-all">
                                            <span class="aiz-square-check"></span>
                                        </label>
                                    </div>
                                </div></th>
                            <th data-breakpoints="lg"><?php echo e(translate('Name')); ?></th>
                            <th data-breakpoints="lg"><?php echo e(translate('Image')); ?></th>
                            <th data-breakpoints="lg"><?php echo e(translate('Point')); ?></th>
                            <th data-breakpoints="lg"><?php echo e(translate('Status')); ?></th>
                            <th data-breakpoints="lg"><?php echo e(translate('Created_at')); ?></th>
                            <th class="text-right"><?php echo e(translate('Tùy chọn')); ?></th>
                        </tr>
                        </thead>
                        <tbody>
                        <?php $__currentLoopData = $gifts; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key => $gift): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <?php if($gift != null): ?>
                                <tr>

                                    <td>
                                        <div class="form-group d-inline-block">
                                            <label class="aiz-checkbox">
                                                <input type="checkbox" class="check-one" name="id[]"
                                                       value="<?php echo e($gift->id); ?>">
                                                <span class="aiz-square-check"></span>
                                            </label>
                                        </div>
                                    </td>

                                    <td><?php echo e($gift->name); ?></td>
                                    <td><img width="110px" src="<?php echo e(uploaded_asset($gift->image)); ?>" alt="<?php echo e(translate('Gift')); ?>"></td>
                                    <td><?php echo e($gift->point); ?></td>
                                    <td>
                                        <label class="aiz-switch aiz-switch-success mb-0">
                                            <input value="4" type="checkbox" <?php if($gift->status==0): ?> checked <?php endif; ?> onclick="ChangeStatus( <?php echo e($gift->id); ?>,<?php echo e($gift->status); ?>)" >
                                            <span class="slider round"></span>
                                        </label>
                                    </td>
                                    <td><?php echo e(date('d-m-Y',strtotime($gift->created_at))); ?></td>
                                    <td class="text-right">
                                        <a href="<?php echo e(route('gift.edit', [ encrypt($gift->id) ])); ?>"
                                           class="btn btn-soft-warning btn-icon btn-circle btn-sm"
                                           title="<?php echo e(translate('Cập nhật thông tin thẻ')); ?>">
                                            <i class="las la-edit"></i>
                                        </a>

                                        <a href="#"
                                           class="btn btn-soft-danger btn-icon btn-circle btn-sm confirm-delete"
                                           data-href="<?php echo e(route('gift.destroy', $gift->id)); ?>"
                                           title="<?php echo e(translate('Delete')); ?>">
                                            <i class="las la-trash"></i>
                                        </a>

                                    </td>
                                </tr>
                            <?php endif; ?>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </tbody>
                    </table>
                </div>

                <div class="aiz-pagination">
                    <?php echo e($gifts->appends(request()->input())->links()); ?>

                </div>
            </div>
        </form>
    </div>


    <div class="modal fade" id="confirm-ban">
        <form action="" id="confirmation" method="GET">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title h6"><?php echo e(translate('Nhập lý do hủy')); ?></h5>
                        <button type="button" class="close" data-dismiss="modal"></button>
                    </div>
                    <div class="modal-body">
                        <input type="text" class="form-control" name="reason" placeholder="Lý do hủy">
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-light"
                                data-dismiss="modal"><?php echo e(translate('Hủy')); ?></button>
                        <button type="submit" class="btn btn-primary"><?php echo e(translate('Tiếp tục')); ?></button>
                    </div>
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


      $('#sort_selectGift').on('change',function () {
          $('#sort_Gift').submit();
      })

      $('#search').on('change',function () {
          console.log($(this).val())
          $('#sort_Gift').submit();
      })

      $(document).on("change", ".check-all", function() {
          if(this.checked) {
              // Iterate each checkbox
              $('.check-one:checkbox').each(function() {
                  this.checked = true;
              });
          } else {
              $('.check-one:checkbox').each(function() {
                  this.checked = false;
              });
          }

      });


        function  ChangeStatus(id,status) {

            if(status==0){
                status=1;
            }else{
                status=0;
            }
            $.post('<?php echo e(route('gift.update_status')); ?>', {_token:'<?php echo e(csrf_token()); ?>',
                id:id, status:status}, function(data){
                if(data.result == 1){
                    location.reload()
                    AIZ.plugins.notify('success', '<?php echo e(translate('update status successfully')); ?>');
                } else{
                    location.reload()
                    AIZ.plugins.notify('danger', '<?php echo e(translate('Something went wrong')); ?>');
                }
            });

        }


        function bulk_delete() {
            var data = new FormData($('#sort_Gift')[0]);
            $.ajax({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                url: "<?php echo e(route('gift.bulk-delete')); ?>",
                type: 'POST',
                data: data,
                cache: false,
                contentType: false,
                processData: false,
                success: function (response) {
                    if (response == 1) {
                        Swal.fire('Xóa thành công')
                        location.reload();
                    }
                }
            });
        }
    </script>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('backend.layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH F:\PHP\PMA\resources\views/backend/marketing/gift/index.blade.php ENDPATH**/ ?>