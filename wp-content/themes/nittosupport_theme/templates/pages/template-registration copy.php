<?php

/** 
 * Template Name: Registration Page 
 * Template Post Type: page 
 */
get_template_part('templates/parts/header');
?>
<div class="innerSlider">
    <section id="myCarousel" class="carousel">
        <?php echo do_shortcode('[metaslider id="90"]'); ?>
    </section>
</div>

<section class="container">
    <div class="InnerContent">
        <div class="row">
            <div class="col-md-12">
                <h2>Registration</h2>
                <div class="error loginerror"></div>
                <div class="regdTxt">
                    <p>Thanks for starting the registration process. Be sure to fill in all the information.</p>
                    <p>Once submitted you will receive and email confirming your request. If you request advanced access your identity will be verified prior to this access being given.</p>
                    <p>Advanced access is only given to Dealers and/or personnel who require access to order clothing, review SRP pricing, have access to showroom material etc. You do not require advanced access to download ad material.</p>
                </div>
            </div>
            <div class="col-md-12">
                <form class="regdForm" 
                    action="<?php echo esc_url(admin_url('admin-post.php')); ?>" 
                    method="post" 
                    enctype="multipart/form-data" 
                    id="frmCms" 
                    name="frmCms">
                    
                    <input type="hidden" name="action" value="custom_user_registration">

                    <div class="regdFormBase">
                        <div class="input-group">
                            <label class="redgLabel">First Name</label><span class="errStar">*</span>
                            <input type="text" name="firstname" id="firstname" tabindex="1" class="form-control" placeholder="" value="">
                        </div>
                        <div class="input-group">
                            <label class="redgLabel">Last Name</label><span class="errStar">*</span>
                            <input type="text" name="lastname" id="lastname" tabindex="1" class="form-control" placeholder="" value="">
                        </div>
                    </div>
                    <div class="regdFormBase">
                        <div class="input-group">
                            <label class="redgLabel">Password</label><span class="errStar">*</span>
                            <input id="password" type="password" name="password" class="form-control" placeholder="">
                        </div>
                        <div class="input-group">
                            <label class="redgLabel">Confirm Password</label><span class="errStar">*</span>
                            <input id="confirm_password" type="password" name="confirm_password" class="form-control" placeholder="">
                        </div>
                    </div>
                    <div class="regdFormBase">
                        <div class="input-group">
                            <label class="redgLabel">Email</label><span class="errStar">*</span>
                            <input type="email" name="email" id="email" tabindex="1" class="form-control" placeholder="" value="">

                        </div>
                        <div class="input-group">
                            <label class="redgLabel">Company Name</label><span class="errStar">*</span>
                            <input type="text" name="companyname" id="companyname" tabindex="1" class="form-control" placeholder="" value="">
                        </div>
                    </div>
                    <div class="regdFormBase">
                        <div class="input-group">
                            <label class="redgLabel">Dealer Number</label>
                            <input type="text" name="dealernumber" id="dealernumber" tabindex="1" class="form-control" placeholder="" value="">
                        </div>
                        <div class="input-group">
                            <label class="redgLabel">Address</label><span class="errStar">*</span>
                            <input type="text" name="address" id="address" tabindex="1" class="form-control" placeholder="" value="">
                        </div>
                    </div>
                    <div class="regdFormBase">
                        <div class="input-group">
                            <label class="redgLabel">City</label><span class="errStar">*</span>
                            <input type="text" name="city" id="city" tabindex="1" class="form-control" placeholder="" value="">
                        </div>
                        <div class="input-group">
                            <label class="redgLabel">Province</label><span class="errStar">*</span>
                            <select class="form-control" name="province" id="province">
                                <option value="">Select Province</option>
                                <option value="AB">Alberta</option>
                                <option value="BC">British Columbia</option>
                                <option value="MB">Manitoba</option>
                                <option value="NB">New Brunswick</option>
                                <option value="NL">Newfoundland and Labrador</option>
                                <option value="NT">Northwest Territories</option>
                                <option value="NS">Nova Scotia</option>
                                <option value="NU">Nunavut</option>
                                <option value="ON">Ontario</option>
                                <option value="PE">Prince Edward Island</option>
                                <option value="QC">Quebec</option>
                                <option value="SK">Saskatchewan</option>
                                <option value="YT">Yukon</option>
                                
                            </select>
                        </div>
                    </div>
                    <div class="regdFormBase">
                        <div class="input-group">
                            <label class="redgLabel">Postal Code</label><span class="errStar">*</span>
                            <input type="text" name="postalcode" id="postalcode" tabindex="1" class="form-control" placeholder="" value="">
                        </div>
                        <div class="input-group">
                            <label class="redgLabel">Phone</label><span class="errStar">*</span>
                            <input type="text" name="phone" id="phone" tabindex="1" class="form-control" placeholder="" value="">
                        </div>
                    </div>
                    <div class="regdFormBase">
                        <div class="input-group">
                            <label class="redgLabel required">Fax</label>
                            <input type="text" name="fax" id="fax" tabindex="1" class="form-control" placeholder="" value="">
                        </div>
                        <div class="input-group">
                            <label class="redgLabel">Nitto rep</label>
                            <select class="form-control" name="NittoRepid" id="NittoRepid">
                                <option value="">Select Nitto rep</option>
                                <option value="132">JP Marion</option>
                                <option value="327">Cliff Culbert</option>
                                <option value="570">James Richardson</option>
                                <option value="633">Steve Gilbert</option>
                                <option value="636">Tim O'shaughnessy</option>
                                <option value="654">Doug Martin</option>
                                <option value="127663">Jean-Patrick Flynn</option>
                                <option value="127893">Dustin Hora</option>
                                <option value="127907">Marc-André Charette</option>
                                <option value="127913">Nicolas Tremblay-Bujold</option>
                                <option value="127956">Caroline Brousseau</option>
                            </select>
                        </div>
                    </div>

                    <div class="regdFormBase2">
                        <div class="input-group">
                            <p>Language Preference</p>
                            <select class="form-control" name="lngprefer" id="lngprefer">
                                <option value="en">English</option>
                                <option value="fr">Français</option>
                            </select>
                        </div>
                    </div>

                    <div class="regdFormBase2">
                        <div class="input-group">
                            <p>Group</p>
                            <select class="form-control" name="Usergroup" id="Usergroup">
                                <option value="">Group</option>
                                <optgroup label="Tire&nbsp;Dealers">
                                    <option value="9">Independent&nbsp;tire&nbsp;dealers</option>
                                    <option value="26">Kal Tire</option>
                                    <option value="8">Tire&nbsp;discounter group</option>
                                </optgroup>
                                <optgroup label="Auto Dealership">
                                    <option value="16">Audi/Volkswagen</option>
                                    <option value="10">Chevrolet/GM</option>
                                    <option value="15">Chrysler</option>
                                    <option value="11">Ford</option>
                                    <option value="13">Honda/Acura</option>
                                    <option value="25">Hyundai</option>
                                    <option value="19">Kia</option>
                                    <option value="17">Mazda</option>
                                    <option value="14">Mitsubishi</option>
                                    <option value="18">Nissan/Infiniti</option>
                                    <option value="29">Subaru</option>
                                    <option value="12">Toyota/Lexus</option>
                                </optgroup>
                                <optgroup label="Misc">
                                    <option value="20">Advertising agency</option>
                                    <option value="21">Media – print-video-radio</option>
                                    <option value="22">Miscellaneous</option>
                                </optgroup>
                                <optgroup label="Admin">
                                    <option value="24">Nitto Emploee</option>
                                </optgroup>
                            </select>
                        </div>
                    </div>

                    <div class="regdFormBase2">
                        <div class="input-group">
                            <p>Do you require ADVANCED support site access? Note must be approved by your Nitto Tire Representative.</p>
                            <select class="form-control" name="delaraccess" id="delaraccess">
                                <option value="0">No I don't need advanced access</option>
                                <option value="1">Yes, please give me access</option>
                            </select>
                        </div>
                    </div>
                    <div class="regBordr"></div>
                    <div class="regdBtm">
                        <div class="regdFormBase2">
                            <div class="input-group">
                                <h5>Would you wish to receive information on:</h5>
                                <p>This would include upcoming sales events material, new advertising initiatives, promotional items such as clothing, point of purchase displays, coop changes or updates, and how to participate.</p>
                            </div>
                        </div>
                        <div class="regdFormBase2">
                            <div class="input-group">
                                <p>Advertising and Sales Promotion<span class="errStar">*</span></p>
                                <label class="checkbox-inline">
                                    <input type="radio" name="Esalespromo" id="Esalespromo1" value="1" checked=""> <span>Yes</span>
                                </label>
                                <label class="checkbox-inline">
                                    <input type="radio" name="Esalespromo" id="Esalespromo2" value="0"> <span>No</span>
                                </label>
                            </div>
                        </div>
                        <div class="regdFormBase2">
                            <div class="input-group">
                                <p>New product launches, increased size options, pricing changes, etc.</p>
                            </div>
                        </div>
                        <div class="regdFormBase2">
                            <div class="input-group">
                                <p>New product information<span class="errStar">*</span></p>
                                <label class="checkbox-inline">
                                    <input type="radio" name="Etireinfo" id="Etireinfo1" value="1" checked=""> <span>Yes</span>
                                </label>
                                <label class="checkbox-inline">
                                    <input type="radio" name="Etireinfo" id="Etireinfo2" value="0"> <span>No</span>
                                </label>
                            </div>
                        </div>
                        <div class="regdFormBase2">
                            <div class="input-group">
                                <p>Sometimes we like to get your opinion on how we are doing. If you would like to participate please let us know. We promise we won’t be sending you too many emails and you’ll always have the option to opt out at any time.</p>
                            </div>
                        </div>
                        <div class="regdFormBase2">
                            <div class="input-group">
                                <p>Accept Surveys<span class="errStar">*</span></p>
                                <label class="checkbox-inline">
                                    <input type="radio" name="Esurveys" id="Esurveys1" value="1" checked=""> <span>Yes</span>
                                </label>
                                <label class="checkbox-inline">
                                    <input type="radio" name="Esurveys" id="Esurveys2" value="0"> <span>No</span>
                                </label>
                            </div>
                        </div>

                        <div class="form-group btn-align-block">
                            <button type="submit" name="Registration" class="btn btn-login">Register</button>
                            <!-- <div class="btn-lnkbx">
                                <a class="btn-link-a" href="</?php echo site_url() . '/login' ?>"><i class="fa fa-long-arrow-right" aria-hidden="true"></i> Back to Login</a>
                            </div> -->
                        </div>
                    </div>



                </form>
            </div>
        </div>
    </div>
</section>

<?php
get_template_part('templates/parts/footer');
