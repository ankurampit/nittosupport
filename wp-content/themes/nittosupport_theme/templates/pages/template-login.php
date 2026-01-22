<?php

/** 
 * Template Name: Login Page 
 * Template Post Type: page 
 */
get_template_part('templates/parts/header');
?>

<section id="myCarousel" class="carousel">
    <?php echo do_shortcode('[metaslider id="90"]'); ?>
</section>


<section class="container">
    <div class="content loginContent">
        <!--Paragraph text start-->
        <div class="topody">
            <div class="row">
                <div class="col-sm-6 paddLFT60">
                    <h2>Login</h2>
                    <div class="error loginerror"></div>
                    <form id="Loginfrm" 
                        name="Loginfrm" 
                        class="Loginfrm mailIcon" 
                        role="form" 
                        autocomplete="off" 
                        action="<?php echo esc_url(admin_url('admin-post.php')); ?>" 
                        method="post"
                        novalidate="novalidate">

                        <!-- 🔸 Tell WordPress what action to trigger -->
                        <input type="hidden" name="action" value="custom_user_login">

                        <div class="input-group">
                            <input type="text" name="EmailAddress" id="EmailAddress" tabindex="1" class="form-control" placeholder="Email">
                            <span class="input-group-btn">
                                <button class="btniptsty btn btn-secondary" type="button">
                                    <i class="fa fa-envelope-o" aria-hidden="true"></i>
                                </button>
                            </span>
                        </div>

                        <div class="input-group">
                            <input id="Password" name="Password" tabindex="2" type="password" class="form-control" placeholder="Password">
                            <span class="input-group-btn">
                                <button id="show_password" class="btniptsty btn btn-secondary" type="button">
                                    <i class="fa fa-eye" aria-hidden="true"></i>
                                </button>
                            </span>
                        </div>

                        <div class="form-group">
                            <p class="forgot-passwordTxt">Forgot your password? <a href="<?php echo wp_lostpassword_url(); ?>">Click here</a></p>
                        </div>

                        <div class="form-group">
                            <div class="row">
                                <div class="col-xs-6">
                                    <button type="submit" tabindex="4" class="btn btn-login">Login</button>
                                </div>
                                <div class="col-xs-6">
                                    <div class="btn-lnkbx btn-lnk-lgn">
                                        <a class="btn-link-a" href="<?php echo site_url('/register'); ?>">
                                            <i class="fa fa-long-arrow-right" aria-hidden="true"></i> Register
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </form>

                </div>
                <div class="col-sm-6 paddLFT60 borleft">
                    <h2>Tweets</h2>
                    <!-- tweets -->
                </div>
            </div>
        </div>
        <!-- <div class="bottombody">
            <h2></?php echo BOTTOM_BODY_HEADING; ?></h2>
            <p><//?php echo BOTTOM_BODY_TEXT; ?></p>
        </div> -->
 
        <!--Paragraph text end-->
    </div>
</section>
<?php
get_template_part('templates/parts/footer');