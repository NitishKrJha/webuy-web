<div class="content">
    <div class="">
        <div class="page-header-title">
            <h4 class="page-title">FAQ</h4></div>
    </div>
    <div class="page-content-wrapper ">
        <div class="container">

           <div class="row">
                <div class="col-sm-12">
                    <div class="panel panel-primary">
                        <div class="panel-body">
                            <h4 class="m-t-0 m-b-30"><?php echo $action; ?></h4>
                            <?php //echo validation_errors(); ?>
      						<ul class="parsley-errors-list filled error text-left" ><li class="parsley-required"><?php echo $errmsg; ?></li></ul>
							<form id="form1" name="form1" class="form-horizontal" action="<?php echo $do_addedit_link;?>" method="post" enctype="multipart/form-data" autocomplete="off">
								<span class="section"></span>
									<div class="form-group">
										

                                     <div class="form-group">
					                    <label class="control-label col-md-3 col-sm-3 col-xs-12" for="email">Question *</label>
					                    <div class="col-md-6 col-sm-6 col-xs-12">
					                      <textarea name="question" id="question" class="form-control" readonly><?php echo isset($question)?$question:'';?></textarea>
					                    </div>
					                </div>
                  





									<div class="form-group">
					                    <label class="control-label col-md-3 col-sm-3 col-xs-12" for="email">Answer *</label>
					                    <div class="col-md-6 col-sm-6 col-xs-12">
					                      <textarea name="answer" id="answer" class="form-control" readonly><?php echo isset($answer)?$answer:'';?></textarea>
					                    </div>
					                </div>

									
									
							
									



									<div class="ln_solid"></div>
									<div class="form-group">
										<div class="col-md-6 col-md-offset-3">
										  <a class="btn btn-primary" href="javascript:window.history.back();">Cancel</a>
										 
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


