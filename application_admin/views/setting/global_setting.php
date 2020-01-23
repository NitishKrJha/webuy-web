<div class="content">
    <div class="">
        <div class="page-header-title">
            <h4 class="page-title">Setting</h4></div>
    </div>
    <div class="page-content-wrapper ">
        <div class="container">
            <div class="row">
                <div class="col-sm-12">
                    <div class="panel panel-primary">
                         <div class="panel-body">
                            <ul class="parsley-errors-list filled error text-left" ><li class="parsley-required"><?php echo $errmsg; ?></li></ul>
                            <form role="form" class="form-horizontal form-label-left" id="form1" action="<?php echo $submit_link;?>" method="post" enctype="multipart/form-data">
                                <span class="section">Global Settings</span>
                                <div class="form-group">
                                    <label for="form-field-1" class="control-label col-md-2 col-sm-3 col-xs-12"><?php echo $global['global_site_name']['field_name'];?> <span class="required">*</span></label>

                                    <div class="col-sm-9">
                                        <input type="text" name="<?php echo $global['global_site_name']['field_key'];?>" id="<?php echo $global['global_site_name']['field_key'];?>" value="<?php echo $global['global_site_name']['field_value'];?>" class="validate[required] col-xs-10 col-sm-5 form-control"  />
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label for="form-field-1" class="control-label col-md-2 col-sm-3 col-xs-12"><?php echo $global['global_meta_title']['field_name'];?> <span class="required">*</span></label>

                                    <div class="col-sm-9">
                                        <input type="text" name="<?php echo $global['global_meta_title']['field_key'];?>" id="<?php echo $global['global_meta_title']['field_key'];?>" value="<?php echo $global['global_meta_title']['field_value'];?>" class="validate[required] col-xs-10 col-sm-5 form-control"  />
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label for="form-field-1" class="control-label col-md-2 col-sm-3 col-xs-12"><?php echo $global['global_meta_keywords']['field_name'];?> <span class="required">*</span></label>

                                    <div class="col-sm-9">
                                        <input type="text" name="<?php echo $global['global_meta_keywords']['field_key'];?>" id="<?php echo $global['global_meta_keywords']['field_key'];?>" value="<?php echo $global['global_meta_keywords']['field_value'];?>" class="validate[required] col-xs-10 col-sm-5 form-control"  />
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label for="form-field-1" class="control-label col-md-2 col-sm-3 col-xs-12"><?php echo $global['global_meta_description']['field_name'];?> <span class="required">*</span></label>

                                    <div class="col-sm-9">
                                        <input type="text" name="<?php echo $global['global_meta_description']['field_key'];?>" id="<?php echo $global['global_meta_description']['field_key'];?>" value="<?php echo $global['global_meta_description']['field_value'];?>" class="validate[required] col-xs-10 col-sm-5 form-control"  />
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label for="form-field-1" class="control-label col-md-2 col-sm-3 col-xs-12"><?php echo $global['global_footer_details']['field_name'];?> <span class="required">*</span></label>

                                    <div class="col-sm-9">
                                        <input type="text" name="<?php echo $global['global_footer_details']['field_key'];?>" id="<?php echo $global['global_footer_details']['field_key'];?>" value="<?php echo $global['global_footer_details']['field_value'];?>" class="validate[required] col-xs-10 col-sm-5 form-control"  />
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label for="form-field-1" class="control-label col-md-2 col-sm-3 col-xs-12"><?php echo $global['global_web_admin_headquarters_address']['field_name'];?> <span class="required">*</span></label>

                                    <div class="col-sm-9">
                                        <textarea name="<?php echo $global['global_web_admin_headquarters_address']['field_key'];?>" id="<?php echo $global['global_web_admin_headquarters_address']['field_key'];?>"  class="validate[required] col-xs-10 col-sm-5 form-control content"><?php echo $global['global_web_admin_headquarters_address']['field_value'];?></textarea>
                                    </div>
                                </div>

                                <!--<div class="form-group">
                                    <label for="form-field-1" class="control-label col-md-2 col-sm-3 col-xs-12"><?php echo $global['global_phone_number']['field_name'];?> <span class="required">*</span></label>

                                    <div class="col-sm-9">
                                        <input type="text" name="<?php echo $global['global_phone_number']['field_key'];?>" id="<?php echo $global['global_phone_number']['field_key'];?>" value="<?php echo $global['global_phone_number']['field_value'];?>" class="validate[required] col-xs-10 col-sm-5 form-control"  />
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label for="form-field-1" class="control-label col-md-2 col-sm-3 col-xs-12"><?php echo $global['global_web_admin_name']['field_name'];?> <span class="required">*</span></label>

                                    <div class="col-sm-9">
                                        <input type="text" name="<?php echo $global['global_web_admin_name']['field_key'];?>" id="<?php echo $global['global_web_admin_name']['field_key'];?>" value="<?php echo $global['global_web_admin_name']['field_value'];?>" class="validate[required] col-xs-10 col-sm-5 form-control"  />
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label for="form-field-1" class="control-label col-md-2 col-sm-3 col-xs-12"><?php echo $global['global_web_admin_headquarters_address']['field_name'];?> <span class="required">*</span></label>

                                    <div class="col-sm-9">
                                        <textarea name="<?php echo $global['global_web_admin_headquarters_address']['field_key'];?>" id="<?php echo $global['global_web_admin_headquarters_address']['field_key'];?>"  class="validate[required] col-xs-10 col-sm-5 form-control content"><?php echo $global['global_web_admin_headquarters_address']['field_value'];?></textarea>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label for="form-field-1" class="control-label col-md-2 col-sm-3 col-xs-12"><?php echo $global['global_web_admin_secondary_address']['field_name'];?> <span class="required">*</span></label>

                                    <div class="col-sm-9">
                                        <textarea name="<?php echo $global['global_web_admin_secondary_address']['field_key'];?>" id="<?php echo $global['global_web_admin_secondary_address']['field_key'];?>" class="validate[required] col-xs-10 col-sm-5 form-control content"><?php echo $global['global_web_admin_secondary_address']['field_value'];?></textarea>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label for="form-field-1" class="control-label col-md-2 col-sm-3 col-xs-12"><?php echo $global['global_webadmin_email']['field_name'];?> <span class="required">*</span></label>

                                    <div class="col-sm-9">
                                        <input type="text" name="<?php echo $global['global_webadmin_email']['field_key'];?>" id="<?php echo $global['global_webadmin_email']['field_key'];?>" value="<?php echo $global['global_webadmin_email']['field_value'];?>" class="validate[required] col-xs-10 col-sm-5 form-control"  />
                                    </div>
                                </div>-->
                                <div class="form-group">
                                    <label for="form-field-1" class="control-label col-md-2 col-sm-3 col-xs-12"><?php echo $global['global_contact_email']['field_name'];?> <span class="required">*</span></label>

                                    <div class="col-sm-9">
                                        <input type="text" name="<?php echo $global['global_contact_email']['field_key'];?>" id="<?php echo $global['global_contact_email']['field_key'];?>" value="<?php echo $global['global_contact_email']['field_value'];?>" class="validate[required] col-xs-10 col-sm-5 form-control"  />
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label for="form-field-1" class="control-label col-md-2 col-sm-3 col-xs-12"><?php echo $global['global_facebook_url']['field_name'];?> <span class="required">*</span></label>

                                    <div class="col-sm-9">
                                        <input type="text" name="<?php echo $global['global_facebook_url']['field_key'];?>" id="<?php echo $global['global_facebook_url']['field_key'];?>" value="<?php echo $global['global_facebook_url']['field_value'];?>" class="validate[required] col-xs-10 col-sm-5 form-control"  />
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label for="form-field-1" class="control-label col-md-2 col-sm-3 col-xs-12"><?php echo $global['global_twitter_url']['field_name'];?> <span class="required">*</span></label>

                                    <div class="col-sm-9">
                                        <input type="text" name="<?php echo $global['global_twitter_url']['field_key'];?>" id="<?php echo $global['global_twitter_url']['field_key'];?>" value="<?php echo $global['global_twitter_url']['field_value'];?>" class="validate[required] col-xs-10 col-sm-5 form-control"  />
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label for="form-field-1" class="control-label col-md-2 col-sm-3 col-xs-12"><?php echo $global['global_google_plus_url']['field_name'];?> <span class="required">*</span></label>

                                    <div class="col-sm-9">
                                        <input type="text" name="<?php echo $global['global_google_plus_url']['field_key'];?>" id="<?php echo $global['global_google_plus_url']['field_key'];?>" value="<?php echo $global['global_google_plus_url']['field_value'];?>" class="validate[required] col-xs-10 col-sm-5 form-control"  />
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label for="form-field-1" class="control-label col-md-2 col-sm-3 col-xs-12"><?php echo $global['global_playstore_url']['field_name'];?> <span class="required">*</span></label>

                                    <div class="col-sm-9">
                                        <input type="text" name="<?php echo $global['global_playstore_url']['field_key'];?>" id="<?php echo $global['global_playstore_url']['field_key'];?>" value="<?php echo $global['global_playstore_url']['field_value'];?>" class="validate[required] col-xs-10 col-sm-5 form-control"  />
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label for="form-field-1" class="control-label col-md-2 col-sm-3 col-xs-12"><?php echo $global['global_appstore_url']['field_name'];?> <span class="required">*</span></label>

                                    <div class="col-sm-9">
                                        <input type="text" name="<?php echo $global['global_appstore_url']['field_key'];?>" id="<?php echo $global['global_appstore_url']['field_key'];?>" value="<?php echo $global['global_appstore_url']['field_value'];?>" class="validate[required] col-xs-10 col-sm-5 form-control"  />
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label for="form-field-1" class="control-label col-md-2 col-sm-3 col-xs-12"><?php echo $global['global_tax_name']['field_name'];?> <span class="required">*</span></label>

                                    <div class="col-sm-9">
                                        <input type="text" name="<?php echo $global['global_tax_name']['field_key'];?>" id="<?php echo $global['global_tax_name']['field_key'];?>" value="<?php echo $global['global_tax_name']['field_value'];?>" class="validate[required] col-xs-10 col-sm-5 form-control"  />
                                    </div>

                                </div>
                                <div class="form-group">
                                    <label for="form-field-1" class="control-label col-md-2 col-sm-3 col-xs-12"><?php echo $global['global_tax_value']['field_name'];?> <span class="required">*</span></label>

                                    <div class="col-sm-9">
                                        <input type="text" name="<?php echo $global['global_tax_value']['field_key'];?>" id="<?php echo $global['global_tax_value']['field_key'];?>" value="<?php echo $global['global_tax_value']['field_value'];?>" class="validate[required] col-xs-10 col-sm-5 form-control"  />
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label for="form-field-1" class="control-label col-md-2 col-sm-3 col-xs-12"><?php echo $global['global_gratuity_value']['field_name'];?> <span class="required">*</span></label>

                                    <div class="col-sm-9">
                                        <input type="text" name="<?php echo $global['global_gratuity_value']['field_key'];?>" id="<?php echo $global['global_gratuity_value']['field_key'];?>" value="<?php echo $global['global_gratuity_value']['field_value'];?>" class="validate[required] col-xs-10 col-sm-5 form-control"  />
                                    </div>
                                </div>
                                <!--<div class="form-group">
                                    <label for="form-field-1" class="control-label col-md-2 col-sm-3 col-xs-12"><?php echo $global['global_email_signature']['field_name'];?> <span class="required">*</span></label>

                                    <div class="col-sm-9">
                                        <textarea name="<?php echo $global['global_email_signature']['field_key'];?>" id="<?php echo $global['global_email_signature']['field_key'];?>" class="content"><?php echo $global['global_email_signature']['field_value'];?></textarea>
                                    </div>
                                </div>
                                

                                <div class="form-group">
                                    <label for="form-field-1" class="control-label col-md-2 col-sm-3 col-xs-12"><?php echo $global['global_googleplus_url']['field_name'];?> <span class="required">*</span></label>

                                    <div class="col-sm-9">
                                        <input type="text" name="<?php echo $global['global_googleplus_url']['field_key'];?>" id="<?php echo $global['global_googleplus_url']['field_key'];?>" value="<?php echo $global['global_googleplus_url']['field_value'];?>" class="validate[required] col-xs-10 col-sm-5 form-control"  />
                                    </div>
                                </div>
                                
                                <div class="form-group">
                                    <label for="form-field-1" class="control-label col-md-2 col-sm-3 col-xs-12"><?php echo $global['global_youtube_url']['field_name'];?> <span class="required">*</span></label>

                                    <div class="col-sm-9">
                                        <input type="text" name="<?php echo $global['global_youtube_url']['field_key'];?>" id="<?php echo $global['global_youtube_url']['field_key'];?>" value="<?php echo $global['global_youtube_url']['field_value'];?>" class="validate[required] col-xs-10 col-sm-5 form-control"  />
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label for="form-field-1" class="control-label col-md-2 col-sm-3 col-xs-12"><?php echo $global['global_playstore_url']['field_name'];?><span class="required">*</span></label>

                                    <div class="col-sm-9">
                                        <input type="text" name="<?php echo $global['global_playstore_url']['field_key'];?>" id="<?php echo $global['global_playstore_url']['field_key'];?>" value="<?php echo $global['global_playstore_url']['field_value'];?>" class="validate[required] col-xs-10 col-sm-5 form-control"  />
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label for="form-field-1" class="control-label col-md-2 col-sm-3 col-xs-12"><?php echo $global['global_appstore_url']['field_name'];?><span class="required">*</span></label>

                                    <div class="col-sm-9">
                                        <input type="text" name="<?php echo $global['global_appstore_url']['field_key'];?>" id="<?php echo $global['global_appstore_url']['field_key'];?>" value="<?php echo $global['global_appstore_url']['field_value'];?>" class="validate[required] col-xs-10 col-sm-5 form-control"  />
                                    </div>
                                </div>-->
                                

                                
                                <div class="form-group">
                                    <div class="col-sm-offset-2 col-sm-9">
                                        <input type="submit" value="Update" class="btn btn-shadow btn-success" />
                                    
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
            
        </div>
    </div>
</div>

<script type="text/javascript">
    $(document).ready(function($){
     $("#form1").validationEngine();
    });
</script>