<?php
defined('BASEPATH') OR exit('No direct script access allowed');
?>

<!-- Report/ Receivable report page content -->

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
                <h3>Receivable Report</h3>
              </div>

            </div>
            <div class="clearfix"></div>
            <!-- Filter Section -->
            <div class="row">
              <div class="col-md-12 col-sm-12 col-xs-12">
                <div class="x_panel">
                  <div class="x_title">
                    <h2>Report Filter<small></small></h2>
                    <div class="clearfix"></div>
                  </div>
                  <div class="x_content">

                    <form method="post" class="form-horizontal">
                      <div class="row">
                      <!-- Agent select box -->
                        <div class="form-group col-sm-4">
                          <label class="col-sm-12">Agent:</label>
                          <div class="input-group col-sm-12">
                              <select class="form-control">
                                <option>Choose option</option>
                                <option>Option one</option>
                                <option>Option two</option>
                                <option>Option three</option>
                                <option>Option four</option>
                              </select>
                          </div>
                        </div>
                        <!-- Agent select box end -->

                        <!-- Product select box -->
                        <div class="form-group col-sm-4">
                          <label class="col-sm-12">Product:</label>
                            <div input-group class="col-sm-12">
                              <select class="form-control">
                                <option>Choose option</option>
                                <option>Option one</option>
                                <option>Option two</option>
                                <option>Option three</option>
                                <option>Option four</option>
                              </select>
                          </div>
                        </div>
                        <!-- Product select box end -->
                        <!-- Product select box -->
                        <div class="form-group col-sm-4">
                          <label class="col-sm-12">Policy Status:</label>
                            <div input-group class="col-sm-12">
                              <select class="form-control">
                                <option>Quote</option>
                                <option>Sold</option>
                                <option>All</option>
                              </select>
                          </div>
                        </div>
                        <!-- Product select box end -->
                      </div>

                      <div class="row">
                        <!-- Application Date -->
                        <div class="form-group col-sm-3">
                          <!-- Application Date from -->
                            <label for="application_date_from" class="col-sm-12">Application Date From</label>
                            <div class="input-group date form_date col-sm-12" data-date="" data-date-format="yyyy/mm/dd" data-link-field="application_date_from" data-link-format="yyyy-mm-dd">
                                <input class="form-control" size="16" type="text" value="" readonly>
                                <span class="input-group-addon"><span class="glyphicon glyphicon-remove"></span></span>
                                <span class="input-group-addon"><span class="glyphicon glyphicon-calendar"></span></span>
                            </div>
                            <input type="hidden" id="application_date_from" value="" />
                            <!-- Application Date from End-->
                            <!-- Application Date to -->
                            <label for="application_date_to" class="col-sm-12">Application Date To</label>
                            <div class="input-group date form_date col-sm-12" data-date="" data-date-format="yyyy/mm/dd" data-link-field="application_date_to" data-link-format="yyyy-mm-dd">
                                <input class="form-control" size="16" type="text" value="" readonly>
                                <span class="input-group-addon"><span class="glyphicon glyphicon-remove"></span></span>
                                <span class="input-group-addon"><span class="glyphicon glyphicon-calendar"></span></span>
                            </div>
                            <input type="hidden" id="application_date_to" value="" /><br/>
                            <!-- Application Date to End -->
                        </div>
                        <!-- Application Date End-->
                        <!-- Create Date-->
                        <div class="form-group col-sm-3">
                            <!-- Create Date From-->
                            <label for="create_date_from" class="col-sm-12">Create Date From</label>
                            <div class="input-group date form_date col-sm-12" data-date="" data-date-format="yyyy/mm/dd" data-link-field="create_date_from" data-link-format="yyyy-mm-dd">
                                <input class="form-control" size="16" type="text" value="" readonly>
                                <span class="input-group-addon"><span class="glyphicon glyphicon-remove"></span></span>
                                <span class="input-group-addon"><span class="glyphicon glyphicon-calendar"></span></span>
                            </div>
                            <input type="hidden" id="create_date_from" value="" />
                            <!-- Create Date From End-->
                            <!-- Create Date to -->
                            <label for="create_date_to" class="col-sm-12">Create Date To</label>
                            <div class="input-group date form_date col-sm-12" data-date="" data-date-format="yyyy/mm/dd" data-link-field="create_date_to" data-link-format="yyyy-mm-dd">
                                <input class="form-control" size="16" type="text" value="" readonly>
                                <span class="input-group-addon"><span class="glyphicon glyphicon-remove"></span></span>
                                <span class="input-group-addon"><span class="glyphicon glyphicon-calendar"></span></span>
                            </div>
                            <input type="hidden" id="create_date_to" value="" /><br/>
                            <!-- Create Date to End -->
                        </div>
                        <!-- Create Date End -->
                        <!-- Effective Date-->
                        <div class="form-group col-sm-3">
                            <!-- Effective Date From-->
                            <label for="effective_date_from" class="col-sm-12">Effective Date From</label>
                            <div class="input-group date form_date col-sm-12" data-date="" data-date-format="yyyy/mm/dd" data-link-field="effective_date_from" data-link-format="yyyy-mm-dd">
                                <input class="form-control" size="16" type="text" value="" readonly>
                                <span class="input-group-addon"><span class="glyphicon glyphicon-remove"></span></span>
                                <span class="input-group-addon"><span class="glyphicon glyphicon-calendar"></span></span>
                            </div>
                            <input type="hidden" id="effective_date_from" value="" />
                            <!-- Effective Date From End-->
                            <!-- Effective Date to -->
                            <label for="effective_date_to" class="col-sm-12">Effective Date To</label>
                            <div class="input-group date form_date col-sm-12" data-date="" data-date-format="yyyy/mm/dd" data-link-field="effective_date_to" data-link-format="yyyy-mm-dd">
                                <input class="form-control" size="16" type="text" value="" readonly>
                                <span class="input-group-addon"><span class="glyphicon glyphicon-remove"></span></span>
                                <span class="input-group-addon"><span class="glyphicon glyphicon-calendar"></span></span>
                            </div>
                            <input type="hidden" id="effective_date_to" value="" /><br/>
                            <!-- Effective Date to End -->
                        </div>
                        <!-- Effective Date End -->
                        
                        <!-- Payment Update Date-->
                        <div class="form-group col-sm-3">
                            <!-- Payment Update Date From-->
                            <label for="payment_update_date_from" class="col-sm-12">Payment Update Date From</label>
                            <div class="input-group date form_date col-sm-12" data-date="" data-date-format="yyyy/mm/dd" data-link-field="payment_update_date_from" data-link-format="yyyy-mm-dd">
                                <input class="form-control" size="16" type="text" value="" readonly>
                                <span class="input-group-addon"><span class="glyphicon glyphicon-remove"></span></span>
                                <span class="input-group-addon"><span class="glyphicon glyphicon-calendar"></span></span>
                            </div>
                            <input type="hidden" id="payment_update_date_from" value="" />
                            <!-- Payment Update Date From End-->
                            <!-- Payment Update Date to -->
                            <label for="payment_update_date_to" class="col-sm-12">Payment Update Date To</label>
                            <div class="input-group date form_date col-sm-12" data-date="" data-date-format="yyyy/mm/dd" data-link-field="payment_update_date_to" data-link-format="yyyy-mm-dd">
                                <input class="form-control" size="16" type="text" value="" readonly>
                                <span class="input-group-addon"><span class="glyphicon glyphicon-remove"></span></span>
                                <span class="input-group-addon"><span class="glyphicon glyphicon-calendar"></span></span>
                            </div>
                            <input type="hidden" id="payment_update_date_to" value="" /><br/>
                            <!-- Payment Update Date to End -->
                        </div>
                        <!-- Payment Update Date End -->
                      </div>
                      <div class="row">
                        <!-- submit button -->
                          <div class="col-sm-12">
                            <button class="btn btn-default pull-right">Display Sales Report</button>
                          </div> 
                        <!-- submit button -->
                      </div>
                    </form>

                  </div>
                </div>
              </div>
            </div><!-- End Filter Section -->

            <!-- List Section -->
            <div class="row">
              <div class="col-md-12 col-sm-12 col-xs-12">
                <div class="x_panel">
                  <div class="x_title">
                    <h2>Search Result<small></small></h2>
                    <div class="clearfix"></div>
                  </div>
                  <div class="x_content">
                     
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
        