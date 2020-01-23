<?php if (!defined('BASEPATH')) exit('No direct script access allowed');
//adding config items.
	$sandbox = TRUE;
	$config['api_version'] = '204.0';
	$config['api_endpoint'] = $sandbox ? 'https://api-3t.sandbox.paypal.com/nvp' : 'https://api-3t.paypal.com/nvp';
	$config['api_username'] = $sandbox ? 'bhanupay24-facilitator_api1.gmail.com' : '';
	$config['api_password'] = $sandbox ? 'TW2MFTYRC3J8EHNR' : '';
	$config['api_signature'] = $sandbox ? 'AFcWxV21C7fd0v3bYYYRCpSSRl31AlyWBKjjQis0slG8cF2cZsqgj0tS' : '';

