<?php
/**
 * @package unite
 */
?>

<article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
    <header class="entry-header page-header">

        <a href="<?php the_permalink() ?>" title="<?php the_title_attribute(); ?>"><?php the_post_thumbnail('unite-featured', array('class' => 'thumbnail')); ?></a>

        <h4><a href="<?php the_permalink(); ?>" rel="bookmark"><?php the_title(); ?></a></h4>

        <?php if ('post' == get_post_type()) : ?>
            <div class="entry-meta">
                <?php unite_posted_on(); ?>
            </div><!-- .entry-meta -->
        <?php endif; ?>
    </header><!-- .entry-header -->

    <?php if (is_search()) : // Only display Excerpts for Search 
    ?>
        <div class="entry-summary">
            <?php the_excerpt(); ?>
            <p><a class="btn btn-primary read-more" href="<?php the_permalink(); ?>"><?php _e('Continue reading', 'unite'); ?> <i class="fa fa-chevron-right"></i></a></p>
        </div><!-- .entry-summary -->
    <?php else : ?>
        <div class="entry-content">

            <?php if (unite_get_option('blog_settings') == 1 || !unite_get_option('blog_settings')) : ?>
                <?php
                $idofpost = get_the_ID();
                $area_object_realestate = get_transient('area_object_realestate_' . $idofpost);
                if ($area_object_realestate === false) {
                    $area_object_realestate = get_field('area');
                    set_transient('area_object_realestate_'. $idofpost, $area_object_realestate, 60 * 60 * 24);
                }
                $price_realestate = get_transient('price_realestate_'. $idofpost);
                if ($price_realestate === false) {
                    $price_realestate = get_field('price');
                    set_transient('price_realestate_'. $idofpost, $price_realestate, 60 * 60 * 24);
                }
                $live_area_realestate = get_transient('live_area_realestate_'. $idofpost);
                if ($live_area_realestate === false) {
                    $live_area_realestate = get_field('live_area');
                    set_transient('live_area_realestate_'. $idofpost, $live_area_realestate, 60 * 60 * 24);
                }
                $flor_realestate = get_transient('flor_realestate_'. $idofpost);
                if ($flor_realestate === false) {
                    $flor_realestate = get_field('live_area');
                    set_transient('flor_realestate_'. $idofpost, $flor_realestate, 60 * 60 * 24);
                }
                $address_realestate = get_transient('address_realestate_'. $idofpost);
                if ($address_realestate === false) {
                    $address_realestate = get_field('address');
                    set_transient('address_realestate_'. $idofpost, $address_realestate, 60 * 60 * 24);
                }
                echo '<div class="parametres_realestate">Стоимость: <span>' . $price_realestate . '$</span><br>';
                echo 'Площадь: <span>' . $area_object_realestate . 'м²</span><br>';
                echo 'Жилая площадь: <span>' . $live_area_realestate . 'м²</span><br>';
                echo 'Этаж: <span>' . $flor_realestate . '</span><br>';
                echo 'Адрес: <span>' . $address_realestate . '</span></div><br>';
                ?>
            <?php elseif (unite_get_option('blog_settings') == 2) : ?>
                <?php the_excerpt(); ?>
            <?php endif; ?>

            <?php
            wp_link_pages(array(
                'before' => '<div class="page-links">' . __('Pages:', 'unite'),
                'after'  => '</div>',
            ));
            ?>
        </div><!-- .entry-content -->
    <?php endif; ?>

    <footer class="entry-meta">
        <?php if ('post' == get_post_type()) : // Hide category and tag text for pages on Search 
        ?>
            <?php
            /* translators: used between list items, there is a space after the comma */
            $categories_list = get_the_category_list(__(', ', 'unite'));
            if ($categories_list && unite_categorized_blog()) :
            ?>
                <span class="cat-links"><i class="fa fa-folder-open-o"></i>
                    <?php printf(__(' %1$s', 'unite'), $categories_list); ?>
                </span>
            <?php endif; // End if categories 
            ?>

            <?php
            /* translators: used between list items, there is a space after the comma */
            $tags_list = get_the_tag_list('', __(', ', 'unite'));
            if ($tags_list) :
            ?>
                <span class="tags-links"><i class="fa fa-tags"></i>
                    <?php printf(__(' %1$s', 'unite'), $tags_list); ?>
                </span>
            <?php endif; // End if $tags_list 
            ?>
        <?php endif; // End if 'post' == get_post_type() 
        ?>

        <?php if (!post_password_required() && (comments_open() || '0' != get_comments_number())) : ?>
            <span class="comments-link"><i class="fa fa-comment-o"></i><?php comments_popup_link(__('Leave a comment', 'unite'), __('1 Comment', 'unite'), __('% Comments', 'unite')); ?></span>
        <?php endif; ?>

        <?php edit_post_link(__('Edit', 'unite'), '<i class="fa fa-pencil-square-o"></i><span class="edit-link">', '</span>'); ?>
    </footer><!-- .entry-meta -->
    <hr class="section-divider">
</article><!-- #post-## -->