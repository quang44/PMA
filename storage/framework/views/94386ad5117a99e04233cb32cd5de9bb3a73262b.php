

<?php $__env->startSection('content'); ?>



<div class="aiz-titlebar text-left mt-2 mb-3">
    <div class="row align-items-center">
        <div class="col-auto">
            <h1 class="h3"><?php echo e(translate('All products')); ?></h1>
        </div>
        <?php if($type != 'Seller'): ?>
        <div class="col text-right">
            <a href="<?php echo e(route('products.create')); ?>" class="btn btn-circle btn-info">
                <span><?php echo e(translate('Add New Product')); ?></span>
            </a>
        </div>
        <?php endif; ?>
    </div>
</div>
<br>

<div class="card">
    <form class="" id="sort_products" action="" method="GET">
        <div class="card-header row gutters-5">
            <div class="col">
                <h5 class="mb-md-0 h6"><?php echo e(translate('All Product')); ?></h5>
            </div>

            <div class="dropdown mb-2 mb-md-0">
                <button class="btn border dropdown-toggle" type="button" data-toggle="dropdown">
                    <?php echo e(translate('Bulk Action')); ?>

                </button>
                <div class="dropdown-menu dropdown-menu-right">
                    <a class="dropdown-item" href="#" onclick="bulk_delete()"> <?php echo e(translate('Delete selection')); ?></a>
                </div>
            </div>

            <?php if($type == 'Seller'): ?>
            <div class="col-md-2 ml-auto">
                <select class="form-control form-control-sm aiz-selectpicker mb-2 mb-md-0" id="user_id" name="user_id" onchange="sort_products()">
                    <option value=""><?php echo e(translate('All Sellers')); ?></option>
                    <?php $__currentLoopData = App\Models\User::where('user_type', '=', 'seller')->get(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key => $seller): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <option value="<?php echo e($seller->id); ?>" <?php if($seller->id == $seller_id): ?> selected <?php endif; ?>>
                            <?php echo e($seller->shop->name); ?> (<?php echo e($seller->name); ?>)
                        </option>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </select>
            </div>
            <?php endif; ?>

            <div class="col-md-2 ml-auto">
                <select class="form-control form-control-sm aiz-selectpicker mb-2 mb-md-0" name="type" id="type" onchange="sort_products()">
                    <option value=""><?php echo e(translate('Sort By')); ?></option>
                    <option value="rating,desc" <?php if(isset($col_name , $query)): ?> <?php if($col_name == 'rating' && $query == 'desc'): ?> selected <?php endif; ?> <?php endif; ?>><?php echo e(translate('Rating (High > Low)')); ?></option>
                    <option value="rating,asc" <?php if(isset($col_name , $query)): ?> <?php if($col_name == 'rating' && $query == 'asc'): ?> selected <?php endif; ?> <?php endif; ?>><?php echo e(translate('Rating (Low > High)')); ?></option>
                    <option value="num_of_sale,desc"<?php if(isset($col_name , $query)): ?> <?php if($col_name == 'num_of_sale' && $query == 'desc'): ?> selected <?php endif; ?> <?php endif; ?>><?php echo e(translate('Num of Sale (High > Low)')); ?></option>
                    <option value="num_of_sale,asc"<?php if(isset($col_name , $query)): ?> <?php if($col_name == 'num_of_sale' && $query == 'asc'): ?> selected <?php endif; ?> <?php endif; ?>><?php echo e(translate('Num of Sale (Low > High)')); ?></option>
                    <option value="unit_price,desc"<?php if(isset($col_name , $query)): ?> <?php if($col_name == 'unit_price' && $query == 'desc'): ?> selected <?php endif; ?> <?php endif; ?>><?php echo e(translate('Base Price (High > Low)')); ?></option>
                    <option value="unit_price,asc"<?php if(isset($col_name , $query)): ?> <?php if($col_name == 'unit_price' && $query == 'asc'): ?> selected <?php endif; ?> <?php endif; ?>><?php echo e(translate('Base Price (Low > High)')); ?></option>
                </select>
            </div>
            <div class="col-md-2">
                <div class="form-group mb-0">
                    <input type="text" class="form-control form-control-sm" id="search" name="search"<?php if(isset($sort_search)): ?> value="<?php echo e($sort_search); ?>" <?php endif; ?> placeholder="<?php echo e(translate('Type & Enter')); ?>">
                </div>
            </div>
        </div>

        <div class="card-body">
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
                        <!--<th data-breakpoints="lg">#</th>-->
                        <th><?php echo e(translate('Name')); ?></th>
                        <?php if($type == 'Seller' || $type == 'All'): ?>
                            <th data-breakpoints="lg"><?php echo e(translate('Added By')); ?></th>
                        <?php endif; ?>
                        <th data-breakpoints="lg"><?php echo e(translate('Status')); ?></th>
                        <th data-breakpoints="lg"><?php echo e(translate('Created_at')); ?></th>

                        <th data-breakpoints="sm" class="text-right"><?php echo e(translate('Options')); ?></th>
                    </tr>
                </thead>
                <tbody>
                <?php $__currentLoopData = $products; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key => $product): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <tr>
                    <!--<td><?php echo e(($key+1) + ($products->currentPage() - 1)*$products->perPage()); ?></td>-->
                        <td>
                            <div class="form-group d-inline-block">
                                <label class="aiz-checkbox">
                                    <input type="checkbox" class="check-one" name="id[]" value="<?php echo e($product->id); ?>">
                                    <span class="aiz-square-check"></span>
                                </label>
                            </div>
                        </td>
                        <td>
                            <div class="row gutters-5 w-200px w-md-300px mw-100">
                                <div class="col-auto">
                                    <img src="<?php echo e(uploaded_asset($product->thumbnail_img)); ?>" alt="sản phẩm" class="size-50px img-fit">
                                </div>
                                <div class="col">
                                    <span
                                        class="text-muted text-truncate-2"><?php echo e($product->getTranslation('name')); ?></span>
                                </div>
                            </div>
                        </td>
                        <?php if($type == 'Seller' || $type == 'All'): ?>
                            <td><?php echo e($product->user!=null ?$product->user->name:'người dùng không tồn tại'); ?></td>
                        <?php endif; ?>
                        <td>
                            <label class="aiz-switch aiz-switch-success mb-0">
                                <input value="4" type="checkbox" <?php if($product->status==0): ?> checked <?php endif; ?> onclick="ChangeStatus( <?php echo e($product->id); ?>,<?php echo e($product->status); ?>)" >
                                <span class="slider round"></span>
                            </label>
                        </td>

                        <td>
                            <?php echo e($product->created_at); ?>

                        </td>


                        <td class="text-right">





                            <?php if($type == 'Seller'): ?>
                                <a class="btn btn-soft-primary btn-icon btn-circle btn-sm"
                                   href="<?php echo e(route('products.seller.edit', ['id'=>$product->id, 'lang'=>env('DEFAULT_LANGUAGE')] )); ?>"
                                   title="<?php echo e(translate('Edit')); ?>">
                                    <i class="las la-edit"></i>
                                </a>
                            <?php else: ?>
                                <a class="btn btn-soft-primary btn-icon btn-circle btn-sm"
                                   href="<?php echo e(route('products.admin.edit', ['id'=>$product->id, 'lang'=>env('DEFAULT_LANGUAGE')] )); ?>"
                                   title="<?php echo e(translate('Edit')); ?>">
                                <i class="las la-edit"></i>
                            </a>
                            <?php endif; ?>
                            <a class="btn btn-soft-warning btn-icon btn-circle btn-sm" href="<?php echo e(route('products.duplicate', ['id'=>$product->id, 'type'=>$type]  )); ?>" title="<?php echo e(translate('Duplicate')); ?>">
                                <i class="las la-copy"></i>
                            </a>
                            <a href="#" class="btn btn-soft-danger btn-icon btn-circle btn-sm confirm-delete" data-href="<?php echo e(route('products.destroy', $product->id)); ?>" title="<?php echo e(translate('Delete')); ?>">
                                <i class="las la-trash"></i>
                            </a>
                        </td>
                    </tr>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </tbody>
            </table>
            <div class="aiz-pagination">
                <?php echo e($products->appends(request()->input())->links()); ?>

            </div>
        </div>
    </form>
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

        $(document).ready(function(){
            //$('#container').removeClass('mainnav-lg').addClass('mainnav-sm');
        });

        function update_todays_deal(el){
            if(el.checked){
                var status = 1;
            }
            else{
                var status = 0;
            }
            $.post('<?php echo e(route('products.todays_deal')); ?>', {_token:'<?php echo e(csrf_token()); ?>', id:el.value, status:status}, function(data){
                if(data == 1){
                    AIZ.plugins.notify('success', '<?php echo e(translate('Todays Deal updated successfully')); ?>');
                }
                else{
                    AIZ.plugins.notify('danger', '<?php echo e(translate('Something went wrong')); ?>');
                }
            });
        }

        function update_published(el){
            if(el.checked){
                var status = 1;
            }
            else{
                var status = 0;
            }
            $.post('<?php echo e(route('products.published')); ?>', {_token:'<?php echo e(csrf_token()); ?>', id:el.value, status:status}, function(data){
                if(data == 1){
                    AIZ.plugins.notify('success', '<?php echo e(translate('Published products updated successfully')); ?>');
                }
                else{
                    AIZ.plugins.notify('danger', '<?php echo e(translate('Something went wrong')); ?>');
                }
            });
        }

        function update_approved(el){
            if(el.checked){
                var approved = 1;
            }
            else{
                var approved = 0;
            }
            $.post('<?php echo e(route('products.approved')); ?>', {
                _token      :   '<?php echo e(csrf_token()); ?>',
                id          :   el.value,
                approved    :   approved
            }, function(data){
                if(data == 1){
                    AIZ.plugins.notify('success', '<?php echo e(translate('Product approval update successfully')); ?>');
                }
                else{
                    AIZ.plugins.notify('danger', '<?php echo e(translate('Something went wrong')); ?>');
                }
            });
        }

        function update_featured(el){
            if(el.checked){
                var status = 1;
            }
            else{
                var status = 0;
            }
            $.post('<?php echo e(route('products.featured')); ?>', {_token:'<?php echo e(csrf_token()); ?>', id:el.value, status:status}, function(data){
                if(data == 1){
                    AIZ.plugins.notify('success', '<?php echo e(translate('Featured products updated successfully')); ?>');
                }
                else{
                    AIZ.plugins.notify('danger', '<?php echo e(translate('Something went wrong')); ?>');
                }
            });
        }

        function sort_products(el) {
            $('#sort_products').submit();
        }

        function bulk_delete() {
            var data = new FormData($('#sort_products')[0]);
            console.log(data)
            $.ajax({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                url: "<?php echo e(route('bulk-product-delete')); ?>",
                type: 'POST',
                data: data,
                cache: false,
                contentType: false,
                processData: false,
                success: function (response) {
                    if (response == 1) {
                        AIZ.plugins.notify('success', '<?php echo e(translate('Delete products successfully')); ?>');
                        location.reload();
                    }
                }
            });
        }

        function  ChangeStatus(id,status) {
            if(status==0){
                status=1;
            }else{
                status=0;
            }
            $.post('<?php echo e(route('products.update_status')); ?>', {_token:'<?php echo e(csrf_token()); ?>',
                id:id, status:status}, function(data){
                if(data == 1){
                    location.reload()
                    AIZ.plugins.notify('success', '<?php echo e(translate('update status successfully')); ?>');
                } else{
                    location.reload()
                    AIZ.plugins.notify('danger', '<?php echo e(translate('Something went wrong')); ?>');
                }
            });

        }


    </script>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('backend.layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH F:\PHP\PMA\resources\views/backend/product/products/index.blade.php ENDPATH**/ ?>