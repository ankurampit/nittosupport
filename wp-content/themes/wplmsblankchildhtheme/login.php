<?php

/** 
 * Template Name: Login Page
 */


get_header('header.php');
?>


<section class="container">
    <div class="content loginContent">
        <div class="topody">
            <div class="row">
                <div class="col-sm-6 paddLFT60">
                    <h2>Login</h2>
                    <?php
                    $error_message = '';

                    if (isset($_GET['login'])) {
                        switch ($_GET['login']) {
                            case 'invalid_request':
                                $error_message = 'Invalid request. Please try again.';
                                break;

                            case 'empty':
                                $error_message = 'Email and password are required.';
                                break;

                            case 'email_not_found':
                                $error_message = 'No account found with this email address.';
                                break;

                            case 'failed':
                                $error_message = 'Incorrect password. Please try again.';
                                break;
                        }
                    }
                    if (!empty($error_message)) :
                    ?>
                        <div class="error loginerror">
                            <?php echo esc_html($error_message); ?>
                        </div>
                    <?php endif; ?>
                    <form id="Loginfrm"
                        name="Loginfrm"
                        class="Loginfrm mailIcon"
                        role="form"
                        autocomplete="off"
                        action="<?php echo esc_url(admin_url('admin-post.php')); ?>"
                        method="post"
                        novalidate="novalidate">

                        <input type="hidden" name="action" value="custom_user_login">

                        <div class="input-group">
                            <input type="text" name="EmailAddress" id="EmailAddress" tabindex="1" class="form-control" placeholder="Email">
                            <span class="input-group-btn">
                                <button class="btniptsty btn btn-secondary login-input-button" type="button">
                                    <i class="fa fa-envelope-o" aria-hidden="true"></i>
                                </button>
                            </span>
                        </div>

                        <div class="input-group">
                            <input id="Password" name="Password" tabindex="2" type="password" class="form-control" placeholder="Password">
                            <span class="input-group-btn">
                                <button id="show_password" class="btniptsty btn btn-secondary login-input-button" type="button">
                                    <i class="fa fa-eye" aria-hidden="true"></i>
                                </button>
                            </span>
                        </div>

                        <div class="form-group">
                            <p class="forgot-passwordTxt">Forgot your password? <a href="<?php echo home_url('forgot-password'); ?>">Click here</a></p>
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
get_footer('footer.php');
