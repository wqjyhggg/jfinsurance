<?php
defined('BASEPATH') or exit('No direct script access allowed');
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
        <h3>Cancel Policy</h3>
      </div>
    </div>
    <div class="clearfix"></div>

    <!-- Filter Section -->
    <?php if (!empty($claims)) { ?>
      <div class="row">
        <div class="col-md-12 col-sm-12 col-xs-12">
          <div class="x_panel">
            <div class="x_content">
              <div class="alert-error">There is an existing claim or open cases in the system, please double check before you proceed.</div>
            </div>
          </div>
        </div>
      </div>
    <?php } ?>
    <div class="row">
      <div class="col-md-12 col-sm-12 col-xs-12">
        <div class="x_panel">
          <div class="x_content">
            <?php if (!empty($error_message)) { ?>
              <div class="alert-error">
                <?php echo $error_message . "<br>"; ?>
              </div>
            <?php } ?>
            <?php if ($plan['premium'] < $admin_fee) { ?>
              <div class="alert-error">
                Premium is less than Admin Fee. Can't cancel
              </div>
            <?php } else { ?>
              <form action='<?php echo $action_url; ?>' method='POST' class="form-horizontal">
                <input type='hidden' name='<?php echo $csrf['name']; ?>' value='<?php echo $csrf['value']; ?>'>
                <input type='hidden' name='plan_id' value='<?php echo $plan_id; ?>'>
                <div class="form-group col-sm-4 col-xs-12" style="display:none;">
                  <label>Refund Premium:</label>
                  <!--span id="refund_amount"></span-->
                  <input readonly type="number" step="any" name='refund_amount' id='refund_amount' value='<?php echo $plan['premium']; ?>' class="form-control" />
                </div>
                <div class="row">
                  <div class="form-group col-sm-5 col-xs-12" style="display:none;">
                    <label class="col-sm-12">Admin Fee:</label>
                    <div class="input-group col-sm-12">
                      <input type="number" step="any" name='admin_fee' value='<?php echo $admin_fee; ?>' class="form-control" />
                    </div>
                  </div>
                </div><br />
                <div class="row">
                  <label style="display:inline-block;vertical-align:middle;">Reason:</label>
                  <div style="display:inline-block;vertical-align:middle;">
                    <select name='reason' id='reason' class="form-control">
                      <option value='Unable to obtain visa/work permit'>Unable to obtain visa/work permit</option>
                      <option value='Client received OHIP card'>Client received OHIP card</option>
                      <option value='Client decided not to come'>Client decided not to come</option>
                      <option value='Client passed away'></option>
                      <option value='Super VISA was declined'>Super VISA was declined</option>
                      <option value='Client returned to home country'>Client returned to home country</option>
                      <option value='PR approval'>PR approval</option>
                      <option value='Policy issued by mistake'>Policy issued by mistake</option>
                      <option value='Requested Void from Claim Department'>Requested Void from Claim Department</option>
                      <option value='Other'>Other</option>
                      <?php foreach ($status_list as $key => $value) { ?>
                        <option value='<?php echo $key; ?>' <?php echo ($key == $status_id) ? 'selected' : ''; ?>><?php echo $value['name']; ?></option>
                      <?php } ?>
                    </select>
                  </div>
                </div>
                <div class="row reason_input" style="display:none;">
                  <label style="display:inline-block;vertical-align:middle;">Detail:</label>
                  <div style="display:inline-block;vertical-align:middle;">
                    <input type="text" name='reason_input' id='reason_input' value='' class="form-control" />
                  </div>
                </div>
                <div class="row">
                  <div class="form-group col-sm-12 text-center">
                    <label class="inline">Are you sure you want to cancel this policy? </label>
                    <input class="btn btn-primary inline" type='submit' name='send' value='YES'>
                    <a class="btn btn-default inline" href="<?php echo $url_back_to_policy; ?>">NO</a>
                  </div>
                </div>
              </form>
            <?php } ?>
          </div>
        </div>
      </div>
    </div><!-- End Filter Section -->
  </div>
</div>
</div>
<!-- /page content -->
<script>
  $(document).ready(function() {
    $("#reason").on("change",function(e) {
  		e.preventDefault();
      var v = $("#reason").val();
      if (v == "Other") {
        $(".reason_input").show();
      } else {
        $(".reason_input").hide();
        $("#reason_input").val('');
      }
    });
  });
</script>