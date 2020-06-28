<?php
echo "By: Tra\n";
echo "VERIF EMAIL MANUAL, BUKA MAILNESIA.COM\n";
echo "?Jumlah	";
$b = trim(fgets(STDIN));
for($a=0;$a<$b;$a++){
	$email = "oudurak".rand(12345678,99999999)."@mailnesia.com";
	echo "[EMAIL : ".strtoupper($email)."]\n";
	MakeAccount:
		$asw = @file_get_contents("bt_booking_com.txt");
		first();
		echo "\t**REGISTER...";
		$token = reg($email);
		//$token = login($email);
		if(empty($token)) die("[ERROR!!]\n");
		currency($token);
		echo " [SUCCESS]\n";
		echo "\t**ADD WISHLIST 1...";
		$listId = createWishlist($token);
		if(empty($listId)) die("[ERROR!!]\n");
		echo " [SUCCESS]\n";
		$hotelId = 2;
		AddWishlist:
			echo "\t**ADD WISHLIST $hotelId...";
			$add = addWishlist($token, $hotelId, $listId);
			if($add['success']==0) die("[ERROR!!]\n");
			echo " [SUCCESS]\n";
			$hotelId += 1;
			if($hotelId!=4) goto AddWishlist;
			$status = strtoupper($add['gta_add_three_items_campaign_status']['status']);
		@file_put_contents("akun_booking_com.txt", "$email|asdqwe123|$token\n", FILE_APPEND);
		echo "\t**DATA RESULT:\n\t\tTOKEN : $token\n\t\tEMAIL : $email\n\t\tPASSWORD : asdqwe123\n\t\tREWARD STATUS : $status\n";
}
function getStr($a, $b, $c){
	return @explode($b, @explode($a, $c)[1])[0];
}

function first(){
	$ch = curl_init();
	curl_setopt($ch, CURLOPT_URL, 'https://iphone-xml.booking.com/json/bookings.getCurrencyExchangeRates?device_id=44444444444444444444444444444444&affiliate_id=1&languagecode=en-us&user_version=24.1-iphone&network_type=none&base_currency=KWD&user_os=14.0');
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
	curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'GET');
	$headers = array();
	$headers[] = 'Host: iphone-xml.booking.com';
	$headers[] = 'Accept: */*';
	$headers[] = 'Connection: close';
	$headers[] = 'X-Booking-Api-Version: 1';
	$headers[] = 'User-Agent: Booking.App/24.1 iOS/14.0; Type: phone; AppStore: apple; Brand: Apple; Model: iPhone12,5;';
	$headers[] = 'Accept-Language: en-us';
	$headers[] = 'Authorization: Basic dGhlc2FpbnRzYnY6ZGdDVnlhcXZCeGdN';

	curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
	curl_setopt($ch, CURLOPT_HEADER, true);
	$result = curl_exec($ch);
	curl_close($ch);
	$bts = getStr("b-t-s: ", "\n", str_replace("\r", "", $result));
	@file_put_contents("bt_booking_com.txt", $bts);
	return $bts;
}

function reg($email){
	global $asw;
	$ch = curl_init();
	curl_setopt($ch, CURLOPT_URL, 'https://iphone-xml.booking.com/json/mobile.createUserAccount');
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
	curl_setopt($ch, CURLOPT_POST, 1);
	curl_setopt($ch, CURLOPT_POSTFIELDS, "{\"return_auth_token\":1,\"password\":\"asdqwe123\",\"device_id\":\"44444444444444444444444444444444\",\"user_version\":\"24.1-iphone\",\"email\":\"$email\",\"language\":\"en-us\",\"network_type\":\"wifi\",\"languagecode\":\"en-us\",\"user_os\":\"14.0\",\"affiliate_id\":\"337862\"}");
	$headers = array();
	$headers[] = 'Host: iphone-xml.booking.com';
	$headers[] = 'Authorization: Basic dGhlc2FpbnRzYnY6ZGdDVnlhcXZCeGdN';
	$headers[] = 'Accept: */*';
	$headers[] = 'X-Booking-Api-Version: 1';
	$headers[] = 'Accept-Language: en-us';
	$headers[] = 'Content-Type: application/json';
	$headers[] = 'B-T: '.$asw;
	$headers[] = 'User-Agent: Booking.App/24.1 iOS/14.0; Type: phone; AppStore: apple; Brand: Apple; Model: iPhone12,5;';
	$headers[] = 'Connection: close';

	curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
	$result = curl_exec($ch);
	curl_close($ch);
	return @json_decode($result, true)['auth_token'];
}

