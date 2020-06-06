<?php
	/*
	 * Copyright (c) 2018.
	 * Author: Tokapps Tm
	 * Programmer: gholamreza beheshtian
	 * mobile: 09353466620
	 * WebSite:http://tokapps.ir
	 */
	
	namespace system\lib;
	
	use SoapClient;
	use yii\base\Model;
	
	class Zarinpal extends Model {
		public $merchant_id = 'fa876130-5ce5-11e9-8067-000c295eb8fc';
		public $callback_url;
		public $testing = false;
		private $_status;
		private $_authority;
		private $_ref_id;
		
		public function request( $amount , $description , $email = null , $mobile = null , $callback_url ) {
			
			$this->callback_url = $callback_url;
			
			if ( $this->testing ) {
				$client = new SoapClient(
				      'https://sandbox.zarinpal.com/pg/services/WebGate/wsdl' ,
				      [ 'encoding' => 'UTF-8' ]
				);
			}
			else {
				$client = new SoapClient(
				      'https://www.zarinpal.com/pg/services/WebGate/wsdl' ,
				      [ 'encoding' => 'UTF-8' ]
				);
			}
			$result = $client->PaymentRequest(
			      [
				    'MerchantID'  => $this->merchant_id ,
				    'Amount'      => $amount ,
				    'Description' => $description ,
				    'Email'       => $email ,
				    'Mobile'      => $mobile ,
				    'CallbackURL' => $this->callback_url ,
			      ]
			);
			
			$this->_status    = $result->Status;
			$this->_authority = $result->Authority;
			
			return $this;
		}
		
		public function verify( $authority , $amount ) {
			$this->_authority = $authority;
			if ( $this->testing ) {
				$client = new SoapClient(
				      'https://sandbox.zarinpal.com/pg/services/WebGate/wsdl' ,
				      [ 'encoding' => 'UTF-8' ]
				);
			}
			else {
				$client = new SoapClient(
				      'https://www.zarinpal.com/pg/services/WebGate/wsdl' ,
				      [ 'encoding' => 'UTF-8' ]
				);
			}
			$result = $client->PaymentVerification(
			      [
				    'MerchantID' => $this->merchant_id ,
				    'Authority'  => $this->_authority ,
				    'Amount'     => $amount ,
			      ]
			);
			
			
			$this->_status = $result->Status;
			$this->_ref_id = $result->RefID;
			
			return $this;
		}
		
		public function getStatus() {
			return $this->_status;
		}
		
		public function getRefId() {
			return $this->_ref_id;
		}
		
		public function getRedirectUrl( $zaringate = true ) {
			if ( $this->testing ) {
				$url = 'https://sandbox.zarinpal.com/pg/StartPay/' . $this->_authority;
			}
			else {
				$url = 'https://www.zarinpal.com/pg/StartPay/' . $this->_authority;
			}
			$url .= ( $zaringate ) ? '/ZarinGate' : '';
			
			return $url;
		}
		
		public function getAuthority() {
			return $this->_authority;
		}
		
		public function errorCode( $code ) {
			switch ( $code ) {
				case - 1:
					return 'اطلاعات وارد شده ناقص ایت';
					break;
				case - 11;
					return 'درخواست مورد نظر یافت نشد(منقضی شده)';
				case - 21:
					return 'هیچ عملیات مالی ای روی این تراکنش انجام نشده است';
					break;
				case - 22:
					return 'تراکنش ناموفق می باشد';
					break;
				case - 33:
					return 'رقم تراکنش با رفع پرداخت شده تطابق ندارد';
					break;
				case 101:
					return 'عملیات پرداخت موفق بوده است، و قبلا تایید شده است';
					break;
			}
		}
	}
