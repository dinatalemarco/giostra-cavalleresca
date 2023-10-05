<?php
/**
 *
 * @author Di Natale Marco <dinatalemarco90@gmail.com>
 * @author Ciavarro Cristina <cristina.ciavarro@gmail.com>
 * @package  business
 */

require_once(dirname(__FILE__) .'/../EventsManagement.php');	


class EventsManagementsImpl implements EventsManagement{



    /*
     * getList()
     *
     * @return array()
     *
     */ 

    public function getList(){

		$Events = new Events();
		$Reservations = new Reservations();
		$date = date('Y-m-d H:i:s');
		$result = null;
		$value = null;    	

		try {

			    $list = $Events->Select(array('e.id',
			    						      'e.borgo',
			    							  'e.date',
			    							  'e.name',
			    							  'e.descrizione',
			    							  'e.places',
			    							  'b.nome',
			    							  'b.stemma'),'e')
							   ->LeftJoin('e',new Borghi(), 'b' , 'e.borgo = b.id')
							   ->Where('e.date >= :current && e.state = :state',
							   	       array(':current' => $date,':state' => 1))
	                           ->Result();

	            for ($i=0; $i < count($list) ; $i++) { 
	            	$listInscription = $Reservations->Select()
	            								    ->Where('id_event = :id_event && state = :state',
	            								    	    array(':id_event' => $list [$i]['id'],
	            								    			  ':state' => 1))
	            								    ->Result();
	            	if (isset($listInscription[0])) {
	            		$list[$i]['remainingplaces'] = $list[$i]['places'];
	            		for ($k=0; $k < count($listInscription) ; $k++) { 
	            			$list[$i]['remainingplaces'] = $list[$i]['remainingplaces']-$listInscription[$k]['places'];
	            		}
	            	}else{
	            		$list[$i]['remainingplaces'] = $list[$i]['places'];
	            	}

	            }

	            if(isset($list[0])){
					$result['status_code'] = 200;
					$result['body']['message'] = "Information retrieval happened successfully";
					$result['body']['response'] = $list;	            	
	            }else{
					$result['status_code'] = 404;
					$result['body']['message'] = "No information was found for this resource";
	            }

		} catch (Exception $e) {
		
			$result['status_code'] = 500;
			$result['body']['message'] = "There was an unexpected error"; 

		}

		return $result;

    }


    /*
     * getEvent()
     *
     * @param $id_event integer
     * @return array()
     *
     */ 

    public function getEvent($id_event){

		$Events = new Events();
		$Reservations = new Reservations();
		$result = null;
		$value = null;    	

		try {

			if($Events->ValidateParameter('id',$id_event)){

			    $list = $Events->Select(array('e.id',
			    							  'e.date',
			    							  'e.name',
			    							  'e.descrizione',
			    							  'e.places',
			    							  'e.state',
			    							  'b.nome',
			    							  'b.stemma'),'e')
							   ->LeftJoin('e',new Borghi(), 'b' , 'e.borgo = b.id')
							   ->Where('e.id = :id',array(':id' => $id_event))
	                           ->Result();

	            if(isset($list[0])){
					$result['status_code'] = 200;
					$result['body']['message'] = "Information retrieval happened successfully";
					$result['body']['response'] = $list[0];
	            }else{
					$result['status_code'] = 404;
					$result['body']['message'] = "No information was found for this resource";	            	
	            }
	        }else{
    			// I parametri passati dall'utente non sono conformi all'input richiesto
				$result['status_code'] = 412;
				$result['body']['message'] = "The input parameters are not correct";
	        }


		} catch (Exception $e) {
		
			$result['status_code'] = 500;
			$result['body']['message'] = "There was an unexpected error"; 

		}

		return $result;

    }



    /*
     * getListByBorgo()
     *
     * @param $id_borgo integer
     * @return array()
     *
     */ 

