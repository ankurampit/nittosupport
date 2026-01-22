<?php



if (!class_exists('WPLMS_Assignments')){

    class WPLMS_Assignments{
 
    public static $instance;
    
    var $schedule;

    public static function init(){

        if ( is_null( self::$instance ) )
            self::$instance = new WPLMS_Assignments();

        return self::$instance;
    }

        private $adminCheckboxes;
        private $adminPrefix    = 'assignmentAttachment';
        private $key            = 'attachment';
        private $settings;


        public function __construct(){ 
            

            add_action('wplms_get_wplms-assignment_result',array($this,'get_assignment_result'),10,2);
            add_action('wplms_get_user_results',array($this,'get_assignment_results'),20,1);
            add_action('pre_get_posts',array($this,'get_assignments_archive'));
            add_action('wplms_course_student_stats',array($this,'wplms_course_student_stats'),10,3);
         
            //FORCE APPROVE ASSIGNMENT SUBMISSIONS
            add_filter( 'pre_comment_approved', array($this,'approve_submissions'));
         
            //handle course reset assignments reset and course retake assignment reset
            add_action('wplms_course_reset',array($this,'course_assignments_reset'),10,2);
            add_action('wplms_course_retake',array($this,'course_assignments_reset'),10,2);
            
        }

        function course_assignments_reset($course_id,$user_id){
            if(!function_exists('bp_course_get_curriculum_units'))
                return;
            $curriculum_units = bp_course_get_curriculum_units($course_id);
            if(empty($curriculum_units))
                return;
            global $wpdb;
            foreach ($curriculum_units as $key => $unit_id) {
                if(get_post_type($unit_id) == 'unit'){
                    $assignments = get_post_meta($unit_id,'vibe_assignment',true);
                    if(!empty($assignments)){
                        foreach ($assignments as $k => $assignment_id) {
                            
                            delete_user_meta($user_id,$assignment_id);
                            delete_post_meta($assignment_id,$user_id);
                            $wpdb->query($wpdb->prepare("UPDATE $wpdb->comments SET comment_approved='trash' WHERE comment_post_ID=%d AND user_id=%d",$assignment_id,$user_id));
                        }
                    }
                }elseif(get_post_type($unit_id) == 'wplms-assignment'){
                    delete_user_meta($user_id,$unit_id);
                    delete_post_meta($unit_id,$user_id);
                    $wpdb->query($wpdb->prepare("UPDATE $wpdb->comments SET comment_approved='trash' WHERE comment_post_ID=%d AND user_id=%d",$unit_id,$user_id));
                }
            }
        }

        function approve_submissions( $approved ){ 
            if(isset($_POST) && isset($_POST['comment_post_ID']) && is_numeric($_POST['comment_post_ID'])){
              $post_type = get_post_type($_POST['comment_post_ID']);
              if( $post_type == 'wplms-assignment'){ 
                return is_user_logged_in() ? 1 : $approved; 
              } 
            }
            return $approved; 
        }

      


        
        /* Assignment Stats in Course - Admin - User - Stats */

        function wplms_course_student_stats($curriculum,$course_id,$user_id=null){
            $assignments = $this->get_course_assignments($course_id);
            if(is_array($assignments) && count($assignments)){
                $curriculum .= '<li><h5>'._x('Assignments','assignments connected to the course, Course - admin - user - stats','wplms').'</h5></li>';
               foreach($assignments as $assignment){
           
                    $marks = get_post_meta($assignment->post_id,$user_id,true);
                    if(is_numeric($marks)){
                      $curriculum .= '<li><span data-id="'.$assignment->post_id.'" class="done"></span> '.get_the_title($assignment->post_id).' <strong>'.(($marks)?_x('Marks Obtained : ',' marks obtained in assignment result','wplms').$marks:__('Under Evaluation','wplms')).'</strong></li>';
                    }else{
                      $curriculum .= '<li><span data-id="'.$assignment->post_id.'"></span> '.get_the_title($assignment->post_id).'</li>';
                    }
                    
                }
            }
            return $curriculum;
        }


        /* End Stats - HK */

        function get_assignments_archive($query){

            if(!$query->is_archive() || !$query->is_main_query() || is_admin()){
                return $query;
            }

            if($query->get('post_type') != 'wplms-assignment'){
                return $query;
            }

            if(!current_user_can('manage_options')){
                $user_id = get_current_user_id();
                $query->set( 'meta_query', array(
                                   array(
                                         'key' => $user_id,
                                         'compare' => 'EXISTS',
                                        )
                                   )
                              );
            }
        }

        function assignment_submission_tab($tabs,$course_id){
            $tabs['assignment'] = sprintf(_x('Assignment Submissions <span>%d</span>','Assignment Submissions in course/admin/submissions','wplms'),self::get_assignment_submission_count($course_id));
            return $tabs;
        }

        function get_assignment_submission_count($course_id){
            global $wpdb;
            $count =0;
            if(!(!empty($this->assignment_submissions) && !empty($this->assignment_submissions[$course_id]))){
                $assignments = $this->get_course_assignments($course_id);
            
                if(empty($assignments)){
                    return 0;
                }
                $assignment_ids = array();
                foreach($assignments as $assignment_id){
                    $assignment_ids[] = $assignment_id->post_id;
                }
                
                $assignment_ids = implode(',',$assignment_ids);
                $query = apply_filters("wplms_assignment_submissions_query_filter","SELECT DISTINCT p.meta_key as count, p.post_id FROM {$wpdb->postmeta} as p
                    LEFT JOIN {$wpdb->comments} as c ON p.post_id = c.comment_post_ID 
                    WHERE CAST(c.user_id as UNSIGNED) = CAST(p.meta_key as UNSIGNED) 
                    AND p.meta_key REGEXP '^[0-9]+$'
                    AND c.comment_approved='1'
                    AND (meta_value = '0' || meta_value = 0) AND p.post_id IN ($assignment_ids)",$assignment_ids,$course_id);
                $this->assignment_submissions[$course_id] = $wpdb->get_results($query);
            }
            if(!empty($this->assignment_submissions[$course_id])){
                $count = count($this->assignment_submissions[$course_id]);
            }else{
                $count = 0;
            }

            return (empty($count)?0:$count);
        }

        function get_assignment_submissions($course_id){
            global $wpdb;
            $assignments = $this->get_course_assignments($course_id);
            if(!empty($assignments)){
                foreach($assignments as $assignment_id){
                    $assignment_ids[] = $assignment_id->post_id;
                }
                $count_array = array();
                $assignment_ids = implode(',',$assignment_ids);
                if(!(!empty($this->assignment_submissions) && !empty($this->assignment_submissions[$course_id]))){

                    $query = apply_filters("wplms_assignment_submissions_query_filter","SELECT DISTINCT p.meta_key as count, p.post_id FROM {$wpdb->postmeta} as p
                    LEFT JOIN {$wpdb->comments} as c ON p.post_id = c.comment_post_ID 
                    WHERE CAST(c.user_id as UNSIGNED) = CAST(p.meta_key as UNSIGNED) 
                    AND p.meta_key REGEXP '^[0-9]+$'
                    AND c.comment_approved='1'
                    AND (meta_value = '0' || meta_value = 0) AND p.post_id IN ($assignment_ids)",$assignment_ids,$course_id);
                    $this->assignment_submissions[$course_id] = $wpdb->get_results($query);

                }


                
                if(!empty($this->assignment_submissions[$course_id])){
                    foreach($this->assignment_submissions[$course_id] as $submission){
                        $count_array[$submission->post_id][]=$submission->count;
                    }
                }
                ?>
                <div class="submissions_form">
                    <select id="fetch_assignment">
                    <?php
                    foreach($assignments as $assignments_id){
                      ?>
                      <option value="<?php echo $assignments_id->post_id; ?>"><?php echo get_the_title($assignments_id->post_id); ?> (<?php echo ((empty($count_array) || empty($count_array[$assignments_id->post_id]))?0:((!empty($count_array) && !empty($count_array[$assignments_id->post_id]))?count($count_array[$assignments_id->post_id]):0)); ?>)</option>
                      <?php
                    }
                    ?>
                    </select>
                    <select id="fetch_assignment_status">
                        <option value="0"><?php echo _x('Pending evaluation','assignment status','wplms') ?></option>
                        <option value="1"><?php echo _x('Evaluation complete','assignment status','wplms') ?></option>
                        <option value="2"><?php echo _x('Unsubmitted','assignment status','wplms') ?></option>
                    </select>
                    <?php wp_nonce_field('assignment_submissions','assignment_submissions'); ?>
                    <a id="fetch_assignment_submissions" class="button"><?php echo _x('Get','get assignment submissions button','wplms'); ?></a>
                </div>
                <script>
                    jQuery(document).ready(function($){
                        $('#fetch_assignment_submissions').on('click',function(){
                            var parent = $(this).parent();
                            $('.quiz_students').remove();
                            $('.message').remove();
                            var $this = $(this);
                            $this.append('<i class="fa fa-spinner"></i>');
                            $.ajax({
                                type: "POST",
                                url: ajaxurl,
                                data: { action: 'fetch_assignment_submissions', 
                                        security: $('#assignment_submissions').val(),
                                        assignment_id:$('#fetch_assignment').val(),
                                        status:$('#fetch_assignment_status').val(),
                                        },
                                cache: false,
                                success: function (html) {
                                    $('ul.assignment_students').remove();
                                    parent.after(html);
                                    $this.find('.fa').remove();
                                    $(' #assignment').trigger('loaded');
                                }
                            });
                        });
                    });
                </script>
                <?php
            }else{
                ?>
                <div class="message">
                    <p><?php echo _x('No assignments found !','No assignments in course, error on course submissions','wplms'); ?></p>
                </div>
                <?php
            }
    
        }

        function fetch_assignment_submissions(){
            if ( !isset($_POST['security']) || !wp_verify_nonce($_POST['security'],'assignment_submissions') || !is_numeric($_POST['assignment_id'])){
             _e('Security check Failed. Contact Administrator.','wplms');
             die();
            }
            $assignment_id = $_POST['assignment_id'];
            global $wpdb;
            if($_POST['status'] == 1){
                $assignment_submissions = $wpdb->get_results($wpdb->prepare ("
                  SELECT DISTINCT c.user_id as user_id,p.post_id as assignment_id 
                  FROM {$wpdb->postmeta} as p 
                  LEFT JOIN {$wpdb->comments} as c ON p.post_id = c.comment_post_ID 
                  WHERE CAST(c.user_id as UNSIGNED) = CAST(p.meta_key as UNSIGNED) 
                  AND c.comment_approved='1'
                  AND CAST(p.meta_value as UNSIGNED) != 0 
                  && p.post_id = %d 
                  LIMIT 0,999",$assignment_id), ARRAY_A);    
            }else if($_POST['status'] == 2){
              $assignment_submissions = $wpdb->get_results($wpdb->prepare ("
                  SELECT meta_key as user_id,post_id as assignment_id 
                  FROM {$wpdb->postmeta}
                  WHERE post_id = %d
                  AND meta_value = 0
                  AND meta_key REGEXP '^[0-9]+$'
                  LIMIT 0,999",$assignment_id), ARRAY_A);
             
            }else{
                $assignment_submissions = $wpdb->get_results($wpdb->prepare ("SELECT DISTINCT c.user_id as user_id,c.comment_post_ID as assignment_id FROM {$wpdb->comments} as c WHERE c.comment_post_ID = %d AND c.comment_approved='1' AND NOT EXISTS (SELECT * FROM {$wpdb->postmeta} as p WHERE p.post_id = %d  AND p.meta_value > '0' AND p.meta_key = c.user_id ) LIMIT 0,999",$assignment_id,$assignment_id), ARRAY_A);  
            }
            
            if(count($assignment_submissions)){
                echo '<ul class="assignment_students">';
                foreach($assignment_submissions as $assignment_submission ){
                    if(is_numeric($assignment_submission['user_id'])){
                    $member_id=$assignment_submission['user_id'];
                    $assignment_id=$assignment_submission['assignment_id'];
                    $bp_name = bp_core_get_userlink( $member_id );
                    if(!isset($student_field))
                        $student_field='Location';
                    $profile_data = 'field='.$student_field.'&user_id='.$member_id;
                    
                    $bp_location ='';
                    if(bp_is_active('xprofile'))
                    $bp_location = bp_get_profile_field_data($profile_data);
                    echo '<li id="as'.$member_id.'">';
                    echo get_avatar($member_id);
                    echo '<h6>'. $bp_name . '</h6>';
                    echo '<span>';
                    if ($bp_location) {
                        echo $bp_location ;
                    }
                    do_action('wplms_assignment_submission_meta',$member_id,$assignment_id);
                    echo '</span>';
                    // PENDING AJAX SUBMISSIONS
                    echo '<ul> 
                            <li><a class="tip reset_assignment_user" data-assignment="'.$assignment_id.'" data-user="'.$member_id.'" title="'.__('Reset Assignment for User','wplms').'"><i class="icon-reload"></i></a></li>
                            <li><a class="tip evaluate_assignment_user" data-assignment="'.$assignment_id.'" data-user="'.$member_id.'" title="'.__('Evaluate Assignment : ','wplms').get_the_title($assignment_id).'"><i class="icon-check-clipboard-1"></i></a></li>
                          </ul>';
                    echo '</li>';
                    }
                }
                echo '</ul>';
                
            }else{
                echo '<div class="error message"><p>'.__('No submissions found !','wplms').'</p></div>';
            }
            wp_nonce_field('vibe_assignment','asecurity');
            die();
        }

        function get_course_assignments($course_id){

            global $wpdb;
            $this->assignments = $wpdb->get_results($wpdb->prepare("SELECT m.post_id as post_id,p.post_title as title FROM {$wpdb->postmeta} as m LEFT JOIN {$wpdb->posts} as p on p.ID = m.post_id WHERE m.meta_key = %s and m.meta_value = %d  AND p.post_type = 'wplms-assignment' ORDER BY p.post_title",'vibe_assignment_course',$course_id));
            $unit_assignments = $this->assignments;
            if(empty($unit_assignments)){
              $unit_assignments = array();
            }
            if(function_exists('bp_course_get_curriculum')){
              $curriculum = bp_course_get_curriculum($course_id);
              if(!empty($curriculum)){
                foreach ($curriculum as $key => $assignment) {
                  if(is_numeric($assignment) && get_post_type($assignment) == 'wplms-assignment' && !$this->check_assignment_is_in_unit_assignments($unit_assignments,$assignment)){
                    $this->assignments[] = (object)array('post_id'=>$assignment,'title'=>get_the_title($assignment));
                  }
                }
              }
            }
            
            return $this->assignments;
        }

        function check_assignment_is_in_unit_assignments($unit_assignments,$assignment_id){
          $flag = 0;
          foreach ($unit_assignments as $key => $unit_assignment) {
            if($unit_assignment->post_id ==  $assignment_id){
              $flag = 1;
              break;
            }
          }
          return $flag;
        }

        function wplms_assignments_before_single_assignment(){
           if(!is_user_logged_in())
             return;

             global $post;
             $user_id = get_current_user_id();
             if(wplms_assignment_answer_posted()){
               $submitted = get_post_meta($post->ID,$user_id,true);
               if(empty($submitted)){
                  update_post_meta($post->ID,$user_id,0);
                  do_action('wplms_submit_assignment',$post->ID,$user_id);
                  return;
               }
             }
        }

        function get_assignment_results($user_id){
            
            //ASSIGNMENTS   
            $paged = 1;
            $per_page = 2;
            if(function_exists('vibe_get_option')){
                $per_page = vibe_get_option('loop_number');  
            }
            $the_assignment=new WP_QUERY(array(
                'post_type'=>'wplms-assignment',
                'paged' => $paged,
                'posts_per_page'=>$per_page,
                'meta_query'=>array(
                    array(
                        'key' => $user_id,
                        'compare' => 'EXISTS'
                        ),
                    ),
                ));
            if($the_assignment->have_posts()){
                
            ?>
            <h3 class="heading"><?php _e('Assignment Results','wplms'); ?></h3>
            <div class="user_results">
                <ul class="quiz_results">   
                <?php
                while($the_assignment->have_posts()) : $the_assignment->the_post();
                global $post;
                    $this->results_item($post,$user_id);
                endwhile;
                ?>
                </ul>
                <?php
                if($the_assignment->max_num_pages>1){
                    ?>
                    <div class="pagination no-ajax">
                        <div class="pag-count">
                            <?php echo sprintf(__('Viewing %d out of %d','wplms'),$paged,$the_assignment->max_num_pages) ?>
                        </div>
                        <div class="pagination-links">
                            <?php
                            for($i=1;$i<=$the_assignment->max_num_pages;$i++){
                                if(($paged==$i)){
                                    ?>
                                    <span class="page-numbers current"><?php echo $i;?></span>
                                    <?php
                                }else{
                                    ?>
                                    <a class="get_results_pagination" data-type="wplms-assignment"><?php echo $i;?></a>
                                    <?php
                                }
                            }
                            ?>
                        </div>
                    </div>
                <?php }
                ?>
            </div>
            <?php 
                wp_nonce_field('security','security');
                wp_reset_query();

            }// End Assignment -> Have posts
        }

        function results_item($post,$user_id){

            $value = get_post_meta($post->ID,$user_id,true);
            $max = get_post_meta($post->ID,'vibe_assignment_marks',true);
            ?>
            <li><i class="icon-task"></i>
                <a href="?action=<?php echo $post->ID; ?>"><?php echo $post->post_title; ?></a>
                <span><?php 
                if($value > 0){
                    echo '<i class="icon-check"></i> '.__('Results Available','wplms');
                }else{
                    echo '<i class="icon-alarm"></i> '.__('Results Awaited','wplms');
                }
                ?></span>
                <span><?php
                global $wpdb,$bp;
                $assignment_activity_date = $wpdb->get_var($wpdb->prepare( "
                    SELECT date_recorded FROM {$bp->activity->table_name} AS activity
                    WHERE activity.component = 'course'
                    AND activity.type = 'assignment_submitted'
                    AND user_id = %d
                    AND ( item_id = %d OR secondary_item_id = %d )
                    ORDER BY date_recorded DESC
                    LIMIT 0,1
                    " ,$user_id,$post->ID,$post->ID));
                if(!empty($assignment_activity_date)){
                    $time = strtotime($assignment_activity_date);

                    echo '<i class="icon-clock"></i> '.sprintf(_x('Submitted %s','assignment submission time','wplms'),human_time_diff($time,time()));
                    ?></span>
                    <?php
                }
                if($value > 0)
                    echo '<span><strong>'.$value.' / '.$max.'</strong></span>';
                ?>
            </li>
            <?php
        }
        function get_assignment_result($assignment_id,$user_id){

            $assignment_post=get_post($assignment_id);
            $assignment_marks = get_post_meta($assignment_id,$user_id,true);
            $total_assignment_marks = get_post_meta($assignment_id,'vibe_assignment_marks',true);

            echo '<div class="assignment_content">';
            echo '<h3 class="heading">'.get_the_title($assignment_id).'</h3>';
            echo apply_filters('the_content',$assignment_post->post_content);

            echo '<h3 class="heading">'.__('My Submission','wplms').'</h3>';
            $answers=get_comments(array(
              'post_id' => $assignment_id,
              'status' => 'approve',
              'number' => 1,
              'user_id' => $user_id
              ));
            if(isset($answers) && is_array($answers) && count($answers)){
                $answer = end($answers);
                echo $answer->comment_content;
                $attachment_id=get_comment_meta($answer->comment_ID, 'attachmentId',true);
                if(!empty($attachment_id) && $attachment_id){
                  if(is_array($attachment_id)){
                    foreach($attachment_id as $attachid){
                      echo '<div class="download_attachment"><a href="'.wp_get_attachment_url($attachid).'" target="_blank"><i class="icon-download-3"></i> '.__('Download Attachment','wplms').'</a></div>';
                    }
                  }else{
                    echo '<div class="download_attachment"><a href="'.wp_get_attachment_url($attachment_id).'" target="_blank"><i class="icon-download-3"></i> '.__('Download Attachment','wplms').'</a></div>';
                  }
                }
            }
            $instructor_id = get_post_field('post_author',$assignment_id);
            global $wpdb,$bp;
            $table_name=$bp->activity->table_name;
            $meta_table_name=$bp->activity->table_name_meta;
            $remarkmessage = $wpdb->get_results($wpdb->prepare( "
                                        SELECT meta.meta_value FROM {$meta_table_name} AS meta 
                                        LEFT JOIN {$table_name} AS activity
                                        ON meta.activity_id = activity.id
                                        WHERE meta.meta_key = 'remarks{$user_id}'
                                        AND   activity.component  = 'course'
                                        AND     activity.type   = 'evaluate_assignment'
                                        AND     activity.user_id = %d
                                        AND     activity.secondary_item_id = %d
                                        ORDER BY activity.date_recorded DESC LIMIT 0,1
                                        
                                    " ,$instructor_id,$assignment_id));
            $remarks=$remarkmessage[0]->meta_value;
            if(isset($remarks)){
                echo '<a href="'.trailingslashit( bp_loggedin_user_domain() . $bp->messages->slug . '/view/' . $remarks ).'" class="button right small">'.__('See Instructor Remarks','wplms').'</a><span class="clearfix"></span>';
            }
            
            echo '<div id="total_marks">'.__('Marks Obtained','wplms').' <strong><span>'.$assignment_marks.'</span> / '.$total_assignment_marks.'</strong> </div>';
            echo '</div>';
        }
        /******************* Inits, innit :D *******************/

        /**
         * Loaded, check request
         */

        public function loaded()
        {
            // check to delete att
            if(isset($_GET['deleteAtt']) && ($_GET['deleteAtt'] == '1')){
                if((isset($_GET['c'])) && is_numeric($_GET['c'])){
                    WPLMS_Assignments::deleteAttachment($_GET['c']);
                    delete_comment_meta($_GET['c'], 'attachmentId');
                    add_action('admin_notices',array($this, 'mynotice'));
                }
            }
        }

       function mynotice(){
            echo "<div class='updated'><p>".__('Assignment Attachment deleted.','wplms')."</p></div>";
        }
        /**
         * Classic init
         */

        public function initialise(){
            
            if(!$this->checkRequirements()){ return; }
            $this->checkformagain = 0;
            add_filter('preprocess_comment',        array($this, 'checkAttachment'));
            add_action('comment_form_top',          array($this, 'displayBeforeForm'));
            add_action('comment_form_before_fields',array($this, 'displayFormAttBefore'));
            add_action('comment_form_logged_in_after',array($this, 'displayFormAtt'));
            add_filter('comment_text',              array($this, 'displayAttachment'));
            
            add_filter('upload_mimes',              array($this, 'getAllowedUploadMimes'));
            
            add_filter('comment_notification_text', array($this, 'notificationText'), 10, 2);
        }


        /**
         * Admin init
         */

        public function adminInit()
        {
            $this->setUserNag();
            add_filter('comment_row_actions', array($this, 'addCommentActionLinks'), 10, 2);
        }


        /*************** Plugins admin settings ****************/

        /**
         * Get's admin settings page variables
         *
         * @return mixed
         */

        public function getSettings() {
            $this->settings = $this->getAllowedFileExtensions();
        }


        private function getSavedSettings(){ 
            $this->settings = $this->getAllowedFileExtensions();
        }


        /**
         * Define plugin constatns
         */

        private function defineConstants(){
            
            if(!defined('ATT_REQ'))
                define('ATT_REQ',   TRUE );

            define('ATT_BIND',  TRUE );
            define('ATT_DEL',   TRUE );
            define('ATT_LINK',  TRUE );
            define('ATT_THUMB',  TRUE );
            define('ATT_PLAY',  TRUE );
            define('ATT_POS',   'before' );
            define('ATT_APOS',  'before');
            define('ATT_TITLE', __('Upload Assignment','wplms'));
            if ( ! defined( 'ATT_MAX' ) )
                define('ATT_MAX',  $this->getmaxium_upload_file_size());    
        }


        /**
         * For image thumb dropdown.
         *
         * @return mixed
         */

        private function getRegisteredImageSizes()
        {
            foreach(get_intermediate_image_sizes() as $size){
                $arr[$size] = ucfirst($size);
            };
            return $arr;
        }

        function getmaxium_upload_file_size($post_id = null){
            if(empty($post_id)){
             global $post;
             if(isset($post) && is_object($post) && isset($post->ID))
                $post_id = $post->ID;
            }
            $upload_size = 1024;
            $max_upload = (int)(ini_get('upload_max_filesize'));
            $max_post = (int)(ini_get('post_max_size'));
            $memory_limit = (int)(ini_get('memory_limit'));
            $upload_mb = min($max_upload, $max_post, $memory_limit);
            $attachment_size=get_post_meta($post_id,'vibe_attachment_size',true); 
            
            if(isset($attachment_size) && is_numeric($attachment_size)){
                if($attachment_size > $upload_mb && empty($this->plupload_assignment_e_d )){
                    $upload_size=$upload_mb;
                }else{
                    $upload_size=$attachment_size;
                }
                
            }

            return $upload_size;
        }
        /**
         * If there's a place to set up those mime types,
         * it's here.
         *
         * @return array
         */

        private function getMimeTypes()
        {
            return apply_filters('wplms_assignments_upload_mimes_array',array(
                'JPG' => array(
                                'image/jpeg',
                                'image/jpg',
                                'image/jp_',
                                'application/jpg',
                                'application/x-jpg',
                                'image/pjpeg',
                                'image/pipeg',
                                'image/vnd.swiftview-jpeg',
                                'image/x-xbitmap'),
                'GIF' => array(
                                'image/gif',
                                'image/x-xbitmap',
                                'image/gi_'),
                'PNG' => array(
                                'image/png',
                                'application/png',
                                'application/x-png'),
                'DOCX'=> 'application/vnd.openxmlformats-officedocument.wordprocessingml.document',
                'RAR'=> 'application/x-rar',
                'ZIP' => array(
                                'application/zip',
                                'application/x-zip',
                                'application/x-zip-compressed',
                                'application/x-compress',
                                'application/x-compressed',
                                'multipart/x-zip'),
                'DOC' => array(
                                'application/msword',
                                'application/doc',
                                'application/text',
                                'application/vnd.msword',
                                'application/vnd.ms-word',
                                'application/winword',
                                'application/word',
                                'application/x-msw6',
                                'application/x-msword'),
                'PDF' => array(
                                'application/pdf',
                                'application/x-pdf',
                                'application/acrobat',
                                'applications/vnd.pdf',
                                'text/pdf',
                                'text/x-pdf'),
                'PPT' => array(
                                'application/vnd.ms-powerpoint',
                                'application/mspowerpoint',
                                'application/ms-powerpoint',
                                'application/mspowerpnt',
                                'application/vnd-mspowerpoint',
                                'application/powerpoint',
                                'application/x-powerpoint',
                                'application/x-m'),
                'PPTX'=> 'application/vnd.openxmlformats-officedocument.presentationml.presentation',
                'PPS' => 'application/vnd.ms-powerpoint',
                'PPSX'=> 'application/vnd.openxmlformats-officedocument.presentationml.slideshow',
                'PSD' => array('application/octet-stream',
                                'image/vnd.adobe.photoshop'
                                ),
                'ODT' => array(
                                'application/vnd.oasis.opendocument.text',
                                'application/x-vnd.oasis.opendocument.text'),
                'XLS' => array(
                                'application/vnd.ms-excel',
                                'application/msexcel',
                                'application/x-msexcel',
                                'application/x-ms-excel',
                                'application/vnd.ms-excel',
                                'application/x-excel',
                                'application/x-dos_ms_excel',
                                'application/xls'),
                'XLSX'=> array('application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
                          'application/vnd.ms-excel'),
                'MP3' => array(
                                'audio/mpeg',
                                'audio/x-mpeg',
                                'audio/mp3',
                                'audio/x-mp3',
                                'audio/mpeg3',
                                'audio/x-mpeg3',
                                'audio/mpg',
                                'audio/x-mpg',
                                'audio/x-mpegaudio'),
                'M4A' => array(
                                'audio/mp4a-latm',
                                'audio/m4a',
                                'audio/mp4'),
                'OGG' => array(
                                'audio/ogg',
                                'application/ogg'),
                'WAV' => array(
                                'audio/wav',
                                'audio/x-wav',
                                'audio/wave',
                                'audio/x-pn-wav'),
                'WMA' => 'audio/x-ms-wma',
                'MP4' => array(
                                'video/mp4v-es',
                                'audio/mp4',
                                'video/mp4'),
                'M4V' => array(
                                'video/mp4',
                                'video/x-m4v'),
                'MOV' => array(
                                'video/quicktime',
                                'video/x-quicktime',
                                'image/mov',
                                'audio/aiff',
                                'audio/x-midi',
                                'audio/x-wav',
                                'video/avi'),
                'WMV' => 'video/x-ms-wmv',
                'AVI' => array(
                                'video/avi',
                                'video/msvideo',
                                'video/x-msvideo',
                                'image/avi',
                                'video/xmpg2',
                                'application/x-troff-msvideo',
                                'audio/aiff',
                                'audio/avi'),
                'MPG' => array(
                                'video/avi',
                                'video/mpeg',
                                'video/mpg',
                                'video/x-mpg',
                                'video/mpeg2',
                                'application/x-pn-mpg',
                                'video/x-mpeg',
                                'video/x-mpeg2a',
                                'audio/mpeg',
                                'audio/x-mpeg',
                                'image/mpg'),
                'OGV' => 'video/ogg',
                '3GP' => array(
                                'audio/3gpp',
                                'video/3gpp'),
                '3G2' => array(
                                'video/3gpp2',
                                'audio/3gpp2'),
                'FLV' => 'video/x-flv',
                'WEBM'=> 'video/webm',
                'APK' => 'application/vnd.android.package-archive',
            ));
        }


        /**
         * Gets allowed file types extensions
         *
         * @return array
         */
        
        public function getAllowedFileExtensions($post_id=null){
            if(empty($post_id) && !isset($_POST['comment_post_ID'])){
                global $post;
                if(isset($post) && is_object($post)){
                  $post_id = $post->ID;  
                }else{
                  return;
                }
            }

            $return = array();
            $pluginFileTypes = $this->getMimeTypes();

            if(isset($_POST['comment_post_ID'])){
                $assignment_id = $_POST['comment_post_ID'];
            }
            
            if(empty($assignment_id)){
                $assignment_id = $post_id;
            }
            $attachment_type=get_post_meta($assignment_id,'vibe_attachment_type',true);
            if(is_array($attachment_type) && in_array('JPG',$attachment_type)){
                $attachment_type[]='JPEG';
            }
            if(empty($attachment_type)){
              $attachment_type=array('JPEG');
            }
            return $attachment_type;
        }


        /**
         * Gets allowed file types for attachment check.
         *
         * @return array
         */

        public function getAllowedMimeTypes($post_id=null)
        {   
            if(empty($post_id)){
                global $post;
                $post_id = $post->ID;
            }
            $return = array();
            $pluginFileTypes = $this->getMimeTypes();
            $ext=$this->getAllowedFileExtensions($post_id);
            foreach($ext as $key){
                if(array_key_exists($key, $pluginFileTypes)){
                    if(!function_exists('finfo_file') || !function_exists('mime_content_type')){
                        if(($key ==  'DOCX') || ($key == 'DOC') || ($key == 'PDF') ||
                            ($key == 'ZIP') || ($key == 'RAR')){
                            $return[] = 'application/octet-stream';
                        }
                    }
                    if(is_array($pluginFileTypes[$key])){
                        foreach($pluginFileTypes[$key] as $fileType){
                            $return[] = $fileType;
                        }
                    } else {
                        $return[] = $pluginFileTypes[$key];
                    }
                }
            }
            return $return;
        }

        function _mime_content_type($filename) {

            /**
            *    mimetype
            *    Returns a file mimetype. Note that it is a true mimetype fetch, using php and OS methods. It will NOT
            *    revert to a guessed mimetype based on the file extension if it can't find the type.
            *    In that case, it will return false
            **/
            if (!file_exists($filename) || !is_readable($filename)) return false;
            if(class_exists('finfo')){
                $result = new finfo();
                if (is_resource($result) === true) {
                    return $result->file($filename, FILEINFO_MIME_TYPE);
                }
            }
            
             // Trying finfo
             if (function_exists('finfo_open')) {
               $finfo = finfo_open(FILEINFO_MIME);
               $mimeType = finfo_file($finfo, $filename);
               finfo_close($finfo);
               // Mimetype can come in text/plain; charset=us-ascii form
               if (strpos($mimeType, ';')) list($mimeType,) = explode(';', $mimeType);
               return $mimeType;
             }
            
             // Trying mime_content_type
             if (function_exists('mime_content_type')) {
               return mime_content_type($filename);
             }
            

             // Trying to get mimetype from images
             $imageData = getimagesize($filename);
             if (!empty($imageData['mime'])) {
               return $imageData['mime'];
             }
             // Trying exec
             if (function_exists('wp_check_filetype')) {
               $mimeType = wp_check_filetype($filename);
               $mimeType = $mimeType['type'];
               if (!empty($mimeType)) return $mimeType;
             }
            return false;
        }

        /**
         * This one actually will need explaining, it's hard
         *
         * @param array $existing
         * @return array
         */

        public function getAllowedUploadMimes($existing = array())
        {
            // we get mime types and saved file types
            $return = array();
            $pluginFileTypes = $this->getMimeTypes();
            if(is_array($this->settings))
            foreach($this->settings as $key ){
                // list thru them and if it's allowed and not in list, we added there,
                // in reality, I'm thinking about removing the wp ones, and all mines,
                // since wordpress mime types are very limited, we can do better guys
                // cuase it sucks, and doesn't have enough mime types, actually let's
                // just do it ...
                if(array_key_exists($key, $pluginFileTypes)){
                    $keyCheck = strtolower($key);
                    // here we would have checked, if mime type is already there,
                    // but we want strong list of mime types, so we just add it all.
                    if(is_array($pluginFileTypes[$key])){
                        foreach($pluginFileTypes[$key] as $fileType){
                            $keyHacked = preg_replace("/[^0-9a-zA-Z ]/", "", $fileType);
                            $return[$keyCheck . '|' . $keyCheck . '_' . $keyHacked] = $fileType;
                        }
                    } else {
                        $return[$keyCheck] = $pluginFileTypes[$key];
                    }
                }
            }
            return array_merge($return, $existing);
        }


        /*
         * For error info, and form upload info.
         */

        public function displayAllowedFileTypes($post_id=null)
        {   
            $fileTypesString = '';
            $filetypes = $this->getAllowedFileExtensions($post_id);
            if(isset($filetypes) && is_Array($filetypes))
            foreach($filetypes as $value){
                $fileTypesString .= $value . ', ';
            }

            return substr($fileTypesString, 0, -2);
        }


        /**
         * For attachment display, get's image mime types
         *
         * @return array
         */

        public function getImageMimeTypes()
        {
            return array(
                'image/jpeg',
                'image/jpg',
                'image/jp_',
                'application/jpg',
                'application/x-jpg',
                'image/pjpeg',
                'image/pipeg',
                'image/vnd.swiftview-jpeg',
                'image/x-xbitmap',
                'image/gif',
                'image/x-xbitmap',
                'image/gi_',
                'image/png',
                'application/png',
                'application/x-png'
            );
        }


        /**
         * For attachment display, get's audio mime types
         *
         * @return array
         */
        // TODO: only check ones audio player can play?

        public function getAudioMimeTypes()
        {
            return array(
                'audio/mpeg',
                'audio/x-mpeg',
                'audio/mp3',
                'audio/x-mp3',
                'audio/mpeg3',
                'audio/x-mpeg3',
                'audio/mpg',
                'audio/x-mpg',
                'audio/x-mpegaudio',
                'audio/mp4a-latm',
                'audio/ogg',
                'application/ogg',
                'audio/wav',
                'audio/x-wav',
                'audio/wave',
                'audio/x-pn-wav',
                'audio/x-ms-wma'
            );
        }


        /**
         * For attachment display, get's audio mime types
         *
         * @return array
         */

        public function getVideoMimeTypes()
        {
            return array(
                'video/mp4v-es',
                'audio/mp4',
                'video/mp4',
                'video/x-m4v',
                'video/quicktime',
                'video/x-quicktime',
                'image/mov',
                'audio/aiff',
                'audio/x-midi',
                'audio/x-wav',
                'video/avi',
                'video/x-ms-wmv',
                'video/avi',
                'video/msvideo',
                'video/x-msvideo',
                'image/avi',
                'video/xmpg2',
                'application/x-troff-msvideo',
                'audio/aiff',
                'audio/avi',
                'video/avi',
                'video/mpeg',
                'video/mpg',
                'video/x-mpg',
                'video/mpeg2',
                'application/x-pn-mpg',
                'video/x-mpeg',
                'video/x-mpeg2a',
                'audio/mpeg',
                'audio/x-mpeg',
                'image/mpg',
                'video/ogg',
                'audio/3gpp',
                'video/3gpp',
                'video/3gpp2',
                'audio/3gpp2',
                'video/x-flv',
                'video/webm',
            );
        }


        /**
         * This way we sort of fake our "enctype" in, since there's not ohter hook
         * that would allow us to put it there naturally, and no, we won't use JS for that
         * since that's rubbish and not bullet-proof. Yes, this creates empty form on page,
         * but who cares, it works and does the trick.
         */

        public function displayBeforeForm(){
            if(get_post_type() != WPLMS_ASSIGNMENTS_CPT)
                return;
            if(empty($this->plupload_assignment_e_d))
            echo '</form><form action="'.site_url( '/wp-comments-post.php' ).'" method="post" enctype="multipart/form-data" id="attachmentForm" class="comment-form" novalidate>';
        }


        
       
        /*
         * Display form upload field.
         */

        public function displayFormAttBefore()  { 
            if(get_post_type() != WPLMS_ASSIGNMENTS_CPT)
                return; 
            if(ATT_POS == 'before'){ $this->displayFormAtt(); } 
        }

        
        /**
         * Rearrange $_Files array
         *
         * @param $data
         * @return mixed
         */
        function reArrayFiles(&$file_post) {

            $file_ary = array();
            if(is_array($file_post)){
                $file_count = count($file_post['name']);
                $file_keys = array_keys($file_post);

                for ($i=0; $i<$file_count; $i++) {
                    foreach ($file_keys as $key) {
                        $file_ary[$i][$key] = $file_post[$key][$i];
                    }
                }

                return $file_ary;
            }
        }
        /**
         * Checks attachment, size, and type and throws error if something goes wrong.
         *
         * @param $data
         * @return mixed
         */

        public function checkAttachment($data){   

            if(get_post_type($data['comment_post_ID']) != WPLMS_ASSIGNMENTS_CPT)
                return $data;

            $assignmenttype = get_post_meta($data['comment_post_ID'],'vibe_assignment_submission_type',true);

            if($assignmenttype != 'upload')
                return $data;

            if(!empty($_FILES)){
                $files = $this->reArrayFiles($_FILES['attachment']);
                if(is_array($files))
                foreach($files as $file){
                    if($file['size'] > 0 && $file['error'] == 0){
                        $fileInfo = pathinfo($file['name']);
                        $fileExtension = strtolower($fileInfo['extension']);
                        $fileType = $this->_mime_content_type($file['tmp_name']); // custom function

                        if (!in_array($fileType, $this->getAllowedMimeTypes()) || !in_array(strtoupper($fileExtension), $this->getAllowedFileExtensions()) || $file['size'] > ($this->getmaxium_upload_file_size($data['comment_post_ID']) * 1048576)) { // file size from admin
                            wp_die('<strong>'.__('ERROR:','wplms').'</strong> '.__('File you upload must be valid file type','wplms').' <strong>('. $this->displayAllowedFileTypes() .')</strong>'.__(', and under ','wplms'). $this->getmaxium_upload_file_size($data['comment_post_ID']) .__('MB(s)!','wplms'));
                        }

                    } elseif (ATT_REQ && $file['error'] == 4 && empty($data['comment_parent'])) {
                        wp_die('<strong>'.__('ERROR:','wplms').'</strong> '.__('Please upload an Attachment.','wplms'));
                    } elseif($file['error'] == 1 && empty($data['comment_parent'])) {
                        wp_die('<strong>'.__('ERROR:','wplms').'</strong> '.__('The uploaded file exceeds the upload_max_filesize directive in php.ini.','wplms'));
                    } elseif($file['error'] == 2 && empty($data['comment_parent'])) {
                        wp_die('<strong>'.__('ERROR:','wplms').'</strong> '.__('The uploaded file exceeds the MAX_FILE_SIZE directive that was specified in the HTML form.','wplms'));
                    } elseif($file['error'] == 3 && empty($data['comment_parent'])) {
                        wp_die('<strong>'.__('ERROR:','wplms').'</strong> '.__('The uploaded file was only partially uploaded. Please try again later.','wplms'));
                    } elseif($file['error'] == 6 && empty($data['comment_parent'])) {
                        wp_die('<strong>'.__('ERROR:','wplms').'</strong> '.__('Missing a temporary folder.','wplms'));
                    } elseif($file['error'] == 7 && empty($data['comment_parent'])) {
                        wp_die('<strong>'.__('ERROR:','wplms').'</strong> '.__('Failed to write file to disk.','wplms'));
                    } elseif($file['error'] == 7 && empty($data['comment_parent'])) {
                        wp_die('<strong>'.__('ERROR:','wplms').'</strong> '.__('A PHP extension stopped the file upload.','wplms'));
                    }
                }
            }elseif(!empty($this->plupload_assignment_e_d) && empty($_POST['attachment_ids']) && empty($data['comment_parent'])){
              wp_die('<strong>'.__('ERROR:','wplms').'</strong> '.__('Please upload an Attachment.','wplms'));
            }
            
            return $data;
        }


        /**
         * Notification email message
         *
         * @param $notify_message
         * @param $comment_id
         * @return string
         */

        public function notificationText($notify_message,  $comment_id)
        {
            if(WPLMS_Assignments::hasAttachment($comment_id)){
                $attachmentIds = get_comment_meta($comment_id, 'attachmentId', TRUE);
                if(is_Array($attachmentIds)){
                    foreach($attachmentIds as $attachmentId){
                        $attachmentName = basename(get_attached_file($attachmentId));
                        $notify_message .= __('Attachment:','wplms') . "\r\n" .  $attachmentName . "\r\n\r\n";
                    }
                }else{
                    $attachmentId = $attachmentIds;
                    $attachmentName = basename(get_attached_file($attachmentId));
                    $notify_message .= __('Attachment:','wplms') . "\r\n" .  $attachmentName . "\r\n\r\n";
                }
            }
            return $notify_message;
        }


        /**
         * Inserts file attachment from your comment to wordpress
         * media library, assigned to post.
         *
         * @param $fileHandler
         * @param $postId
         * @return mixed
         */
            

        public function insertAttachment($fileHandler, $postId)
        {
            require_once(ABSPATH . "wp-admin" . '/includes/image.php');
            require_once(ABSPATH . "wp-admin" . '/includes/file.php');
            require_once(ABSPATH . "wp-admin" . '/includes/media.php');

            
            return media_handle_upload($fileHandler, $postId);
        }


        public function displayAttachment($comment)
        {
            $attachmentIds = get_comment_meta(get_comment_ID(), 'attachmentId', TRUE);
            if(!is_array($attachmentIds)){
                $attachmentIds = array($attachmentIds);
            }
            foreach($attachmentIds as $attachmentId){
                if(is_numeric($attachmentId) && !empty($attachmentId)){

                    // atachement info
                    $attachmentLink = wp_get_attachment_url($attachmentId);
                    $attachmentMeta = wp_get_attachment_metadata($attachmentId);
                    $attachmentName = basename(get_attached_file($attachmentId));
                    $attachmentType = get_post_mime_type($attachmentId);
                    $attachmentRel  = '';
                    $contentInner = '';
                    // let's do wrapper html
                    $contentBefore  = '<div class="attachmentFile"><p>' . $this->settings[$this->adminPrefix . 'ThumbTitle'] . ' ';
                    $contentAfter   = '</p><div class="clear clearfix"></div></div>';

                    // admin behaves differently
                    if(is_admin()){
                        $contentInner = $attachmentName;
                    } else {
                        // shall we do image thumbnail or not?
                        if(ATT_THUMB && in_array($attachmentType, $this->getImageMimeTypes()) && !is_admin()){
                            $attachmentRel = 'rel="lightbox"';
                            $contentInner = wp_get_attachment_image($attachmentId, 'thumb');
                            // audio player?
                        } elseif (ATT_PLAY && in_array($attachmentType, $this->getAudioMimeTypes())){
                            if(shortcode_exists('audio')){
                                $contentInner = do_shortcode('[audio src="'. $attachmentLink .'"]');
                            } else {
                                $contentInner = $attachmentName;
                            }
                            // video player?
                        } elseif (ATT_PLAY && in_array($attachmentType, $this->getVideoMimeTypes())){
                            if(shortcode_exists('video')){
                                $contentInner .= do_shortcode('[video src="'. $attachmentLink .'"]');
                            } else {
                                $contentInner = $attachmentName;
                            }
                            // rest ..
                        } else {
                            $contentInner = '&nbsp;<strong>' . $attachmentName . '</strong>';
                        }
                    }

                    // attachment link, if it's not video / audio
                    if(is_admin()){
                        $contentInnerFinal = '<a '.$attachmentRel.' class="attachmentLink" target="_blank" href="'. $attachmentLink .'" title="'.__('Download','wplms').': '. $attachmentName .'">';
                            $contentInnerFinal .= $contentInner;
                        $contentInnerFinal .= '</a>';
                    } else {
                        if((ATT_LINK) && !in_array($attachmentType, $this->getAudioMimeTypes()) && !in_array($attachmentType, $this->getVideoMimeTypes())){
                            $contentInnerFinal = '<a '.$attachmentRel.' class="attachmentLink" target="_blank" href="'. $attachmentLink .'" title="Download: '. $attachmentName .'">';
                                $contentInnerFinal .= $contentInner;
                            $contentInnerFinal .= '</a>';
                        } else {
                            $contentInnerFinal = $contentInner;
                        }
                    }

                    // bring a sellotape, this needs taping together
                    $contentInsert = $contentBefore . $contentInnerFinal . $contentAfter;

                    // attachment comment position
                    if(ATT_APOS == 'before' && !is_admin()){
                        $comment = $contentInsert . $comment;
                    } elseif(ATT_APOS == 'after' || is_admin()) {
                        $comment .= $contentInsert;
                    }
                }
            }
            return $comment;
        }


        /**
         * This deletes attachment after comment deletition.
         *
         * @param $commentId
         */

        public function deleteAttachment($commentId)
        {
            $attachmentId = get_comment_meta($commentId, 'attachmentId', TRUE);
            if(is_numeric($attachmentId) && !empty($attachmentId) && ATT_DEL){
                wp_delete_attachment($attachmentId, TRUE);
            }
        }


        /**
         * Has attachment
         *
         * @param $commentId
         * @return bool
         */

        public static function hasAttachment($commentId)
        {
            $attachmentId = get_comment_meta($commentId, 'attachmentId', TRUE);
            if(is_numeric($attachmentId) && !empty($attachmentId)){
                return true;
            }
            return false;
        }


        /*************** Admin Settings Functions **************/

        /**
         * Comment Action links
         *
         * @param $actions
         * @param $comment
         * @return array
         */

        public function addCommentActionLinks($actions, $comment)
        {
            if(WPLMS_Assignments::hasAttachment($comment->comment_ID)){
                $url = $_SERVER["SCRIPT_NAME"] . "?c=$comment->comment_ID&deleteAtt=1";
                $actions['deleteAtt'] = "<a href='$url' title='".esc_attr__('Delete Attachment','wplms')."'>".__('Delete Attachment','wplms').'</a>';
            }
            return $actions;
        }


        /***************** Plugin basic weapons ****************/

        /**
         * Let's check Wordpress version, and PHP version and tell those
         * guys whats needed to upgrade, if anything.
         *
         * @return bool
         */

        private function checkRequirements()
        {
            if (!function_exists('mime_content_type') && !function_exists('finfo_file') && version_compare(PHP_VERSION, '5.3.0') < 0){
                add_action('admin_notices', array($this, 'displayFunctionMissingNotice'));
                return TRUE;
            }
            return TRUE;
        }


        /**
         * Notify use about missing needed functions, and less security caused by that, let them hide nag of course.
         */

        public function displayFunctionMissingNotice()
        {
            $currentUser = wp_get_current_user();
            if (!get_user_meta($currentUser->ID, 'AssignmentAttachmentIgnoreNag') && current_user_can('install_plugins')){
                $this->displayAdminError((sprintf(
                    __('Regarding WPLMS Assignments Upload Assignment Functionality : It seems like your PHP installation is missing "mime_content_type" or "finfo_file" functions which are crucial '.
                    'for detecting file types of uploaded attachments. Please update your PHP installation OR be very careful with allowed file types, so '.
                    'intruders won\'t be able to upload dangerous code to your website! | <a href="%1$s">Hide Notice</a>','wplms'), '?AssignmentAttachmentIgnoreNag=1')), 'updated');
            }
        }


        /**
         * Save user nag if set, if they want to hide the message above.
         */

        private function setUserNag()
        {
            $currentUser = wp_get_current_user();
            if (isset($_GET['AssignmentAttachmentIgnoreNag']) && '1' == $_GET['AssignmentAttachmentIgnoreNag'] && current_user_can('install_plugins')){
                update_user_meta($currentUser->ID, 'AssignmentAttachmentIgnoreNag', 'true', true);
            }
        }


        /**
         * Admin error helper
         *
         * @param $error
         */

        private function displayAdminError($error, $class="error") { echo '<div id="message" class="'. $class .'"><p><strong>' . $error . '</strong></p></div>';  }


        function activate(){
            flush_rewrite_rules(false );
        }

        function deactivate(){
            flush_rewrite_rules(false );
        }

        function assignment_result(){
            if(!is_user_logged_in())
                return;
            $user_id = get_current_user_id();
            global $post;
            $expiry=get_user_meta($user_id,$post->ID,true);
            if($expiry < time()){
                $verify = get_post_meta($post->ID,$user_id,true);
                if(!is_numeric($verify)){
                    update_post_meta($post->ID,$user_id,2);
                }
            }

        }
        protected function __clone(){}

    }
}

