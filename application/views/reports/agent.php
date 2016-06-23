<?php
defined('BASEPATH') OR exit('No direct script access allowed');
?>

<!-- Report/ Sales report to agent page content -->

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
          <div class="">
            <div class="page-title">
              <div class="title_left">
                <h3>Sales Report to Agent</h3>
              </div>

            </div>
            <div class="clearfix"></div>
            <!-- Filter Section -->
            <div class="row">
              <!-- Common Filter Form -->
              <div class="col-md-12 col-sm-12 col-xs-12">
                <div class="x_panel">
                  <div class="x_title">
                    <h2>Common Filter<small></small></h2>
                    <div class="clearfix"></div>
                  </div>
                  <div class="x_content">
                    <br />
                    <form class="form-horizontal">
                      <input type='hidden' name='<?php echo $csrf['name']; ?>' value='<?php echo $csrf['value']; ?>'>

                      <div class="col-md-4 col-sm-4 col-xs-12 form-group">
                        <div class="form-group">
                          <label class="col-sm-12">Product:</label>
                          <div class="col-sm-12">
                            <select class="form-control">
                              <option>Choose option</option>
                              <option>Option one</option>
                              <option>Option two</option>
                              <option>Option three</option>
                              <option>Option four</option>
                            </select>
                          </div>
                        </div>
                      </div>

                      <div class="col-md-4 col-sm-4 col-xs-12 form-group">
                        <div class="form-group">
                          <label class="col-sm-12">Agent:</label>
                          <div class="col-sm-12">
                            <select class="form-control">
                              <option>Choose option</option>
                              <option>Option one</option>
                              <option>Option two</option>
                              <option>Option three</option>
                              <option>Option four</option>
                            </select>
                          </div>
                        </div>
                        
                      </div>
                      <div class="col-md-4 col-sm-4 col-xs-12 form-group">
                        <div class="form-group">
                          <label class="col-sm-12">Date Type</label>
                          <div class="col-sm-12">
                            <select class="form-control">
                              <option>Choose option</option>
                              <option>Option one</option>
                              <option>Option two</option>
                              <option>Option three</option>
                              <option>Option four</option>
                            </select>
                          </div>
                        </div>
                      </div>
                      <div class="col-sm-12 form-group">
                        <div class="form-group">
                          <div class="col-sm-12">
                            <button class="btn btn-default pull-right">Display Sales Report</button>
                          </div>
                        </div>
                      </div>
                    </form>
                  </div>
                </div>
              </div>
              <!-- Common Filter Form End -->

              <!-- Monthly Report Filter Form -->
              <div class="col-md-12 col-sm-12 col-xs-12">
                <div class="x_panel">
                  <div class="x_title">
                    <h2>Monthly Report Filter<small></small></h2>
                    <div class="clearfix"></div>
                  </div>
                  <div class="x_content">
                    <br />
                    <form class="form-horizontal">
                      <input type='hidden' name='<?php echo $csrf['name']; ?>' value='<?php echo $csrf['value']; ?>'>

                      <div class="col-md-4 col-sm-4 col-xs-12 form-group">
                        <div class="form-group">
                          <label class="col-sm-12">Year:</label>
                          <div class="col-sm-12">
                            <select class="form-control">
                              <option>Choose option</option>
                              <option>Option one</option>
                              <option>Option two</option>
                              <option>Option three</option>
                              <option>Option four</option>
                            </select>
                          </div>
                        </div>
                      </div>

                      <div class="col-md-4 col-sm-4 col-xs-12 form-group">
                        <div class="form-group">
                          <label class="col-sm-12">Month:</label>
                          <div class="col-sm-12">
                            <select class="form-control">
                              <option>Choose option</option>
                              <option>Option one</option>
                              <option>Option two</option>
                              <option>Option three</option>
                              <option>Option four</option>
                            </select>
                          </div>
                        </div>
                        
                      </div>
                      
                      <div class="col-md-4 col-sm-4 col-xs-12 form-group">
                        <div class="form-group">
                          <label class="col-sm-12">&nbsp;</label>
                          <div class="col-sm-12">
                            <button class="btn btn-default pull-right">Display Sales Report</button>
                          </div>
                        </div>
                      </div>

                    </form>
                  </div>
                </div>
              </div>
              <!-- Monthly Report Filter Form End-->

              <!-- Daily Report Filter Form -->
              <div class="col-md-12 col-sm-12 col-xs-12">
                <div class="x_panel">
                  <div class="x_title">
                    <h2>Daily Report Filter<small></small></h2>
                    <div class="clearfix"></div>
                  </div>
                  <div class="x_content">
                    <br />
                    <form class="form-horizontal">
                      <input type='hidden' name='<?php echo $csrf['name']; ?>' value='<?php echo $csrf['value']; ?>'>

                      <div class="col-md-4 col-sm-4 col-xs-12 form-group">
                        <div class="form-group">
                          <label class="col-sm-12">Year:</label>
                          <div class="col-sm-12">
                            <select class="form-control">
                              <option>Choose option</option>
                              <option>Option one</option>
                              <option>Option two</option>
                              <option>Option three</option>
                              <option>Option four</option>
                            </select>
                          </div>
                        </div>
                      </div>

                      <div class="col-md-4 col-sm-4 col-xs-12 form-group">
                        <div class="form-group">
                          <label class="col-sm-12">Month:</label>
                          <div class="col-sm-12">
                            <select class="form-control">
                              <option>Choose option</option>
                              <option>Option one</option>
                              <option>Option two</option>
                              <option>Option three</option>
                              <option>Option four</option>
                            </select>
                          </div>
                        </div>
                        
                      </div>
                      <div class="col-md-4 col-sm-4 col-xs-12 form-group">
                        <div class="form-group">
                          <label class="col-sm-12">Day</label>
                          <div class="col-sm-12">
                            <select class="form-control">
                              <option>Choose option</option>
                              <option>Option one</option>
                              <option>Option two</option>
                              <option>Option three</option>
                              <option>Option four</option>
                            </select>
                          </div>
                        </div>
                      </div>
                      <div class="col-sm-12 form-group">
                        <div class="form-group">
                          <div class="col-sm-12">
                            <button class="btn btn-default pull-right">Display Sales Report</button>
                          </div>
                        </div>
                      </div>

                    </form>
                  </div>
                </div>
              </div>
              <!-- Daily Report Filter Form End-->

              <!-- Duration Report Filter Form -->
              <div class="col-md-12 col-sm-12 col-xs-12">
                <div class="x_panel">
                  <div class="x_title">
                    <h2>Duration Report Filter<small></small></h2>
                    <div class="clearfix"></div>
                  </div>
                  <div class="x_content">
                    <br />
                    <form class="form-horizontal">
                      <input type='hidden' name='<?php echo $csrf['name']; ?>' value='<?php echo $csrf['value']; ?>'>

                      <div class="col-md-4 col-sm-4 col-xs-12 form-group">
                        <div class="form-group">
                          <label class="col-sm-12">From Year:</label>
                          <div class="col-sm-12">
                            <select class="form-control">
                              <option>Choose option</option>
                              <option>Option one</option>
                              <option>Option two</option>
                              <option>Option three</option>
                              <option>Option four</option>
                            </select>
                          </div>
                        </div>
                      </div>

                      <div class="col-md-4 col-sm-4 col-xs-12 form-group">
                        <div class="form-group">
                          <label class="col-sm-12">Month:</label>
                          <div class="col-sm-12">
                            <select class="form-control">
                              <option>Choose option</option>
                              <option>Option one</option>
                              <option>Option two</option>
                              <option>Option three</option>
                              <option>Option four</option>
                            </select>
                          </div>
                        </div>              
                      </div>
                      <div class="col-md-4 col-sm-4 col-xs-12 form-group">
                        <div class="form-group">
                          <label class="col-sm-12">Day</label>
                          <div class="col-sm-12">
                            <select class="form-control">
                              <option>Choose option</option>
                              <option>Option one</option>
                              <option>Option two</option>
                              <option>Option three</option>
                              <option>Option four</option>
                            </select>
                          </div>
                        </div>
                      </div>

                      <div class="col-md-4 col-sm-4 col-xs-12 form-group">
                        <div class="form-group">
                          <label class="col-sm-12">To Year:</label>
                          <div class="col-sm-12">
                            <select class="form-control">
                              <option>Choose option</option>
                              <option>Option one</option>
                              <option>Option two</option>
                              <option>Option three</option>
                              <option>Option four</option>
                            </select>
                          </div>
                        </div>
                      </div>

                      <div class="col-md-4 col-sm-4 col-xs-12 form-group">
                        <div class="form-group">
                          <label class="col-sm-12">Month:</label>
                          <div class="col-sm-12">
                            <select class="form-control">
                              <option>Choose option</option>
                              <option>Option one</option>
                              <option>Option two</option>
                              <option>Option three</option>
                              <option>Option four</option>
                            </select>
                          </div>
                        </div>    
                      </div>

                      <div class="col-md-4 col-sm-4 col-xs-12 form-group">
                        <div class="form-group">
                          <label class="col-sm-12">Day:</label>
                          <div class="col-sm-12">
                            <select class="form-control">
                              <option>Choose option</option>
                              <option>Option one</option>
                              <option>Option two</option>
                              <option>Option three</option>
                              <option>Option four</option>
                            </select>
                          </div>
                        </div>
                      </div>


                      <div class="col-sm-12 form-group">
                        <div class="form-group">
                          <div class="col-sm-12">
                            <button class="btn btn-default pull-right">Display Sales Report</button>
                          </div>
                        </div>
                      </div>

                    </form>
                  </div>
                </div>
              </div>
              <!-- Duration Report Filter Form End-->
            </div><!-- Filter Section End -->
            <!-- List Section -->
            <div class="row">
              <div class="col-md-12 col-sm-12 col-xs-12">
                <div class="x_panel">
                  <div class="x_title">
                    <h2>Search Result<small></small></h2>
                    <div class="clearfix"></div>
                  </div>
                  <div class="x_content">
                    <br />
                    <div class="table-responsive">
                      <table class="table table-hover table-bordered">
                        <thead>
                          <tr>
                            <th>Travel Plan Number</th>
                            <th>First Name</th>
                            <th>Last Name</th>
                            <th>Gender</th>
                            <th>Birthdate</th>
                            <th>Address Line 1</th>
                            <th>City</th>
                            <th>Province</th>
                            <th>Postal Code</th>
                            <th>Option</th>
                            <th>Agent</th>
                            <th>Application Date</th>
                            <th>Create Date</th>
                            <th>Effective Date</th>
                            <th>Expiry Date</th>
                            <th>Number of Days</th>
                            <th>Sum Insured</th>
                            <th>Net Premium</th>
                           
                            <th>Gross Premium</th>
                           
                            <th>Rate Per Day</th>
                          </tr>
                        </thead>
                        <tbody>
                            <tr>
                              <td colspan="22">JF Optimum Plus</td>
                            </tr>
                            <tr>
                              <td>OPL248778</td>
                              <td>TestGiven</td>
                              <td>TestLast</td>
                              <td>M</td>
                              <td>1980-05-03</td>
                              <td>14 Asdf fdsfsdf</td>
                              <td>Toronto</td>
                              <td>ON</td>
                              <td>M2M 4M5</td>
                              <td>9283</td>
                              <td>458286</td>
                              <td>2016-06-05</td>
                              <td>2016-05-05</td>
                              <td>2016-08-03</td>
                              <td>2017-08-02</td>
                              <td>365</td>
                              <td>10,000</td>
                              <td>733.21</td>
                              <td>1,357.80</td>
                              <td>3.72</td>
                            </tr>
                            <tr>
                              <td>OPL248777</td>
                              <td>TestGiven</td>
                              <td>TestLast</td>
                              <td>M</td>
                              <td>1980-05-03</td>
                              <td>14 Asdf fdsfsdf</td>
                              <td>Toronto</td>
                              <td>ON</td>
                              <td>M2M 4M5</td>
                              <td>9283</td>
                              <td>458286</td>
                              <td>2016-06-05</td>
                              <td>2016-05-05</td>
                              <td>2016-08-03</td>
                              <td>2017-08-02</td>
                              <td>365</td>
                              <td>10,000</td>
                              <td>733.21</td>
                              <td>1,357.80</td>
                              <td>3.72</td>
                            </tr>
                            <tr>
                              <td colspan="22">JF Royal Visitor to Canada</td>
                            </tr>
                            <tr>
                              <td>JFR248775</td>
                              <td>TestGiven</td>
                              <td>TestLast</td>
                              <td>M</td>
                              <td>1980-05-03</td>
                              <td>14 Asdf fdsfsdf</td>
                              <td>Toronto</td>
                              <td>ON</td>
                              <td>M2M 4M5</td>
                              <td>9283</td>
                              <td>458286</td>
                              <td>2016-06-05</td>
                              <td>2016-05-05</td>
                              <td>2016-08-03</td>
                              <td>2017-08-02</td>
                              <td>365</td>
                              <td>10,000</td>
                              <td>733.21</td>
                              <td>1,357.80</td>
                              <td>3.72</td>
                            </tr>
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
        