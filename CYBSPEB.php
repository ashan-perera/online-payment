<?php
class CYBSPEB{
	
	
	
	public function getDefaultForm($OrderID,$PurchaseAmt){
		$SECRET_KEY='d6f12e61ecd74bdca5997a727c00a45d5b1f0525ceed4c46be8e1a41b18ed657eacdc84f2fd04de99d65d2b1510c8f8b74690c16aae64edcaf2da8cab0a2fd8961a411d112074c02b9043c7fdba947610fd107c3783445c4a7738091a3adaa75bcad164bbe97446284d0178b236a73c95ca36aab20ea452197fff76c134fab08';	
		$access_key='2d9185da7bde3ab2ad26d02eb32dd425';
		$profile_id='43590890-F5C8-4F10-AC7F-1882673A79F2';
		//$url='https://secureacceptance.cybersource.com/pay';
		$url='https://testsecureacceptance.cybersource.com/pay';				
		$params=array();
		$params["transaction_uuid"]=uniqid() ;
		$params["access_key"]=$access_key;
		$params["profile_id"]=$profile_id;
		$params["signed_field_names"]="access_key,profile_id,transaction_uuid,signed_field_names,unsigned_field_names,signed_date_time,locale,transaction_type,reference_number,amount,currency";
		$params["unsigned_field_names"]="auth_trans_ref_no,bill_to_forename,bill_to_surname,bill_to_address_line1,bill_to_address_city,bill_to_address_country,bill_to_email";
		$params["signed_date_time"]=gmdate("Y-m-d\TH:i:s\Z");
		$params["locale"]="en";
		$params["transaction_type"]="sale";
		$params["reference_number"]=$OrderID;
		$params["auth_trans_ref_no"]=$OrderID;
		$params["amount"]=$PurchaseAmt;
		$params["currency"]="LKR";
		$params["bill_to_email"]="tsystCybs@peb.org";
		$params["bill_to_forename"]="tsys to cybs";
		$params["bill_to_surname"]="Converter";
		$params["bill_to_address_line1"]="PEB";
		$params["bill_to_address_city"]="Borella";
		$params["bill_to_address_country"]="LK";
		$params["signature"]=$this->sign($params,$SECRET_KEY);
		
		$formtext='<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>';
		$formtext.='<script>$("#payment_confirmation").ready(function(){$("#payment_confirmation").submit();});</script>';
		$formtext.='<form type="hidden" id="payment_confirmation" action="'.$url.'" method="post"/>';
        foreach($params as $name => $value) {
            $formtext.="<input  type='hidden' id='". $name . "' name='" . $name . "' value='" . $value . "'/><br/>";
        }
		$formtext.='</form>';
		
		
		return $formtext;
			
	}
	
	
	private function sign ($params,$SECRET_KEY) {
		return $this->signData($this->buildDataToSign($params),$SECRET_KEY);
	}

	private function signData($data, $secretKey) {
		return base64_encode(hash_hmac('sha256', $data, $secretKey, true));
	}

	private function buildDataToSign($params) {
		$signedFieldNames = explode(",",$params["signed_field_names"]);
		foreach ($signedFieldNames as $field) {
		   $dataToSign[] = $field . "=" . $params[$field];
		}
		return $this->commaSeparate($dataToSign);
	}

	private function commaSeparate ($dataToSign) {
		return implode(",",$dataToSign);
	}
	
	
}


?>