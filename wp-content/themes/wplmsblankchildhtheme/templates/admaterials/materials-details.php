<?php

/**
 * Template Name: View Page
 */

get_header();


require_once get_stylesheet_directory() . '/header-inner.php';
require_once get_stylesheet_directory() . '/templates/admaterials/top-navigation.php';

$post_id = $_GET['preview_id'] ?? null;
$type = $_GET['type'] ?? null;

echo $post_id;

$title_english = get_post_meta($post_id, 'ad_name_english', true);
$title_french = get_post_meta($post_id, 'ad_name_french', true);

$description_english = get_post_meta($post_id, 'description_english', true);
$description_french = get_post_meta($post_id, 'description_english', true);

$start_date = get_post_meta($post_id, 'do_not_use_before', true);
if (!empty($start_date)) {
    $start_formatted_date = date('d M Y', strtotime($start_date));
}

$coop = get_post_meta($post_id, 'coop_%', true);

$pdf_english = get_image_by_id(get_post_meta($post_id, 'pdf_version_english', true));
$pdf_french = get_image_by_id(get_post_meta($post_id, 'pdf_version_french', true));

$image_english = get_image_by_id(get_post_meta($post_id, 'picture_jpeg_version_english', true));
$image_french = get_image_by_id(get_post_meta($post_id, 'picture_jpeg_version_french', true));

$zip_english = get_image_by_id(get_post_meta($post_id, 'eps_zip_version_english', true));
$zip_french = get_image_by_id(get_post_meta($post_id, 'eps_zip_version_french', true));

$audio_english = get_image_by_id(get_post_meta($post_id, 'mp3_english', true));
$audio_french = get_image_by_id(get_post_meta($post_id, 'mp3_french', true));

$logo_jpg_english = get_image_by_id(get_post_meta($post_id, 'logo_jpg_version_english', true));
$logo_jpg_french = get_image_by_id(get_post_meta($post_id, 'logo_jpg_version_french', true));

$logo_title_english = get_post_meta($post_id, 'logo_name_english', true);
$logo_title_french = get_post_meta($post_id, 'logo_name_french', true);

$tire_photo_english = get_post_meta($post_id, 'tire_photo_name', true);

// Tire Photo Details
$image_icon = get_image_by_id(get_post_meta($post_id, 'image_icon', true));

$logo_jpg = get_image_by_id(get_post_meta($post_id, 'logo_jpeg_version', true));
$logo_zip = get_image_by_id(get_post_meta($post_id, 'logo_zip_for_download', true));

$logo_jpg_two = get_image_by_id(get_post_meta($post_id, 'logo_2_jpeg_version', true));
$logo_zip_two = get_image_by_id(get_post_meta($post_id, 'logo2_zip_for_download', true));

$logo_jpg_three = get_image_by_id(get_post_meta($post_id, 'logo_3_jpeg_version', true));
$logo_zip_three = get_image_by_id(get_post_meta($post_id, 'logo_3_zip_for_download', true));

$logo_jpg_four = get_image_by_id(get_post_meta($post_id, 'logo_4_jpeg_version', true));
$logo_zip_four = get_image_by_id(get_post_meta($post_id, 'logo_4_zip_for_download', true));

$left_side_tire = get_image_by_id(get_post_meta($post_id, 'left_thumbnail_jpeg_version', true));
$left_side_zip = get_image_by_id(get_post_meta($post_id, 'left_thumbnail_zip_for_download', true));

$right_side_tire = get_image_by_id(get_post_meta($post_id, 'right_thumbnail_jpeg_version', true));
$right_side_zip = get_image_by_id(get_post_meta($post_id, 'right_thumbnail_zip_for_download', true));

$front_side_tire = get_image_by_id(get_post_meta($post_id, 'front_thumbnail_jpeg_version', true));
$front_side_zip = get_image_by_id(get_post_meta($post_id, 'font_thumbnail_zip_for_download', true));

$side_side_tire = get_image_by_id(get_post_meta($post_id, 'side_thumbnail_jpeg_version', true));
$side_side_zip = get_image_by_id(get_post_meta($post_id, 'side_thumbnail_zip_version', true));

$youtube_url_english = get_post_meta($post_id, 'you_tube_link_english', true);
$youtube_url_french = get_post_meta($post_id, 'you_tube_link_french', true);

$web_banner_name_english = get_post_meta($post_id, 'web_banner_name_english', true);
$web_banner_name_english = get_post_meta($post_id, 'web_banner_name_french', true);

