<?php
/**
 * Uuoden Php Web framework
 *
 * @author Di Natale Marco <dinatalemarco90@gmail.com>
 * @package  Object
 */

class Events extends Model{

	use FunctionDB;

	public function __construct(){  

		$this->SetModule("Giostra");
		$this->SetNameService('Events');

		$this->NewTable("events");
		$this->NewEntity("id", INTEGER, array(NOT_NULL=>true, AUTO_INCREMENT=>true));
		$this->NewEntity("borgo", INTEGER, array(NOT_NULL => true, length => 11));
		$this->ForeignKey(array("borgo"),new Borghi());
		$this->NewEntity("date", DATETIME);

		$this->NewEntity("name", VARCHAR, array(NOT_NULL=>true, length=>255));
		$this->NewEntity("descrizione", TEXT, array(length => 65535));
		$this->NewEntity("places", INTEGER, array(NOT_NULL => true, length => 11));

		$this->NewEntity("state", BOOLEAN, array(NOT_NULL=>true));	
		$this->PrimaryKey(array("id"));


	}

}