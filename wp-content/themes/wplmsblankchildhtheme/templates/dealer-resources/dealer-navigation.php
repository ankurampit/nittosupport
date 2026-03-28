<!-- Top Tabs -->
    <div class="resources-tabs">
        <h1 class="dealer-header">Dealer Resources</h1>
        <div>
            <div class="radio-container">
                <a href="<?php echo home_url() . '/catalogues-download'; ?>">
                    <div class="tab-item <?php echo $page == 'catalogue' ? 'active' : '' ; ?>">
                        <img src="<?php echo get_stylesheet_directory_uri(); ?>/assets/images/dealer1.png" alt="">
                        <span>Catalogue <br> Download</span>
                    </div>
                </a>
        
                <div class="tab-item">
                    <img src="<?php echo get_stylesheet_directory_uri(); ?>/assets/images/dealer2.png" alt="">
                    <span>Regional <br> Manager</span>
                </div>
        
                <a href="<?php echo home_url() . '/brochure'; ?>">
                    <div class="tab-item">
                        <img src="<?php echo get_stylesheet_directory_uri(); ?>/assets/images/brochure.png" alt="">
                        <span>Brochures and Sell sheets</span>
                    </div>
                </a>
        
                <a href="<?php echo home_url() . '/misc-documents'; ?>">
                    <div class="tab-item <?php echo $page == 'misc-document' ? 'active' : ''; ?>">
                        <img src="<?php echo get_stylesheet_directory_uri(); ?>/assets/images/dealer3.png" alt="">
                        <span>MISC Documents</span>
                    </div>
                </a>
            </div>
        </div>  
    </div>