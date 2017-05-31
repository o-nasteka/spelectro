var submitter = null;
var paymentVals = new Array();

function echeck(str) {

        var at="@"
        var dot="."
        var lat=str.indexOf(at)
        var lstr=str.length
        var ldot=str.indexOf(dot)
        if (str.indexOf(at)==-1){
           return false
        }

        if (str.indexOf(at)==-1 || str.indexOf(at)==0 || str.indexOf(at)==lstr){
           return false
        }

        if (str.indexOf(dot)==-1 || str.indexOf(dot)==0 || str.indexOf(dot)==lstr){
            return false
        }

         if (str.indexOf(at,(lat+1))!=-1){
            return false
         }

         if (str.substring(lat-1,lat)==dot || str.substring(lat+1,lat+2)==dot){
            return false
         }

         if (str.indexOf(dot,(lat+2))==-1){
            return false
         }

         if (str.indexOf(" ")!=-1){
            return false
         }

  var filter=/^([\w-]+(?:\.[\w-]+)*)@((?:[\w-]+\.)*\w[\w-]{0,66})\.([a-z]{2,6}(?:\.[a-z]{2})?)$/i;
if (!(filter.test(str))) {return false}

          return true
    }

function submitFunction() {
    submitter = 1;
}

var errCSS = {
    'border-color': 'red',
    'border-style': 'solid'
};

function bindAutoFill($el){
    if ($el.attr('type') == 'select-one'){
        var method = 'change';
    }else{
        var method = 'blur';
    }

    $el.blur(unsetFocus).focus(setFocus);

    if (document.attachEvent){
        $el.get(0).attachEvent('onpropertychange', function (){
            if ($(event.srcElement).data('hasFocus') && $(event.srcElement).data('hasFocus') == 'true') return;
            if ($(event.srcElement).val() != '' && $(event.srcElement).hasClass('required')){
                $(event.srcElement).trigger(method);
            }
        });
    }else{
        $el.get(0).addEventListener('onattrmodified', function (e){
            if ($(e.currentTarget).data('hasFocus') && $(e.currentTarget).data('hasFocus') == 'true') return;
            if ($(e.currentTarget).val() != '' && $(e.currentTarget).hasClass('required')){
                $(e.currentTarget).trigger(method);
            }
        }, false);
    }
}

function setFocus(){
    $(this).data('hasFocus', 'true');
}

function unsetFocus(){
    $(this).data('hasFocus', 'false');
}

