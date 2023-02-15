<?php $__env->startSection('content'); ?>

    <div class="row">
        <div class="col-lg-6 mx-auto">
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0 h6"><?php echo e(translate('Update warranty code')); ?></h5>
                </div>

                <form action="<?php echo e(route('warranty_codes.update',$warranty_code->id)); ?>" method="POST">
                    <?php echo method_field('PUT'); ?>
                    <?php echo csrf_field(); ?>
                    <div class="card-body">
                        <div class="form-group row">
                            <label class="col-sm-3 col-from-label" for="Seri"><?php echo e(translate('code')); ?><span
                                    class="text-danger"> *</span> </label>
                            <div class="col-sm-9">
                                <input type="text" placeholder="<?php echo e(translate('code')); ?>" id="code" name="code"
                                       value="<?php echo e(old('code',$warranty_code->code)); ?>" class="form-control"  required>
                                <?php $__errorArgs = ['code'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                <div class="" style="color: red"><?php echo e($message); ?></div>
                                <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                            </div>

                        </div>
                        <div class="form-group row">
                            <label class="col-sm-3 col-from-label" for="Seri">Phục hồi<span
                                    class="text-danger"> *</span> </label>
                            <div  class="col-sm-9">
                                <label class="aiz-switch aiz-switch-success mb-0">
                                    <input  data-toggle="tooltip" name="status" value="<?php echo e($warranty_code->status); ?>" type="checkbox" <?php if($warranty_code->status==1): ?>  <?php else: ?> checked disabled <?php endif; ?> onclick="ChangeStatus( <?php echo e($warranty_code->id); ?>,<?php echo e($warranty_code->status); ?>)" >
                                    <span class="slider round"></span>
                                </label>
                            </div>
                            <i class="ml-3 mt-2 text-danger">phục hồi sẽ khôi phục lại mã sang chưa sử dụng  </i>
                        </div>


                        <div class="form-group mb-0 text-right">
                            <button type="submit" class="btn btn-sm btn-primary"><?php echo e(translate('Save')); ?></button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
<?php $__env->stopSection(); ?>
<?php $__env->startSection('script'); ?>
    <script !src="" type="text/javascript">
        function  ChangeStatus(id,status) {
            let value= $('input[name="status"]').val()
            if(value==1){
                value=0
            }else{
                value=1
            }
            $('input[name="status"]').val(value)
        
            
            
            
            
            
            
            
            
            
            

        }
    </script>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('backend.layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH F:\PHP\PMA\resources\views/backend/warranty/warrantyCodes/edit.blade.php ENDPATH**/ ?>