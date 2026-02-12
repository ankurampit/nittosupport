<section class="cl-wrapper">
    <div class="container">
        <div class="navbar-inrtp-MenuBx">
            <div class="text-center"><!--New div add-->
                <button type="button" class="navbar-toggle" data-toggle="collapse" data-target="#NavbarInrtopMenu"><!--New button for mobile icon-->
                    <span class="sr-only">Toggle navigation</span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                </button>

                <div class="collapse navbar-collapse" id="NavbarInrtopMenu">
                    <ul class="clearfix lst-cate">
                        <li><a href="<?php echo home_url('print-ads/') ?>"><i><img src="<?php echo get_stylesheet_directory_uri(); ?>/assets/images/link.png" alt=""> </i>Advertising Material</a></li>
                        <li><a href="https://nittosupport.ca/dealerresource/index"><i><img src="<?php echo get_stylesheet_directory_uri(); ?>/assets/images/dealer-resources.png"></i>Dealer Resources</a></li>

                        <li><a href="https://nittosupport.ca/training/?v=4326ce96e26c#" onclick="promo_met_Submissions()"><i><img src="<?php echo get_stylesheet_directory_uri(); ?>/assets/images/point-of-purchase.png"></i>Point of Purchase</a></li>
                        <form name="promomet_frm" id="promomet_frm" method="POST" action="https://pos.nittosupport.ca/webservices/lncatch.php?v=4326ce96e26c" accept-charset="UTF-8">
                            <input name="email" type="hidden" value="rgolab@toyocanada.com">
                            <input name="language" type="hidden" value="en">
                            <!-- <input name="link" type="hidden" value="contactus"  /> -->
                        </form>
                        <script>
                            function promo_met_Submissions() {
                                document.forms['promomet_frm'].submit();
                            }
                        </script>
                        <li><a onclick="promoSubmissions()" href="https://nittosupport.ca/training/?v=4326ce96e26c#"><i><img src="<?php echo get_stylesheet_directory_uri(); ?>/assets/images/dealer31.png"></i>Promo Materials</a></li>
                        <form name="promo_frm" id="promo_frm" method="POST" action="https://promo.nittosupport.ca/webservices/lncatch.php?v=4326ce96e26c" accept-charset="UTF-8">
                            <input name="email" type="hidden" value="rgolab@toyocanada.com">
                            <input name="language" type="hidden" value="en">

                        </form>
                        <script>
                            function promoSubmissions() {
                                document.forms['promo_frm'].submit();
                            }
                        </script>
                        <li><a href="https://nittosupport.ca/training?v=4326ce96e26c"><i><img src="<?php echo get_stylesheet_directory_uri(); ?>/assets/images/lst-ico-1.png"></i>Training Site</a></li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</section>