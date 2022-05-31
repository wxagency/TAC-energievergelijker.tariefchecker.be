<div class="Footer-main">
    <div class="container">
        <div class="row">
            <div class="col-md-12">
                <div class="footer">
                    <ul>
                     
                        
                         <?php if(Session::get('locale')=='nl'): ?>
                                <?php $__currentLoopData = $link_status; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $link): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <?php if( $link->link_status == 1): ?>
                                    <li>
                                        <?php if($link->slug == 'terms_conditions'): ?>
                                        <a href="<?php echo e(trans('home.terms_link')); ?>" target=_blank><?php echo app('translator')->getFromJson('home.terms&conditions'); ?></a>
                                        <?php endif; ?>
                                        <?php if($link->slug == 'contact'): ?>
                                        <a href="<?php echo e(trans('home.contact_link')); ?>" target=_blank><?php echo app('translator')->getFromJson('home.Contact'); ?></a>
                                        <?php endif; ?>
                                        <?php if($link->slug == 'frequent_questions'): ?>
                                        <a href="<?php echo e(trans('home.frequent_link')); ?>" target=_blank><?php echo app('translator')->getFromJson('home.frequent_questions'); ?></a>
                                        <?php endif; ?>
                                        <?php if($link->slug == 'powered_by'): ?>
                                        <a href="<?php echo e(trans('home.powered_by_link')); ?>" target=_blank><?php echo app('translator')->getFromJson('home.powered_by'); ?></a>
                                        <?php endif; ?>
                                    </li>
                                <?php else: ?>

                                    <li>
                                        <?php if($link->slug == 'terms_conditions'): ?>
                                        <a href="<?php echo e(trans('home.terms_link')); ?>"><?php echo app('translator')->getFromJson('home.terms&conditions'); ?></a>
                                        <?php endif; ?>
                                        <?php if($link->slug == 'contact'): ?>
                                        <a href="<?php echo e(trans('home.contact_link')); ?>"><?php echo app('translator')->getFromJson('home.Contact'); ?></a>
                                        <?php endif; ?>
                                        <?php if($link->slug == 'frequent_questions'): ?>
                                        <a href="<?php echo e(trans('home.frequent_link')); ?>"><?php echo app('translator')->getFromJson('home.frequent_questions'); ?></a>
                                        <?php endif; ?>
                                        <?php if($link->slug == 'powered_by'): ?>
                                        <a href="<?php echo e(trans('home.powered_by_link')); ?>"><?php echo app('translator')->getFromJson('home.powered_by'); ?></a>
                                        <?php endif; ?>
                                    </li>
                                <?php endif; ?>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        <?php else: ?>
                                <?php $__currentLoopData = $link_status; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $link): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <?php if( $link->link_status == 1): ?>
                                    <li>
                                        <?php if($link->slug == 'terms_conditions'): ?>
                                        <a href="<?php echo e(trans('home.terms_link')); ?>" target=_blank><?php echo app('translator')->getFromJson('home.terms&conditions'); ?></a>
                                        <?php endif; ?>
                                        <?php if($link->slug == 'contact'): ?>
                                        <a href="<?php echo e(trans('home.contact_link')); ?>" target=_blank><?php echo app('translator')->getFromJson('home.Contact'); ?></a>
                                        <?php endif; ?>
                                        <?php if($link->slug == 'frequent_questions'): ?>
                                        <a href="<?php echo e(trans('home.frequent_link')); ?>" target=_blank><?php echo app('translator')->getFromJson('home.frequent_questions'); ?></a>
                                        <?php endif; ?>
                                        <?php if($link->slug == 'powered_by'): ?>
                                        <a href="<?php echo e(trans('home.powered_by_link')); ?>" target=_blank><?php echo app('translator')->getFromJson('home.powered_by'); ?></a>
                                        <?php endif; ?>
                                    </li>
                                <?php else: ?>

                                    <li>
                                        <?php if($link->slug == 'terms_conditions'): ?>
                                        <a href="<?php echo e(trans('home.terms_link')); ?>"><?php echo app('translator')->getFromJson('home.terms&conditions'); ?></a>
                                        <?php endif; ?>
                                        <?php if($link->slug == 'contact'): ?>
                                        <a href="<?php echo e(trans('home.contact_link')); ?>"><?php echo app('translator')->getFromJson('home.Contact'); ?></a>
                                        <?php endif; ?>
                                        <?php if($link->slug == 'frequent_questions'): ?>
                                        <a href="<?php echo e(trans('home.frequent_link')); ?>"><?php echo app('translator')->getFromJson('home.frequent_questions'); ?></a>
                                        <?php endif; ?>
                                        <?php if($link->slug == 'powered_by'): ?>
                                        <a href="<?php echo e(trans('home.powered_by_link')); ?>"><?php echo app('translator')->getFromJson('home.powered_by'); ?></a>
                                        <?php endif; ?>
                                    </li>
                                <?php endif; ?>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>

                        <?php endif; ?>
                    </ul>
                    <P><?php echo app('translator')->getFromJson('home.Copyright'); ?> <?php echo e(date('Y')); ?></P>
                </div>
            </div>
        </div>
    </div>
</div>

      <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.0/umd/popper.min.js" integrity="sha384-cs/chFZiN24E4KMATLdqdvsezGxaGsi4hLGOzlXwp5UZB1LY//20VyM2taTB4QvJ" crossorigin="anonymous"></script>
      <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js" integrity="sha384-JjSmVgyd0p3pXB1rRibZUAYoIIy6OrQ6VrjIEaFf/nJGzIxFDsf4x0xIM+B07jRM" crossorigin="anonymous"></script>
     <script src="https://kit.fontawesome.com/5371eb2245.js"></script>
     
   