<!-- Footer start -->
<footer class="footerWide">
    <div class="container footer">
        <div class="row">
            <div class="col-sm-12">
                <div class="social-ft">
                    <a href="https://twitter.com/nittotire" target="_blank" class="twitter-ico"></a>
                    <a href="https://www.facebook.com/NittoTire/" target="_blank" class="facebook-ico"></a>
                    <a href="https://www.youtube.com/channel/UCacuBR0xB-Hay8pkx1JtDZg" target="_blank" class="youtube-ico"></a>
                </div>
                <ul class="footerMenu">
                    <li><a href="https://nittosupport.ca/frontend/privacypolicy/">Privacy Policy</a></li>
                    <li><a href="https://nittosupport.ca/frontend/contactus/">Contact Us</a></li>
                </ul>
                <p>Copyright © 2018 Nitto Tires. All rights reserved. Powered by BIT</p>
            </div>
        </div>
    </div>
</footer>
<!-- Footer end -->
<script>
    const ajax_obj = {
        ajax_url: "<?php echo admin_url('admin-ajax.php'); ?>",
        nonce: "<?php echo wp_create_nonce('security_nonce'); ?>"
    };
</script>
<!-- jQuery (necessary for Bootstrap's JavaScript plugins) -->
<script src="<?php echo get_stylesheet_directory_uri() . '/assets/js/jquery-min-3.2.1.js' ?>"></script>
<script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
<script src="<?php echo get_stylesheet_directory_uri() . '/assets/js/bootstrap.min.js' ?>"></script>
<script src="https://cdn.jsdelivr.net/npm/xlsx@0.18.5/dist/xlsx.full.min.js"></script>
<!-- Script for custom select -->
<script src="<?php echo get_stylesheet_directory_uri() . '/assets/js/customSelect.js' ?>"></script>
<script src="<?php echo get_stylesheet_directory_uri() . '/assets/js/custom-script.js' ?>"></script>
<script src="<?php echo get_stylesheet_directory_uri() . '/assets/js/admaterials.js' ?>"></script>
<script src="<?php echo get_stylesheet_directory_uri() . '/assets/js/security.js' ?>"></script>

<?php wp_footer(); ?>

</body>

</html>