<?php
defined('BASEPATH') OR exit('No direct script access allowed');
$isFrench = "";
$agree = $this->lang->line("Agree");
if ($agree != "Agree") {
  $isFrench = "_French";
}
?>

<!-- Product page content -->

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
          <duv class-"main-div">
            <div class="page-title">
              <div class="title_left">
                <h3><?php echo $this->lang->line("Our Products"); ?></h3>
              </div>

            </div>
            <div class="clearfix"></div>
          
            <!-- Product List Section -->
            <div class="row">
              <div class="col-md-12 col-sm-12 col-xs-12">
                <div class="x_panel">
                  <div class="x_title">
                    <h2><?php echo $this->lang->line("Product List"); ?><small></small></h2>
                    <div class="clearfix"></div>
                  </div>
                  <div class="x_content">
                  
                    <div class="table-responsive">
                      <table class="table table-hover table-bordered">
                        <thead>
                          <tr>
                            <th><?php echo $this->lang->line("Product Short Name"); ?></th>
                            <th><?php echo $this->lang->line("Full Name"); ?></th>
                            <th><?php echo $this->lang->line("Quote"); ?></th>
                            <th><?php echo $this->lang->line("Summary"); ?></th>
                            
                          </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($products as $p) { ?>
                            <tr>
                              <td><?php echo $p['product_short']; ?></td>
                              <td><?php echo $p['full_name']; ?></td>
                              <td><a href="<?php echo $quote_url . "?product_short=" . $p['product_short']; ?>" class="btn btn-primary"><?php echo $this->lang->line("Quote"); ?></a></td>
                              <td><a target="_bland" href="<?php echo $downloads_url . $p['product_short'] . $url_benefit . $isFrench . '.pdf'; ?>" class="btn btn-info"><?php echo $this->lang->line("View Summary"); ?></a></td>
                            </tr>
                            <?php } ?>
                        </tbody>
                      </table>
                    </div>
                  </div>
                </div>
              </div>
            </div><!-- End List Section -->

          </div>
        </div>
        <!-- /page content -->