function currency($token){
	global $asw;
	$ch = curl_init();
	curl_setopt($ch, CURLOPT_URL, 'https://mobile-apps.booking.com/json/mobile.products?currency_code=KWD&user_latitude=&device_id=44444444444444444444444444444444&user_longitude=&affiliate_id=332731&app_supports_gem_rewards=1&user_version=24.1-iphone&languagecode=en-us&network_type=wifi&user_os=14.0&auth_token='.$token);
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
	curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'GET');
	$headers = array();
	$headers[] = 'Host: mobile-apps.booking.com';
	$headers[] = 'Accept: */*';
	$headers[] = 'Connection: close';
	$headers[] = 'Authorization: Basic dGhlc2FpbnRzYnY6ZGdDVnlhcXZCeGdN';
	$headers[] = 'B-T: '.$asw;
	$headers[] = 'X-Booking-Api-Version: 1';
	$headers[] = 'User-Agent: Booking.App/24.1 iOS/14.0; Type: phone; AppStore: apple; Brand: Apple; Model: iPhone12,5;';
	$headers[] = 'Accept-Language: en-us';
	curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
	$result = curl_exec($ch);
	curl_close($ch);
	return $result;
}

function createWishlist($token){
	global $asw;
	$ch = curl_init();
	curl_setopt($ch, CURLOPT_URL, 'https://mobile-apps.booking.com/json/mobile.Wishlist?num_children=0&user_os=14.0&num_rooms=1&num_adults=2&wishlist_action=create_new_wishlist&affiliate_id=337862&use_list_details=1&user_version=24.1-iphone&network_type=wifi&checkout=2020-06-29&languagecode=en-us&desc=&user_latitude=&auth_token='.$token.'&checkin=2020-06-28&device_id=44444444444444444444444444444444&hotel_id=1&name=Aasdasd&user_longitude=');
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
	curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'GET');
	$headers = array();
	$headers[] = 'Host: mobile-apps.booking.com';
	$headers[] = 'Accept: */*';
	$headers[] = 'Connection: close';
	$headers[] = 'Authorization: Basic dGhlc2FpbnRzYnY6ZGdDVnlhcXZCeGdN';
	$headers[] = 'B-T: '.$asw;
	$headers[] = 'X-Booking-Api-Version: 1';
	$headers[] = 'User-Agent: Booking.App/24.1 iOS/14.0; Type: phone; AppStore: apple; Brand: Apple; Model: iPhone12,5;';
	$headers[] = 'Accept-Language: en-us';

	curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
	$result = curl_exec($ch);
	curl_close($ch);
	return @json_decode($result,true)['id'];
}

function addWishlist($token, $hotelId, $listId){
	global $asw;
	$ch = curl_init();
	curl_setopt($ch, CURLOPT_URL, 'https://mobile-apps.booking.com/json/mobile.Wishlist?update_list_search_config=1&user_os=14.0&affiliate_id=337862&wishlist_action=save_hotel_to_wishlists&auth_token='.$token.'&list_ids='.$listId.'&user_version=24.1-iphone&network_type=wifi&languagecode=en-us&user_latitude=&new_states=1&device_id=44444444444444444444444444444444&hotel_id='.$hotelId.'&user_longitude=');
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
	curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'GET');
	$headers = array();
	$headers[] = 'Host: mobile-apps.booking.com';
	$headers[] = 'Accept: */*';
	$headers[] = 'Connection: close';
	$headers[] = 'Authorization: Basic dGhlc2FpbnRzYnY6ZGdDVnlhcXZCeGdN';
	$headers[] = 'B-T: '.$asw;
	$headers[] = 'X-Booking-Api-Version: 1';
	$headers[] = 'User-Agent: Booking.App/24.1 iOS/14.0; Type: phone; AppStore: apple; Brand: Apple; Model: iPhone12,5;';
	$headers[] = 'Accept-Language: en-us';

	curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
	$result = curl_exec($ch);
	curl_close($ch);
	return @json_decode($result,true);
}

function cekReward($token){
	global $asw;
	$ch = curl_init();
	curl_setopt($ch, CURLOPT_URL, 'https://mobile-apps.booking.com/json/mobile.getRewards?user_latitude=&device_id=44444444444444444444444444444444&user_longitude=&affiliate_id=337862&app_supports_gem_rewards=1&user_version=24.1-iphone&languagecode=en-us&network_type=wifi&user_os=14.0&auth_token='.$token);
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
	curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'GET');
	$headers = array();
	$headers[] = 'Host: mobile-apps.booking.com';
	$headers[] = 'Accept: */*';
	$headers[] = 'Connection: close';
	$headers[] = 'Authorization: Basic dGhlc2FpbnRzYnY6ZGdDVnlhcXZCeGdN';
	$headers[] = 'B-T: '.$asw;
	$headers[] = 'X-Booking-Api-Version: 1';
	$headers[] = 'User-Agent: Booking.App/24.1 iOS/14.0; Type: phone; AppStore: apple; Brand: Apple; Model: iPhone12,5;';
	$headers[] = 'Accept-Language: en-us';

	curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
	$result = curl_exec($ch);
	curl_close($ch);
	return $result;
}
