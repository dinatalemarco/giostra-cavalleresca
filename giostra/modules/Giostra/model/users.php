<?php
/**
 * Uuoden Php Web framework
 *
 * @author Di Natale Marco <dinatalemarco90@gmail.com>
 * @package  Object
 */

class Users extends Model{

	use FunctionDB;

	public function __construct(){

		$this->SetModule("Giostra");
		$this->SetNameService("Users");

		$this->NewTable("users");
		$this->NewEntity("id", INTEGER, array(NOT_NULL => true, AUTO_INCREMENT => true));
		$this->NewEntity("name", VARCHAR, array(NOT_NULL => true, length => 255));
		$this->NewEntity("surname", VARCHAR, array(NOT_NULL => true, length => 255));
		$this->NewEntity("email", VARCHAR, array(NOT_NULL => true, length => 255));
		$this->NewEntity("password", CHAR, array(NOT_NULL => true, length => 255));
		$this->NewEntity("encryption_key", CHAR, array(NOT_NULL => true, length => 255));
	
		$this->Unique(array("email"));
		$this->PrimaryKey(array("id"));
		

			$Validate = new Validate();
			$Validate->SetRegularExpression('email',"/^[a-z0-9][_.a-z0-9-]+@([a-z0-9][0-9a-z-]+.)+([a-z]{2,4})/");
			$Validate->SetRegularExpression('password',Validate::$PASSWORD);
			$this->SetValidators($Validate);




	}
}