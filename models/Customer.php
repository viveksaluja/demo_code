<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Customer extends CI_Model {

    public function Customer() {
        parent::__construct();
    }

    public function addCustomerDetail($userDetail, $shipdetail) {

        $length = 8;
        $randomString = substr(str_shuffle("0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ"), 0, $length);
        $randomString1 = substr(str_shuffle("0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ"), 0, $length);
        $randomString = $randomString . $randomString1;
        $userId = $this->session->userdata('userId');
        $salonRef = $this->session->userdata('salonRef');

        $this->db->select('aws_customerInSalon.customerRefId,aws_users.id,aws_users.userRef');
        $this->db->from('aws_customerInSalon');
        $this->db->join('aws_users', 'aws_users.userRef=aws_customerInSalon.customerRefId', 'inner');
        $this->db->where('aws_users.userType', $userDetail['userType']);
        $this->db->where('aws_customerInSalon.salonRefId', $salonRef);
        if ($userDetail['emailId'] != "") {
            $this->db->group_start();
            $this->db->where('AES_DECRYPT(mobileNumber,"/*awshp$*/")', $userDetail['mobileNumber']);
            $this->db->or_where('AES_DECRYPT(emailId,"/*awshp$*/")', $userDetail['emailId']);
            $this->db->group_end();
        } else {
            $this->db->where('AES_DECRYPT(mobileNumber,"/*awshp$*/")', $userDetail['mobileNumber']);
        }
        $output = $this->db->get();
        $output = $output->result();
        if (count($output) > 0) {
            $msg = 'Customer With  Email Id or Mobile Number Already Exist';
            return $msg;
        }

        $this->db->select('max(LPAD(`uniqueId`+1, 3, "0")) as nextNumber');
        $this->db->from('aws_users');
        $output = $this->db->get();
        $output = $output->result();
        $nextNumber = $output[0]->nextNumber;
        if ($nextNumber == NULL) {
            $nextNumber = '001';
        }
        $this->db->set('userRef', $randomString);
        $this->db->set('uniqueId', $nextNumber);
        if ($userDetail['userName']) {
            $this->db->set('userName', "AES_ENCRYPT('{$userDetail['userName']}','/*awshp$*/')", FALSE);
        }
        if ($userDetail['emailId']) {
            $this->db->set('emailId', "AES_ENCRYPT('{$userDetail['emailId']}','/*awshp$*/')", FALSE);
        }
        $this->db->set('mobileNumber', "AES_ENCRYPT('{$userDetail['mobileNumber']}','/*awshp$*/')", FALSE);
        $this->db->set('password', $userDetail['password']);
        $this->db->set('userType', $userDetail['userType']);
        $this->db->set('createDate', $userDetail['createDate']);
        $this->db->set('ipAddress', $_SERVER['REMOTE_ADDR']);
        $this->db->set('status', '1');
        $this->db->insert('aws_users');

        $this->db->set('namePrefix', $userDetail['namePrefix']);
        $this->db->set('firstName', "AES_ENCRYPT('{$userDetail['firstName']}','/*awshp$*/')", FALSE);
        if (isset($userDetail['lastName'])) {
            $this->db->set('lastName', "AES_ENCRYPT('{$userDetail['lastName']}','/*awshp$*/')", FALSE);
        }
        if ($userDetail['dob']) {
            $this->db->set('dob', "AES_ENCRYPT('{$userDetail['dob']}','/*awshp$*/')", FALSE);
        }
        $this->db->set('gender', $userDetail['gender']);
        $this->db->set('userRef', $randomString);
        $this->db->set('status', '1');
        $this->db->insert('aws_userProfile');

        $this->db->set('billingAddress', "AES_ENCRYPT('{$shipdetail['billingAddress']}','/*awshp$*/')", FALSE);
        $this->db->set('billingPostalCode', $shipdetail['billingPostalCode']);
        $this->db->set('billingCountry', $shipdetail['billingCountry']);
        $this->db->set('billingProvince', $shipdetail['billingProvince']);
        $this->db->set('Billingcity', $shipdetail['Billingcity']);
        $this->db->set('shipToContact', "AES_ENCRYPT('{$shipdetail['shipToContact']}','/*awshp$*/')", FALSE);
        $this->db->set('ShipPhone', $shipdetail['ShipPhone']);
        $this->db->set('ShipAddress', "AES_ENCRYPT('{$shipdetail['ShipAddress']}','/*awshp$*/')", FALSE);
        $this->db->set('ShipPostalCode', $shipdetail['ShipPostalCode']);
        $this->db->set('ShipCity', $shipdetail['ShipCity']);
        $this->db->set('shipCountryId', $shipdetail['shipCountryId']);
        $this->db->set('ShipProvince', $shipdetail['ShipProvince']);
        $this->db->set('customerRef', $randomString);
        $this->db->set('status', '1');
        $this->db->insert('aws_billingShippingAddress');

        $this->db->set('customerRefId', $randomString);
        $this->db->set('salonRefId', $salonRef);
        $this->db->set('addedOn', date('Y-m-d H:i:s'));
        $this->db->set('addedBy', $userId);
        $this->db->insert('aws_customerInSalon');

        $msg = 'Customer Created Successfully';
        return $msg;
    }

    public function getCustomerDetailOld($limit, $start) {
        $userId = $this->session->userdata('userId');
        $usertype = $this->session->userdata('userType');
        $salonRef = $this->session->userdata('salonRef');
        $this->db->select('aws_users.userRef,aws_users.salonRefId,AES_DECRYPT(aws_users.userName,"/*awshp$*/") as userName,AES_DECRYPT(aws_users.emailId,"/*awshp$*/") as emailId,AES_DECRYPT(aws_users.mobileNumber,"/*awshp$*/") as mobileNumber,AES_DECRYPT(aws_userProfile.firstName,"/*awshp$*/") as firstName,AES_DECRYPT(aws_userProfile.lastName,"/*awshp$*/") as lastName,AES_DECRYPT(aws_salon.salonName,"/*awshp$*/") as salonName,count(aws_customerOrder.orderRef) totalOrder');
        $this->db->from('aws_users');
        $this->db->join('aws_userProfile', 'aws_userProfile.userRef = aws_users.userRef', 'inner');
        $this->db->join('aws_customerOrder', 'aws_customerOrder.customerRefId = aws_users.userRef', 'left');
        $this->db->join('aws_salon', 'aws_salon.salonRef = aws_users.salonRefId', 'inner');
        $this->db->where('aws_users.status', '1');
        $this->db->where('aws_users.userType', 'customer');
        $this->db->where('aws_users.salonRefId !=""');
        $this->db->where('aws_salon.salonRef', $salonRef);
        //  $this->db->where('aws_salon.salonStatus', '1');
        $this->db->order_by('aws_userProfile.id DESC');
        $tempdb = clone $this->db;
        $num_results = $tempdb->count_all_results();
        $this->db->limit($limit, $start);
        $output = $this->db->get();
        $output = $output->result();
        $response['data'] = $output;
        $response['totalRecords'] = $num_results;
        return $response;
    }

    public function getCustomerDetail($limit, $start) {
        $userId = $this->session->userdata('userId');
        $usertype = $this->session->userdata('userType');
        $salonRef = $this->session->userdata('salonRef');
        $this->db->select('aws_customerInSalon.customerRefId,aws_customerInSalon.salonRefId,aws_users.userRef as customerRef,aws_users.userRef,aws_users.userActive,aws_users.status,AES_DECRYPT(aws_users.emailId,"/*awshp$*/") as emailId,AES_DECRYPT(aws_users.mobileNumber,"/*awshp$*/") as mobileNumber,AES_DECRYPT(aws_userProfile.firstName,"/*awshp$*/") as firstName,( select count(`id`) from aws_customerOrder where `customerRefId` = `customerRef` and `salonRefId` = "' . $salonRef . '") as totalOrder');
        $this->db->from('aws_customerInSalon');
        $this->db->join('aws_users', 'aws_users.userRef = aws_customerInSalon.customerRefId', 'inner');
        $this->db->join('aws_userProfile', 'aws_userProfile.userRef = aws_users.userRef', 'inner');
        //  $this->db->where('aws_users.status', '1');
        $this->db->where('aws_users.userType', 'customer');
        $this->db->where('aws_customerInSalon.salonRefId', $salonRef);
        $this->db->order_by('aws_users.id DESC');
        $this->db->group_by('aws_customerInSalon.id');
        $tempdb = clone $this->db;
        $num_results = $tempdb->count_all_results();
        $this->db->limit($limit, $start);
        $query = $this->db->get();
        $result = $query->result_array();
        $array = array();
        foreach ($result as $val) {
            $array[] = $val['userRef'];
        }

        $this->db->select('aws_customerOrder.customerRefId,aws_users.userRef,aws_users.userRef as customerRef,aws_customerOrder.salonRefId,aws_users.userActive,aws_users.status,AES_DECRYPT(aws_userProfile.firstName,"/*awshp$*/") as firstName,AES_DECRYPT(aws_users.emailId,"/*awshp$*/") as emailId,AES_DECRYPT(aws_users.mobileNumber,"/*awshp$*/") as mobileNumber,( select count(`orderRef`) from aws_customerOrder where `customerRefId` = `customerRef` and `salonRefId` = "' . $salonRef . '") as totalOrder');
        $this->db->from('aws_customerOrder');
        $this->db->join('aws_users', 'aws_users.userRef=aws_customerOrder.customerRefId', 'left');
        $this->db->join('aws_userProfile', 'aws_userProfile.userRef=aws_customerOrder.customerRefId', 'left');
        $this->db->where('aws_customerOrder.salonRefId', $salonRef);
        // $this->db->where('aws_users.userActive!=', '0');
        if (!empty($array)) {
            $this->db->where_not_in('aws_customerOrder.customerRefId', $array);
        }
        $this->db->group_by('aws_customerOrder.customerRefId');
        $this->db->limit($limit, $start);
        $query1 = $this->db->get();
        $result1 = $query1->result_array();

        $this->db->select('aws_customerOrder.customerRefId,aws_users.userRef');
        $this->db->from('aws_customerOrder');
        $this->db->join('aws_users', 'aws_users.userRef=aws_customerOrder.customerRefId', 'left');
        $this->db->where('aws_customerOrder.salonRefId', $salonRef);
        // $this->db->where('aws_users.userActive!=', '0');
        if (!empty($array)) {
            $this->db->where_not_in('aws_customerOrder.customerRefId', $array);
        }
        $this->db->group_by('customerRefId');
        $query2 = $this->db->get();
        $result2 = $query1->result_array();
        $num_results1 = count($result2);

        $result = array_merge($result, $result1);

        $response['data'] = $result;
        $response['totalRecords'] = $num_results + $num_results1;

        return $response;
    }

    public function searchCustomerDetails($detailLower, $detailUpper, $limit, $start) {
        $detailUpper = ucwords($detailUpper);

        $userId = $this->session->userdata('userId');
        $usertype = $this->session->userdata('userType');
        $salonRef = $this->session->userdata('salonRef');
        $this->db->select('aws_customerInSalon.salonRefId,aws_users.userRef,aws_users.userActive,aws_users.status,aws_users.userRef as customerRef,aws_users.userRef as customerRefId,AES_DECRYPT(aws_users.emailId,"/*awshp$*/") as emailId,AES_DECRYPT(aws_users.mobileNumber,"/*awshp$*/") as mobileNumber,AES_DECRYPT(aws_userProfile.firstName,"/*awshp$*/") as firstName,( select count(`id`) from aws_customerOrder where `customerRefId` = `customerRef` and `salonRefId` = "' . $salonRef . '") as totalOrder');
        $this->db->from('aws_customerInSalon');
        $this->db->join('aws_users', 'aws_users.userRef = aws_customerInSalon.customerRefId', 'inner');
        $this->db->join('aws_userProfile', 'aws_userProfile.userRef = aws_users.userRef', 'inner');
        $this->db->group_start();
        $this->db->like('AES_DECRYPT(aws_userProfile.firstName,"/*awshp$*/")', $detailLower, 'after');
        $this->db->or_like('AES_DECRYPT(aws_userProfile.firstName,"/*awshp$*/")', $detailUpper, 'after');
        $this->db->or_like('AES_DECRYPT(aws_userProfile.firstName,"/*awshp$*/")', strtolower($detailLower), 'after');
        $this->db->or_like('AES_DECRYPT(aws_userProfile.firstName,"/*awshp$*/")', strtoupper($detailLower), 'after');
        $this->db->or_like('AES_DECRYPT(aws_userProfile.firstName,"/*awshp$*/")', ucfirst($detailLower), 'after');
        $this->db->or_like('AES_DECRYPT(aws_users.emailId,"/*awshp$*/")', $detailUpper);
        $this->db->or_like('AES_DECRYPT(aws_users.emailId,"/*awshp$*/")', $detailLower);
        $this->db->or_like('AES_DECRYPT(aws_users.mobileNumber,"/*awshp$*/")', $detailLower);
        $this->db->group_end();
        //  $this->db->where('aws_users.status', '1');
        // $this->db->where('aws_users.userActive!=', '0');
        $this->db->where('aws_users.userType', 'customer');
        $this->db->where('aws_customerInSalon.salonRefId', $salonRef);
        $this->db->order_by('aws_users.id DESC');
        $this->db->group_by('aws_users.id');
        $tempdb = clone $this->db;
        $num_results = $tempdb->count_all_results();
        $this->db->limit($limit, $start);
        $query = $this->db->get();
        $result = $query->result_array();

        $array = array();
        foreach ($result as $val) {
            $array[] = $val['customerRefId'];
        }

        $this->db->select('aws_customerOrder.customerRefId,aws_customerOrder.salonRefId,aws_users.userActive,aws_users.status,AES_DECRYPT(aws_userProfile.firstName,"/*awshp$*/") as firstName,AES_DECRYPT(aws_users.emailId,"/*awshp$*/") as emailId,AES_DECRYPT(aws_users.mobileNumber,"/*awshp$*/") as mobileNumber,aws_users.userRef,( select count(`orderRef`) from aws_customerOrder where `customerRefId` = `customerRefId` and `salonRefId` = "' . $salonRef . '") as totalOrder');
        $this->db->from('aws_customerOrder');
        $this->db->join('aws_users', 'aws_users.userRef=aws_customerOrder.customerRefId', 'left');
        $this->db->join('aws_userProfile', 'aws_userProfile.userRef=aws_customerOrder.customerRefId', 'left');
        $this->db->group_start();
        $this->db->like('AES_DECRYPT(aws_userProfile.firstName,"/*awshp$*/")', $detailLower, 'after');
        $this->db->or_like('AES_DECRYPT(aws_userProfile.firstName,"/*awshp$*/")', strtolower($detailLower), 'after');
        $this->db->or_like('AES_DECRYPT(aws_userProfile.firstName,"/*awshp$*/")', strtoupper($detailLower), 'after');
        $this->db->or_like('AES_DECRYPT(aws_userProfile.firstName,"/*awshp$*/")', ucfirst($detailLower), 'after');
        $this->db->or_like('AES_DECRYPT(aws_userProfile.firstName,"/*awshp$*/")', $detailUpper, 'after');
        $this->db->or_like('AES_DECRYPT(aws_users.emailId,"/*awshp$*/")', $detailUpper, 'after');
        $this->db->or_like('AES_DECRYPT(aws_users.emailId,"/*awshp$*/")', $detailLower, 'after');
        $this->db->or_like('AES_DECRYPT(aws_users.mobileNumber,"/*awshp$*/")', $detailLower);
        $this->db->group_end();
        $this->db->where('aws_customerOrder.salonRefId', $salonRef);
        if (!empty($array)) {
            $this->db->where_not_in('aws_customerOrder.customerRefId', $array);
        }
        $this->db->group_by('aws_customerOrder.customerRefId');
        $this->db->limit($limit, $start);
        $query1 = $this->db->get();
        $result1 = $query1->result_array();

        $this->db->select('aws_customerOrder.customerRefId,aws_customerOrder.salonRefId,aws_users.userActive,aws_users.status,AES_DECRYPT(aws_userProfile.firstName,"/*awshp$*/") as firstName,AES_DECRYPT(aws_users.emailId,"/*awshp$*/") as emailId,AES_DECRYPT(aws_users.mobileNumber,"/*awshp$*/") as mobileNumber,aws_users.userRef,( select count(`orderRef`) from aws_customerOrder where `customerRefId` = `customerRefId` and `salonRefId` = "' . $salonRef . '") as totalOrder');
        $this->db->from('aws_customerOrder');
        $this->db->join('aws_users', 'aws_users.userRef=aws_customerOrder.customerRefId', 'left');
        $this->db->join('aws_userProfile', 'aws_userProfile.userRef=aws_customerOrder.customerRefId', 'left');
        $this->db->group_start();
        $this->db->like('AES_DECRYPT(aws_userProfile.firstName,"/*awshp$*/")', $detailLower, 'after');
        $this->db->or_like('AES_DECRYPT(aws_userProfile.firstName,"/*awshp$*/")', strtolower($detailLower), 'after');
        $this->db->or_like('AES_DECRYPT(aws_userProfile.firstName,"/*awshp$*/")', strtoupper($detailLower), 'after');
        $this->db->or_like('AES_DECRYPT(aws_userProfile.firstName,"/*awshp$*/")', ucfirst($detailLower), 'after');
        $this->db->or_like('AES_DECRYPT(aws_userProfile.firstName,"/*awshp$*/")', $detailUpper, 'after');
        $this->db->or_like('AES_DECRYPT(aws_users.emailId,"/*awshp$*/")', $detailUpper, 'after');
        $this->db->or_like('AES_DECRYPT(aws_users.emailId,"/*awshp$*/")', $detailLower, 'after');
        $this->db->or_like('AES_DECRYPT(aws_users.mobileNumber,"/*awshp$*/")', $detailLower);
        $this->db->group_end();
        $this->db->where('aws_customerOrder.salonRefId', $salonRef);
        if (!empty($array)) {
            $this->db->where_not_in('aws_customerOrder.customerRefId', $array);
        }
        $this->db->group_by('aws_customerOrder.customerRefId');
        $query2 = $this->db->get();
        $result2 = $query2->result_array();

        $num_results1 = count($result2);

        $result = array_merge($result, $result1);

        $response['data'] = $result;
        $response['totalRecords'] = $num_results + $num_results1;
        return $response;
    }

    public function getCustomerByRef($detail) {
        $salonRef = $this->session->userdata('salonRef');
        $this->db->select('aws_customerInSalon.salonRefId,AES_DECRYPT(aws_userProfile.dob,"/*awshp$*/") as dob,aws_users.uniqueId,aws_users.userRef,aws_users.userActive,aws_users.status,aws_users.userRef as customerRef,AES_DECRYPT(aws_users.userName,"/*awshp$*/") as userName,AES_DECRYPT(aws_users.emailId,"/*awshp$*/") as emailId,AES_DECRYPT(aws_users.mobileNumber,"/*awshp$*/") as mobileNumber,AES_DECRYPT(aws_userProfile.firstName,"/*awshp$*/") as firstName,AES_DECRYPT(aws_userProfile.lastName,"/*awshp$*/") as lastName,aws_userProfile.namePrefix,aws_userProfile.gender,AES_DECRYPT(aws_billingShippingAddress.billingAddress,"/*awshp$*/") as billingAddress,aws_billingShippingAddress.billingPostalCode,aws_billingShippingAddress.billingCountry,aws_billingShippingAddress.billingProvince,aws_billingShippingAddress.Billingcity,AES_DECRYPT(aws_billingShippingAddress.shipToContact,"/*awshp$*/") as shipToContact,aws_billingShippingAddress.ShipPhone,AES_DECRYPT(aws_billingShippingAddress.ShipAddress,"/*awshp$*/") as ShipAddress,aws_billingShippingAddress.ShipPostalCode,aws_billingShippingAddress.ShipCity,aws_billingShippingAddress.shipCountryId,aws_billingShippingAddress.ShipProvince,AES_DECRYPT(aws_salon.salonName,"/*awshp$*/") as salonName,( select count(`id`) from aws_customerOrder where `customerRefId` = `customerRef` and `salonRefId` = "' . $salonRef . '") as totalOrder');
        $this->db->from('aws_customerInSalon');
        $this->db->join('aws_users', 'aws_users.userRef = aws_customerInSalon.customerRefId', 'left');
        $this->db->join('aws_userProfile', 'aws_userProfile.userRef = aws_users.userRef', 'left');
        $this->db->join('aws_customerOrder', 'aws_customerOrder.customerRefId = aws_users.userRef', 'left');
        $this->db->join('aws_salon', 'aws_users.salonRefId = aws_salon.salonRef', 'left');
        $this->db->join('aws_billingShippingAddress', 'aws_billingShippingAddress.customerRef = aws_users.userRef', 'left');
        $this->db->where('aws_customerInSalon.salonRefId', $salonRef);
        $this->db->where('aws_users.userRef', $detail);
        //  $this->db->where('aws_users.status', '1');
        $this->db->group_by('aws_users.id');
        $output = $this->db->get();
        $output = $output->result();
        return $output;
    }

    public function updateCustomer($updateDetail, $billShipDetail) {
        $salonRef = $this->session->userdata('salonRef');
        $userId = $this->session->userdata('userId');
        $this->db->set('mobileNumber', "AES_ENCRYPT('{$updateDetail['mobileNumber']}','/*awshp$*/')", FALSE);
        if ($updateDetail['emailId']) {
            $this->db->set('emailId', "AES_ENCRYPT('{$updateDetail['emailId']}','/*awshp$*/')", FALSE);
        }
        $this->db->where('userRef', $updateDetail['userRef']);
        $this->db->update('aws_users');
        $total = $this->db->affected_rows();

        if ($updateDetail['dob']) {
            $dob = date('Y-m-d', strtotime($updateDetail['dob']));
        }
        $this->db->set('namePrefix', $updateDetail['namePrefix']);
        $this->db->set('firstName', "AES_ENCRYPT('{$updateDetail['firstName']}','/*awshp$*/')", FALSE);
        if (isset($updateDetail['lastName'])) {
            $this->db->set('lastName', "AES_ENCRYPT('{$updateDetail['lastName']}','/*awshp$*/')", FALSE);
        }
        if (isset($dob)) {
            $this->db->set('dob', "AES_ENCRYPT('{$dob}','/*awshp$*/')", FALSE);
        }
        $this->db->set('gender', $updateDetail['gender']);
        $this->db->where('userRef', $updateDetail['userRef']);
        $this->db->update('aws_userProfile');

        $total1 = $this->db->affected_rows();

        $this->db->set('billingAddress', "AES_ENCRYPT('{$billShipDetail['billingAddress']}','/*awshp$*/')", FALSE);
        $this->db->set('billingPostalCode', $billShipDetail['billingPostalCode']);
        $this->db->set('billingCountry', $billShipDetail['billingCountry']);
        $this->db->set('billingProvince', $billShipDetail['billingProvince']);
        $this->db->set('Billingcity', $billShipDetail['Billingcity']);
        $this->db->set('shipToContact', "AES_ENCRYPT('{$billShipDetail['shipToContact']}','/*awshp$*/')", FALSE);
        $this->db->set('ShipPhone', $billShipDetail['ShipPhone']);
        $this->db->set('ShipAddress', "AES_ENCRYPT('{$billShipDetail['ShipAddress']}','/*awshp$*/')", FALSE);
        $this->db->set('ShipPostalCode', $billShipDetail['ShipPostalCode']);
        $this->db->set('ShipCity', $billShipDetail['ShipCity']);
        $this->db->set('shipCountryId', $billShipDetail['shipCountryId']);
        $this->db->set('ShipProvince', $billShipDetail['ShipProvince']);
        $this->db->where('customerRef', $updateDetail['userRef']);
        $this->db->update('aws_billingShippingAddress');

        if (isset($updateDetail['insert'])) {
            $this->db->set('customerRefId', $updateDetail['userRef']);
            $this->db->set('salonRefId', $salonRef);
            $this->db->set('addedOn', date('Y-m-d H:i:s'));
            $this->db->set('addedBy', $userId);
            $this->db->insert('aws_customerInSalon');
        }

        $total2 = $this->db->affected_rows();
        return array($total, $total1, $total2);
    }

    public function updateCustomerAccount($accoutnDetail) {
        $where = "userRef !='" . $accoutnDetail['userRef'] . "' AND AES_DECRYPT(userName,'/*awshp$*/')='" . $accoutnDetail['userName'] . "'";
        $this->db->select('userRef');
        $this->db->from('aws_users');
        $this->db->where($where);
        $output = $this->db->get();
        $output = $output->result();
        if (count($output) > 0) {
            $msg = false;
            return $msg;
        } else {
//if(array_key_exists('password',$accoutnDetail)){
            if ($_POST['accountDetail']['password'] != '') {
                $salt = mcrypt_create_iv(22, MCRYPT_DEV_URANDOM);
                $salt = base64_encode($salt);
                $salt = str_replace('+', '.', $salt);
                $hash = crypt('123456', '$2y$10$' . $salt . '$');
                $this->db->set('userName', "AES_ENCRYPT('{$accoutnDetail['userName']}','/*awshp$*/')", FALSE);
                $this->db->set('password', $hash);
                $this->db->where('userRef', $accoutnDetail['userRef']);
                $this->db->update('aws_users');
                $msg = true;
                return $msg;
            } else {
                $this->db->set('userName', "AES_ENCRYPT('{$accoutnDetail['userName']}','/*awshp$*/')", FALSE);
                $this->db->where('userRef', $accoutnDetail['userRef']);
                $this->db->update('aws_users');
                $msg = true;
                return $msg;
            }
        }
    }

    public function updateCustomerAccountDelete($accountRef) {
//$this->db->set('status','0');
        $this->db->where('userRef', $accountRef);
        $this->db->delete('aws_users');
//$this->db->set('status','0');
        $this->db->where('userRef', $accountRef);
        $this->db->delete('aws_userProfile');
        $this->db->where('customerRef', $accountRef);
        $this->db->delete('aws_billingShippingAddress');
        $msg = true;
        return $msg;
    }

    public function emailValidationCheck($emailVal, $userRef = NULL, $userType) {
        $salonRef = $this->session->userdata('salonRef');
        $emailExist = '';

        if ($userType == "customer") {
            $this->db->select('aws_customerInSalon.customerRefId,aws_users.id,aws_users.userRef');
            $this->db->from('aws_customerInSalon');
            $this->db->join('aws_users', 'aws_users.userRef=aws_customerInSalon.customerRefId', 'inner');
            $this->db->where('aws_users.userType', $userType);
            if ($userRef) {
                $this->db->where('aws_users.userRef !=', $userRef);
            }
            $this->db->where('AES_DECRYPT(aws_users.emailId,"/*awshp$*/")', $emailVal);
            if ($salonRef) {
                $this->db->where('aws_customerInSalon.salonRefId', $salonRef);
            }
            if ($userType) {
                $this->db->where('aws_users.userType', $userType);
            }
        } else {
            $this->db->select('id,userRef');
            $this->db->from('aws_users');
            $this->db->where('userType', $userType);
            if ($userRef) {
                $this->db->where('userRef !=', $userRef);
            }
            $this->db->where('AES_DECRYPT(emailId,"/*awshp$*/")', $emailVal);
            if ($salonRef) {
                $this->db->where('salonRefId', $salonRef);
            }
            if ($userType) {
                $this->db->where('userType', $userType);
            }
        }

        $result = $this->db->get();
        $result = $result->result();
        if (count($result) > 0) {
            $emailExist = true;
        }
        return $emailExist;
    }

    public function checkMobileValidation($mobileNumber = NULL, $userRef = NULL, $userType) {
        $salonRef = $this->session->userdata('salonRef');
        $mobileExist = '';
        if ($userType == "customer") {
            $this->db->select('aws_customerInSalon.customerRefId,aws_users.id,aws_users.userRef');
            $this->db->from('aws_customerInSalon');
            $this->db->join('aws_users', 'aws_users.userRef=aws_customerInSalon.customerRefId', 'inner');
            $this->db->where('aws_users.userType', $userType);
            if ($userRef) {
                $this->db->where('aws_users.userRef !=', $userRef);
            }
            $this->db->where('AES_DECRYPT(aws_users.mobileNumber,"/*awshp$*/")', $mobileNumber);
            if ($salonRef) {
                $this->db->where('aws_customerInSalon.salonRefId', $salonRef);
            }
            if ($userType) {
                $this->db->where('aws_users.userType', $userType);
            }
        } else {
            $this->db->select('id,userRef');
            $this->db->from('aws_users');
            $this->db->where('userType', $userType);
            $this->db->where('AES_DECRYPT(mobileNumber,"/*awshp$*/")', $mobileNumber);
            if ($userRef) {
                $this->db->where('userRef !=', $userRef);
            }
            if ($salonRef) {
                $this->db->where('salonRefId', $salonRef);
                $this->db->where('salonRefId !=', "");
            }
            if ($userType) {
                $this->db->where('userType', $userType);
            }
        }
        $result = $this->db->get();
        $result = $result->result();
        if (count($result) > 0) {
            $mobileExist = true;
        }
        return $mobileExist;
    }

    public function userNameValidationCheck($userName) {
        $emailExist = '';
        $this->db->select('id');
        $this->db->from('aws_users');
        $this->db->where('AES_DECRYPT(userName,"/*awshp$*/")', $userName);
        $result = $this->db->get();
//echo $this->db->last_query(); 
        $result = $result->result();
        if (count($result) > 0) {
            $emailExist = true;
        }
        return $emailExist;
    }

    public function checkusernamenameValidationUpdate($userName, $userRef) {
        $emailExist = '';
        $this->db->select('id');
        $this->db->from('aws_users');
        $this->db->where('AES_DECRYPT(userName,"/*awshp$*/")', $userName);
        $this->db->where('userRef !=', $userRef);
        $result = $this->db->get();
//echo $this->db->last_query(); 
        $result = $result->result();
        if (count($result) > 0) {
            $emailExist = true;
        }
        return $emailExist;
    }

    public function checkemailValidationUpdate($userName, $userRef, $userType) {
        $salonRef = $this->session->userdata('salonRef');
        $emailExist = '';
        if ($userType == "customer") {
            $this->db->select('aws_customerInSalon.customerRefId,aws_users.id,aws_users.userRef');
            $this->db->from('aws_customerInSalon');
            $this->db->join('aws_users', 'aws_users.userRef=aws_customerInSalon.customerRefId', 'inner');
            $this->db->where('aws_users.userType', $userType);
            if ($userRef) {
                $this->db->where('aws_users.userRef !=', $userRef);
            }
            $this->db->where('AES_DECRYPT(aws_users.emailId,"/*awshp$*/")', $emailVal);
            if ($salonRef) {
                $this->db->where('aws_customerInSalon.salonRefId', $salonRef);
            }
            if ($userType) {
                $this->db->where('aws_users.userType', $userType);
            }
        } else {
            $this->db->select('id,userRef');
            $this->db->from('aws_users');
            $this->db->where('userType', $userType);
            $this->db->where('AES_DECRYPT(emailId,"/*awshp$*/")', $emailVal);
            $this->db->where('userRef !=', $userRef);
            if ($salonRef) {
                $this->db->where('salonRefId', $salonRef);
            }
            $this->db->where('salonRefId !=', "");
        }
        $result = $this->db->get();
        $result = $result->result();
        if (count($result) > 0) {
            $emailExist = true;
        }
        return $emailExist;
    }

    public function checkServiceDate() {
        $today = date('Y-m-d');
        $onemonth = date('Y-m-d', strtotime('-1 month', strtotime($today)));
        $threemonth = date('Y-m-d', strtotime('-3 month', strtotime($today)));
        $sixmonth = date('Y-m-d', strtotime('-6 month', strtotime($today)));
        $oneyear = date('Y-m-d', strtotime('-1 year', strtotime($today)));
        $this->db->select('aws_serviceBooking.serviceDate,aws_serviceBooking.orderRef,aws_serviceBooking.serviceName,aws_serviceBooking.serviceImage, aws_services.serviceCycle,aws_serviceBooking.serviceBookedBy');
        $this->db->from('aws_serviceBooking');
        $this->db->join('aws_services', 'aws_services.serviceRef = aws_serviceBooking.serviceRef');
        $this->db->where('aws_serviceBooking.serviceDate', $onemonth);
        $this->db->or_where('aws_serviceBooking.serviceDate', $threemonth);
        $this->db->or_where('aws_serviceBooking.serviceDate', $sixmonth);
        $this->db->or_where('aws_serviceBooking.serviceDate', $oneyear);
        $result = $this->db->get();
        return $result->result();
    }

    public function getUserEmail($orderRef = NULL) {
        $this->db->select('firstName,lastName,email,mobile');
        $this->db->from('aws_orderPersonDetail');
        $this->db->where('orderRef', $orderRef);
        $result = $this->db->get();
        return $result->result();
    }

    public function total_business($oid) {
        $this->db->select('businessId,businessName');
        $this->db->from('aws_business');
        $this->db->where('businessOwnerId', $oid);
        $result = $this->db->get();
        $rows = $result->num_rows();
        $result = $result->result_array();
        return $result;
    }

    public function checkCustomer($mobileNumber = NULL) {
        $salonRef = $this->session->userdata('salonRef');
        $this->db->select('aws_customerInSalon.salonRefId,aws_users.uniqueId,aws_users.userRef,aws_users.userRef as customerRef,AES_DECRYPT(aws_users.userName,"/*awshp$*/") as userName,AES_DECRYPT(aws_users.emailId,"/*awshp$*/") as emailId,AES_DECRYPT(aws_users.mobileNumber,"/*awshp$*/") as mobileNumber');
        $this->db->from('aws_customerInSalon');
        $this->db->join('aws_users', 'aws_users.userRef = aws_customerInSalon.customerRefId', 'left');
        // $this->db->where('aws_customerInSalon.salonRefId', $salonRef);
        $this->db->where('aws_users.userType', 'customer');
        $this->db->where('AES_DECRYPT(aws_users.mobileNumber,"/*awshp$*/")', $mobileNumber);
        $this->db->group_by('aws_users.id');
        $output = $this->db->get();
        $output = $output->row();
        return $output;
    }

}
