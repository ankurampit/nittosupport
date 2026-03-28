<?php

get_header();
$current_term = get_queried_object();

$subcategories = get_terms(array(
    'taxonomy'   => 'course-cat',
    'hide_empty' => false,
    'parent'     => $current_term->term_id
));
?>

<section id="content">
    <div class="container">
        <div class="vc_row wpb_row vc_row-fluid vc_custom_1525327714591">
            <div class="wpb_column vc_column_container vc_col-sm-12">
                <div class="vc_column-inner">
                    <div class="wpb_wrapper">
                        <div class="wpb_text_column wpb_content_element  vc_custom_1709152689470">
                            <div class="wpb_wrapper course-cat-heading">
                                <h2 style="font-size: 36px; font-weight: 800; text-align: center;">Choose your <span style="color: #880b17;"><span style="color: #880b17;">Course.</span></span></h2>

                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="row" style="margin-bottom:40px;">
            <div class=" col-sm-12">
                <?php
                if ($subcategories) {
                    foreach ($subcategories as $subcategory) { ?>
                        <div class="col-sm-6 col-md-4 mb-4">

                            <?php
                            $thumbnail_id = get_term_meta($subcategory->term_id, 'course_cat_thumbnail_id', true);
                            $image_url = $thumbnail_id ? wp_get_attachment_url($thumbnail_id) : get_template_directory_uri() . '/assets/images/default-category.jpg';
                            ?>

                            <div class="course-category-card">

                                <div class="category-image">
                                    <img class="sub-category-image" src="<?php echo esc_url($image_url); ?>" alt="<?php echo esc_attr($subcategory->name); ?>">
                                </div>

                                <div class="category-card-body">

                                    <h5 class="category-title">
                                        <?php echo esc_html($subcategory->name); ?>
                                    </h5>

                                    <p class="category-description">
                                        <?php echo esc_html($subcategory->description); ?>
                                    </p>

                                    <div class="category-meta">
                                        <span class="course-count">
                                            <?php echo $subcategory->count; ?> Courses
                                        </span>
                                    </div>

                                    <div class="category-action">
                                        <a href="<?php echo esc_url(get_term_link($subcategory)); ?>" class="enter-btn">
                                            Enter Category
                                        </a>
                                    </div>

                                </div>

                            </div>

                        </div>
                    <?php
                    }
                } else {
                    $courses = new WP_Query(array(
                        'post_type' => 'course',
                        'posts_per_page' => 12,
                        'tax_query' => array(
                            array(
                                'taxonomy' => 'course-cat',
                                'field'    => 'term_id',
                                'terms'    => $current_term->term_id
                            )
                        )
                    ));
                    ?>

                    <?php if ($courses->have_posts()) : ?>

                        <div class="row">

                            <?php while ($courses->have_posts()) : $courses->the_post(); ?>

                                <div class="col-sm-6 col-md-4 mb-4">

                                    <div class="course-category-card">

                                        <div class="category-image">

                                            <?php if (has_post_thumbnail()) { ?>
                                                <?php the_post_thumbnail('medium'); ?>
                                            <?php } ?>

                                        </div>

                                        <div class="category-card-body">

                                            <div class="category-action">
                                                <a href="<?php the_permalink(); ?>" class="enter-btn">
                                                    Toyo Potential $: <?php echo get_post_meta(get_the_ID(), 'toyo_dollars', true) ?: '0'; ?>
                                                </a>
                                            </div>

                                            <h5 class="category-title course-card-title">
                                                <?php the_title(); ?>
                                            </h5>

                                            <p class="category-description">
                                                <?php echo wp_trim_words(get_the_excerpt(), 15); ?>
                                            </p>

                                            <div class="category-action">

                                                <a href="<?php the_permalink(); ?>" class="enter-btn">
                                                    View Course
                                                </a>

                                            </div>

                                        </div>

                                    </div>

                                </div>

                            <?php endwhile; ?>

                        </div>

                        <?php wp_reset_postdata(); ?>

                    <?php endif; ?>

                <?php } ?>
            </div>
        </div>
        <!-- .padder -->
    </div>
</section>

<?php
get_footer();
