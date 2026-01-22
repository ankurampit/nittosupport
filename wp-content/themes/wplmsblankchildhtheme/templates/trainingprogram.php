<?php

/**
 * Template Name: Training Program page 
 */

get_header('header.php');
?>

<?php get_template_part('header-inner');
$terms = get_terms(array(
    'taxonomy'   => 'course-cat',
    'hide_empty' => false,
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

                        <div class="wpb_text_column wpb_content_element  vc_custom_1758737504965">
                            <div class="wpb_wrapper">
                                <p style="margin-bottom: 60px; text-align: left;">The intention of this site is to offer educational material for a user with a single profile to become comfortable with Nitto Products and Tire Technology in general.<br>
                                    For making this effort, in most courses, you will be rewarded with the Nitto $s program. Courses are offered in both English and French. However, please note you will only be rewarded in the language completed first, not for both. Thank you and good luck!<br>
                                    <strong style="color: red;">Because of our starting to transition to a new Nitto support site, we have temporarily suspended the Nitto $ program . Please continue with the courses. We ensure you are credited for these courses on the new site.</strong>
                                </p>

                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="row" style="margin-bottom:40px;">
            <div class=" col-sm-12">
                <?php
                if ($terms) {
                    foreach ($terms as $term) { ?>
                        <div class="col-sm-6">
                            <div class="course-cate">
                                <a href="#"><?php echo $term->name ?></a>
                            </div>
                        </div>
                <?php
                    }
                }
                ?>
            </div>
        </div>
        <!-- .padder -->
    </div>
</section>
<?php

get_footer('footer.php');
