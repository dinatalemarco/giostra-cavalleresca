<?php
/**
 * Uuoden Php Web framework
 *
 * @author Di Natale Marco <dinatalemarco90@gmail.com>
 * @package  Object
 */


class Borghi extends Model{

	use FunctionDB;

	public function __construct(){

		$this->SetModule("Giostra");
		$this->SetNameService('Borghi');

		$this->NewTable("borghi");
		$this->NewEntity("id", INTEGER, array(NOT_NULL=>true, AUTO_INCREMENT=>true));
		$this->NewEntity("nome", VARCHAR, array(NOT_NULL=>true, length=>255));	
		$this->NewEntity("motto", VARCHAR, array(NOT_NULL=>true, length=>255));	
		$this->NewEntity("cavaliere", VARCHAR, array(NOT_NULL=>true, length=>255));
		$this->NewEntity("capitano", VARCHAR, array(NOT_NULL=>true, length=>255));
		$this->NewEntity("descrizione", TEXT, array(length => 65535));	
		$this->NewEntity('stemma', VARCHAR, array(NOT_NULL=>true, length=>255));
		$this->NewEntity('quartiere', VARCHAR, array(NOT_NULL=>true, length=>255));
		$this->Unique(array("nome"));
		$this->PrimaryKey(array("id"));

	}
}







