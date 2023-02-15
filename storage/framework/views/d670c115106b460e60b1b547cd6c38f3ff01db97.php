<?php $__env->startSection('content'); ?>

    <div class="aiz-titlebar text-left mt-2 mb-3 row">
        <div class=" col-md-4 align-items-center">
            <h1 class="h3"><?php echo e(translate('List of warranty codes')); ?></h1>
        </div>
        <div class="col-md-5 ">
            <form class="form-horizontal" action="<?php echo e(route('warranty_codes.upload')); ?>" method="POST" enctype="multipart/form-data">
                <?php echo csrf_field(); ?>
                <div class="form-group row">
                    <div class="col-sm-8">
                        <div class="custom-file">
                            <label class="custom-file-label">
                                <input type="file" name="bulk_file" class="custom-file-input" required>
                                <span class="custom-file-name">Chọn tập tin excel</span>
                            </label>
                        </div>
                    </div>
                    <div class="form-group col-sm-4">
                        <button type="submit" class="btn btn-info">Tải lên <i class="la la-upload"></i></button>
                    </div>
                </div>
              <?php $__errorArgs = ['bulk_file'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                <p class="text-danger">
              <?php echo e($message); ?>

                </p>
                <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
            </form>
        </div>
        <div class="col-md-3 text-md-right">
            <a href="<?php echo e(route('warranty_codes.create')); ?>" class="btn btn-circle btn-info">
                <span><?php echo e(translate('Add New warranty code')); ?></span>
            </a>
        </div>
    </div>


    <div class="col-md-3 mb-3">
        <a href="<?php echo e(static_asset('download/warrantycode.xlsx')); ?>" class="btn  btn-success">
            <i class="la la-file-excel"></i>
            <span>Tải xuống file mẫu   <i class="la la-download"></i></span>
        </a>
    </div>
    <div class="card">
        <form class="" id="sort_Card" action="" method="GET">
            <div class="card-header row gutters-5">
                <div class="col">
                    <h5 class="mb-0 h6"><?php echo e(translate('Warranty Code')); ?></h5>
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
                        <select name="sort_status" id="sort_selectCart" class="form-control aiz-selectpicker"
                                data-selected-text-format="count"
                                data-live-search="true"
                        >
                            <option value="-1"><?php echo e(translate('warranty code status')); ?></option>
                            <option value="0"
                                    <?php if(request('sort_status',-1)==0): ?> selected <?php endif; ?>><?php echo e(translate('Unused')); ?></option>
                            <option value="1"
                                    <?php if(request('sort_status',-1)==1): ?> selected <?php endif; ?>><?php echo e(translate('used')); ?></option>

                        </select>
                    </div>
                </div>
                                <div class="col-md-3">
                                    <div class="form-group mb-0">

                                        <input type="text" class="form-control" id="search" name="search"
                                               value="<?php echo e(request('search','')); ?>"
                                               placeholder="nhập mã và enter">
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
                            <th data-breakpoints="lg"><?php echo e(translate('Code')); ?></th>
                            <th data-breakpoints="lg"><?php echo e(translate('Status')); ?></th>
                            <th data-breakpoints="lg"><?php echo e(translate('Created_at')); ?></th>
                            <th data-breakpoints="lg"><?php echo e(translate('Use at')); ?></th>
                            <th class="text-right"><?php echo e(translate('Tùy chọn')); ?></th>
                        </tr>
                        </thead>
                        <tbody>
                        <?php $__currentLoopData = $warranty_codes; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key => $warranty_code): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <?php if($warranty_code != null): ?>
                                <tr>

                                    <td>
                                        <div class="form-group d-inline-block">
                                            <label class="aiz-checkbox">
                                                <input type="checkbox" class="check-one" name="id[]"
                                                       value="<?php echo e($warranty_code->id); ?>">
                                                <span class="aiz-square-check"></span>
                                            </label>
                                        </div>
                                    </td>

                                    <td><?php echo e($warranty_code->code); ?></td>
                                    <td>
                                        <?php if($warranty_code->status==0): ?>
                                            <span
                                                class="badge badge-inline badge-success"><?php echo e(translate('Unused')); ?></span>
                                        <?php else: ?>
                                            <span class="badge badge-inline badge-danger"><?php echo e(translate('Used')); ?></span>

                                        <?php endif; ?>
                                    </td>

                                    <td><?php echo e(convertTime($warranty_code->updated_at)); ?></td>
                                    <td>
                                        <?php if($warranty_code->use_at==null): ?>
                                          ---
                                            <?php else: ?>
                                            <?php echo e(convertTime($warranty_code->use_at)); ?></td>

                                    <?php endif; ?>
                                    <td class="text-right">
                                        <a href="<?php echo e(route('warranty_codes.edit',[ encrypt($warranty_code->id) ])); ?>"
                                           class="btn btn-soft-warning btn-icon btn-circle btn-sm"
                                           title="<?php echo e(translate('Cập nhật thông tin thẻ')); ?>">
                                            <i class="las la-edit"></i>
                                        </a>

                                        <a href="#"
                                           class="btn btn-soft-danger btn-icon btn-circle btn-sm confirm-delete"
                                           data-href="<?php echo e(route('warranty_codes.destroy', $warranty_code->id)); ?>"
                                           title="<?php echo e(translate('Xóa')); ?>">
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
                    <?php echo e($warranty_codes->appends(request()->input())->links()); ?>

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
    <?php echo $__env->make('modals.delete_modal', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('script'); ?>
    <script src="<?php echo e(asset('public/assets/js/sweetalert2@11.js')); ?>"></script>
    <script type="text/javascript">
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
        $(document).on('change','#search',function () {
            $('#sort_Card').submit();
        })

        $('#sort_selectCart').on('change', function () {
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
                url: "<?php echo e(route('warranty_codes.bulk-delete')); ?>",
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

<?php echo $__env->make('backend.layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH F:\PHP\PMA\resources\views/backend/warranty/warrantyCodes/index.blade.php ENDPATH**/ ?>