<?php
acf_form_head();
/**
 * Template Name: Print Ads Page
 */
get_header('header.php');
?>

<?php get_template_part('header-inner'); ?>

<?php echo do_shortcode('[advertising_tabs]'); ?>

<div class="material-form-wrapper" style="margin-top:30px;">
    <?php
    acf_form(array(
        'post_id'           => 'new_post',
        'new_post'          => array(
            'post_type'     => 'advertising_material',
            'post_status'   => 'publish'
        ),
        'field_groups'      => array('group_6926bd61e41f4'),
        'submit_value'      => 'Submit Print Ad Material',
        'html_before_fields' => '<input type="hidden" name="material_category_slug" value="print-ads" />',
        'return'            => get_permalink()
    ));
    ?>
</div>

<?php get_footer('footer.php'); ?>