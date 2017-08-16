<?php
$this->load->view('header');
$this->load->view('sidebar');
?>

<div id="page-wrapper" class="add-customer customerAddPage">
    <div class="container-fluid"> 

        <!-- Page Heading -->

        <div class="row">
            <div class="col-lg-12 cus">
                <div class="col-lg-6">
                    <h1 class="page-header title">Customer Details </h1>
                </div>
                <div class="col-lg-6 pull-right">
                    <a href="<?php echo base_url() . 'dashboard'; ?>" class="back-aero btn btn-info backarrowCat">
                        <span class="glyphicon glyphicon-arrow-left"></span> back 
                    </a>
                    <span id="view-order-det"><?php if ($customer[0]->totalOrder > 0) { ?> <a href="javascript:void(0)" rel="<?php echo $customer[0]->userRef; ?>" class="viewCustomerOrderList">View Order Details</a><?php } ?></span>
                </div>
            </div>
        </div>

        <div class="row personal-info">
            <div class="col-lg-12">
                <div class="ajax_report alert-message alert alert-dismissible updateclientdetails" role="alert" style="display:none">
                    <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                    <span class="ajax_message updateclientmessage">Hello Message</span> 
                </div>
                <div class="error-mess"> <span class="successMsg">
                        <?php if (isset($queryResult)) echo $queryResult; ?>
                    </span>
                    <?php if (isset($error_message)) echo $error_message; ?>
                </div>
            </div>
            <form class="form-horizontal" method="post">
                <input type="hidden" id="userType" value="customer">
                <input type="hidden" id="userRef" name="userRef" class="success personalInfo"/>
                <div class="col-lg-12 personal-info-form pr-detail">
                    <div class="col-lg-6">
                        <h5 class="m-lft">Personal Details</h5>
                        <div class="form-group">
                            <label for="inputEmail3" class="col-sm-3 control-label">Salutation :</label>
                            <div class="col-sm-9">
                                <?php
                                $namePrefix = "";
                                if (isset($customer[0]->namePrefix) && $customer[0]->namePrefix != "") {
                                    if ($customer[0]->namePrefix == 1) {
                                        $namePrefix = 'Mr.';
                                    } elseif ($customer[0]->namePrefix == 1) {
                                        $namePrefix = 'Mrs.';
                                    } else if ($customer[0]->namePrefix == 1) {
                                        $namePrefix = 'Ms.';
                                    } else if ($customer[0]->namePrefix == 1) {
                                        $namePrefix = 'Dr.';
                                    }
                                }
                                echo $namePrefix;
                                ?>    
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="inputPassword3" class="col-sm-3 control-label">Name :</label>
                            <div class="col-sm-9">
                                <?php
                                if (isset($customer[0]->firstName)) {
                                    echo $customer[0]->firstName;
                                }
                                ?>
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="inputPassword3" class="col-sm-3 control-label" style="text-transform:none;">Date of Birth :</label>
                            <div class="col-sm-9">
                                <?php
                                if (isset($customer[0]->dob)) {
                                    echo date($this->config->item('dateFormat'), strtotime($customer[0]->dob));
                                }
                                ?>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-6">
                        <div class="form-group">
                            <label for="inputPassword3" class="col-sm-3 control-label">Gender :</label>
                            <div class="col-sm-9">
                                <?php
                                if (isset($customer[0]->gender)) {
                                    echo ucfirst($customer[0]->gender);
                                }
                                ?>
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="inputPassword3" class="col-sm-3 control-label">Email Id :</label>
                            <div class="col-sm-9">
                                <?php
                                if (isset($customer[0]->emailId)) {
                                    echo $customer[0]->emailId;
                                }
                                ?>
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="inputPassword3" class="col-sm-3 control-label">Mobile Number :</label>
                            <div class="col-sm-9">
                                <?php
                                if (isset($customer[0]->mobileNumber)) {
                                    echo $customer[0]->mobileNumber;
                                }
                                ?>
                            </div>
                        </div>
                    </div>
                    <span class="clearfix"></span>
                    <div class="bill-ship">
                        <div class="col-lg-6">
                            <h5 class="m-lft">Billing Information</h5>
                            <div class="form-group">
                                <label for="comment" class="col-sm-3">Address</label>
                                <div class="col-sm-9">
                                    <?php
                                    if (isset($customer[0]->billingAddress)) {
                                        echo $customer[0]->billingAddress;
                                    }
                                    ?>
                                </div>
                            </div>
                            <div class="form-group">
                                <label for="inputPassword3" class="col-sm-3 control-label">Postal code</label>
                                <div class="col-sm-9">
                                    <?php
                                    if (isset($customer[0]->billingPostalCode)) {
                                        echo $customer[0]->billingPostalCode;
                                    }
                                    ?>
                                </div>
                            </div>
                            <div class="form-group">
                                <label for="inputPassword3" class="col-sm-3 control-label"> City </label>
                                <div class="col-sm-9">
                                    <?php
                                    if (isset($customer[0]->Billingcity)) {
                                        echo $customer[0]->Billingcity;
                                    }
                                    ?>
                                </div>
                            </div>
                            <div class="form-group">
                                <label for="inputEmail3" class="col-sm-3 control-label">State</label>
                                <div class="col-sm-9">
                                    <?php
                                    if (isset($customer[0]->billingProvince)) {
                                        echo $customer[0]->billingProvince;
                                    }
                                    ?>
                                </div>
                            </div>
                            <span class="clearfix"></span>
                            <div class="form-group">
                                <label for="inputPassword3" class="col-sm-3 control-label">Country</label>
                                <div class="col-sm-9">
                                    <?php
                                    if (isset($customer[0]->billingCountry)) {
                                        echo $customer[0]->billingCountry;
                                    }
                                    ?>
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-6">
                            <h5 class="m-lft">Shipping Information</h5>
                            <div class="form-group">
                                <label for="inputPassword3" class="col-sm-3 control-label" style="text-transform:none;">Ship to Contact</label>
                                <div class="col-sm-9">
                                    <?php
                                    if (isset($customer[0]->shipToContact)) {
                                        echo $customer[0]->shipToContact;
                                    }
                                    ?>
                                </div>
                            </div>
                            <div class="form-group">
                                <label for="inputPassword3" class="col-sm-3 control-label">Phone</label>
                                <div class="col-sm-9">
                                    <?php
                                    if (isset($customer[0]->ShipPhone)) {
                                        echo $customer[0]->ShipPhone;
                                    }
                                    ?>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-sm-3" for="comment">Address</label>
                                <div class="col-sm-9">
                                    <?php
                                    if (isset($customer[0]->ShipAddress)) {
                                        echo $customer[0]->ShipAddress;
                                    }
                                    ?>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-sm-3 control-label" for="inputPassword3">Postal code</label>
                                <div class="col-sm-9">
                                    <?php
                                    if (isset($customer[0]->ShipPostalCode)) {
                                        echo $customer[0]->ShipPostalCode;
                                    }
                                    ?>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-sm-3 control-label" for="inputPassword3"> City </label>
                                <div class="col-sm-9">
                                    <?php
                                    if (isset($customer[0]->ShipCity)) {
                                        echo $customer[0]->ShipCity;
                                    }
                                    ?>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-sm-3 control-label" for="inputEmail3">State</label>
                                <div class="col-sm-9">
                                    <?php
                                    if (isset($customer[0]->ShipProvince)) {
                                        echo $customer[0]->ShipProvince;
                                    }
                                    ?>
                                </div>
                            </div>
                            <span class="clearfix"></span>
                            <div class="form-group">
                                <label class="col-sm-3 control-label" for="inputPassword3">Country</label>
                                <div class="col-sm-9">
                                    <?php
                                    if (isset($customer[0]->shipCountryId)) {
                                        echo $customer[0]->shipCountryId;
                                    }
                                    ?>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>
    <!-- /.container-fluid --> 
</div>
<!-- /#page-wrapper -->
<?php
$this->load->view('footer');
?>
