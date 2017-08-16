jQuery(document).ready(function () {
//jQuery('.timepicker').timepicki({show_meridian:false});

// code for return message if jquery is disabled 
    var x = navigator.cookieEnabled;
    if (x == false) {
        alert('Please enable your browser cookies.For using login functionality.');
    }
    if (jQuery('.successMsg').html() != '') {
        jQuery('.successMsg').fadeOut(5000);
    } else {
        jQuery('.successMsg').fadeIn(5000);
    }
    if (jQuery('.error-messelement1').html() != '') {
        jQuery('.error-messelement1').fadeOut(5000);
    }
   
    var rowCount = $('.table >tbody >tr').length;
    var rowCount = jQuery('.table tr').length;
    rowCount = rowCount - 1;
    if (rowCount > 1) {
        $('.table th').children('span').css('display', 'block');
    } else {
        $('.table th').children('span').css('display', 'none');
    }
// Dont accpect extra space in fileds
    jQuery('body').on('keyup blur', 'input[type = "textbox"],input[type = "text"],input[type = "email"],input[type = "password"]', function (eve) {
        if ((eve.which != 37) && (eve.which != 38) && (eve.which != 39) && (eve.which != 40)) {
            var text = jQuery(this).val();
            text = text.trim();
            if (text == '') {
                jQuery(this).val('');
            }

            var string = jQuery(this).val();
            if (string != "") {
                string = string.replace(/\s+/g, " ");
                jQuery(this).val(string);
//                if (string != "") {
//                    string = string.replace(/\s+/g, " ");
//                    jQuery(this).val(string);
//                }
            }
        }
    });
    // Dont accpect spance password filed 
    $(document).on('keypress', 'input[type = "password"]', function (eve) {
        if (eve.which == 32) {
            return false;
        }
    });
    // Required input validation 
    jQuery('body').on('keyup blur change', '.error,.success', function () {
        var text = jQuery(this).val();
        text = text.trim();
        if (text != '') {
            jQuery('.error-messelement').fadeOut();
            jQuery(this).css('border', '1px solid #3c763d');
        } else {
            jQuery(this).val('');
            //jQuery('.error-messelement').show().html('This Field is Required.').css('color','#F00');  
            jQuery(this).addClass('error');
            jQuery(this).css('border', '1.8px solid #a94442');
            if (jQuery(this).attr('id') == "startTime" || jQuery(this).attr('id') == "endTime" || jQuery(this).attr('id') == "timePerService") {
                jQuery(this).attr('placeholder', 'hh:mm (24 hour format)');
            } else {
                jQuery(this).attr('placeholder', 'This field is required');
            }
            //jQuery(this).css('border','1px solid #F00');
        }
    });
    // Type only number keys validation
    $(document).on('keypress', '.validNumber', function (eve) {
        if (eve.which == 0) {
            return true;
        } else {
            if (eve.which == '.') {
                eve.preventDefault();
            }
            if ((eve.which != 46 || $(this).val().indexOf('.') != -1) && (eve.which < 48 || eve.which > 57)) {
                if (eve.which != 8)
                {
                    eve.preventDefault();
                }
            }

            $('.validNumber').keyup(function (eve) {
                if ($(this).val().indexOf('.') == 0) {
                    $(this).val($(this).val().substring(1));
                }
            });
        }
    });
   
// Type only Alphabet keys validation 
    jQuery(document).on('keypress', '.alphabetValidation', function (event) {
        var inputValue = event.charCode;
        if (!(inputValue >= 65 && inputValue <= 122) && (inputValue != 32 && inputValue != 0)) {
            event.preventDefault();
        }
    });
    jQuery('.alphabet').click(function () {
        var currentVal = jQuery(this).val()
    });
    
    // Add customer validation check
    jQuery('.addCustomer').click(function () {
        var count = 0;
        jQuery("html, body").animate({scrollTop: 0}, "slow");
        jQuery(".success").each(function () {

            if (jQuery(this).val() == '') {
                jQuery(this).addClass('error');
                jQuery(this).attr('placeholder', 'This field is required');
                jQuery(this).css('border', '1.8px solid #a94442');
                count = parseInt(count) + parseInt(1);
            }
        });
        jQuery('.selectBox').each(function () {
            if (jQuery(this).val() == '') {
                jQuery(this).addClass('error');
                jQuery(this).attr('placeholder', 'This field is required');
                jQuery(this).css('border', '1.8px solid #a94442');
                //jQuery(this).next('span').html('Field Required');
                count = parseInt(count) + parseInt(1);
            }
        });
     
        if (count > 0) {
            return false;
        }

        var emailVal = jQuery('#emailId').val();
        if (emailVal != "") {
            var email = checkEmailId(emailVal);
            if (email == false) {
                return false;
            }
        }

        var mobileVal = jQuery('#mobile').val();
        if (mobileVal != "") {
            var mob = checkMobile(mobileVal);
            if (mob == false) {
                return false;
            }
        }

        var pattern = /^[0-9]{6}$/;
        jQuery(".postalCode").each(function () {
            if (jQuery(this).val() != "") {
                if (!pattern.test(jQuery(this).val())) {
                    jQuery(this).parent('.col-sm-9').children('.postalError').html('Postal code must be at least 6 digits');
                    jQuery(this).css('border', '1px solid #a94442');
                    jQuery(".updateCustomer").prop("disabled", true);
                    jQuery(".addCustomer").prop("disabled", true);
                    jQuery(this).focus();
                    return false;
                } else {
                    jQuery(this).parent('.col-sm-9').children('.postalError').html('');
                    jQuery(this).css('border', '');
                    jQuery(".updateCustomer").prop("disabled", false);
                    jQuery(".addCustomer").prop("disabled", false);
                }
            }
        });

        // jQuery(".addCustomer").prop("disabled", true);
        jQuery('.successMsg').fadeOut(5000);
        //  jQuery('form#addCustomer').submit();
    });
   // Customer listing simple pagination
    jQuery("body").on('click', '#ajax_pagingsearc0 a', function () {
        var url = jQuery(this).attr("href");
        jQuery('#ajax_pagingsearc0 li.active').removeClass('active');
        jQuery(this).parent('li').addClass('active');
        jQuery('#wait').show();
        jQuery.ajax({
            type: "POST",
            data: "ajax=1",
            url: url,
            success: function (msg) {
                jQuery("#allRecords").html(msg);
                jQuery('#wait').hide();
                // applyPagination();
            }
        });
        return false;
    });
    // Customer listing pagination with searching 
    jQuery("body").on('click', '#ajax_pagingsearc1 a', function () {
        var url = jQuery(this).attr("href");
        //var lowerCase	=	jQuery('#').html().toLowerCase();
        //var upperCase	=	jQuery('.activeSort').html().toUpperCase();
        var lowerCase = jQuery('#searchCustomer').val().toLowerCase();
        var upperCase = jQuery('#searchCustomer').val();
        //jQuery('#ajax_pagingsearc').find('li').removeClass('active');
        //jQuery(this).parent('li').addClass('active');
        jQuery('#wait').show();
        jQuery.ajax({
            type: "POST",
            data: "ajax=1",
            url: url,
            data:{lowerCase: lowerCase, upperCase: upperCase},
            dataType: 'html',
            success: function (msg) {
                jQuery("#allRecords").html(msg);
                jQuery('#wait').hide();
                // applyPagination();
            }
        });
        return false;
    });
    
    (function ($) {
        $.fn.extend({
            donetyping: function (callback, timeout) {
                timeout = timeout || 1e3; // 1 second default timeout
                var timeoutReference,
                        doneTyping = function (el) {
                            if (!timeoutReference)
                                return;
                            timeoutReference = null;
                            callback.call(el);
                        };
                return this.each(function (i, el) {
                    var $el = $(el);
                    // Chrome Fix (Use keyup over keypress to detect backspace)
                    // thank you @palerdot
                    $el.is(':input') && $el.on('keyup keypress paste', function (e) {
                        // This catches the backspace button in chrome, but also prevents
                        // the event from triggering too preemptively. Without this line,
                        // using tab/shift+tab will make the focused element fire the callback.
                        if (e.type == 'keyup' && e.keyCode != 8)
                            return;
                        // Check if timeout has been set. If it has, "reset" the clock and
                        // start over again.
                        if (timeoutReference)
                            clearTimeout(timeoutReference);
                        timeoutReference = setTimeout(function () {
                            // if we made it here, our timeout has elapsed. Fire the
                            // callback
                            doneTyping(el);
                        }, timeout);
                    }).on('blur', function () {
                        // If we can, fire the event since we're leaving the field
                        doneTyping(el);
                    });
                });
            }
        });
    })(jQuery);
// Search customer 
    $('#searchCustomer').donetyping(function () {
        var lowerCase = jQuery(this).val().toLowerCase();
        var upperCase = jQuery(this).val();
        var pageName = jQuery(this).attr('rel');
        var setdefault = jQuery(this).attr('src');
        if (lowerCase != '' && upperCase != '') {
            jQuery.ajax({
                type: "post",
                url: site_url + pageName,
                data: {lowerCase: lowerCase, upperCase: upperCase},
                dataType: 'html',
                success: function (data) {
                    try {
                        jQuery("#allRecords").html(data);
                    } catch (e) {
                        //alert('Exception while request..');
                        jQuery("#allRecords").html(data);
                    }
                },
                error: function () {
                    //alert('Error while request..');
                    jQuery("#allRecords").html(data);
                }
            });
        } else {
            jQuery.ajax({
                type: "post",
                url: site_url + setdefault,
                data: {lowerCase: lowerCase, upperCase: upperCase},
                dataType: 'html',
                success: function (data) {
                    try {
                        jQuery("#allRecords").html(data);
                    } catch (e) {
                        jQuery("#allRecords").html(data);
                        //alert('Exception while request..');
                    }
                },
                error: function () {
                    jQuery("#allRecords").html(data);
                    //alert('Error while request..');
                }
            });
        }
    });
// Set show numer per page customer 
    jQuery('body').on('change', '#fileterByNum', function () {
        var currentVal = jQuery(this).val();
        var pageName = jQuery(this).attr('rel');
        jQuery.ajax({
            type: "post",
            url: site_url + pageName,
            data: {currentVal: currentVal},
            dataType: 'html',
            success: function (data) {
                try {
                    jQuery("#allRecords").html(data);
                } catch (e) {
                    jQuery("#allRecords").html(data);
                    //alert('Exception while request..');
                }
            },
            error: function () {
                jQuery("#allRecords").html(data);
                // alert('Error while request..');
            }
        });
    });

// Call datepicker for date of birth
    jQuery('body').on('focus', '#dateOfBirth', function () {

        jQuery(this).datepicker({
            dateFormat: 'dd-mm-yy',
            changeMonth: true,
            changeYear: true,
            yearRange: "-70:+0",
            maxDate: new Date()
        }).val();
    });
    // Dont type date of birth input
    jQuery('body').on('keyup', '#dateOfBirth', function () {
        jQuery(this).val('');
    });
    
    // Get customer details for edit customer
    jQuery('body').on('click', '.editCustomer', function () {
        var CurrentCustomerId = jQuery(this).attr('rel');
        jQuery('#userRef').val(CurrentCustomerId);
        jQuery('#userRefaccount').val(CurrentCustomerId);
        jQuery('#editProfile').toggle();
        jQuery('.customerList').toggle();
        jQuery.ajax({
            type: "post",
            url: site_url + "getCustomerDetail",
            beforeSend: function () {
                $('#wait').show();
            },
            complete: function () {
                $('#wait').hide();
            },
            cache: false,
            data: {CurrentCustomerId: CurrentCustomerId},
            dataType: "json",
            success: function (data) {
                try {
                    if (data[0].dob != null && data[0].dob != "") {
                        var dobSplite = data[0].dob.split('-');
                        var dob = dobSplite[2] + '-' + dobSplite[1] + '-' + dobSplite[0];
                        jQuery('#dateOfBirth').val(dob);
                    }
                    jQuery('#customerModal').modal();
                    jQuery('#namePrefix').val(data[0].namePrefix);
                    jQuery('#firstName').val(data[0].firstName);
                    jQuery('#gender').val(data[0].gender);
                    if (data[0].emailId != null && data[0].emailId != "") {
                        jQuery('#emailId').val(data[0].emailId);
                    }
                    jQuery('#mobile').val(data[0].mobileNumber);
                    jQuery('#billingAddress').val(data[0].billingAddress);
                    jQuery('#billingPostalCode').val(data[0].billingPostalCode);
                    jQuery('#billingCountry').val(data[0].billingCountry);
                    jQuery('#userName').val(data[0].userName);
                    jQuery('#uniqueId').val(data[0].uniqueId);
                    jQuery('#Billingcity').val(data[0].Billingcity);
                    jQuery('#billingProvince').val(data[0].billingProvince);
                    jQuery('#shipToContact').val(data[0].shipToContact);
                    jQuery('#ShipPhone').val(data[0].ShipPhone);
                    jQuery('#ShipAddress').val(data[0].ShipAddress);
                    jQuery('#ShipPostalCode').val(data[0].ShipPostalCode);
                    jQuery('#ShipCity').val(data[0].ShipCity);
                    jQuery('#shipCountryId').val(data[0].shipCountryId);
                    jQuery('#ShipProvince').val(data[0].ShipProvince);
                    if (data[0].billingAddress == data[0].ShipAddress && data[0].billingPostalCode == data[0].ShipPostalCode && data[0].billingCountry == data[0].shipCountryId && data[0].Billingcity == data[0].ShipCity && data[0].billingProvince == data[0].ShipProvince && data[0].shipToContact == data[0].firstName && data[0].ShipPhone == data[0].mobile) {
                        jQuery('.shipCheck').attr('checked', true);
                    }

                } catch (e) {
                    // alert('Exception while request..');
                }
            },
            error: function () {
                // alert('Error while request..');
            }
        });
    });
    
    // Update customer Info 
    jQuery('body').on('click', '.updatePersonlInfo', function (e) {
        e.preventDefault();
        var userDetail = {};
        var accountDetail = {};
        var billShipDetail = {};
        var count = 0;
        jQuery(".success").each(function () {
            if (jQuery(this).val() == '') {
                jQuery(this).addClass('error');
                jQuery(this).attr('placeholder', 'This field is required');
                jQuery(this).css('border', '1.8px solid #a94442');
                jQuery("html, body").animate({scrollTop: 0}, "slow");
                count = parseInt(count) + parseInt(1);
            }
        });
        jQuery('.selectBox').each(function () {
            if (jQuery(this).val() == '') {
                jQuery(this).next('span').html('Field Required');
                count = parseInt(count) + parseInt(1);
            }
        });
        if (jQuery(".success").val() === '') {
            return false;
        }

        if (count > 0) {
            return false
        } else {

            var emailVal = jQuery('#emailId').val();
            if (emailVal != "") {
                var email = checkEmailId(emailVal);
                if (email == false) {
                    return false;
                }
            }
            var mobileVal = jQuery('#mobile').val();
            if (mobileVal != "") {
                var mob = checkMobile(mobileVal);
                if (mob == false) {
                    return false;
                }
            }

            var pattern = /^[0-9]{6}$/;
            jQuery(".postalCode").each(function () {
                if (jQuery(this).val() != "") {
                    if (!pattern.test(jQuery(this).val())) {
                        jQuery(this).parent('.col-sm-9').children('.postalError').html('Postal code must be at least 6 digits');
                        jQuery(this).css('border', '1px solid #a94442');
                        jQuery(".updateCustomer").prop("disabled", true);
                        jQuery(this).focus();
                        return false;
                    } else {
                        jQuery(this).parent('.col-sm-9').children('.postalError').html('');
                        jQuery(this).css('border', '');
                        jQuery(".updateCustomer").prop("disabled", false);
                    }
                }
            });

            jQuery('.personalInfo').each(function () {
//if(jQuery(this).val() != ''){
                var customerName = jQuery(this).attr('name');
                userDetail[customerName] = jQuery(this).val();
                //}
            });
            jQuery('.accountUpdate').each(function () {
//if(jQuery(this).val() != ''){
                var customerName1 = jQuery(this).attr('name');
                accountDetail[customerName1] = jQuery(this).val();
                //}
            });
            jQuery('.billingAddress').each(function () {
                var billshipName = jQuery(this).attr('name');
                billShipDetail[billshipName] = jQuery(this).val();
            });
            //console.log(userDetail);
            jQuery.ajax({
                type: "post",
                url: site_url + "updateCustomer",
                beforeSend: function () {
                    $('#wait').show();
                },
                complete: function () {
                    $('#wait').hide();
                },
                cache: false,
                data: {userDetail: userDetail, accountDetail: accountDetail, billShipDetail: billShipDetail},
                dataType: "json",
                success: function (data) {
                    try {
                        if (data.total == 1 || data.total1 == 1 || data.total2 == 1) {
                            //alert('fdg');
                            jQuery('#customername' + userDetail['userRef']).html(userDetail['firstName']);
                            jQuery('#customermobile' + userDetail['userRef']).html(userDetail['mobileNumber']);
                            jQuery('#customeremailId' + userDetail['userRef']).html(userDetail['emailId']);
                            jQuery('.updateclientdetails').show().addClass('alert-success').removeClass('alert-danger').fadeOut(5000);
                            jQuery('.updateclientmessage').html('Customer details updated successfully');
                            jQuery("html, body").animate({scrollTop: 0}, "slow");
                            //jQuery('#customerModal').modal('hide');						
                        } else if (data.total == 0 && data.total1 == 0 && data.total2 == 0) {
                            jQuery('.updateclientdetails').show().addClass('alert-danger').removeClass('alert-success').fadeOut(5000);
                            jQuery('.updateclientmessage').html('Customer details already updated');
                            jQuery("html, body").animate({scrollTop: 0}, "slow");
                        } else if (data == false) {
                            jQuery('.updateclientdetails').show().addClass('alert-danger').removeClass('alert-success').fadeOut(5000);
                            jQuery('.updateclientmessage').html('Customer email or username already exist');
                            jQuery("html, body").animate({scrollTop: 0}, "slow");
                        }
                        //	jQuery("div#popupContact").html(data);

                        //jQuery('.dropdown-toggle').dropdown();
                    } catch (e) {
                        // alert('Exception while request..');
                    }
                },
                error: function () {
                    //alert('Error while request..');
                }
            });
        }
    });
    
    // cehck email id validation
     
    jQuery('body').on('keyup blur', '.emailId', function () {
        var emailVal = jQuery(this).val();
        var email = checkEmailId(emailVal);
        if (email == false) {
            return false;
        }
    });
    
    function checkEmailId(emailId) {
        var emailVal = emailId;
        var userType = jQuery('#userType').val();
        if (jQuery('#userRef').val()) {
            var userRef = jQuery('#userRef').val();
        } else {
            var userRef = '';
        }
        var emailReg = new RegExp(/^(("[\w-\s]+")|([\w-]+(?:\.[\w-]+)*)|("[\w-\s]+")([\w-]+(?:\.[\w-]+)*))(@((?:[\w-]+\.)*\w[\w-]{0,66})\.([a-z]{2,6}(?:\.[a-z]{2})?)$)|(@\[?((25[0-5]\.|2[0-4][0-9]\.|1[0-9]{2}\.|[0-9]{1,2}\.))((25[0-5]|2[0-4][0-9]|1[0-9]{2}|[0-9]{1,2})\.){2}(25[0-5]|2[0-4][0-9]|1[0-9]{2}|[0-9]{1,2})\]?$)/i);
        var valid = emailReg.test(emailVal);
        if (emailVal != '') {
            if (!valid) {
                jQuery('.emailId').addClass('has-error');
                jQuery('#emailVald').html('');
                jQuery('#emailMsg').html('Please enter a valid email');
                jQuery('.addCustomer').prop('disabled', true);
                jQuery('.updateCustomer').prop('disabled', true);
                jQuery('.emailId').css('border', '1px solid #a94442');
                jQuery('.emailId').focus();
                return false;
            } else {
                jQuery('#emailVald').html('');
                jQuery('.addCustomer').prop('disabled', false);
                jQuery('.updateCustomer').prop('disabled', false);
                jQuery('.emailId').removeClass('has-error');
                jQuery('.emailId').css('border', '1px solid #3c763d');
                jQuery("#emailMsg").html('');
                jQuery.ajax({
                    type: "post",
                    url: site_url + "checkEmailValidation",
                    cache: false,
                    data: {emailVal: emailVal, userRef: userRef, userType: userType},
                    dataType: "json",
                    success: function (data) {
                        try {
                            if (data == true) {
                                jQuery("#emailMsg").html('');
                                jQuery('#emailVald').html('Email already exist');
                                jQuery('.addCustomer').prop('disabled', true);
                                jQuery('.updateCustomer').prop('disabled', true);
                                jQuery('.emailId').addClass('has-error');
                                jQuery('.emailId').css('border', '1px solid #a94442');
                                jQuery('.emailId').focus();
                                return false;
                            } else {
                                jQuery('#emailVald').html('');
                                jQuery('.addCustomer').prop('disabled', false);
                                jQuery('.updateCustomer').prop('disabled', false);
                                jQuery('.emailId').removeClass('has-error');
                                jQuery('.emailId').css('border', '1px solid #3c763d');
                                jQuery("#emailMsg").html('');
                            }
                           
                        } catch (e) {
                            //alert('Exception while request..');
                        }
                    },
                    error: function () {
                        //alert('Error while request..');
                    }
                });
            }
        } else {
            jQuery('#emailVald').html('');
            jQuery('.addCustomer').prop('disabled', false);
            jQuery('.addEmployee').prop('disabled', false);
            jQuery('.emailId').removeClass('has-error');
            jQuery('.emailId').css('border', '');
            jQuery("#emailMsg").html('');
        }
    }

 // cehck mobile number validation
 
    jQuery('body').on('keyup blur', '#mobile', function (event) {
        var mobileVal = jQuery(this).val();
        var mobile = checkMobile(mobileVal);
        if (mobile == false) {
            return false;
        }
    });
   
    function checkMobile(mobile) {
        var mobileVal = mobile;
        if (mobileVal != "") {
            var userType = jQuery('#userType').val();
            if (jQuery('#userRef').val()) {
                var userRef = jQuery('#userRef').val();
            } else {
                var userRef = '';
            }
            var number = mobileVal.length;
            if (number != 10) {
                jQuery("#mobileMsg").html('');
                jQuery('#mobileVald').html('Mobile number must be in 10 digits.');
                jQuery('.addCustomer').prop('disabled', true);
                jQuery('.updateCustomer').prop('disabled', true);
                jQuery('#mobile').addClass('has-error');
                jQuery('#mobile').focus();
                return false;
            } else {
                jQuery('#mobileVald').html('');
                jQuery('.addCustomer').prop('disabled', false);
                jQuery('.updateCustomer').prop('disabled', false);
                jQuery('#mobile').removeClass('has-error');
                jQuery('#mobile').css('border', '1px solid #3c763d');
                jQuery("#mobileMsg").html('');
                jQuery.ajax({
                    type: "post",
                    url: site_url + "checkMobileValidation",
                    cache: false,
                    data: {mobileNumber: mobileVal, userRef: userRef, userType: userType},
                    dataType: "json",
                    success: function (data) {
                        try {
                            if (data == true) {
                                jQuery("#mobileMsg").html('');
                                jQuery('#mobileVald').html('Mobile number already exist');
                                jQuery('.addCustomer').prop('disabled', true);
                                jQuery('.updateCustomer').prop('disabled', true);
                                jQuery('#mobile').addClass('has-error');
                                jQuery('#mobile').focus();
                                return false;
                            } else {
                                jQuery('#mobileVald').html('');
                                jQuery('.addCustomer').prop('disabled', false);
                                jQuery('.updateCustomer').prop('disabled', false);
                                jQuery('#mobile').removeClass('has-error');
                                jQuery('#mobile').css('border', '1px solid #3c763d');
                                jQuery("#mobileMsg").html('');
                            }
                        } catch (e) {
                            //alert('Exception while request..');
                        }
                    },
                    error: function () {
                        //alert('Error while request..');
                    }
                });
            }
        } else {
            jQuery('#mobileVald').html('');
            jQuery("#mobileMsg").html('');
        }
    }

   // Shipping number validation 
    $(document).on('keypress', '.shipValidNumber', function (eve) {
        if (eve.which == 0 || eve.which == 32) {
            return true;
        } else {
            if ((eve.which != 46 || $(this).val().indexOf('.') != -1) && (eve.which < 48 || eve.which > 57)) {
                if (eve.which != 8)
                {
                    eve.preventDefault();
                }
            }

            $('.validNumber').keyup(function (eve) {
                if ($(this).val().indexOf('.') == 0) {
                    $(this).val($(this).val().substring(1));
                }
            });
        }
    });
    
    // Shipping Phone number Validation
    
    jQuery('body').on('keyup blur', '#ShipPhone', function () {
        var number = jQuery(this).val().length;
        if (jQuery(this).val() != "") {
            // if (number < 8 || number > 15) {
            if (number < 10) {
                jQuery(this).css('border', '1px solid #a94442');
                jQuery('#shipPhoneError').html("Mobile number must be in 10 digits.");
                jQuery(".addCustomer").prop("disabled", true);
                jQuery(".updateCustomer").prop("disabled", true);
                return false;
            } else {
                jQuery(this).css('border', '');
                jQuery('#shipPhoneError').html("");
                jQuery(".addCustomer").prop("disabled", false);
                jQuery(".updateCustomer").prop("disabled", false);
            }
        } else {
            jQuery(this).css('border', '');
            jQuery('#shipPhoneError').html("");
            jQuery(".addCustomer").prop("disabled", false);
            jQuery(".updateCustomer").prop("disabled", false);
        }
    });
});
