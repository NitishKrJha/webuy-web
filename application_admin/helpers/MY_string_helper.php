<?php

   //this function formates currency according to format argument passed

   if ( ! function_exists('formate_currency'))

	{

		function formate_currency($amount,$country='India',$number_format=true)

		{

			$format	=	array(

							"India"=>array("P"=>"INR ","S"=>""),

							"USA"=>array("P"=>"&dollar; ","S"=>"USD")

					);

			if(!array_key_exists($country, $format)){

				$country='India';

			}

			$return_format = $amount;

			$prefix = $format[$country]['P'];

			$suffix = $format[$country]['S'];

			if($number_format){

				$return_format = $prefix.number_format($amount,2,'.','').' '.$suffix;

			}else{

				$return_format = $prefix.$amount.' '.$suffix;

			}

			return trim($return_format);

		}

	}

