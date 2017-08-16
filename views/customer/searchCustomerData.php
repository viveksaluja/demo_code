<div class="row">
    <div class="col-lg-12">

        <div class="table-responsive">
            <table class="table table-bordered table-hover">

                <thead>
                    <tr>
<!--                        <th class="sortVal">Username</th>-->
                        <th class="sortVal">Customer Name</th>
                        <th class="sortVal">Contact Number</th>
                        <th class="sortVal">Email</th>
                        <th class="sortVal">Total Order</th>
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
                        <tr >
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