    public function getListByBorgo($id_borgo){

		$Events = new Events();
		$Inscriptions = new Inscriptions();
		$Reservations = new Reservations();
		$date = date('Y-m-d H:i:s');
		$result = null;
		$value = null;    	

		try {

	    if($Events->ValidateParameter('borgo',$id_borgo)){

			    $list = $Events->Select(array('e.id',
			    							  'e.date',
			    							  'e.name',
			    							  'e.descrizione',
			    							  'e.places',
			    							  'e.state',
			    							  'b.nome',
			    							  'b.stemma'),'e')
							   ->LeftJoin('e',new Borghi(), 'b' , 'e.borgo = b.id')
							   ->Where('e.borgo = :borgo',array(':borgo' => $id_borgo))
	                           ->Result();

	            for ($i=0; $i < count($list) ; $i++) { 
	            	$listInscription = $Reservations->Select()
	            								    ->Where('id_event = :id_event && state = :state',
	            								    	    array(':id_event' => $list [$i]['id'],
	            								    			  ':state' => 1))
	            								    ->Result();
	            	if (isset($listInscription[0])) {
	            		$list[$i]['remainingplaces'] = 0;
	            		for ($k=0; $k < count($listInscription) ; $k++) { 
	            			$list[$i]['remainingplaces'] = $list[$i]['remainingplaces']+$listInscription[$k]['places'];
	            		}
	            	}else{
	            		$list[$i]['remainingplaces'] = 0;
	            	}

	            }

	            if(isset($list[0])){
					$result['status_code'] = 200;
					$result['body']['message'] = "Information retrieval happened successfully";
					$result['body']['response'] = $list;
	            }else{
					$result['status_code'] = 404;
					$result['body']['message'] = "No information was found for this resource";            	
	            }

		}else{
			// Validazione parametri fallita
			$result['status_code'] = 412;
			$result['body']['message'] = "The input parameters are not correct";			
		}


		} catch (Exception $e) {
		
			$result['status_code'] = 500;
			$result['body']['message'] = "There was an unexpected error"; 

		}

		return $result;

    }



    /*
     * addEvent()
     *
     * @param $id_user_action integer
     * @param $id_borgo integer
     * @param $date string
     * @param $name string
     * @param $description string
     * @param $places string
     * @param $state string
     * @return array()
     *
     */ 

    public function addEvent($id_user_action,$id_borgo,$date,$name,$description,$places,$state){

		$Events = new Events();
		$InscriptionsManagement = new InscriptionsManagementImpl();
		$Inscriptions = new Inscriptions();

		$result = null;
		$value = null;  

		try {
	
			// Procedo alla validazione dei campi
			if($Events->ValidateParameter('date',$date) &&
			   $Events->ValidateParameter('name',$name) &&
			   $Events->ValidateParameter('descrizione',$description) &&
			   $Events->ValidateParameter('places',$places) &&
			   $Events->ValidateParameter('state',$state)){

				$ValidatePermits = $InscriptionsManagement->checkPermissions('CREATEEVENTS',
																		     $id_user_action,
																		     $id_borgo);
			
				if($ValidatePermits['bool']){

					$value['borgo'] = $id_borgo;
					$value['date'] = $date;
					$value['name'] = $name;
					$value['descrizione'] = $description;
					$value['places'] = $places;
					$value['state'] = $state;

					$controlIns = $Events->Insert($value);

					if($controlIns->getBoolean()){
						// Inserimento avvenuto con successo
						$result['status_code'] = 201;
						$result['body']['message'] = "Insert occurred successfully";
					}else{
						// Si è verificato un errore durante l'inserimento
						$result['status_code'] = 422;
						$result['body']['message'] = "Unprocessable entity";
					}


				}else{
					/* Pemesso negato */
					$result['status_code'] = 403;
					$result['body']['message'] = $ValidatePermits['message'];

				}	

			}else{
			 	// Validazione parametri fallita
				$result['status_code'] = 412;
				$result['body']['message'] = "The input parameters are not correct";
			}	

		} catch (Exception $e) {
		
			$result['status_code'] = 500;
			$result['body']['message'] = "There was an unexpected error"; 

		}			


		return $result;

    }    


