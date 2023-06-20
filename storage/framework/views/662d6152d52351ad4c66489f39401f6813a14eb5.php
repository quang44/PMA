<?php $__env->startSection('content'); ?>

<div class="aiz-titlebar text-left mt-2 mb-3">
  <div class="row">
      <div class="col-md-6 align-items-center">
          <h1 class="h3"><?php echo e(translate('Tất cả tài khoản')); ?></h1>
      </div>

      <div class="col-md-6 text-md-right">
          <a href="<?php echo e(route('customers.create')); ?>" class="btn btn-circle btn-info">
              <span><?php echo e(translate('Thêm người dùng')); ?></span>
          </a>
      </div>

  </div>
</div>


<div class="card">
    <form class="" id="sort_customers" action="" method="GET">
        <div class="card-header row gutters-5">
            <div class="col">
                <h5 class="mb-0 h6"><?php echo e(translate('Tài khoản')); ?></h5>
            </div>

           <div class="dropdown mb-2 mb-md-0">
                <button class="btn border dropdown-toggle" type="button" data-toggle="dropdown">
                    <?php echo e(translate('Bulk Action')); ?>

                </button>
                <div class="dropdown-menu dropdown-menu-right">
                    <a class="dropdown-item" href="#" onclick="bulk_delete()"><?php echo e(translate('Delete selection')); ?></a>
                </div>
            </div>










            <div class="col-md-3">
                <div class="form-group mb-0">
                    <select name="banned" id="banned" class="form-control aiz-selectpicker"
                            data-selected-text-format="count"
                            data-live-search="true"
                    >
                        <option value="-1">Trạng thái tài khoản</option>
                        <option value="1" <?php if(request('banned', -1) == 1): ?> selected <?php endif; ?>>Khóa</option>
                        <option value="0" <?php if(request('banned', -1) == 0): ?> selected <?php endif; ?>>Đang hoạt động</option>

                    </select>
                </div>
            </div>






















            <div class="col-md-3">
                <div class="form-group mb-0">

                    <input type="text" class="form-control" id="search" name="search"<?php if(isset($sort_search)): ?> value="<?php echo e($sort_search); ?>" <?php endif; ?> placeholder="<?php echo e(translate('Nhập tên hoặc số điện thoại')); ?>">
                </div>
            </div>
        </div>

        <div class="card-body">
            <div class="table-responsive">
                <table class="table aiz-table mb-0">
                    <thead>
                    <tr>

                                       <th>
                                                    <div class="form-group">
                                                        <div class="aiz-checkbox-inline">
                                                            <label class="aiz-checkbox">
                                                                <input type="checkbox" class="check-all">
                                                                <span class="aiz-square-check"></span>
                                                            </label>
                                                        </div>
                                                    </div>
                                                </th>
                        <th><?php echo e(translate('Name')); ?></th>
                        <th data-breakpoints="md">  <?php echo e(translate('Phone')); ?></th>
                        <th data-breakpoints="md">  <?php echo e(translate('Email')); ?></th>
                        <th data-breakpoints="md">  <?php echo e(translate('Address')); ?></th>
                        <th data-breakpoints="md">Thuộc đại lý - Tổng kho</th>
                        <th data-breakpoints="md"><?php echo e(translate('Status')); ?></th>
                        <th data-breakpoints="md"><?php echo e(translate('Created_at')); ?></th>
                        <th data-breakpoints="md"><?php echo e(translate('Updated_at')); ?></th>
                        
                        <th class="text-right"><?php echo e(translate('Options')); ?></th>
                    </tr>
                    </thead>
                    <tbody>


                    <?php $__currentLoopData = $users; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key => $user): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <?php if($user != null): ?>
                            <tr>
                                                        <td>
                                    <div class="form-group">
                                        <div class="aiz-checkbox-inline">
                                            <label class="aiz-checkbox">
                                                <input type="checkbox" class="check-one" name="id[]" value="<?php echo e($user->id); ?>">
                                                <span class="aiz-square-check"></span>
                                            </label>
                                        </div>
                                    </div>
                                </td>
                                <td><a title="lịch sử thanh toán"
                                       href="<?php echo e(route('customers.show',encrypt($user->id))); ?>"><?php echo e($user->name); ?></a>
                                </td>
                                <td>
                                   <?php echo e($user->phone); ?>

                                </td>
                                <td>
                                   <?php echo e($user->email); ?>

                                </td>
                                <td>


                                         <?php echo e($user->address); ?>







                                </td>

                                <td>
                                    <?php if($user->user_agent!=null): ?>
                                   <?php echo e($user-> user_agent->name); ?>

                               <?php endif; ?>
                                </td>





























                                
                                <td>
                                    <?php if($user->banned == 1): ?>
                                        <span class="badge badge-inline badge-danger"><?php echo e(trans('Khóa')); ?></span>

                                    <?php else: ?>
                                        <span class="badge badge-inline badge-success"><?php echo e(trans('Hoạt động')); ?></span>
                                    <?php endif; ?>
                                </td>
                                
                                
                                
                                
                                
                                
                                <td><?php echo e($user->created_at); ?></td>
                                <td><?php echo e($user->updated_at); ?></td>
                                
                                <td class="text-right">
                                
                                
                                
                                
                                
                                <!--                                    onclick="openUpdatePackage(`<?php echo e($user->id); ?>`, `<?php echo e($user->customer_package_id); ?>`)"-->
                                    <a href="<?php echo e(route('wallet-balance.balance',encrypt($user->id))); ?>" class="btn btn-soft-success btn-icon btn-circle btn-sm" title="Lịch sử giao dịch" >
                                        <i class="las la-money-bill"></i>
                                    </a>
                                    <a href="<?php echo e(route('customers.edit', [encrypt($user->id)])); ?>"
                                       class="btn btn-soft-primary btn-icon btn-circle btn-sm"
                                       title="<?php echo e(translate('Update account')); ?>">
                                        <i class="las la-edit"></i>
                                    </a>

                                    <?php if($user->banned == 0): ?>
                                        <a href="#" class="btn btn-soft-danger btn-icon btn-circle btn-sm"
                                           onclick="confirm_ban('<?php echo e(route('customers.ban', encrypt($user->id))); ?>');"
                                           title="<?php echo e(translate('Lock account')); ?>">
                                            <i class="las la-user-slash"></i>
                                        </a>
                                    <?php else: ?>
                                        <a href="#" class="btn btn-soft-success btn-icon btn-circle btn-sm"
                                           onclick="confirm_unban('<?php echo e(route('customers.ban', encrypt($user->id))); ?>');"
                                           title="<?php echo e(translate('Active account')); ?>">
                                            <i class="las la-user-check"></i>
                                        </a>
                                <?php endif; ?>

                                  <a href="#" class="btn btn-soft-danger btn-icon btn-circle btn-sm confirm-delete" data-href="<?php echo e(route('customers.destroy', $user->id)); ?>" title="<?php echo e(translate('Delete')); ?>">
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
                <?php echo e($users->appends(request()->input())->links()); ?>

            </div>
        </div>
    </form>
