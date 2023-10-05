<?php
/**
 * Uuoden Php Web framework
 *
 * @author Di Natale Marco <dinatalemarco90@gmail.com>
 * @package  Object
 */


class Palii extends Model{

	use FunctionDB;

	public function __construct(){

		$this->SetModule("Giostra");
		$this->SetNameService('Palii');

		$this->NewTable("palii");
		$this->NewEntity("id", INTEGER, array(NOT_NULL=>true, AUTO_INCREMENT=>true));
		$this->NewEntity("anno", DATE);	
		$this->NewEntity("autore", VARCHAR, array(NOT_NULL=>true, length=>255));
		$this->NewEntity("cavaliere", VARCHAR, array(NOT_NULL=>true, length=>255));	
		$this->NewEntity("borgo", INTEGER, array(NOT_NULL => true, length => 11));
		$this->ForeignKey(array("borgo"),new Borghi());
		$this->PrimaryKey(array("id"));	


	}
}


