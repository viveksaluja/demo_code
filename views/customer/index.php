<?php
$this->load->view('header');
$this->load->view('sidebar');
?>

<div id="page-wrapper" class="customer-information customerList customerPageList" >

    <div class="container-fluid">

        <!-- Page Heading -->
        <div class="row">
            <div class="col-lg-12">
                <h1 class="page-header title">
                    Customer Information
                </h1>
                <div class="error-messelement1" style="color:#3c763d;"><?php if ($this->session->flashdata('customermessage')) echo $this->session->flashdata('customermessage'); ?></div>
                <p><a href="<?php echo site_url() ?>add-customer" class="add-customer"> <i class="fa fa-plus-circle adds" aria-hidden="true"></i> Add New Customer</a></p>
            </div>
        </div>
        <!-- /.row -->
        <div class="row alpha">
            <div class="col-lg-9 col-md-6">
                <div class="sorting">
                    <div class="input-group drop-downs srch-div">
                        <label>Search</label>
                        <input type="text" placeholder="Search by Customer, Contact or Email" src="setToDefault" rel="getCustomerDetailByName" id="searchCustomer" class="form-control car-own"><span class="glyphicon glyphicon-search ico" aria-hidden="true"></span>
                    </div>
                </div>
            </div>
            <!--            <div class="col-lg-3 col-md-6">
                            <div class="display">Display: 
                                <select id="fileterByNum" rel="getCustomerDetailByNum">
                                    <option value='20'>20</option>                        	
                                    <option value='15'>15</option>
                                    <option value='10'>10</option>
                                    <option value='5'>5</option>             ￼               
                                </select>
                                per page
                            </div>
                        </div>-->
        </div>
        <div  id="allRecords">
            <div class="row">
                <div class="col-lg-12">                       
                    <div class="table-responsive">
                        <table class="table table-bordered table table-striped table-hover">
                            <thead>
                                <tr>
<!--                                <th class="sortVal">Username</th>-->
                                    <th class="sortVal">Customer Name</th>
                                    <th class="sortVal">Contact Number</th>
                                    <th class="sortVal">Email</th>
                                    <th class="sortVal">Orders</th>
                                    <th class="text-center"><i class="fa fa-cog" aria-hidden="true"></i></th>
                                </tr>
                            </thead>
                            <tbody id="tableBody">
                                <?php
                                if (count($customerList) > 0) {
                                    foreach ($customerList as $customerDetail) {
                                        ?>
                                        <tr id="customerInfo<?php echo $customerDetail['userRef'] ?>">
                                            <td id="customername<?php echo $customerDetail['userRef'] ?>" rel="<?php echo $customerDetail['userRef'] ?>" <?php
                                            if ($customerDetail['userActive'] != 1) {
                                                echo 'class="editCustomer"';
                                            }
                                            ?>><?php echo $customerDetail['firstName']; ?></td>
                                            <td id="customermobile<?php echo $customerDetail['userRef'] ?>"><?php echo $customerDetail['mobileNumber']; ?></td>
                                            <td id="customeremailId<?php echo $customerDetail['userRef'] ?>"><?php echo $customerDetail['emailId']; ?></td>
                                            <td id="totalDealOrder<?php echo $customerDetail['userRef'] ?>"><?php echo $customerDetail['totalOrder']; ?> <?php if ($customerDetail['totalOrder'] > 0) { ?> <a href="javascript:void(0)" rel="<?php echo $customerDetail['userRef'] ?>" class="viewCustomerOrderList">view details</a><?php } ?></td>
                                            <td>
                                                <?php
                                                if ($customerDetail['userActive'] != 1) {
                                                    ?>
                                                    <a href="javascript:void(0)" rel="<?php echo $customerDetail['userRef'] ?>" class="edit-btn editCustomer" title="Edit"><span class="glyphicon glyphicon-pencil"></span></a><a class="delete-btn accountDelete" href="javascript:void(0)" rel="<?php echo $customerDetail['userRef'] ?>" title="Delete"><span class="glyphicon glyphicon-remove"></span></a>
                                                    <?php
                                                }
                                                ?>
                                            </td>
                                        </tr>
                                        <?php
                                    }
                                } else {
                                    ?>
                                    <tr>
                                        <td colspan="5">No Records Found.</td>
                                    </tr>
                                    <?php
                                }
                                ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-lg-3 pull-right pagelinks">
                    <?php echo $links; ?>
                </div>
            </div>
        </div>
    </div>
    <!-- /.container-fluid -->

</div>
<!-- /#page-wrapper -->


<div  id="editProfile" style="display:none">
    <div class="col-lg-12">
        <?php
        $this->load->view('customer/updateCustomer');
        ?>
    </div>
</div>
<!-- END CONTAINER -->
<div id="myModal" class="modal fade">
    <div class="modal-dialog confirm-delete">
        <div class="modal-content">
            <!-- dialog body -->
            <div class="modal-body">
                <input type="hidden" id="deleteRef"/>
                <input type="hidden" id="pageName" value="updateCustomerDelete"/>
                <input type="hidden" id="deleteMsg" value="Customer deleted successfully">
                <h2>Confirm Delete?</h2>
                <p>Are you sure you want to delete this customer from database?<br/>
                    Deleted Data will not retrievable.</p>
                <p></p>
                <p></p>
                <p>
                    <a href="javascript:void(0)" id="keepData">No, keep this data</a>
                    <button type="button" class="btn btn-primary upload-img"  id="deleteInfo">Yes, delete this information</button>
                </p>
            </div>
            <!-- dialog buttons -->

        </div>
    </div>
</div>
<div id="wait"></div>
<?php
$this->load->view('footer');
?>
