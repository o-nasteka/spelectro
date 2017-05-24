  function showCartpopup(){
    jQuery.get('/popup_cart.php',null,function(response) {
      modal({
        id: 'cart_popup',
        body: response,
        width:'900px',
      }); 
    });
  }
  
  function refreshTopCart() {
    jQuery.get('/ajax_refresh_cart.php',null,function(response) {
      jQuery(".sm_popup_cart").html(response);
    });
  }
  
  function modal(options){
    var settings = {
      id: Math.floor(Math.random() * (1000 - 1 + 1)) + 1,
      after : function(){},
      width: 0,
      before : function(){}
    }
    
    jQuery.extend(true, settings, options);
  
    var width = '';
    if(settings.width!=0) width = 'style="width:auto;max-width:'+settings.width+'"';
    
      if(jQuery('.modal').length == 0){ 
        var $html = '<div class="modal fade" id="modal_'+settings.id+'" tabindex="-1" role="dialog" aria-labelledby="'+settings.id+'_label" aria-hidden="true">';
        $html +='<div class="modal-dialog" '+width+'><div class="modal-content">';
        $html +='<div class="modal-header">';
          $html += '<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>';
            if(settings.title){
                $html += '<h4 class="modal-title" id="modal_'+settings.id+'_label">'+settings.title+'</h4>';
            }
            $html +='</div>';
            $html +='<div class="modal-body">'+settings.body+'</div>\
          </div>\
        </div>\
        </div>';
        jQuery('body').append($html).promise().done(function(){
          var $modal = jQuery('#modal_'+settings.id);
          var $before = settings.before($modal);
          if($before !== false){
              if(settings.classes){
              $modal.addClass(settings.classes);
            }
  
            if(settings.title == null){
                $modal.addClass('no-title');
            }
           
            if($modal.hasClass('valign-false') == false){
              centerModal($modal);
            }
            $modal.on('hidden.bs.modal', function (e) {
               $modal.remove(); 
            });
            $modal.on('shown.bs.modal', function (e) {
              if(settings.after){
                settings.after($modal); 
              }
            });
            $modal.modal(); 
            
          }else{
            $modal.remove(); 
          }
  
      });
    }
  
  }
  
  function centerModal(el) {
      jQuery(el).css('display', 'block');
      var $dialog = jQuery(el).find(".modal-dialog");
      var offset = (jQuery(window).height() - $dialog.height()) / 2;
      var bottomMargin = $dialog.css('marginBottom');
      bottomMargin = parseInt(bottomMargin);
      if(offset < bottomMargin) offset = bottomMargin;
      $dialog.css("margin-top", offset);
  }
  
  function showPopupResponse(res)  {
      refreshTopCart();
      // обновляем чекаут
      if(typeof checkout != 'undefined') {
          checkout.updateOrderTotals();
      }
  } 

jQuery(function($) { 

//-----------popup cart:----------
	jQuery('body').on('click', '.popup_cart', function(event) {
		event.preventDefault();
		showCartpopup();
	});
  
  // add product in prodict info
  jQuery('body').on('submit', 'form[name="cart_quantity"]', function(event) {
	//$('form[name="cart_quantity"]').on('submit', function(event) {
		event.preventDefault();

		$.post($(this).attr('action'), $(this).serialize(), function(data) {
      showCartpopup();
      refreshTopCart();
		}); 
    
	});
  
  // add product in product listing
	jQuery('body').on('click', '.product-details .btn-success', function(event) {
		event.preventDefault();
		$.get($(this).attr('href'), null, function(data) {
      showCartpopup();
      refreshTopCart();
		}); 
	});

  
//-----------popup cart-END---------

	/* ---------  Shopping cart --------- */
/*
	jQuery('body').on('submit', 'form[name="cart_quantity"]', function(event) {
		event.preventDefault();
		doAddProduct(this); 
	});   

	jQuery('body').on('click', '#checkoutButton', function(event) {
		event.preventDefault();
		var href = jQuery(this).attr('href');
		jQuery("#popup_cart_form").ajaxSubmit({
			target: '#modal_cart_popup .modal-body',
			success: function() {
				showPopupResponse();
				window.location.href = href;
			}
		});
	});  */
	jQuery('body').on('focus', 'input[name="cart_quantity[]"]', function(event) {
		$(this).next('.ok').fadeIn();
	});
	jQuery('body').on('click', '#popup_cart_form .ok', function(event) {
		jQuery("#popup_cart_form").ajaxSubmit({
			target: '#modal_cart_popup .modal-body',
			success: showPopupResponse
		});
		//var options={target:'#modal_cart_popup .modal-body'};
		//ajaxSubmitSerializePopup (options);
	});
	jQuery('body').on('click', '#voucherRedeem', function(event) {
		jQuery("#popup_cart_form").ajaxSubmit({
			target: '#modal_cart_popup .modal-body',
			success: showPopupResponse
		});
		//var options={target:'#modal_cart_popup .modal-body'};
		//ajaxSubmitSerializePopup (options);
	});
	jQuery('body').on('click', '#popup_cart_form .delete', function(event) {
		jQuery("#cart_delete" + jQuery(this).val()).attr('checked', 'checked');
		if (jQuery("#popup_cart_form .delete").length == 1) { // если остался один элемент то скрываем все элементы корзины
			jQuery("#popup_cart_form").animate({
				opacity: 0
			}, 200, function() { // затухание удаляемого элемента
				jQuery("#modal_cart_popup .modal-body").animate({
					height: jQuery("#modal_cart_popup .modal-body").height() - 163
				}, 200, function() { // затухание удаляемого элемента
					jQuery("#popup_cart_form").ajaxSubmit({
						target: '#modal_cart_popup .modal-body',
						success: showPopupResponse
					}); // применяем форму
					//var options={target:'#modal_cart_popup .modal-body'};
					//ajaxSubmitSerializePopup (options);
				});
			});
		} else {
			jQuery(this).parent().parent().slideUp(200, function() { // затухание удаляемого элемента
				jQuery("#popup_cart_form").ajaxSubmit({
					target: '#modal_cart_popup .modal-body',
					success: showPopupResponse
				}); // применяем форму
				//var options={target:'#modal_cart_popup .modal-body'};
				//ajaxSubmitSerializePopup (options);
			});
		}
		var _vall = $(this).val().split('_');
//		$('.r_buy' + jQuery(this).val()).each(function() {
//			$(this).html('<input type="hidden" name="cart_quantity" maxlength="3" size="1" value="1"><input type="hidden" name="products_id" value="' + _vall + '"><button class="btn-primary btn" type="submit">'+IMAGE_BUTTON_ADDTO_CART+'</button>')
//		});
		// Замена кнопки в карточці товару
//		jQuery("#r_buy_intovar").html('<input type="hidden" name="cart_quantity" value="1" maxlength="3" size="3"><input type="hidden" name="products_id" value="' + _vall[0] + '"><button class="btn-primary buy" type="submit">'+IMAGE_BUTTON_ADDTO_CART+'</button>');
	});

	/* ---------  Shopping cart --------- */

});