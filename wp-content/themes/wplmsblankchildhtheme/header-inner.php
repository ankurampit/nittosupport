<?php
$img_path = content_url('/themes/wplmsblankchildhtheme/assets/images');
?>

<section class="cl-wrapper">
    <div class="container">
        <div class="navbar-inrtp-MenuBx">
            <div class="text-center">
                <button type="button" class="navbar-toggle" data-toggle="collapse" data-target="#NavbarInrtopMenu">
                    <span class="sr-only">Toggle navigation</span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                </button>

                <div class="collapse navbar-collapse" id="NavbarInrtopMenu">
                    <ul class="clearfix lst-cate">

                        <li>
                            <a href="<?php echo network_home_url('/print-ads/'); ?>">
                                <i><img src="<?php echo $img_path; ?>/link.png" alt=""></i>
                                Advertising Material
                            </a>
                        </li>

                        <li>
                            <a href="<?php echo network_home_url('/dealer-resource/'); ?>">
                                <i><img src="<?php echo $img_path; ?>/dealer-resources.png" alt=""></i>
                                Dealer Resources
                            </a>
                        </li>

                        <li>
                            <a href="<?php echo network_home_url('/pointofpurchase'); ?>">
                                <i>
                                    <img src="<?php echo $img_path; ?>/point-of-purchase.png" alt="">
                                </i>
                                Point of Purchase
                            </a>
                        </li>

                        <form name="promomet_frm" id="promomet_frm" method="POST"
                            action="<?php echo network_home_url('/webservices/lncatch.php'); ?>">
                            <input name="email" type="hidden" value="rgolab@toyocanada.com">
                            <input name="language" type="hidden" value="en">
                        </form>

                        <li>
                            <a href="<?php echo network_home_url('/promomaterials/store-page/'); ?>">
                                <i><img src="<?php echo $img_path; ?>/dealer31.png" alt=""></i>
                                Promo Materials
                            </a>
                        </li>

                        <form name="promo_frm" id="promo_frm" method="POST"
                            action="<?php echo network_home_url('/webservices/lncatch.php'); ?>">
                            <input name="email" type="hidden" value="rgolab@toyocanada.com">
                            <input name="language" type="hidden" value="en">
                        </form>

                        <li>
                            <a href="<?php echo network_home_url('/training/'); ?>">
                                <i><img src="<?php echo $img_path; ?>/lst-ico-1.png" alt=""></i>
                                Training Site
                            </a>
                        </li>

                    </ul>
                </div>
            </div>
        </div>
    </div>
</section>

<script>
    function promo_met_Submissions() {
        document.getElementById('promomet_frm').submit();
    }

    function promoSubmissions() {
        document.getElementById('promo_frm').submit();
    }
</script>