<?php

namespace Original\Validation;

class Fieldsetex extends \Fuel\Core\Fieldset
{
	
	/**
	* fieldsetの$_instancesを初期化するメソッド
	* Form instance already exists, cannot be created. Use instance() instead of forge() to retrieve the existing instance.array(0) { }
	* Validationのrunを繰り返すと、このエラーが出るため、この拡張が必要になる
	* http://qiita.com/masahikoofjoyto/items/206e6f8d5b0aa7126678
	*
	*/
	public static function reset(){
		parent::$_instances = array();
	}
	
}