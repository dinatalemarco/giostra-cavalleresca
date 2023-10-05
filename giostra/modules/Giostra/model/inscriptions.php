<?php
/**
 * Uuoden Php Web framework
 *
 * @author Di Natale Marco <dinatalemarco90@gmail.com>
 * @package  Object
 */

class Inscriptions extends Model{

	use FunctionDB;

	public function __construct(){  

		$this->SetModule("Giostra");
		$this->SetNameService('Inscriptions');

		$this->NewTable("inscriptions");
		$this->NewEntity("id", INTEGER, array(NOT_NULL=>true, AUTO_INCREMENT=>true));
		$this->NewEntity("id_borgo", INTEGER, array(NOT_NULL => true, length => 11));
		$this->NewEntity("id_user", INTEGER, array(NOT_NULL => true, length => 11 ));
		$this->NewEntity("id_role", INTEGER, array(NOT_NULL => true, length => 11 ));
		$this->ForeignKey(array("id_borgo"),new Borghi());
		$this->ForeignKey(array("id_user"),new Users());
		$this->ForeignKey(array("id_role"),new Roles());		
		$this->NewEntity("year", DATE);	
		$this->NewEntity("state", BOOLEAN, array(NOT_NULL=>true));	
		$this->Unique(array("id_user"));
		$this->PrimaryKey(array("id"));


	}

}






