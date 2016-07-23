<?php
defined('BASEPATH') OR exit('No direct script access allowed');
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
                <h3>Our Products</h3>
              </div>

            </div>
            <div class="clearfix"></div>
          
            <!-- Product List Section -->
            <div class="row">
              <div class="col-md-12 col-sm-12 col-xs-12">
                <div class="x_panel">
                  <div class="x_title">
                    <h2>Product List<small></small></h2>
                    <div class="clearfix"></div>
                  </div>
                  <div class="x_content">
                  
                    <div class="table-responsive">
                      <table class="table table-hover table-bordered">
                        <thead>
                          <tr>
                            <th>Product Short Name</th>
                            <th>Full Name</th>
                            <th>Quote</th>
                            <th>Summary</th>
                            
                          </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($products as $p) { ?>
                            <tr>
                              <td><?php echo $p['product_short']; ?></td>
                              <td><?php echo $p['full_name']; ?></td>
                              <td><a href="<?php echo $quote_url . "?product_short=" . $p['product_short']; ?>" class="btn btn-primary">Quote</a></td>
                              <td><a target="_bland" href="<?php echo $downloads_url . $p['product_short'] . $url_benefit . '.pdf'; ?>" class="btn btn-info">View Summary</a></td>
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