$banner_description_english = get_post_meta($post_id, 'banner_description_english', true);
$banner_description_french = get_post_meta($post_id, 'banner_description_french', true);

$banner_english = get_image_by_id(get_post_meta($post_id, 'banner_image_english', true));
$banner_french = get_image_by_id(get_post_meta($post_id, 'banner_image_french', true));

$banner_zip_english = get_image_by_id(get_post_meta($post_id, 'banner_image_english', true));
$banner_zip_french = get_image_by_id(get_post_meta($post_id, 'banner_image_english', true));

$banner_english_2 = get_image_by_id(get_post_meta($post_id, 'banner_2_image_english', true));
$banner_french_2 = get_image_by_id(get_post_meta($post_id, 'banner_2_image_french', true));
$banner_zip_english_2 = get_image_by_id(get_post_meta($post_id, 'banner_2_zip_for_download_english', true));
$banner_zip_french_2 = get_image_by_id(get_post_meta($post_id, 'banner_2_zip_for_download_french', true));
$banner_description_english_2 = get_image_by_id(get_post_meta($post_id, 'banner_2_description_english', true));
$banner_description_french_2 = get_image_by_id(get_post_meta($post_id, 'banner_2_description_french', true));

$banner_english_3 = get_image_by_id(get_post_meta($post_id, 'banner_3_image_english', true));
$banner_french_3 = get_image_by_id(get_post_meta($post_id, 'banner_3_image_french', true));
$banner_zip_english_3 = get_image_by_id(get_post_meta($post_id, 'banner_3_zip_for_download_english', true));
$banner_zip_french_3 = get_image_by_id(get_post_meta($post_id, 'banner_3_zip_for_download_french', true));
$banner_description_english_3 = get_image_by_id(get_post_meta($post_id, 'banner_3_description_english', true));
$banner_description_french_3 = get_image_by_id(get_post_meta($post_id, 'banner_3_description_french', true));

$banner_english_4 = get_image_by_id(get_post_meta($post_id, 'banner_4_image_english', true));
$banner_french_4 = get_image_by_id(get_post_meta($post_id, 'banner_4_image_french', true));
$banner_zip_english_4 = get_image_by_id(get_post_meta($post_id, 'banner_4_zip_for_download_english', true));
$banner_zip_french_4 = get_image_by_id(get_post_meta($post_id, 'banner_4_zip_for_download_french', true));
$banner_description_english_4 = get_image_by_id(get_post_meta($post_id, 'banner_4_description_english', true));
$banner_description_french_4 = get_image_by_id(get_post_meta($post_id, 'banner_4_description_french', true));

$banner_english_5 = get_image_by_id(get_post_meta($post_id, 'banner_5_image_english', true));
$banner_french_5 = get_image_by_id(get_post_meta($post_id, 'banner_5_image_french', true));
$banner_zip_english_5 = get_image_by_id(get_post_meta($post_id, 'banner_5_zip_for_download_english', true));
$banner_zip_french_5 = get_image_by_id(get_post_meta($post_id, 'banner_5_zip_for_download_french', true));
$banner_description_english_5 = get_image_by_id(get_post_meta($post_id, 'banner_5_description_english', true));
$banner_description_french_5 = get_image_by_id(get_post_meta($post_id, 'banner_5_description_french', true));


$materials_arg = [
    'post_id' => $post_id,
    'type' => $type,
];

$edit_link = get_edit_link($materials_arg);
?>

