<?php
/*
  One Page Checkout, Version: 1.08

  I.T. Web Experts
  http://www.itwebexperts.com

  Copyright (c) 2009 I.T. Web Experts

  Released under the GNU General Public License
*/

define('NAVBAR_TITLE', 'Checkout order');
define('NAVBAR_TITLE_1', 'Checkout order');
define('HEADING_TITLE', 'Checkout order');
define('TABLE_HEADING_SHIPPING_ADDRESS', 'Shipping address');
define('TABLE_HEADING_BILLING_ADDRESS', 'Your info:');
define('TABLE_HEADING_PRODUCTS_MODEL', 'Name');
define('TABLE_HEADING_PRODUCTS_PRICE', 'Price');
define('TABLE_HEADING_PRODUCTS', 'Cart');
define('TABLE_HEADING_TAX', 'Tax');
define('TABLE_HEADING_TOTAL', 'Total');
define('ENTRY_TELEPHONE', 'Phone number');
define('ENTRY_COMMENT', 'Comment');
define('TABLE_HEADING_SHIPPING_METHOD', 'Shipping method');
define('TABLE_HEADING_PAYMENT_METHOD', 'Payment method');
define('TEXT_CHOOSE_SHIPPING_METHOD', '');
define('TEXT_SELECT_PAYMENT_METHOD', '');
define('TEXT_ENTER_SHIPPING_INFORMATION', 'This is currently the only shipping method available to use on this order.');
define('TEXT_ENTER_PAYMENT_INFORMATION', 'This is currently the only payment method available to use on this order.');
define('TABLE_HEADING_COMMENTS', 'Comments:');
define('TITLE_CONTINUE_CHECKOUT_PROCEDURE', 'Continue checkout');
define('EMAIL_SUBJECT', 'Welcome to ' . STORE_NAME);
define('EMAIL_GREET_MR', 'Dear %s,' . "\n\n");
define('EMAIL_GREET_MS', 'Dear %s,' . "\n\n");
define('EMAIL_GREET_NONE', 'Dear(ая) %s' . "\n\n");
define('EMAIL_WELCOME', 'Welcome to <b>' . STORE_NAME . '</b>.' . "\n\n");
define('EMAIL_TEXT', 'Now you have access to some additional features that are available to signed in users only <br /><Li> <b> Cart </b> - Any products added to the cart, remain there until you eather delete or order them<br /><Li> <b> Address Book </b> - We can now send our products to the address you specified in "Delivery address".<br /><Li> <b> Order History </b> - you have the opportunity to look through the history of orders in our store.');
define('EMAIL_CONTACT', 'If you have questions, email us: ' . STORE_OWNER_EMAIL_ADDRESS . '.' . "\n\n");
define('EMAIL_WARNING', '');

// Start - CREDIT CLASS Gift Voucher Contribution
define('EMAIL_GV_INCENTIVE_HEADER', "\n\n" .'As part of our welcome to new customers, we have sent you an e-Gift Voucher worth %s');
define('EMAIL_GV_REDEEM', 'The redeem code for the e-Gift Voucher is %s, you can enter the redeem code when checking out while making a purchase');
define('EMAIL_GV_LINK', 'or by following this link ');
define('EMAIL_COUPON_INCENTIVE_HEADER', 'Congratulations, to make your first visit to our online shop a more rewarding experience we are sending you an e-Discount Coupon.' . "\n" .
										' Below are details of the Discount Coupon created just for you' . "\n");
define('EMAIL_COUPON_REDEEM', 'To use the coupon enter the redeem code which is %s during checkout while making a purchase');
// End - CREDIT CLASS Gift Voucher Contribution
define('TEXT_PLEASE_SELECT', 'Select');
define('TEXT_PASSWORD_FORGOTTEN', 'Forgot password?');
define('IMAGE_LOGIN', 'Login');
define('TEXT_HAVE_COUPON_KGT', 'Have A Coupon?');
define('TEXT_DIFFERENT_SHIPPING', 'Different from billing address?');
// Points/Rewards Module V2.1rc2a EOF
define('TEXT_MIN_SUM', 'MINIMUM ORDER PRICE');
define('TEXT_NEW_CUSTOMER', 'New customer');
define('TEXT_LOGIN_SOCIAL', 'Facebook login');

define('TEXT_EMAIL_EXISTS', 'This email is already registered, please');
define('TEXT_EMAIL_EXISTS2', 'login');
define('TEXT_EMAIL_EXISTS3', 'via your email');
define('TEXT_EMAIL_WRONG', 'you entered wrong email');
define('TEXT_ORDER_PROCESSING', 'Order processing, please wait...');
define('TEXT_EMAIL_LOGIN', 'Login');
define('TEXT_EMAIL_PASS', 'Password');

define('CH_JS_REFRESH', 'Refreshing');
define('CH_JS_REFRESH_METHOD', 'Refreshing method:');
define('CH_JS_SETTING_METHOD', 'Setting method:');
define('CH_JS_SETTING_ADDRESS', 'Setting address:');
define('CH_JS_SETTING_ADDRESS_BIL', 'Billing');
define('CH_JS_SETTING_ADDRESS_SHIP', 'Shipping');

define('CH_JS_ERROR_SCART', 'There was an error refreshing shopping cart, please inform');
define('CH_JS_ERROR_SOME1', 'refreshing');
define('CH_JS_ERROR_SOME2', 'There was an error, please inform');

define('CH_JS_ERROR_SET_SOME1', 'There was an error setting');
define('CH_JS_ERROR_SET_SOME2', 'method, please inform');
define('CH_JS_ERROR_SET_SOME3', 'about this error.');

define('CH_JS_ERROR_REQ_BIL', 'Please fill in required fields in section "Payment Address"');
define('CH_JS_ERROR_ERR_BIL', 'Please check fields in section "Payment Address"');
define('CH_JS_ERROR_REQ_SHIP', 'Please fill in required fields in section "Shipping Address"');
define('CH_JS_ERROR_ERR_SHIP', 'Please check fields in section "Shipping Address"');
define('CH_JS_ERROR_ADDRESS', 'Address error');
define('CH_JS_ERROR_PMETHOD', 'Error selecting payment method');
define('CH_JS_ERROR_SELECT_PMETHOD', 'Please select payment method');
define('CH_JS_CHECK_EMAIL', 'Checking your email address');
define('CH_JS_ERROR_EMAIL', 'There was an error checking email addresses, please inform');
?>