    /*
     * updateEvent()
     *
     * @param $id_user_action integer
     * @param $id_borgo integer
     * @param $id_event integer
     * @param $date string
     * @param $name string
     * @param $description string
     * @param $places string
     * @param $state string
     * @return array()
     *
     */ 

    public function updateEvent($id_user_action,$id_borgo,$id_event,$date,$name,$description,$places,$state){

		$Events = new Events();
		$InscriptionsManagement = new InscriptionsManagementImpl();
		$Inscriptions = new Inscriptions();

		$result = null;
		$value = null;  
		$key = null; 
		
		try {

			// Procedo alla validazione dei campi
			if($Events->ValidateParameter('date',$date) &&
			   $Events->ValidateParameter('name',$name) &&
			   $Events->ValidateParameter('descrizione',$description) &&
			   $Events->ValidateParameter('places',$places) &&
			   $Events->ValidateParameter('state',$state)){

				$ValidatePermits = $InscriptionsManagement->checkPermissions('CREATEEVENTS',
																		     $id_user_action,
																		     $id_borgo);
			
				if($ValidatePermits['bool']){

					$key['id'] = $id_event;
					$value['borgo'] = $id_borgo;
					$value['date'] = $date;
					$value['name'] = $name;
					$value['descrizione'] = $description;
					$value['places'] = $places;
					$value['state'] = $state;

					$controlIns = $Events->Update($value,$key);

					if($controlIns->getBoolean()){
						// Inserimento avvenuto con successo
						$result['status_code'] = 200;
						$result['body']['message'] = "Modify occurred successfully";
					}else{
						// Si è verificato un errore durante l'inserimento
						$result['status_code'] = 422;
						$result['body']['message'] = "Unprocessable entity";
					}


				}else{
					/* Pemesso negato */
					$result['status_code'] = 403;
					$result['body']['message'] = $ValidatePermits['message'];

				}	

			}else{
			 	// Validazione parametri fallita
				$result['status_code'] = 412;
				$result['body']['message'] = "The input parameters are not correct";
			}


		} catch (Exception $e) {
		
			$result['status_code'] = 500;
			$result['body']['message'] = "There was an unexpected error"; 

		}


		return $result;

    }    




    /*
     * removeEvent()
     *
     * @param $id_user_action integer
     * @param $id_borgo integer
     * @param $id_event integer
     * @return array()
     *
     */ 

    public function removeEvent($id_user_action,$id_borgo,$id_event){
		$Events = new Events();
		$InscriptionsManagement = new InscriptionsManagementImpl();
		$Inscriptions = new Inscriptions();

		$result = null;
		$key = null;  
	
		try {

			// Procedo alla validazione dei campi
			if($Events->ValidateParameter('id',$id_event)){

				$ValidatePermits = $InscriptionsManagement->checkPermissions('CANCELEVENTS',
																		     $id_user_action,
																		     $id_borgo);
			
				if($ValidatePermits['bool']){

					$key['id'] = $id_event;

					$controlIns = $Events->Delete($key);

					if($controlIns->getBoolean()){
						// Inserimento avvenuto con successo
						$result['status_code'] = 200;
						$result['body']['message'] = "Cancellation occurred successfully";
					}else{
						// Si è verificato un errore durante l'inserimento
						$result['status_code'] = 422;
						$result['body']['message'] = "Unprocessable entity";
					}


				}else{
					/* Pemesso negato */
					$result['status_code'] = 403;
					$result['body']['message'] = $ValidatePermits['message'];

				}	

			}else{
			 	// Validazione parametri fallita
				$result['status_code'] = 412;
				$result['body']['message'] = "The input parameters are not correct";
			}
			

		} catch (Exception $e) {
		
			$result['status_code'] = 500;
			$result['body']['message'] = "There was an unexpected error"; 

		}		


		return $result;    	
    }

}