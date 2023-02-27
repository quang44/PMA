

<?php $__env->startSection('content'); ?>

<div class="aiz-titlebar text-left mt-2 mb-3">
    <div class="row align-items-center">
        <div class="col-md-6">
            <h1 class="h3"><?php echo e(translate('List of depot')); ?></h1>
        </div>
        <div class="col-md-6 text-md-right">
            <a href="<?php echo e(route('affiliate.depot.create')); ?>" class="btn btn-circle btn-info">
                <span><?php echo e(translate('Create Account')); ?></span>
            </a>
        </div>
    </div>
</div>


<div class="card">
    <form class="" id="sort_customers" action="" method="GET">
        <div class="card-header row gutters-5">
            <div class="col">
                <h5 class="mb-0 h6"><?php echo e(translate('Account depot')); ?></h5>
            </div>

<!--            <div class="dropdown mb-2 mb-md-0">
                <button class="btn border dropdown-toggle" type="button" data-toggle="dropdown">
                    <?php echo e(translate('Bulk Action')); ?>

                </button>
                <div class="dropdown-menu dropdown-menu-right">
                    <a class="dropdown-item" href="#" onclick="bulk_delete()"><?php echo e(translate('Delete selection')); ?></a>
                </div>
            </div>-->
            <div class="col-md-3">
                <div class="form-group mb-0">
                    <select name="banned" id="banned" class="form-control aiz-selectpicker "
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
                    <input type="text" class="form-control" id="search" name="search" <?php if(isset($sort_search)): ?> value="<?php echo e($sort_search); ?>" <?php endif; ?> placeholder="<?php echo e(translate('Nhập tên hoặc số điện thoại')); ?>" >
                </div>
            </div>
        </div>

        <div class="card-body">
            <table class="table aiz-table mb-0">
                <thead>
                    <tr>
                        <!--<th data-breakpoints="lg">#</th>-->
<!--                        <th>
                            <div class="form-group">
                                <div class="aiz-checkbox-inline">
                                    <label class="aiz-checkbox">
                                        <input type="checkbox" class="check-all">
                                        <span class="aiz-square-check"></span>
                                    </label>
                                </div>
                            </div>
                        </th>-->
                        <th>#</th>
                        <th><?php echo e(translate('Tên Tổng kho')); ?></th>
                        <th data-breakpoints="md"><?php echo e(translate('Email')); ?></th>
                        <th data-breakpoints="md"><?php echo e(translate('Phone')); ?></th>
                        <th data-breakpoints="md" ><?php echo e(translate('Address')); ?></th>
                        <th data-breakpoints="md" class="text-center"><?php echo e(translate('Status')); ?></th>
                        <th class="text-right"><?php echo e(translate('Options')); ?></th>
                    </tr>
                </thead>
                <tbody>
                    <?php $__currentLoopData = $users; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key => $user): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <?php if($user != null): ?>
                            <tr>
                                <td><?php echo e(($key+1) + ($users->currentPage() - 1)*$users->perPage()); ?></td>










                                <td><?php if($user->banned == 1): ?> <i class="fa fa-ban text-danger" aria-hidden="true"></i> <?php endif; ?> <?php echo e($user->name); ?></td>
                                <td><?php if($user->email!=null): ?> <?php echo e($user->email); ?> <?php else: ?>  <span class="text-danger">Chưa có email</span> <?php endif; ?></td>
                                <td><?php echo e($user->phone); ?></td>






                                <td class="text-left">
                                       <?php echo e($user->address_one!=null?$user->address_one->province->name:''); ?>

                                        - <?php echo e($user->address_one!=null?$user->address_one->district->name:''); ?>

                                        - <?php echo e($user->address_one!=null ?$user->address_one->ward->name:''); ?>

                                </td>

                                <td class="text-center">
                                    <?php if($user->user_type=='customer' && $user->status ==1): ?>
                                        <span class="badge badge-inline badge-warning">Chờ duyệt</span>
                               <?php else: ?>
                                    <?php if($user->banned != 1): ?>
                                        <span class="badge badge-inline badge-success"><?php echo e(trans('Hoạt động')); ?></span>
                                    <?php else: ?>
                                        <span class="badge badge-inline badge-danger"><?php echo e(trans('Chưa kích hoạt')); ?></span>
                                    <?php endif; ?>
                                    <?php endif; ?>
                                </td>
                                <td class="text-right">
                                    <a class="btn btn-soft-primary btn-icon btn-circle btn-sm" href="<?php echo e(route('affiliate.depot.edit', encrypt($user->id))); ?>" title="<?php echo e(translate('Sửa thông tin')); ?>">
                                        <i class="las la-edit"></i>
                                    </a>
                                    <?php if($user->status ==1): ?>
                                        <a href="#" class="btn btn-soft-success btn-icon btn-circle btn-sm" onclick="confirm_lever_up('<?php echo e(route('affiliate.employee.updateToDepot', encrypt($user->id))); ?>');" title="<?php echo e(translate('Approve')); ?>">
                                            <i class="las la-level-up-alt"></i>
                                        </a>
                                    <?php else: ?>
                                    <?php if($user->banned != 1): ?>
                                        <a href="#" class="btn btn-soft-danger btn-icon btn-circle btn-sm" onclick="confirm_ban('<?php echo e(route('customers.ban', encrypt($user->id))); ?>');" title="<?php echo e(translate('Khóa tài khoản')); ?>">
                                            <i class="las la-user-slash"></i>
                                        </a>
                                    <?php else: ?>
                                        <a href="#" class="btn btn-soft-success btn-icon btn-circle btn-sm" onclick="confirm_unban('<?php echo e(route('customers.ban', encrypt($user->id))); ?>');" title="<?php echo e(translate('Kích hoạt tài khoản')); ?>">
                                            <i class="las la-user-check"></i>
                                        </a>
                                    <?php endif; ?>
                                <?php endif; ?>
<!--                                    <a href="#" class="btn btn-soft-danger btn-icon btn-circle btn-sm confirm-delete" data-href="<?php echo e(route('affiliate.employee.destroy', $user->id)); ?>" title="<?php echo e(translate('Xóa tài khoản')); ?>">
                                        <i class="las la-trash"></i>
                                    </a>-->
                                </td>
                            </tr>
                        <?php endif; ?>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </tbody>
            </table>
            <div class="aiz-pagination">
                <?php echo e($users->appends(request()->input())->links()); ?>

            </div>
        </div>
    </form>
</div>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('modal'); ?>
    <?php echo $__env->make('modals.confirm_banned_modal', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
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

        function confirm_lever_up(url)
        {
            $('#confirm-leverup').modal('show', {backdrop: 'static'});
            document.getElementById('confirmationleverup').setAttribute('href' , url);
        }

        $('#banned').on('change',function (){
            $('#sort_customers').submit();
        })

        $('#search').on('change',function () {
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

<?php echo $__env->make('backend.layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH F:\PHP\PMA\resources\views/backend/affiliate/depot/index.blade.php ENDPATH**/ ?>