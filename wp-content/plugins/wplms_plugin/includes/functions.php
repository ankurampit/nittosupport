<?php
/**
 * Functions for WPLMS 4/5
 *
 * @author      VibeThemes
 * @category    Admin
 * @package     WPLMS Plugin
 * @version     5
 */

 if ( ! defined( 'ABSPATH' ) ) exit;


function wplms_vibebp_carousel_blocks(){

	$blocks=[];
	$blocks['blog_card']=__('Blog Card','wplms'); 
    $blocks['general']=__('General Card','wplms'); 
    $blocks['simple']=__('Simple Card','wplms'); 
    $blocks['generic_card']=__('Generic Card','wplms'); 
    $blocks['generic']=__('Generic block','wplms'); 
    $blocks['blogpost']=__('Blog Post','wplms'); 
    $blocks['images_only']=__('Feature Image','wplms'); 
    $blocks['postblock']=__('Post Block','wplms'); 
    $blocks['course10']=__('Course 10','wplms'); 
    $blocks['course9']=__('Course 9','wplms'); 
    $blocks['course8']=__('Course 8','wplms'); 
    $blocks['course7']=__('Course 7','wplms'); 
    $blocks['course6']=__('Course 6','wplms'); 
    $blocks['course5']=__('Course 5','wplms'); 
    $blocks['course4']=__('Course 4','wplms'); 
    $blocks['course3']=__('Course 3','wplms'); 
    $blocks['course2']=__('Course 2','wplms'); 
    $blocks['course']=__('Course 1','wplms'); 
    $blocks['course_card']=__('Course Card','wplms'); 

    return $blocks;
}

function bp_get_sample_course_certificate_url($course_id){
    $url = apply_filters('bp_get_sample_course_certificate_url','',$course_id);
    if(empty($url)){
        $template_id = get_post_meta($course_id,'vibe_certificate_template',true);
        $url = get_permalink($template_id);
    }
    
    return $url;
}

if(!function_exists('pagination')){
function pagination($pages = '', $range = 4)
{  
     $showitems = ($range * 2)+1;  
 
     global $paged;
     if(empty($paged)) $paged = 1;
 
     if($pages == '')
     {
         global $wp_query;
         $pages = $wp_query->max_num_pages;
         if(!$pages)
         {
             $pages = 1;
         }
     }   
 
     if(1 != $pages)
     {
         echo "<div class=\"pagination\"><span>".__('Page','vibe')." ".$paged." ".__('of','vibe')." ".$pages."</span>";
         if($paged > 2 && $paged > $range+1 && $showitems < $pages) echo "<a href='".get_pagenum_link(1)."'>&laquo; ".__('First','vibe')."</a>";
         if($paged > 1 && $showitems < $pages) echo "<a href='".get_pagenum_link($paged - 1)."'>&lsaquo; ".__('Previous','vibe')."</a>";
 
         for ($i=1; $i <= $pages; $i++)
         {
             if (1 != $pages &&( !($i >= $paged+$range+1 || $i <= $paged-$range-1) || $pages <= $showitems ))
             {
                 echo ($paged == $i)? "<span class=\"current\">".$i."</span>":"<a href='".get_pagenum_link($i)."' class=\"inactive\">".$i."</a>";
             }
         }
 
         if ($paged < $pages && $showitems < $pages) echo "<a href=\"".get_pagenum_link($paged + 1)."\">".__('Next','vibe')." &rsaquo;</a>";  
         if ($paged < $pages-1 &&  $paged+$range-1 < $pages && $showitems < $pages) echo "<a href='".get_pagenum_link($pages)."'>".__('Last','vibe')." &raquo;</a>";
         echo "</div>\n";
     }
}
}

function wplms_getAllFiles($dir) {
    $files = [];

    // Get all items (files and directories) in the given directory
    $items = scandir($dir);

    // Loop through each item
    foreach ($items as $item) {
        // Ignore "." and ".." directories
        if ($item != "." && $item != "..") {
            // Construct the full path
            $path = $dir . "/" . $item;

            // If the item is a directory, recursively call this function
            if (is_dir($path)) {
                $files = array_merge($files, wplms_getAllFiles($path));
            } else {
                // If it's a file, add it to the list
                $files[] = $path;
            }
        }
    }

    return $files;
}