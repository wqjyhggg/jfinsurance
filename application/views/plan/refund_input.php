<?php
defined('BASEPATH') OR exit('No direct script access allowed');
?>
<!-- Plan List page content -->

	    <!-- Content top navigation -->
        <div class="top_nav">
          <div class="nav_menu">
            <nav class="" role="navigation">
              <div class="nav toggle">
                <a id="menu_toggle"><i class="fa fa-bars"></i></a>
              </div>   
            </nav>
          </div>
        </div>
        <!-- Content top navigation End-->

        <!-- page content -->
        <div class="right_col" role="main">
          <div class="main-div">
            <div class="page-title">
              <div class="title_left">
                <h3>Send Policy Package</h3>
              </div>
            </div>
            <div class="clearfix"></div>

           <!-- Filter Section -->
            <div class="row">
              <div class="col-md-12 col-sm-12 col-xs-12">
                <div class="x_panel">
                  <div class="x_content">
                  <?php if (!empty($error_message)) { echo $error_message . "<br>"; } ?>
                    <form action='<?php echo $action_url; ?>' method='POST'  class="form-horizontal">
    				  <input type='hidden' name='<?php echo $csrf['name']; ?>' value='<?php echo $csrf['value']; ?>'>
    				  <input type='hidden' name='plan_id' value='<?php echo $plan_id; ?>'>
					  <div class="row">
                        <div class="form-group col-sm-5 col-xs-12">
                          <label class="col-sm-12">Effective Date:</label>
                          <div class="input-group col-sm-12">
	              			<?php echo $plan['effective_date']; ?>
                          </div>
                        </div>

                        <div class="form-group col-sm-5 col-xs-12">
                          <label class="col-sm-12">Expiry Date:</label>
                          <div class="input-group col-sm-12">
	              			<?php echo $plan['expiry_date']; ?>
                          </div>
                        </div>

                        <div class="form-group col-sm-5 col-xs-12">
                          <label class="col-sm-12">Refund Date:</label>
                          <div class="input-group col-sm-12">
                            <input type="date" name='refund_date' id='refund_date' value='<?php echo $plan['expiry_date']; ?>' min='<?php echo $plan['effective_date']; ?>' max='<?php echo $plan['expiry_date']; ?>' class="form-control"/>
                          </div>
                        </div>

                        <div class="form-group col-sm-5 col-xs-12">
                          <label class="col-sm-12">Refund Amount:</label>
                          <div class="input-group col-sm-12">
                            <input type="number" step="any" name='refund_amount' id='refund_amount' value='' class="form-control"/>
                          </div>
                        </div>

                        <div class="form-group col-sm-5 col-xs-12">
                          <label class="col-sm-12">Admin Fee:</label>
                          <div class="input-group col-sm-12">
                            <input type="number" step="any" name='admin_fee' value='40' class="form-control"/>
                          </div>
                        </div>

			            <div class="form-group col-sm-4">
			           	  <label class="col-sm-12">&nbsp;</label>
			              <input class="btn btn-primary" type='submit' name='send' value='Send'>
			            </div>	
                      </div>
			        </form>
				  </div>
                </div>
              </div>
            </div><!-- End Filter Section -->
          </div>
        </div>
      </div>
      <!-- /page content -->
<<script type="text/javascript">
<!--
$( document ).ready(function() {
	$('#refund_date').change(get_refund_amount); 
	get_refund_amount();
});

function get_refund_amount() {
	var refund_date = $('input[name="refund_date"]').val();
	
	$.ajax({
		url: '<?php echo $refund_amount_url; ?>',
		type: 'get',
		data: {	refund_date: refund_date },
		success: function(data, textStatus, jqXHR) {
			if (data['status'] == 'OK') {
        		$('input[name="refund_amount"]').val(data['refund_amount']);
			}
    	},
	});
} 
//-->
</script>
