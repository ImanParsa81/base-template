<?php
get_header();
?>
<div class="container">
    <?php
    if (have_posts()) :
        while (have_posts()) : the_post(); ?>

            <div class="nirweb_ksb_main_content ksb_blog_page">
                <!-- نمایش عنوان نوشته -->
                <h1 class="mb-4"><?php the_title(); ?></h1>

                <!-- نمایش تصویر شاخص -->
                <?php if (has_post_thumbnail()) : ?>
                    <div class="mb-4">
                        <?php the_post_thumbnail('large', array('style' => 'border-radius: 10px; width: 100%; height: auto;')); ?>
                    </div>
                <?php endif; ?>
                <?=
                get_the_content()
                ?>
            </div>

        <?php endwhile;
    endif;
    ?>
</div>
<?php
get_footer();