<?php
	/**
	 * Copyright (c) 2018.
	 * Author: Tokapps Tm
	 * Programmer: gholamreza beheshtian
	 * mobile: 09353466620
	 * WebSite:http://tokapps.ir
	 *
	 *
	 */
	
	namespace system\lib;
	
	use BadMethodCallException;
	use common\models\Transactions;
	use Exception;
	use system\lib\payments\payir\SendException;
	use system\lib\payments\payir\Traits\Data;
	use system\lib\payments\payir\Traits\Request;
	use system\lib\payments\payir\VerifyException;
	use Yii;
	use yii\base\Component;
	use yii\web\BadRequestHttpException;
	use yii\web\ForbiddenHttpException;
	use yii\web\NotFoundHttpException;
	
	class payir extends Component {
		
		use Data , Request;
		
		public $transqId;
		
		public function __construct() {
			$this->factorNumber = null;
		}
		
		public function ready( $amount , $factor , $callback , $model = null ) {
			
			$params                 = [];
			$params['api']          = '1c69f451543c310b5707caee2308885b';
			$params['amount']       = $amount;
			$params['factorNumber'] = $factor;
			$params['redirect']     = $callback;
			
			
			$res = $this->send_request( "https://pay.ir/payment/send" , $params , false );
			
			
			if ( ! empty( $res ) ) {
				if ( empty( $res->status ) ) {
					throw new NotFoundHttpException( $res->errorMessage );
				}
				
			} else {
				throw new NotFoundHttpException( 'ارتباط با سایت پرداخت انجام نشد، لطفا مجددا امتحان کنید' );
			}
			if ( $res->status == 1 ) {
				
				$this->transId = $res->transId;
				return $res;
				
			} else {
				throw new NotFoundHttpException( $res->errorCode );
			}
			
			
		}
		
		public function start() {
			
			return Yii::$app->response->redirect( "https://pay.ir/payment/gateway/" . $this->transId );
		}
		
		public function verify( ) {
			$params['api']     = '1c69f451543c310b5707caee2308885b';
			$params['transId'] = $_REQUEST['transId'];
			
			
			
			$res = $this->send_request( "https://pay.ir/payment/verify" , $params );
			
			if ( $res->status != 1 ) {
//            $transaction->status=$model::PAY_STATUS_FAILED;
//            $transaction->save();
				throw new NotFoundHttpException( $res->errorCode );
			}
			
			
			
			return $res;
		}
	}