</div>













































<div class="modal fade" id="confirm-ban">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title h6"><?php echo e(translate('Xác nhận')); ?></h5>
                <button type="button" class="close" data-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <p><?php echo e(translate('Bạn muốn khóa tài khoản ?')); ?></p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-light" data-dismiss="modal"><?php echo e(translate('Hủy')); ?></button>
                <a type="button" id="confirmation" class="btn btn-primary"><?php echo e(translate('Tiếp tục')); ?></a>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="confirm-unban">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title h6"><?php echo e(translate('Xác nhận')); ?></h5>
                <button type="button" class="close" data-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <p><?php echo e(translate('Bạn muốn kích hoạt tài khoản ?')); ?></p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-light" data-dismiss="modal"><?php echo e(translate('Hủy')); ?></button>
                <a type="button" id="confirmationunban" class="btn btn-primary"><?php echo e(translate('Tiếp tục')); ?></a>
            </div>
        </div>
    </div>
</div>
<?php $__env->stopSection(); ?>


<?php $__env->startSection('modal'); ?>
    <?php echo $__env->make('modals.delete_modal', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('script'); ?>
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
        $('#referred_by').on('change',function (){
            $('#sort_customers').submit();
        })
        $('#banned').on('change',function (){
            $('#sort_customers').submit();
        })
        $('#has_best_api').on('change',function (){
            $('#sort_customers').submit();
        })
        $('#bank_updated').on('change',function (){
            $('#sort_customers').submit();
        })

        $('#search').change(function () {
            $('#sort_customers').submit();

        })
        function sort_customers(el){
            $('#sort_customers').submit();
        }

        function openUpdatePackage(user_id, package_id)
        {
            if(package_id){
                $('#update-package').find('select[name="package_id"]').val(package_id);
            }
            $('#update-package').find('input[name="user_id"]').val(user_id);
            $('#update-package').modal('show');
        }

        $('#updatePackage').on('click',function (){
            let user_id =  $('#update-package').find('input[name="user_id"]').val();
            let package_id =  $('#update-package').find('select[name="package_id"]').val();
            $.ajax({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                url: "<?php echo e(route('customers.update_package')); ?>",
                type: 'POST',
                data: {
                    user_id:user_id,
                    package_id:package_id
                },
                success: function (response) {
                    if(response.result === true) {
                        location.reload();
                    }
                }
            });
        })

        function confirm_ban(url)
        {
            $('#confirm-ban').modal('show', {backdrop: 'static'});
            document.getElementById('confirmation').setAttribute('href' , url);
        }

        function confirm_unban(url)
        {
            $('#confirm-unban').modal('show', {backdrop: 'static'});
            document.getElementById('confirmationunban').setAttribute('href' , url);
        }

        function updateBank(url){
            $('#confirm-update-bank').modal('show', {backdrop: 'static'});
            document.getElementById('updateBank').setAttribute('href' , url);
        }

        function bulk_delete() {
            var data = new FormData($('#sort_customers')[0]);
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
                    if(response == 1) {
                        location.reload();
                    }
                }
            });
        }
    </script>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('backend.layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH F:\PHP\PMA\resources\views/backend/customer/customers/index.blade.php ENDPATH**/ ?>