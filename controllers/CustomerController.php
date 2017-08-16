<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class CustomerController extends CI_Controller {

    /**
     * Index Page for this controller.
     *
     * Maps to the following URL
     * 		http://example.com/index.php/welcome
     * 	- or -
     * 		http://example.com/index.php/welcome/index
     * 	- or -
     * Since this controller is set as the default controller in
     * config/routes.php, it's displayed at http://example.com/
     *
     * So any other public methods not prefixed with an underscore will
     * map to /index.php/welcome/<method_name>
     * @see https://codeigniter.com/user_guide/general/urls.html
     */
    public function CustomerController() {
        parent::__construct();
        $this->load->Model('customer');
        $this->perPageNum = 20;
        //$this->load->library('pagination');
    }

    public function index() {

        $this->load->view('customer/index', $customers);
    }

    //Function Name 		:	allCustomers.
    //Function Parameters 	:	---.
    //Function working 		:	Function using for geting customer list.
    //Function Output 		:	Return customer list array with pagination;
    //Used View				:	customer/index;

    public function allCustomers() {
        $data['page'] = ($this->uri->segment(2)) ? $this->uri->segment(2) : 0;
        $response = $this->customer->getCustomerDetail($this->perPageNum, $data['page']);
        $data['customerList'] = $response['data'];
        $resultCount = $response['totalRecords'];
        $data['links'] = $this->getPagination($this->perPageNum, $resultCount, site_url() . 'customer', 0, $data['page']);
        if ($this->input->post('ajax')) {
            $this->load->view('customer/searchCustomerData', $data);
        } else {
            $this->load->view('customer/index', $data);
        }
    }

    public function getPagination($perPage, $totalItem = 0, $url, $idNum, $pagesegment) {
        //pagination settings
        $config['base_url'] = $url;
        $config['total_rows'] = $totalItem;
        $config['per_page'] = $perPage;
        $config["uri_segment"] = 2;
        $choice = $config["total_rows"] / $config["per_page"];
        // $config["num_links"] = floor($choice);
        $config["num_links"] = 2;
        $config['first_link'] = "&lt;&lt; First";
        $config['last_link'] = "Last &gt;&gt;";
        //config for bootstrap pagination class integration
        $config['full_tag_open'] = '<ul class="pagination" id="ajax_pagingsearc' . $idNum . '">';
        $config['full_tag_close'] = '</ul>';
        $config['first_link'] = false;
        $config['last_link'] = false;
        $config['first_tag_open'] = '<li>';
        $config['first_tag_close'] = '</li>';
        $config['prev_link'] = '&laquo';
        $config['prev_tag_open'] = '<li class="prev1">';
        $config['prev_tag_close'] = '</li>';
        $config['next_link'] = '&raquo';
        $config['next_tag_open'] = '<li>';
        $config['next_tag_close'] = '</li>';
        $config['last_tag_open'] = '<li>';
        $config['last_tag_close'] = '</li>';
        $config['cur_tag_open'] = '<li class="active"><a href="#">';
        $config['cur_tag_close'] = '</a></li>';
        $config['num_tag_open'] = '<li>';
        $config['num_tag_close'] = '</li>';
        $this->pagination->initialize($config);
        //load the department_view

        return $this->pagination->create_links();
    }

    //Function Name 		:	addNewCustomer.
    //Function Parameters 	:	pass user detail array.
    //Function working 		:	Function using for add new customers.
    //Function Output 		:	Return true and false (true if data inserted and false if not inserted);
    //Used View				:	customer/addCustomer;

    public function addNewCustomer() {
        $output = '';
        if ($_POST) {
            $this->form_validation->set_rules('namePrefix', 'Salutation', 'required');
            $this->form_validation->set_rules('firstName', 'First Name', 'required');
            $this->form_validation->set_rules('gender', 'Gender', 'required');
            $this->form_validation->set_rules('mobileNumber', 'Mobile Number', 'required');
            if ($this->form_validation->run()) {
                if ($_POST['password']) {
                    $salt = mcrypt_create_iv(22, MCRYPT_DEV_URANDOM);
                    $salt = base64_encode($salt);
                    $salt = str_replace('+', '.', $salt);
                    $hash = crypt($_POST['password'], '$2y$10$' . $salt . '$');
                } else {
                    $hash = "";
                }

                if ($_POST['dob']) {
                    $_POST['dob'] = date('Y-m-d', strtotime($_POST['dob']));
                }

                $_POST['userType'] = 'customer';
                $_POST['password'] = $hash;
                $_POST['createDate'] = date('Y-m-d H:i:s');
                $shippingDetail['shipToContact'] = $_POST['shipToContact'];
                $shippingDetail['ShipPhone'] = $_POST['ShipPhone'];
                $shippingDetail['ShipAddress'] = $_POST['ShipAddress'];
                $shippingDetail['ShipPostalCode'] = $_POST['ShipPostalCode'];
                $shippingDetail['ShipCity'] = $_POST['ShipCity'];
                $shippingDetail['shipCountryId'] = $_POST['shipCountryId'];
                $shippingDetail['ShipProvince'] = $_POST['ShipProvince'];

                $shippingDetail['billingAddress'] = $_POST['billingAddress'];
                $shippingDetail['billingPostalCode'] = $_POST['billingPostalCode'];
                $shippingDetail['billingCountry'] = $_POST['billingCountry'];
                $shippingDetail['billingProvince'] = $_POST['billingProvince'];
                $shippingDetail['Billingcity'] = $_POST['Billingcity'];

                unset($_POST['dobDay']);
                unset($_POST['dobMonth']);
                unset($_POST['dobyear']);
                unset($_POST['shipToContact']);
                unset($_POST['ShipPhone']);
                unset($_POST['ShipAddress']);
                unset($_POST['ShipPostalCode']);
                unset($_POST['ShipCity']);
                unset($_POST['shipCountryId']);
                unset($_POST['ShipProvince']);

                unset($_POST['bilingAddress']);
                unset($_POST['billingPostalCode']);
                unset($_POST['billingCountry']);
                unset($_POST['billingProvince']);
                unset($_POST['Billingcity']);

                $customer = $this->customer->checkCustomer($_POST['mobileNumber']);
                if (!empty($customer)) {
                    $_POST['insert'] = 'insert';
                    $_POST['userRef'] = $customer->userRef;
                    $output['queryResult'] = $this->customer->updateCustomer($_POST, $shippingDetail);
                } else {
                    $output['queryResult'] = $this->customer->addCustomerDetail($_POST, $shippingDetail);
                }
                $alertmsg = '<div class="ajax_report alert-message alert alert-success updatecartDetail" role="alert">
						<button type="button" class="close closeCart"  aria-label="Close"><span aria-hidden="true">&times;</span></button>
						<span class="ajax_message updateclientmessage">Customer added successfully.</span> 
					</div>';
                $this->session->set_flashdata("customermessage", $alertmsg);
                redirect(site_url() . 'customer');
            } else {
                $errors = validation_errors();
                $output['error_message'] = $errors;
            }
        }
        $this->load->view('customer/addCustomer', $output);
    }

    //Function Name 		:	getCustomerDetailByName.
    //Function Parameters 	:	pass alphabet.
    //Function working 		:	Function using for sorting customers by alphabet.
    //Function Output 		:	Return customer records array;
    //Used View				:	customer/searchCustomerData;


    public function getCustomerDetailByName() {
        $data['page'] = ($this->uri->segment(2)) ? $this->uri->segment(2) : 0;
        $response = $this->customer->searchCustomerDetails($_POST['lowerCase'], $_POST['upperCase'], $this->perPageNum, $data['page']);
        $data['customerList'] = $response['data'];
        $resultCount = $response['totalRecords'];
        $data['links'] = $this->getPagination($this->perPageNum, $resultCount, site_url() . 'getCustomerDetailByName', 1, $data['page']);
        if ($this->input->post('ajax')) {
            $this->load->view('customer/searchCustomerData', $data);
        } else {
            $this->load->view('customer/searchCustomerData', $data);
        }
    }

    //Function Name 		:	searchCustomerList.
    //Function Parameters 	:	pass alphabets.
    //Function working 		:	Function using for sorting customers by alphabet.
    //Function Output 		:	Return customer records array;
    //Used View			:	customer/searchCustomerData;


    public function searchCustomerList() {
        $response = $this->customer->searchCustomerDetails($_POST['lowerCase'], $_POST['upperCase'], $this->perPageNum, $data['page']);
        $data['customerList'] = $response['data'];
        $resultCount = $response['totalRecords'];
        $data['links'] = $this->getPagination($this->perPageNum, $resultCount, site_url() . 'searchCustomerList', 1, $data['page']);
        if ($this->input->post('ajax')) {

            $this->load->view('customer/searchCustomerData', $data);
        } else {
            $this->load->view('customer/searchCustomerData', $data);
        }
    }

    public function setToDefault() {
        $data['page'] = ($this->uri->segment(2)) ? $this->uri->segment(2) : 0;
        $response = $this->customer->getCustomerDetail($this->perPageNum, $data['page']);
        $data['customerList'] = $response['data'];
        $resultCount = $response['totalRecords'];
        $data['links'] = $this->getPagination($this->perPageNum, $resultCount, site_url() . 'customer', 0, $data['page']);
        $this->load->view('customer/searchCustomerData', $data);
    }

    //Function Name 		:	getCustomerDetailByNum.
    //Function Parameters 	:	pass record limit.
    //Function working 		:	Function using for sorting customers by number.
    //Function Output 		:	Return customer records array;
    //Used View				:	customer/searchCustomerData;

    public function getCustomerDetailByNum() {
        $data['page'] = ($this->uri->segment(2)) ? $this->uri->segment(2) : 0;
        $response = $this->customer->getCustomerDetail($_POST['currentVal'], $data['page']);
        $data['customerList'] = $response['data'];
        $resultCount = $response['totalRecords'];
        $data['links'] = $this->getPagination($_POST['currentVal'], $resultCount, site_url() . 'getCustomerDetailByNum', 2, $data['page']);
        if ($this->input->post('ajax')) {
            $this->load->view('customer/searchCustomerData', $data);
        } else {
            $this->load->view('customer/searchCustomerData', $data);
        }
    }

    //Function Name 		:	getCustomerDetailByRef.
    //Function Parameters 	:	pass customer ref number.
    //Function working 		:	Function using for geting customer detail by customer ref.
    //Function Output 		:	Return customer records array;

    public function getCustomerDetailByRef() {
        $result = $this->customer->getCustomerByRef($_POST['CurrentCustomerId']);
        echo json_encode($result);
        exit;
    }

    //Function Name 		:	updateCustomerDetailByRef.
    //Function Parameters 	:	pass customer ref id.
    //Function working 		:	Function using for update customer PERSONAL detail by customer ref.
    //Function Output 		:	Return true and false;



    public function updateCustomerDetailByRef() {
        $result = [];
        if (array_key_exists("password", $_POST['accountDetail'])) {
            if ($_POST['accountDetail']['password'] != '') {
                $salt = mcrypt_create_iv(22, MCRYPT_DEV_URANDOM);
                $salt = base64_encode($salt);
                $salt = str_replace('+', '.', $salt);
                $hash = crypt($_POST['accountDetail']['password'], '$2y$10$' . $salt . '$');
                $_POST['accountDetail']['password'] = $hash;
            }
        }

        $returnVal = $this->customer->updateCustomer($_POST['userDetail'], $_POST['billShipDetail']);
        $result['total'] = $returnVal[0];
        $result['total1'] = $returnVal[1];
        $result['total2'] = $returnVal[2];
        echo json_encode($result);
        exit;
    }

    //Function Name 		:	updateCustomerAccountDetail.
    //Function Parameters 	:	pass customer ref id.
    //Function working 		:	Function using for update customer ACCOUNT detail by customer ref.
    //Function Output 		:	Return true and false;


    public function updateCustomerAccountDetail() {
        $result = $this->customer->updateCustomerAccount($_POST['userDetail']);
        echo json_encode($result);
        exit;
    }

    //Function Name 		:	updateCustomerDeleteInfo.
    //Function Parameters 	:	pass customer ref id.
    //Function working 		:	Function using for delete customer ACCOUNT detail by customer ref.
    //Function Output 		:	Return true and false;


    public function updateCustomerDeleteInfo() {
        $result = $this->customer->updateCustomerAccountDelete($_POST['currentVal']);
        echo json_encode($result);
        exit;
    }

    //Function Name 		:	checkEmailValidation.
    //Function working 		:	Function using for unique email id validation .
    //Function Output 		:	Return true and false;

    public function checkEmailValidation() {
        $output = $this->customer->emailValidationCheck($_POST['emailVal'], $_POST['userRef'], $_POST['userType']);
        echo json_encode($output);
        exit;
    }

    //Function Name 		:	checkMobileValidation.
    //Function working 		:	Function using for unique mobile number validation .
    //Function Output 		:	Return true and false;
    public function checkMobileValidation() {
        $output = $this->customer->checkMobileValidation($_POST['mobileNumber'], $_POST['userRef'], $_POST['userType']);
        echo json_encode($output);
        exit;
    }

    //Function Name 		:	checkUsernameValidation.
    //Function working 		:	Function using for unique username validation .
    //Function Output 		:	Return true and false;
    public function checkUsernameValidation() {
        $output = $this->customer->userNameValidationCheck($_POST['emailVal']);
        echo json_encode($output);
        exit;
    }

    //Function Name 		:	checkusernamenameValidationUpdate.
    //Function working 		:	Function using for update customer unique username validation .
    //Function Output 		:	Return true and false;
    public function checkusernamenameValidationUpdate() {
        $output = '';
        if ($_POST['emailVal'] != '') {
            $output = $this->customer->checkusernamenameValidationUpdate($_POST['emailVal'], $_POST['userRef']);
        }
        echo json_encode($output);
        exit;
    }

    //Function Name 		:	checkEmailUpdate.
    //Function working 		:	Function using for update customer unique email id validation .
    //Function Output 		:	Return true and false;
    public function checkEmailUpdate() {
        $output = $this->customer->checkemailValidationUpdate($_POST['emailVal'], $_POST['customerId'], $_POST['userType']);
        echo json_encode($output);
        exit;
    }

    public function sentServiceMail() {
        $output = $this->customer->checkServiceDate();

        foreach ($output as $value) {
            $serviceCycle = $value->serviceCycle;
            $orderRef = $value->orderRef;
            /* switch ($serviceCycle) {
              case '1month':
              $time = 1;
              $validDate = date('Y-m-d', strtotime('+1 month', strtotime($value->serviceDate)));
              break;
              case '3month':
              $time = 3;
              $validDate =date('Y-m-d', strtotime('+3 month', strtotime($value->serviceDate)));
              break;
              case '6month':
              $time = 6;
              $validDate = date('Y-m-d', strtotime('+6 month', strtotime($value->serviceDate)));
              break;
              case '1.0':
              $time = 12;
              $validDate = date('Y-m-d', strtotime('+1 year', strtotime($value->serviceDate)));
              break;
              } */

            $emailUser = $this->customer->getUserEmail($orderRef);
            $row['serviceName'] = $value->serviceName;
            $row['serviceDate'] = $value->serviceDate;
            $row['serviceImage'] = $value->serviceImage;
            $row['customerName'] = $emailUser[0]->firstName . ' ' . $emailUser[0]->lastName;
            $row['emailId'] = $emailUser[0]->email;
            $row['serviceCycle'] = $serviceCycle;

            $from_email = "noreply@acma.sg";
            $to_email = $emailUser[0]->email;
            $this->email->from($from_email, 'ACMA ENGINEERING WORKS & TRADING PTE LTD');
            $this->email->to($to_email);
            $this->email->subject('Service Cycle');
            $this->email->set_mailtype('html');
            $body = $this->load->view('service/template', $row, TRUE);
            $this->email->message($body);
            $this->email->send();
        }
    }

    /*
     * Function Name 	: customerRefSession
     * Function Working : Set customer userRef session for view customer order list
     */

    public function customerRefSession() {
        $this->session->set_userdata('orderCustomerRefId', $this->input->post('userRef'));
        return true;
    }

    /*
     * Function Name : customerRefSessionDestroy
     * Function Working : Destroy customer userRef and show all customer order list
     */

    public function customerRefSessionDestroy() {
        if ($this->session->userdata('orderCustomerRefId')) {
            $this->session->unset_userdata('orderCustomerRefId');
        }
        return true;
    }

    /*
     * Function Name : searchCustomerDetails
     * Function Working: Dashboard Search Customer Details
     */

    public function searchCustomerDetails($userRef) {
        $data['customer'] = $this->customer->getCustomerByRef($userRef);
        $this->load->view('customer/detailCustomer', $data);
    }

}