var checkout = {
    charset: 'utf8',
    pageLinks: {},
    errors:true,
    checkoutClicked:false,
    amountRemaininginTotal:true,
    billingInfoChanged: false,
    shippingInfoChanged: false,
    fieldSuccessHTML: '<div class="success_icon"><i class="fa fa-check"></i></div>',
    fieldErrorHTML: '<div class="error_icon"><i class="fa fa-times-circle-o"></i></div>',
    fieldRequiredHTML: '<div class="required_icon"><i class="fa fa-warning"></i></div>',
    showAjaxLoader: function (){
        if(this.showMessagesPopUp == true)
        {
            $('#ajaxMessages').dialog('open');
        }
        $('#ajaxLoader').show();
    },
    hideAjaxLoader: function (){
        $('#ajaxLoader').hide();
        if(this.showMessagesPopUp == true)
        {
            $('#ajaxMessages').dialog('close');
        }
    },
    showAjaxMessage: function (message){

            // $('#checkoutButtonContainer').hide();
        $('#checkoutButtonContainer').find('.btn').addClass('unactive');

        $('#ajaxMessages').show().html('<span><img src="/includes/checkout/ajax_load.gif"><br>' + message + '</span>');

    },
	hideAjaxMessage: function (){

	// raid ------ минимальный заказ!!!---------------- //
	if($('#minsum').length) {
	   $('#minimal_sum').html($('#minsum').val());
       $('#checkoutButtonContainer_minimal').css('display','block');
   }
	// raid ------ минимальный заказ!!!---------------- //
   else {
     // $('#checkoutButtonContainer').show();
     $('#checkoutButtonContainer').find('.btn').removeClass('unactive');
     $('#checkoutButtonContainer_minimal').css('display','none');
   }
	 //$('#checkoutButtonContainer').show();

   $('#ajaxMessages').hide();

	},
    fieldErrorCheck: function ($element, forceCheck, hideIcon){

        forceCheck = forceCheck || false;
        hideIcon = hideIcon || false;
        var errMsg = this.checkFieldForErrors($element, forceCheck);
        if (hideIcon == false){
            if (errMsg != false){
                this.addIcon($element, 'error', errMsg);
                return true;
            }else{
                this.addIcon($element, 'success', errMsg);
                }
        }else{
            if (errMsg != false){
                return true;
            }
        }
        return false;
    },
    checkFieldForErrors: function ($element, forceCheck){
        var hasError = false;
        if ($element.is(':visible') && ($element.hasClass('required') || forceCheck == true)){
            var errCheck = getFieldErrorCheck($element);
            if (!errCheck.errMsg){
                return false;
            }

            switch($element.attr('type')){
                case 'password':
                if ($element.attr('name') == 'password'){
                    if ($element.val().length < errCheck.minLength){
                        hasError = true;
                    }
                }else{
                    if ($element.val() != $(':password[name="password"]', $('#billingAddress')).val() || $element.val().length <= 0){
                        hasError = true;
                    }
                }
                break;
                case 'radio':
                if ($(':radio[name="' + $element.attr('name') + '"]:checked').length <= 0){
                    hasError = true;
                }
                break;
                case 'checkbox':
                if ($(':checkbox[name="' + $element.attr('name') + '"]:checked').length <= 0){
                    hasError = true;
                }
                break;
                case 'select-one':
                if ($element.val() == ''){
                    hasError = true;
                }
                break;
                default:
                if ($element.val().length < errCheck.minLength){
                    hasError = true;
                } else
        if (($element.attr('name') == 'billing_email_address') && (!(echeck($element.val())))) {
            hasError = true;

        }


                break;
            }
            if (hasError == true){
                return errCheck.errMsg;
            }
        }
        return hasError;
    },
    addIcon: function ($curField, iconType, title){
        title = title || false;
        $('.success_icon, .error_icon, .required_icon', $curField.parent()).hide();
        switch(iconType){
            case 'error':
            if (this.initializing == true){
                this.addRequiredIcon($curField, 'Required');
            }else{
                this.addErrorIcon($curField, title);
            }
            break;
            case 'success':
            this.addSuccessIcon($curField, title);
            break;
            case 'required':
            this.addRequiredIcon($curField, 'Required');
            break;
        }
    },
    addSuccessIcon: function ($curField, title){
        if ($('.success_icon', $curField.parent()).length <= 0){
            $curField.parent().append(this.fieldSuccessHTML);
        }
        $('.success_icon', $curField.parent()).attr('title', title).show();
      },
    addErrorIcon: function ($curField, title){
         if ($('.error_icon', $curField.parent()).length <= 0){
            $curField.parent().append(this.fieldErrorHTML);
        }
        $('.error_icon', $curField.parent()).attr('title', title).show();
    },
    addRequiredIcon: function ($curField, title){
        if ($curField.hasClass('required')){
            if ($('.required_icon', $curField.parent()).length <= 0){
                $curField.parent().append(this.fieldRequiredHTML);
            }
            $('.required_icon', $curField.parent()).attr('title', title).show();
        }
    },
    clickButton: function (elementName){
        if ($(':radio[name="' + elementName + '"]').length <= 0){
            $('input[name="' + elementName + '"]').trigger('click', true);
        }else{
            $(':radio[name="' + elementName + '"]:checked').trigger('click', true);
         //   console.log(111);
        }

    },
    addRowMethods: function($row){
        var checkoutClass2 = this;
        $row.click(function (){
            if (!$(this).hasClass('moduleRowSelected')) {
              // удаляет все классы .moduleRowSelected
              var selector = ($(this).hasClass('shippingRow') ? '.shippingRow' : '.paymentRow') + '.moduleRowSelected';
              $(selector).removeClass('moduleRowSelected');

              // назначает выбранной строке класс .moduleRowSelected
              $(this).addClass('moduleRowSelected');
			    //    $(':radio', $(this)).click();

        			/*  ЗАКОМЕНТИРОВАТЬ ЕСЛИ НЕ НУЖЕН ship2pay */
        			// checkoutClass2.updatePaymentMethods(true);
        	    // checkoutClass2.updateShippingMethods(true);

              if($(':radio', $(this)).is(':disabled')!==true)
              if (!$(':radio', $(this)).is(':checked')){
                $(':radio', $(this)).attr('checked', 'checked').click();
              }
            }
        });
    },
    queueAjaxRequest: function (options){
        var checkoutClass = this;
        var o = {
            url: options.url,
            cache: options.cache || false,
            dataType: options.dataType || 'html',
            type: options.type || 'GET',
            contentType: options.contentType || 'application/x-www-form-urlencoded; charset=' + this.ajaxCharset,
            data: options.data || false,
            beforeSend: options.beforeSend || function (){
                checkoutClass.showAjaxMessage(options.beforeSendMsg || 'Ajax Operation, Please Wait...');
                checkoutClass.showAjaxLoader();
            },
            complete: function (){
                    checkoutClass.hideAjaxMessage();
                    // raid!!!---------------------------
                    // закоментил и перенес в setCheckoutAddress()
                    //if(checkoutClass.errors != true) $('#onePageCheckoutForm').submit();
                    // raid!!!---------------------------

                    if (document.ajaxq.q['orderUpdate'].length <= 0){
                        //alert(checkoutClass.errors);  alert(checkoutClass.checkoutClicked);
                        if(checkoutClass.errors != true && checkoutClass.checkoutClicked == true){
                            var buttonConfirmOrder = $('.ui-dialog-buttonpane button:first');
                            buttonConfirmOrder.removeClass('ui-state-disabled');
                            $('#imgDlgLgr').hide();
                        }
                        checkoutClass.hideAjaxLoader();
                    }
            },
            success: options.success
						//,
           // error: function (XMLHttpRequest, textStatus, errorThrown){
           //     if (XMLHttpRequest.responseText == 'session_expired') document.location = this.pageLinks.shoppingCart;
           //     alert(options.errorMsg || 'There was an ajax error, please contact ' + checkoutClass.storeName + ' for support.');
                //alert(textStatus +'\n'+ errorThrown+'\n'+options.data+'\n'+options.url);
           // }
        };
        $.ajaxq('orderUpdate', o);
    },
	updateAddressHTML: function (type){
	 	var checkoutClass = this;
		this.queueAjaxRequest({
			url: this.pageLinks.checkout,
			data: 'action=' + (type == 'shipping' ? 'getShippingAddress' : 'getBillingAddress'),
			type: 'post',
			beforeSendMsg: 'Updating ' + (type == 'shipping' ? 'Shipping' : 'Billing') + ' Address',
			success: function (data){
				$('#' + type + 'Address').html(data);
				if(checkoutClass.showAddressInFields == true)
				{
				  checkoutClass.attachAddressFields();
				  if(checkoutClass.stateEnabled == true)
				  {
					  $('*[name="billing_state"]').trigger('change');
					  $('*[name="delivery_state"]').trigger('change');
				  }
			    }
			},
			errorMsg: 'There was an error loading your ' + type + ' address, please inform ' + checkoutClass.storeName + ' about this error.'
		});
	},
	attachAddressFields: function(){
		var checkoutClass = this;
		$('input', $('#billingAddress')).each(function (){
			if ($(this).attr('name') != undefined && $(this).attr('type') != 'checkbox' && $(this).attr('type') != 'radio'){
				$(this).blur(function (){
					if ($(this).hasClass('required')){
						checkoutClass.fieldErrorCheck($(this));
					}
				});
				bindAutoFill($(this));

				if ($(this).hasClass('required')){
					if (checkoutClass.fieldErrorCheck($(this), true, true) == false){
						checkoutClass.addIcon($(this), 'success');
					}else{
						checkoutClass.addIcon($(this), 'required');
					}
				}
			}
		});

		$('input,select[name="billing_country"], ', $('#billingAddress')).each(function (){
			var processFunction = function (){
				if ($(this).hasClass('required')){
					if (checkoutClass.fieldErrorCheck($(this)) == false){
						checkoutClass.processBillingAddress();
					}
				}else{
					checkoutClass.processBillingAddress();
				}
			};

			$(this).unbind('blur');
			if ($(this).attr('type') == 'select-one'){
				$(this).change(processFunction);
			}else{
				$(this).blur(processFunction);
			}
			bindAutoFill($(this));
		});
		$('input,select[name="shipping_country"]', $('#shippingAddress')).each(function (){
			if ($(this).attr('name') != undefined && $(this).attr('type') != 'checkbox'){
				var processAddressFunction = function (){
					if ($(this).hasClass('required')){
						if (checkoutClass.fieldErrorCheck($(this)) == false){
							checkoutClass.processShippingAddress();
						}else{
							$('#noShippingAddress').show();
							$('#shippingMethods').hide();
						}
					}else{
						checkoutClass.processShippingAddress(true);
					}
				};

				$(this).blur(processAddressFunction);
				bindAutoFill($(this));

				if ($(this).hasClass('required')){
					var icon = 'required';
					if ($(this).val() != '' && checkoutClass.fieldErrorCheck($(this), true, true) == false){
						icon = 'success';
					}
					checkoutClass.addIcon($(this), icon);
				}
			}
		});
		if(checkoutClass.stateEnabled == true)
		{

			$('select[name="shipping_country"], select[name="billing_country"]').each(function (){
				var $thisName = $(this).attr('name');
				var fieldType = 'billing';
				if ($thisName == 'shipping_country'){
					fieldType = 'delivery';
				}
		//		checkoutClass.addCountryAjax($(this), fieldType + '_state', 'stateCol_' + fieldType);

			});

			$('*[name="billing_state"], *[name="delivery_state"]').each(function (){
				var processAddressFunction = checkoutClass.processBillingAddress;
				if ($(this).attr('name') == 'delivery_state'){
					processAddressFunction = checkoutClass.processShippingAddress;
				}

				var processFunction = function (){
					if ($(this).hasClass('required')){
						if (checkoutClass.fieldErrorCheck($(this)) == false){
							processAddressFunction.call(checkoutClass);
						}
					}else{
						processAddressFunction.call(checkoutClass);
					}
				}

				if ($(this).attr('type') == 'select-one'){
					$(this).change(processFunction);
				}else{
					$(this).blur(processFunction);
				}
				bindAutoFill($(this));
			});
		}
	},
    updateOrderTotals: function (){
        var checkoutClass = this;
        this.queueAjaxRequest({
            url: this.pageLinks.checkout,
            cache: false,
            data: 'action=getOrderTotals&randomNumber='+Math.random(),
            type: 'post',
            beforeSendMsg: checkoutClass.refresh,
            success: function (data){
                $('.orderTotals').html(data);
                checkoutClass.hideAjaxLoader();
                //checkoutClass.updateRadiosforTotal();
            },
            errorMsg: checkoutClass.error_scart+' ' + checkoutClass.storeName
        });
    },
    updateModuleMethods: function (action, noOrdertotalUpdate){
        var checkoutClass = this;
        var descText = (action == 'shipping' ? 'Shipping' : 'Payment');
        if (action == 'shipping'){
          var setMethod = checkoutClass.setShippingMethod;
        } else {
          var setMethod = checkoutClass.setPaymentMethod;
        }

        this.queueAjaxRequest({
            url: this.pageLinks.checkout,
            data: 'action=update' + descText + 'Methods',
            type: 'post',
            beforeSendMsg: checkoutClass.refresh_method+' ' + descText,
            success: function (data){
                $('#no' + descText + 'Address').hide();
                $('#' + action + 'Methods').html(data).show();
                $('.' + action + 'Row').each(function (){

                  checkoutClass.addRowMethods($(this));

                      $('input[name="' + action + '"]', $(this)).each(function (){
                          $(this).click(function (e, noOrdertotalUpdate){
                              setMethod.call(checkoutClass, $(this));
                          });
                      });

                });
                checkoutClass.clickButton(descText.toLowerCase());



			// raid  - обновляем выпадалку оплаты
				  $("#current_payment_module [value='"+$("input[name=payment]:checked").val()+"']").attr("selected", "selected");
			// raid  - обновляем выпадалку оплаты


			// raid  - показуємо додаткові поля в залежності від способів доставки
			//  console.log($('.shippingRow.moduleRowSelected input[type=radio]').val());
		/*
    		var curr_sposob = $('.shippingRow.moduleRowSelected input[type=radio]').val();
			  var suburbblock = $('input[name=billing_suburb]').parent().parent();
			  var streetblock = $('input[name=billing_street_address]').parent().parent();

			  if(curr_sposob=='flat_flat') { // якщо курєр то адрес
				  suburbblock.fadeOut(0);
					streetblock.fadeIn(100);
				} else if(curr_sposob=='nwpochta_nwpochta'  ) { // якщо НП то
				  streetblock.fadeOut(0);
				  suburbblock.fadeIn(100);
				} else { // якщо курєр то адрес
				  streetblock.fadeOut(0);
				  suburbblock.fadeOut(100);
				}   */

                $(function () {
      $('.checkout [data-toggle="tooltip"]').tooltip()
    });

			// raid end

			      },
            errorMsg:  checkoutClass.error_some1+' ' + action + ' '+checkoutClass.error_some2+' ' + checkoutClass.storeName
        });
    },
    updateShippingMethods: function (noOrdertotalUpdate){
        if (this.shippingEnabled == false){
            return false;
        }
        this.updateModuleMethods('shipping', noOrdertotalUpdate);

    },
    updatePaymentMethods: function (noOrdertotalUpdate){
        this.updateModuleMethods('payment', noOrdertotalUpdate);
    },
    setModuleMethod: function (type, method, successFunction){
        var checkoutClass = this;
        this.queueAjaxRequest({
            url: this.pageLinks.checkout,
            data: 'action=set' + (type == 'shipping' ? 'Shipping' : 'Payment') + 'Method&method=' + method,
            type: 'post',
            beforeSendMsg: checkoutClass.setting_method+' ' + (type == 'shipping' ? 'Shipping' : 'Payment'),
            dataType: 'json',
            success: successFunction,
            errorMsg: checkoutClass.error_set_some1+' ' + type + ' '+checkoutClass.error_set_some2+' ' + checkoutClass.storeName + ' '+checkoutClass.error_set_some2
        });
                // for PHP 5.4 :
          //      if (type == 'shipping') {
          //        checkoutClass.updatePaymentMethods(true);
          //      } else {
          //        checkoutClass.updateOrderTotals();
          //      }
      },
    setShippingMethod: function ($button){
        if (this.shippingEnabled == false){
            return false;
        }

        var checkoutClass = this;
        this.setModuleMethod('shipping', $button.val(), function (data){
          // for PHP 5.4 :
          checkoutClass.updatePaymentMethods(true);
        });
    },
    setPaymentMethod: function ($button){

        var checkoutClass = this;
        this.setModuleMethod('payment', $button.val(), function (data){
                // for PHP 5.4 :
                  checkoutClass.updateOrderTotals();
          /*
            $('.paymentFields').remove();

            if (data.inputFields != ''){
                $(data.inputFields).insertAfter($button.parent().parent());
                $('input,select,radio','#paymentMethods').each( function ()
                {
                    if(paymentVals[$(this).attr('name')])
                    {
                        $(this).val(paymentVals[$(this).attr('name')]);
                    }
                    $(this).blur(function (){
                        paymentVals[$(this).attr('name')] = $(this).val();

                    });
                });
            }
         */

        });
    },

    processBillingAddress: function (skipUpdateTotals){
        var hasError = false;
        var checkoutClass = this;
        $('select[name="billing_country"], input[name="billing_street_address"], input[name="billing_zipcode"], input[name="billing_city"], input[id="checkoutButton"], *[name="billing_state"]', $('#billingAddress')).each(function (){
      if (checkoutClass.fieldErrorCheck($(this), false, true) == true){
                hasError = true;
            }
        });
        if (hasError == true){
            return;
        }

        this.setBillTo();

        if(skipUpdateTotals == true)
        {
    //        this.updatePaymentMethods(true);
    //        this.updateShippingMethods(true);
    //        this.updateOrderTotals();
        }

    },
    processShippingAddress: function (skipUpdateTotals){
        var hasError = false;
        var checkoutClass = this;
        $('select[name="shipping_country"], input[name="shipping_street_address"], input[name="shipping_zipcode"], input[name="shipping_city"]', $('#shippingAddress')).each(function (){
            if (checkoutClass.fieldErrorCheck($(this), false, true) == true){
                hasError = true;
            }
        });
        if (hasError == true){
            return;
        }

        this.setSendTo(true);
        if (this.shippingEnabled == true && skipUpdateTotals != true){
            this.updateShippingMethods(true);
        }
        if(skipUpdateTotals == true)
        {
    //        this.updatePaymentMethods(true);
    //        this.updateShippingMethods(true);
    //        this.updateOrderTotals();
        }
    },
    setCheckoutAddress: function (type, useShipping){
         var checkoutClass = this;
        var selector = '#' + type + 'Address';
        var sendMsg = checkoutClass.setting_address+' ' + (type == 'shipping' ? checkoutClass.setting_address_ship : checkoutClass.setting_address_bil);
        var errMsg = type + ' address';
        if (type == 'shipping' && useShipping == false){
         //   selector = '#billingAddress';
         //   sendMsg = 'Установка адреса доставки';
         //   errMsg = 'адрес оплаты';
        }

        action = 'setBillTo';
        if (type == 'shipping'){
            action = 'setSendTo';
        }

        this.queueAjaxRequest({
            url: this.pageLinks.checkout,
            cache: false,
            beforeSendMsg: sendMsg,
            dataType: 'json',
            data: 'action=' + action + '&' + $('*', $(selector)).serialize(),
            type: 'post',
            success: function (data){
              // raid!!!---------------------------
              if(checkoutClass.errors != true) $('#onePageCheckoutForm').submit();
              // raid!!!---------------------------
            }
				//		,
        //    errorMsg: 'There was an error updating your ' + errMsg + ', please inform ' + checkoutClass.storeName + ' about this error.'
        });
    },
    setBillTo: function (){
        this.setCheckoutAddress('billing', false);
    },
    setSendTo: function (useShipping){
        this.setCheckoutAddress('shipping', useShipping);
    },
     checkAllErrors: function(){
            var checkoutClass = this;
            var errMsg = '';
            if ($('.required_icon:visible', $('#billingAddress')).length > 0){
                errMsg += checkoutClass.error_req_bil+"\n";
            }

            if ($('.error_icon:visible', $('#billingAddress')).length > 0){
                errMsg += checkoutClass.error_err_bil+ "\n";
            }

            //if ($('#diffShipping:checked').length > 0){
            //    if ($('.required_icon:visible', $('#shippingAddress')).length > 0){
            //        errMsg += checkoutClass.error_req_ship+ "\n";
            //    }
            //
            //    if ($('.error_icon:visible', $('#shippingAddress')).length > 0){
            //        errMsg += checkoutClass.error_err_ship + "\n";
            //    }
            //}

            if (errMsg != ''){
                errMsg = '------------------------------------------------' + "\n" +
                '                 '+checkoutClass.error_address+'                 ' + "\n" +
                '------------------------------------------------' + "\n" +
                errMsg;
            }

            if(checkoutClass.amountRemaininginTotal == true){
                if ($(':radio[name="payment"]:checked').length <= 0){
                if ($('input[name="payment"]:hidden').length <= 0){
                    errMsg += '------------------------------------------------' + "\n" +
                    '           '+checkoutClass.error_pmethod+'              ' + "\n" +
                    '------------------------------------------------' + "\n" +
                    checkoutClass.error_select_pmethod + "\n";
                }
            }
                }


            if (checkoutClass.shippingEnabled === true){
                if ($(':radio[name="shipping"]:checked').length <= 0){
                    if ($('input[name="shipping"]:hidden').length <= 0){
                        errMsg += '------------------------------------------------' + "\n" +
                        '           '+checkoutClass.error_pmethod+'             ' + "\n" +
                        '------------------------------------------------' + "\n" +
                        checkoutClass.error_select_pmethod + "\n";
                    }
                }
            }
            if(checkoutClass.ccgvInstalled == true)
            {
                if($('input[name="gv_redeem_code"]').val() == 'redeem code')
                {
                    $('input[name="gv_redeem_code"]').val('');
                }
            }

            if(checkoutClass.kgtInstalled == true)
            {
                if($('input[name="coupon"]').val() == 'redeem code')
                {
                    $('input[name="coupon"]').val('');
                }
            }


            if (errMsg.length > 0){
                checkoutClass.errors = true;
                alert(errMsg);
                return false;
            }else{
                checkoutClass.errors = false;
		          //  if (checkoutClass.billingInfoChanged == true && $('.required_icon:visible', $('#billingAddress')).length <= 0 && checkoutClass.loggedIn != true){
		            if (checkoutClass.billingInfoChanged == true && $('.required_icon:visible', $('#billingAddress')).length <= 0){
		                //errMsg += 'You tried to checkout without first clicking update. We have updated for you. Please review your order to make sure it is correct and click checkout again.' + "\n";
		                checkoutClass.processBillingAddress();
		                checkoutClass.billingInfoChanged = false;
		            }
                return true;
            }
        },
    initCheckout: function (){
        var checkoutClass = this;

        /*var billingInfoChanged = false;
        if ($('#diffShipping').checked && this.loggedIn != true){
        var shippingInfoChanged = false;
        }*/

        $('#diffShipping').click(function (){
            if (this.checked){
                $('#shippingAddress').slideDown();
                //			$('#shippingMethods').html('');
                $('#noShippingAddress').show();
                $('select[name="shipping_country"]').trigger('change');
            }else{
                $('#shippingAddress').slideUp();
                var errCheck = checkoutClass.processShippingAddress();
                if (errCheck == ''){
                    $('#noShippingAddress').hide();
                }else{
                    $('#noShippingAddress').show();
                }
            }
        });

        if(this.autoshow == true &&  this.loggedIn == false){
            $('#shippingAddress').hide();
     //       this.setBillTo();
     //       this.setSendTo(false);
     //       this.updatePaymentMethods(true);
            this.updateShippingMethods(true);
     //       this.updateOrderTotals();

        }else    if (this.loggedIn == false){
            $('#shippingAddress').hide();
            $('#shippingMethods').html('');
        }

        $('#checkoutNoScript').remove();
        $('#checkoutYesScript').show();


    //    this.updateOrderTotals();


        if (this.loggedIn == true){
            $('.shippingRow, .paymentRow').each(function (){
                checkoutClass.addRowMethods($(this));
            });

            $('input[name="payment"]').each(function (){
                $(this).click(function (){
                    checkoutClass.setPaymentMethod($(this));
                    checkoutClass.updateOrderTotals();
                });
            });

            if (this.shippingEnabled == true){
                $('input[name="shipping"]').each(function (){
                    $(this).click(function (){
                        checkoutClass.setShippingMethod($(this));
                        checkoutClass.updateOrderTotals();
                    });
                });
            }
        }

        if ($('#paymentMethods').is(':visible')){
            this.clickButton('payment');
        }

        if (this.shippingEnabled == true){
            if ($('#shippingMethods').is(':visible')){
                this.clickButton('shipping');
            }
        }



        $('input, password', $('#billingAddress')).each(function (){
            if ($(this).attr('name') != undefined && $(this).attr('type') != 'checkbox' && $(this).attr('type') != 'radio'){
                if ($(this).attr('type') == 'password'){
                    $(this).blur(function (){
                        if ($(this).hasClass('required')){
                            checkoutClass.fieldErrorCheck($(this));
                        }
                    });
                    /* Used to combat firefox 3 and it's auto-populate junk */
                    $(this).val('');

                }else{
            //      $(this).keyup(function (){
                    $(this).blur(function (){
                                           checkoutClass.billingInfoChanged = true;
                        if ($(this).hasClass('required')){
                            checkoutClass.fieldErrorCheck($(this));
                        }
                    });
                    if($(this).attr('name')!='billing_email_address') {
	                    $(this).keyup(function (){
	                                           checkoutClass.billingInfoChanged = true;
	                        if ($(this).hasClass('required')){
	                            checkoutClass.fieldErrorCheck($(this));
	                        }
	                    });
                    }
                    bindAutoFill($(this));
                }

                if ($(this).hasClass('required')){
                    checkoutClass.billingInfoChanged = true;
                    if (checkoutClass.fieldErrorCheck($(this), true, true) == false){
                        checkoutClass.addIcon($(this), 'success');
                    }else{
                        checkoutClass.addIcon($(this), 'required');
                    }
                }
            }
        });


		$('input[name="billing_email_address"]').each(function (){
			$(this).unbind('blur').change(function (){
				var $thisField = $(this);
				checkoutClass.billingInfoChanged = true;
				if (checkoutClass.initializing == true){
					checkoutClass.addIcon($thisField, 'required');
				}else{
					//if (this.changed == false) return;
					if (checkoutClass.fieldErrorCheck($thisField, true, true) == false){
						this.changed = false;
						if($thisField.val() == '')
						{
							checkoutClass.addIcon($thisField, 'error', data.errMsg.replace('/n', "\n"));
						}
						checkoutClass.queueAjaxRequest({
							url: checkoutClass.pageLinks.checkout,
							data: 'action=checkEmailAddress&emailAddress=' + $thisField.val(),
							type: 'post',
							beforeSendMsg: checkoutClass.check_email,
							dataType: 'json',
							success: function (data){
								$('.success, .error', $thisField.parent()).hide();
								if (data.success == 'false'){
									checkoutClass.addIcon($thisField, 'error', data.errMsg.replace('/n', "\n"));
							//		alert(data.errMsg.replace('/n', "\n").replace('/n', "\n").replace('/n', "\n"));
							    $("#email_error").html(data.errMsg.replace('/n', "\n").replace('/n', "\n").replace('/n', "\n"));
								}else{
								  $("#email_error").html('');
									checkoutClass.addIcon($thisField, 'success');
								}
							},
							errorMsg: checkoutClass.error_email+' ' + checkoutClass.storeName + ' '+checkoutClass.error_set_some3
						});
					}
				}
			}).keyup(function (){
				this.changed = true;
			});
			bindAutoFill($(this));
		});

		$('input,select[name="shipping_country"]', $('#shippingAddress')).each(function (){
			if ($(this).attr('name') != undefined && $(this).attr('type') != 'checkbox'){
				var processAddressFunction = function (){
					if ($(this).hasClass('required')){
						if (checkoutClass.fieldErrorCheck($(this)) == false){
							checkoutClass.processShippingAddress();
						}else{
							$('#noShippingAddress').show();
							$('#shippingMethods').hide();
						}
					}else{
						checkoutClass.processShippingAddress(true);
					}
				};

				$(this).blur(processAddressFunction);
				bindAutoFill($(this));

				if ($(this).hasClass('required')){
					var icon = 'required';
					if ($(this).val() != '' && checkoutClass.fieldErrorCheck($(this), true, true) == false){
						icon = 'success';
					}
					checkoutClass.addIcon($(this), icon);
				}
			}
		});
		if(checkoutClass.stateEnabled == true)
		{
			$('select[name="shipping_country"], select[name="billing_country"]').each(function (){
				var $thisName = $(this).attr('name');
				var fieldType = 'billing';
				if ($thisName == 'shipping_country'){
					fieldType = 'delivery';
				}
	//			checkoutClass.addCountryAjax($(this), fieldType + '_state', 'stateCol_' + fieldType);
			});

			$('*[name="billing_state"], *[name="delivery_state"]').each(function (){
				var processAddressFunction = checkoutClass.processBillingAddress;
				if ($(this).attr('name') == 'delivery_state'){
					processAddressFunction = checkoutClass.processShippingAddress;
				}

				var processFunction = function (){
					if ($(this).hasClass('required')){
						if (checkoutClass.fieldErrorCheck($(this)) == false){
							processAddressFunction.call(checkoutClass);
						}
					}else{
						processAddressFunction.call(checkoutClass);
					}
				}

				if ($(this).attr('type') == 'select-one'){
					$(this).change(processFunction);
				}else{
					$(this).blur(processFunction);
				}
				bindAutoFill($(this));
			});
		}

        // Accept Rules before send order (enable Checkout Button)
        $('#checkBtn').click(function () {
            if($('#checkoutButton').hasClass('disabled')){
                $('#checkoutButton').removeClass('disabled');
            } else {
                $('#checkoutButton').addClass('disabled');
            }
        })
        // Default disable checkout button befor accept rules
        $('#checkoutButton').click(function() {
            if(!$(this).hasClass('disabled'))checkoutClass.checkAllErrors();
          return false;
        });

        if (this.loggedIn == true && this.showAddressInFields == true){
            $('*[name="billing_state"]').trigger('change');
            $('*[name="delivery_state"]').trigger('change');
        }

        this.initializing = false;


    }
}


// Update QTY product checkout.php
$('body').on('click', '.ok', function(event) {

    console.log('ok');

    var url = 'includes/checkout/checkout_cart.php?action=update_product';

    event.preventDefault();

    senddata = $('#cart-item :input').serialize();


    $.post(url,senddata, function(data) {
        $('#cart-item').html(data);
    });
});


// DELETE QTY product checkout.php
$('body').on('click', '.delete', function(event) {

    console.log('delete');

    $("#cart_delete" + jQuery(this).val()).attr('checked', 'checked');

    var url = 'includes/checkout/checkout_cart.php?action=update_product';
    event.preventDefault();

    senddata = $('#cart-item :input').serialize();


    $.post(url,senddata, function(data) {
        $('#cart-item').html(data);
    });
});