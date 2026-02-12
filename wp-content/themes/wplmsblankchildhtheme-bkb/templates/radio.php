<?php
acf_form_head();
/**
 * Template Name: Radio Page 
 */
get_header('header.php');
?>

<?php get_template_part('header-inner'); ?>


<?php echo do_shortcode('[advertising_tabs]'); ?>

<?php
acf_form(array(
    'post_id'       => 'new_post',
    'field_groups'  => array('group_6926c151009e5'), 
    'submit_value'  => 'Submit Radio Material'
));
?>

<?php get_footer('footer.php'); ?>