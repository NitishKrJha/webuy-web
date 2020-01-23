<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/*

- Use PAYTM_ENVIRONMENT as 'PROD' if you wanted to do transaction in production environment else 'TEST' for doing transaction in testing environment.
- Change the value of PAYTM_MERCHANT_KEY constant with details received from Paytm.
- Change the value of PAYTM_MERCHANT_MID constant with details received from Paytm.
- Change the value of PAYTM_MERCHANT_WEBSITE constant with details received from Paytm.
- Above details will be different for testing and production environment.

*/


$config['PAYTM_ENVIRONMENT']  = 'TEST' ; // PROD
$config['PAYTM_MERCHANT_KEY']  = 'd2fKCTvmb6YQkfGi' ;//Change this constant's value with Merchant key downloaded from portal //DC1WExNwmTzmiF_q
$config['PAYTM_MERCHANT_MID']  = 'STAKE487720997293088' ;//Change this constant's value with MID (Merchant ID) received from Paytm //vaptec60439499324403
$config['PAYTM_MERCHANT_WEBSITE']  = 'WEB_STAGING' ; //Change this constant's value with Website name received from Paytm
$PAYTM_DOMAIN = "pguat.paytm.com";
/*if (PAYTM_ENVIRONMENT == 'PROD') {
	$PAYTM_DOMAIN = 'secure.paytm.in';
}*/
$config['PAYTM_REFUND_URL']  = 'https://'.$PAYTM_DOMAIN.'/oltp/HANDLER_INTERNAL/REFUND' ;
$config['PAYTM_STATUS_QUERY_URL']  = 'https://'.$PAYTM_DOMAIN.'/oltp/HANDLER_INTERNAL/TXNSTATUS' ;
$config['PAYTM_STATUS_QUERY_NEW_URL']  = 'https://'.$PAYTM_DOMAIN.'/oltp/HANDLER_INTERNAL/getTxnStatus';
$config['PAYTM_TXN_URL']  = 'https://'.$PAYTM_DOMAIN.'/oltp-web/processTransaction' ;
$PAYTM_STATUS_QUERY_NEW_URL='https://securegw-stage.paytm.in/merchant-status/getTxnStatus';
$PAYTM_TXN_URL='https://securegw-stage.paytm.in/theia/processTransaction';

if (PAYTM_ENVIRONMENT == 'PROD') {
	$PAYTM_STATUS_QUERY_NEW_URL='https://securegw.paytm.in/merchant-status/getTxnStatus';
	$PAYTM_TXN_URL='https://securegw.paytm.in/theia/processTransaction';
}
// define('PAYTM_REFUND_URL', '');
// define('PAYTM_STATUS_QUERY_URL', $PAYTM_STATUS_QUERY_NEW_URL);
// define('PAYTM_STATUS_QUERY_NEW_URL', $PAYTM_STATUS_QUERY_NEW_URL);
// define('PAYTM_TXN_URL', $PAYTM_TXN_URL);
?>