<div class="main-table">
    <div class="table-header materials-area">
        <h2>Material Details</h2>
        <div class="header-actions">
            <a href="<?php echo home_url() . '/print-ads'; ?>" ><button class="btn btn-blue">Home</button></a>
            <a href="<?php echo $edit_link; ?>" ><button class="btn btn-blue">Edit</button></a>
            <button class="btn btn-red">Delete</button>
        </div>

        <h1 class="main-title"><?php echo $title_english; ?></h1>
        <?php
        if($tire_photo_english){
            echo '<h1 class="main-title">' . $tire_photo_english . '</h1>';
        }
        ?>


        <!-- Description section -->
        <div class="asset-grid">
            <div class="asset-column">
                <div class="logo-preview">
                    <?php
                    if($type == 'print-ads') { ?>
                        <img src="<?php echo $image_english; ?>" alt="Toyo Tires English">
                    <?php } elseif($type == 'radio') { ?>
                        <audio controls>
                            <source src="<?php echo $audio_english;?>" type="audio/mpeg">
                            Your browser does not support the audio element.
                        </audio>
                    <?php } elseif($type == 'nittologo') {?>
                        <img src="<?php echo $logo_jpg_english ?>" >
                    <?php } elseif($type == 'television-and-video') { ?>
                        <iframe 
                            width="350" 
                            height="300" 
                            src="<?php echo $youtube_url_english; ?> "
                            title="YouTube video player" 
                            frameborder="0"
                            allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture">
                        </iframe>
                    <?php } elseif ($banner_english) {?>
                        <img src="<?php echo $banner_english; ?>" alt="Toyo Tires English">
                    <?php } ?>
                    
                </div>
                <h2 class="column-title">English Description</h2>
                <div class="description-meta">
                    <?php if($logo_title_english) { ?>
                        <p><strong>Title: </strong><?php echo $logo_title_english ?></p>
                    <?php } ?>
                    <?php if($title_english) { ?>
                        <p><strong>Title: </strong><?php echo $title_english ?></p>
                    <?php } ?>
                    <?php if($tire_photo_english) { ?>
                        <p><strong>Tire Name: </strong><?php echo $tire_photo_english ?></p>
                    <?php } ?>
                    <?php if($web_banner_name_english) { ?>
                        <p><strong>Banner Title: </strong><?php echo $web_banner_name_english ?></p>
                    <?php } ?>
                    
                    <?php if ($description_english) { ?>
                        <p><strong>Description: </strong><?php echo $description_english; ?></p>
                    <?php } ?>
                    
                    <?php if($banner_description_english) { ?>
                        <p><strong>Description: </strong><?php echo $banner_description_english; ?></p>
                    <?php } ?>
                    
                    <p><strong>Start Date: </strong><?php echo $start_formatted_date; ?></p>
                    <p><strong>Coop %: </strong><?php echo $coop; ?></p>
                </div>
                <?php if ($type == 'print-ads' || $type == 'radio' || $type == 'nittologo' || $type = 'web-online') { ?>
                    <?php if($image_english != null){?>
                        <div class="download-row">
                            <strong>JPEG Version</strong>
                            <span class="subtext">Digital bitmap artwork. 300 DPI</span>
                    
                            <a href="<?php echo esc_url($image_english); ?>" download>
                                <i class="fa fa-download" style="font-size:36px"></i>
                            </a>
                        </div>
                    <?php } ?>
                    
                    <?php if($pdf_english != null){?>
                        <div class="download-row">
                            <strong>PDF Version</strong>
                            <span class="subtext">Script affidavit</span>
                    
                            <a href="<?php echo esc_url($pdf_english); ?>" download>
                                <i class="fa fa-download" style="font-size:36px"></i>
                            </a>
                        </div>
                    <?php } ?>
                    
                    <?php if($zip_english != null){?>
                        <div class="download-row">
                            <strong>EPS Zip Version</strong>
                            <span class="subtext">Encapsulated Postscript document. High resolution print document.</span>
                    
                            <a href="<?php echo esc_url($zip_english); ?>" download>
                                <i class="fa fa-download" style="font-size:36px"></i>
                            </a>
                        </div>
                    <?php } ?>
                <?php } ?>
                
                <?php if($type == 'television-and-video') { ?>
                    <div class="download-row">
                        <strong>Youtube Link English</strong>
                        <span class="subtext">Encapsulated Postscript document. High resolution print document.</span>
                
                        <a href="<?php echo esc_url($youtube_url_english); ?>">
                            <i class="fa fa-youtube-play" style="font-size:48px;color:red"></i>
                        </a>
                    </div>
                <?php } ?>
            </div>

            <div class="asset-column divider">
                <div class="logo-preview">
                    <?php
                    if($type == 'print-ads') { ?>
                        <img src="<?php echo $image_french; ?>" alt="Toyo Tires English">
                    <?php } elseif($type == 'radio') { ?>
                        <audio controls>
                            <source src="<?php echo $audio_french; ?>" type="audio/mpeg">
                            Your browser does not support the audio element.
                        </audio>
                    <?php } elseif($type == 'nittologo') {?>
                        <img src="<?php echo $logo_jpg_english ?>" >
                    <?php } elseif($type == 'television-and-video') { ?>
                        <iframe 
                            width="350" 
                            height="300" 
                            src="<?php echo $youtube_url_french; ?> "
                            title="YouTube video player" 
                            frameborder="0"
                            allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture">
                        </iframe>
                    <?php } elseif ($banner_french) {?>
                        <img src="<?php echo $banner_french; ?>" alt="Toyo Tires English">
                    <?php } ?>
                </div>
                <h2 class="column-title">French Description</h2>
                <div class="description-meta">
                    <?php if($logo_title_french) { ?>
                        <p><strong>Title: </strong><?php echo $logo_title_french ?></p>
                    <?php } ?>
                    
                    <?php if($title_french) { ?>
                        <p><strong>Title: </strong><?php echo $title_french ?></p>
                    <?php } ?>
                    
                    <?php if($tire_photo_english) { ?>
                        <p><strong>Tire Name: </strong><?php echo $tire_photo_english ?></p>
                    <?php } ?>
                    
                    <?php if($web_banner_name_english) { ?>
                        <p><strong>Banner Title: </strong><?php echo $web_banner_name_english ?></p>
                    <?php } ?>
                    
                    <?php if ($description_french) { ?>
                        <p><strong>Description: </strong><?php echo $description_french; ?></p>
                    <?php } ?>
                    
                    <?php if($banner_description_french) { ?>
                        <p><strong>Description: </strong><?php echo $banner_description_french; ?></p>
                    <?php } ?>
                    
                    <p><strong>Start Date: </strong><?php echo $start_formatted_date; ?></p>
                    <p><strong>Coop %: </strong><?php echo $coop; ?></p>
                </div>
                
                <?php if ($type == 'print-ads' || $type == 'radio' || $type == 'nittologo' || $type = 'web-online') { ?>
                
                    <?php if($image_french != null){?>
                        <div class="download-row">
                            <strong>JPEG Version</strong>
                            <span class="subtext">Digital bitmap artwork. 300 DPI</span>
                    
                            <a href="<?php echo esc_url($image_french); ?>" download>
                                <i class="fa fa-download" style="font-size:36px"></i>
                            </a>
                        </div>
                    <?php } ?>
                    
                    <?php if($pdf_french != null){?>
                        <div class="download-row">
                            <strong>PDF Version</strong>
                            <span class="subtext">Script affidavit</span>
                    
                            <a href="<?php echo esc_url($pdf_french); ?>" download>
                                <i class="fa fa-download" style="font-size:36px"></i>
                            </a>
                        </div>
                    <?php } ?>
                    
                    <?php if($zip_french != null){?>
                        <div class="download-row">
                            <strong>EPS Zip Version</strong>
                            <span class="subtext">Encapsulated Postscript document. High resolution print document.</span>
                    
                            <a href="<?php echo esc_url($zip_french); ?>" download>
                                <i class="fa fa-download" style="font-size:36px"></i>
                            </a>
                        </div>
                    <?php } ?>
                    
                <?php } ?>
                
                <?php if($type == 'television-and-video') { ?>
                    <div class="download-row">
                        <strong>Youtube Link English</strong>
                        <span class="subtext">Encapsulated Postscript document. High resolution print document.</span>
                
                        <a href="<?php echo esc_url($youtube_url_english); ?>">
                            <i class="fa fa-youtube-play" style="font-size:48px;color:red"></i>
                        </a>
                    </div>
                <?php } ?>
            </div>
        </div>
        <!-- Description section end -->

        <?php  if($type == 'tire-photo') { ?>
                <table class="tire-details" style="border: none;">
                    <thead>
                        <tr>
                            <th></th>
                            <th>Description</th>
                            <th>Eps Version</th>
                            <th>Jpeg Version</th>
                        </tr>
                    </thead>
                    <tbody>

                        <?php
                        $items = [
                            ['img' => $logo_jpg,        'zip' => $logo_zip,        'label' => 'Logo'],
                            ['img' => $logo_jpg_two,    'zip' => $logo_zip_two,    'label' => 'Logo 2'],
                            ['img' => $logo_jpg_three,  'zip' => $logo_zip_three,  'label' => 'Logo 3'],
                            ['img' => $logo_jpg_four,   'zip' => $logo_zip_four,   'label' => 'Logo 4'],
                        
                            ['img' => $left_side_tire,  'zip' => $left_side_zip,   'label' => 'Tire Right View'],
                            ['img' => $right_side_tire, 'zip' => $right_side_zip,  'label' => 'Tire Left View'],
                            ['img' => $front_side_tire, 'zip' => $front_side_zip,  'label' => 'Tire Front View'],
                            ['img' => $side_side_tire,  'zip' => $side_side_zip,   'label' => 'Tire Side View'],
                        ];
                        
                        foreach ($items as $item) :
                        
                            // Skip if no image
                            if (empty($item['img'])) {
                                continue;
                            }
                        ?>
                        
                        <tr>
                            <td>
                                <img class="tire-images" src="<?php echo esc_url($item['img']); ?>">
                            </td>
                            <td><?php echo esc_html($item['label']); ?></td>
                            <td>
                                <?php if (!empty($item['zip'])) : ?>
                                    <a href="<?php echo esc_url($item['zip']); ?>" download>
                                        <i class="fa fa-download" style="font-size:36px"></i>
                                    </a>
                                <?php endif; ?>
                            </td>
                            <td>
                                <a href="<?php echo esc_url($item['img']); ?>" download>
                                    <i class="fa fa-download" style="font-size:36px"></i>
                                </a>
                            </td>
                        </tr>
                        
                        <?php endforeach; ?>
                        
                    </tbody>
                    
                </table>
        <?php } ?>
        
        <?php if ($type == 'web-online') { ?>

            <table class="tire-details" style="border: none;">
                <thead>
                    <tr>
                        <th>Preview</th>
                        <th>Description</th>
                        <th>Language</th>
                        <th>Zip Download</th>
                        <th>Image Download</th>
                    </tr>
                </thead>
                <tbody>
            
                    <?php for ($i = 2; $i <= 5; $i++) : ?>
            
                        <?php
                        // English
                        $img_en  = ${"banner_english_$i"};
                        $zip_en  = ${"banner_zip_english_$i"};
                        $desc_en = ${"banner_description_english_$i"};
            
                        if (!empty($img_en)) :
                        ?>
                            <tr>
                                <td>
                                    <img class="tire-images" src="<?php echo esc_url($img_en); ?>">
                                </td>
            
                                <td>
                                    <?php echo esc_html($desc_en ?: "Banner $i (English)"); ?>
                                </td>
            
                                <td><strong>English</strong></td>
            
                                <td>
                                    <?php if (!empty($zip_en)) : ?>
                                        <a href="<?php echo esc_url($zip_en); ?>" download>
                                            <i class="fa fa-download" style="font-size:30px"></i>
                                        </a>
                                    <?php endif; ?>
                                </td>
            
                                <td>
                                    <a href="<?php echo esc_url($img_en); ?>" download>
                                        <i class="fa fa-download" style="font-size:30px"></i>
                                    </a>
                                </td>
                            </tr>
                        <?php endif; ?>
            
            
                        <?php
                        // French
                        $img_fr  = ${"banner_french_$i"};
                        $zip_fr  = ${"banner_zip_french_$i"};
                        $desc_fr = ${"banner_description_french_$i"};
            
                        if (!empty($img_fr)) :
                        ?>
                            <tr>
                                <td>
                                    <img class="tire-images" src="<?php echo esc_url($img_fr); ?>">
                                </td>
            
                                <td>
                                    <?php echo esc_html($desc_fr ?: "Banner $i (French)"); ?>
                                </td>
            
                                <td><strong>French</strong></td>
            
                                <td>
                                    <?php if (!empty($zip_fr)) : ?>
                                        <a href="<?php echo esc_url($zip_fr); ?>" download>
                                            <i class="fa fa-download" style="font-size:30px"></i>
                                        </a>
                                    <?php endif; ?>
                                </td>
            
                                <td>
                                    <a href="<?php echo esc_url($img_fr); ?>" download>
                                        <i class="fa fa-download" style="font-size:30px"></i>
                                    </a>
                                </td>
                            </tr>
                        <?php endif; ?>
            
                    <?php endfor; ?>
            
                </tbody>
            </table>
        
        <?php } ?>
        
        <!-- WebFtp section -->
        <div class="footer-section">
            <h2 class="column-title">Use the FTP portal to download original files</h2>
            <div class="ftp-button">
                <span>☁️ Download Files from webftp portal</span>
            </div>
            <div class="restrictions">
                <h3>Image Restrictions</h3>
                <p>* Note all advertising must be submitted not later than January 15th to be eligible for reimbursement</p>
            </div>
        </div>
        <!-- ftp section end -->


    </div>
</div>
</div>
<?php get_footer('footer.php');