<?php
/**
 * Uuoden Php Web framework
 *
 * @author Di Natale Marco <dinatalemarco90@gmail.com>
 * @package  Object
 */

class Reservations extends Model{

	use FunctionDB;

	public function __construct(){  

		$this->SetModule("Giostra");
		$this->SetNameService('Reservations');

		$this->NewTable("reservations");
		$this->NewEntity("id_event", INTEGER, array(NOT_NULL => true, length => 11));
		$this->NewEntity("id_user", INTEGER, array(NOT_NULL => true, length => 11 ));
		$this->ForeignKey(array("id_event"),new Events());
		$this->ForeignKey(array("id_user"),new Users());
		$this->NewEntity("places", INTEGER, array(NOT_NULL => true, length => 11));
		$this->NewEntity("date", DATETIME);		
		
		$this->NewEntity("state", BOOLEAN, array(NOT_NULL=>true));	
		$this->PrimaryKey(array("id_event","id_user"));


	}

}