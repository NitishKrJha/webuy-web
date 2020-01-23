<?php
  function callcurl($url){
	  $curl_handle=curl_init();
	  curl_setopt($curl_handle,CURLOPT_URL,$url);
	  curl_setopt($curl_handle,CURLOPT_CONNECTTIMEOUT,2);
	  curl_setopt($curl_handle,CURLOPT_RETURNTRANSFER,1);
	  $buffer = curl_exec($curl_handle);
	  curl_close($curl_handle);
	  if (empty($buffer)){
		  return "Nothing returned from url.<p>";
	  }
	  else{
		  return $buffer;
	  }
  }
  callcurl('http://vaptechdesigns.co.in/codeigniter/rentingstreet/cron/checkPlanDate');
  callcurl('http://vaptechdesigns.co.in/codeigniter/rentingstreet/cron/adsFiveDaysBeforeReminder');
  callcurl('http://vaptechdesigns.co.in/codeigniter/rentingstreet/cron/adsOneDaysBeforeReminder');
  callcurl('http://vaptechdesigns.co.in/codeigniter/rentingstreet/cron/update_featureads_status');