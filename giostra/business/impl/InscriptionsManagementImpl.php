<?php
/**
 *
 * @author Di Natale Marco <dinatalemarco90@gmail.com>
 * @author Ciavarro Cristina <cristina.ciavarro@gmail.com>
 * @package  business/impl
 */

require_once(dirname(__FILE__) .'/../InscriptionsManagement.php');	


class InscriptionsManagementImpl implements InscriptionsManagement{


	/*
     * inscription()
     *   
     * @param $id_borgo interger
     * @param $id_user integer
     * @return array()
     *
     */ 

    public function inscription($id_borgo,$id_user){

    	$Roles = new Roles();
		$Inscriptions = new Inscriptions();
		$UsersManagement = new UsersManagementImpl();
		$result = null;
		$value = null;

		try {

		// Procedo alla validazione dei campi
		if($Inscriptions->ValidateParameter('id_borgo',$id_borgo)){


			$infoUser = $UsersManagement->getInfoUser($id_user);

			// Verifico l'inesistenza dell'utente
			if(isset($infoUser[0])){
				// L'utente esiste posso procedere all'iscrizione ad un borgo
				// Verifico che questo non sia iscritto ad un altro borgo
				$checkInscription = $Inscriptions->Select()
												 ->Where('id_user = :id',
												         array(':id' => $infoUser[0]['id']))
												 ->Result();

				if(!isset($checkInscription[0])){
					// L'utente non ha altre registrazioni, procedo alla registrazione
					$id_role = $Roles->Select()
									 ->Where('code = :c',array(':c' => 300))
									 ->Result();

					if(isset($id_role[0])){
						$value=null;
						$value['id_borgo'] = $id_borgo;
						$value['id_user'] = $infoUser[0]['id'];
						$value['id_role'] = $id_role[0]['id'];
						$value['year'] = date('Y-m-d H:i:s');
						$value['state'] = 0;

						$controlIns = $Inscriptions->Insert($value);

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
						// Errore nel recupero dei ruoli
						$result['status_code'] = 403;
						$result['body']['message'] = "Permission denied";
					}					


				}else{
					// L'utente ha già una registrazione
					// Recupero la sua vecchia iscrizione le la rimuovo
					$key['id'] = $checkInscription[0]['id'];
					$controlIns = $Inscriptions->Delete($key);

					$id_role = $Roles->Select()
									 ->Where('code = :c',array(':c' => 300))
									 ->Result();


					if($controlIns->getBoolean()){
						// Ho cancellato correttamente la vecchia iscrizione
						// Inserisco la nuova

						$value=null;
						$value['id_borgo'] = $id_borgo;
						$value['id_user'] = $infoUser[0]['id'];
						$value['id_role'] = $id_role[0]['id'];
						$value['year'] = date('Y-m-d H:i:s');
						$value['state'] = 0;

						$controlIns = $Inscriptions->Insert($value);

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
						// Non sono riuscito a rimuovere la vecchia iscrizione
						$result['status_code'] = 422;
						$result['body']['message'] = "Unprocessable entity"; 
					}						

				}

			}else{
				// L'utente non risulta registrato al sistema nego la richiesta
				$result['status_code'] = 403;
				$result['body']['message'] = "Permission denied";  
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
     * delete()
     *   
     * @param $id_user integer
     * @return array()
     *
     */ 

    public function delete($id_user){

    	$User = new Users();
		$Inscriptions = new Inscriptions();
		$UsersManagement = new UsersManagementImpl();
		$result = null;


		try {

		/* Dato l'id in input verifico se questo è associato ad un utente */
		$infoUser = $UsersManagement->getInfoUser($id_user);


		// Verifico l'esistenza dell'utente
		if(isset($infoUser[0])){
			// L'utente esiste posso procedere alla sua cancellazione da un borgo					

			/* Recupero l'ID del borgo a cui l'utente si è registrato in precedenza */
			$id_inscription = $Inscriptions->Select(array('id'))
								          ->Where('id_user = :id', array(':id' => $infoUser[0]['id']))
								          ->Result();


			if(isset($id_inscription[0]['id'])){
				// L'ID è estato recuperato
				$key['id'] = $id_inscription[0]['id'];

				$controlDel = $Inscriptions->Delete($key);	

				if($controlDel->getBoolean()){
					// Inserimento avvenuto con successo
					$result['status_code'] = 200;
				    $result['body']['message'] = "Cancellazione avvenuta con successo";
				}else{
					// Si è verificato un errore durante l'inserimento
					$result['status_code'] = 422;
					$result['body']['message'] = "Unprocessable entity";
				}
												
			}else{
				// Si è verificato un errore nel recupero dell'ID
				$result['status_code'] = 403;
				$result['body']['message'] = "Permission denied";
			}	


		}else{
			// L'utente è già registrato al sistema
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
     * active()
     *
     * @param $id_borgo integer
     * @param $id_user integer
     * @param $id_user_action integer
     * @return array()
     *
     */ 

    public function active($id_borgo,$id_user,$id_user_action){

		$Events = new Events();
		$InscriptionsManagement = new InscriptionsManagementImpl();
		$Inscriptions = new Inscriptions();

		$result = null;
		$value = null;  
		$key = null;

		try {
	
			// Procedo alla validazione dei campi
			if($Inscriptions->ValidateParameter('id_borgo',$id_borgo) &&
			   $Inscriptions->ValidateParameter('id_user',$id_user) &&
			   $Inscriptions->ValidateParameter('id_user',$id_user_action)){

				$ValidatePermits = $this->checkPermissions('ACCEPTREGISTRATION',$id_user_action,$id_borgo);
			
				if($ValidatePermits['bool']){

					$info = $Inscriptions->Select()
										 ->Where('id_borgo = :borgo && id_user = :user',
										 		  array(':borgo' => $id_borgo, ':user' => $id_user))
										 ->Result();

					if(isset($info[0])){
						// Dati recuperati procedo con la modifica

						if ($info[0]['state'] == 0) {
							$key['id'] = $info[0]['id'];
							$value['id_borgo'] = $info[0]['id_borgo'];
							$value['id_user'] = $info[0]['id_user'];
							$value['id_role'] = $info[0]['id_role'];
							$value['year'] = $info[0]['year'];
							$value['state'] = 1;

							$controlIns = $Inscriptions->Update($value,$key);		

							if($controlIns->getBoolean()){
								// Inserimento avvenuto con successo
								$result['status_code'] = 200;
								$result['body']['message'] = "Attivazione eseguita con successo";
							}else{
								// Si è verificato un errore durante l'inserimento
								$result['status_code'] = 422;
								$result['body']['message'] = "Unprocessable entity";
							}
						}else{
							// Si è verificato un errore durante l'inserimento
							$result['status_code'] = 409;
							$result['body']['message'] = "L'utente risulta già essere attivo";							
						}


					}else{
						// Si è verificato un errore durante l'inserimento
						$result['status_code'] = 404;
						$result['body']['message'] = "No information was found for this resource";
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
     * deactivates()
     *
     * @param $id_borgo integer
     * @param $id_user integer
     * @param $id_user_action integer
     * @return array()
     *
     */ 

    public function deactivates($id_borgo,$id_user,$id_user_action){

		$Events = new Events();
		$InscriptionsManagement = new InscriptionsManagementImpl();
		$Inscriptions = new Inscriptions();

		$result = null;
		$value = null;  
		$key = null;

		try {
	
			// Procedo alla validazione dei campi
			if($Inscriptions->ValidateParameter('id_borgo',$id_borgo) &&
			   $Inscriptions->ValidateParameter('id_user',$id_user) &&
			   $Inscriptions->ValidateParameter('id_user',$id_user_action)){

				$ValidatePermits = $this->checkPermissions('ACCEPTREGISTRATION',$id_user_action,$id_borgo);
			
				if($ValidatePermits['bool']){

					$info = $Inscriptions->Select()
										 ->Where('id_borgo = :borgo && id_user = :user',
										 		  array(':borgo' => $id_borgo, ':user' => $id_user))
										 ->Result();

					if(isset($info[0])){
						// Dati recuperati procedo con la modifica

						if ($info[0]['state'] == 1) {
							$key['id'] = $info[0]['id'];
							$value['id_borgo'] = $info[0]['id_borgo'];
							$value['id_user'] = $info[0]['id_user'];
							$value['id_role'] = $info[0]['id_role'];
							$value['year'] = $info[0]['year'];
							$value['state'] = 0;

							$controlIns = $Inscriptions->Update($value,$key);		

							if($controlIns->getBoolean()){
								// Inserimento avvenuto con successo
								$result['status_code'] = 200;
								$result['body']['message'] = "Disattivazione eseguita con successo";
							}else{
								// Si è verificato un errore durante l'inserimento
								$result['status_code'] = 422;
								$result['body']['message'] = "Unprocessable entity";
							}
						}else{
							// Si è verificato un errore durante l'inserimento
							$result['status_code'] = 409;
							$result['body']['message'] = "The user is already active";							
						}


					}else{
						// Si è verificato un errore durante l'inserimento
						$result['status_code'] = 404;
						$result['body']['message'] = "No information was found for this resource";
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
     * changePermissions()
     *
     * @param $id_borgo integer
     * @param $id_user integer
     * @param $id_role integer
     * @param $id_user_action integer
     *   
     * @return array()
     *
     */ 

    public function changePermissions($id_borgo,$id_user,$id_role,$id_user_action){

		$Events = new Events();
		$Roles = new Roles();
		$Inscriptions = new Inscriptions();

		$result = null;
		$value = null;  
		$key = null;

		try {
	
			// Procedo alla validazione dei campi
			if($Inscriptions->ValidateParameter('id_borgo',$id_borgo) &&
			   $Inscriptions->ValidateParameter('id_user',$id_user) &&
			   $Inscriptions->ValidateParameter('id_role',$id_role) &&
			   $Inscriptions->ValidateParameter('id_user',$id_user_action)){

				$ValidatePermits = $this->checkPermissions('ASSIGNROLESBORGO',$id_user_action,$id_borgo);
			
				if($ValidatePermits['bool']){

					$info = $Inscriptions->Select()
										 ->Where('id_borgo = :borgo && id_user = :user',
										 		  array(':borgo' => $id_borgo, ':user' => $id_user))
										 ->Result();

					if(isset($info[0])){
						// Dati recuperati procedo con la modifica

						$UserActionRole = $Inscriptions->Select(array('r.code'),'i')
												 ->LeftJoin('i',new Roles(),'r','i.id_role = r.id')
										         ->Where('i.id_user = :id',array(':id' => $id_user_action))
										         ->Result();

						$UserReceiveRole = $Roles->Select()
												 ->Where('id = :id',array(':id' => $id_role))
												 ->Result();


						if (isset($UserActionRole[0]) && isset($UserReceiveRole[0])) {

							if($UserActionRole[0]['code'] <= $UserReceiveRole[0]['code']){


								if($info[0]['id_role'] != $id_role){
									$key['id'] = $info[0]['id'];
									$value['id_borgo'] = $info[0]['id_borgo'];
									$value['id_user'] = $info[0]['id_user'];
									$value['id_role'] = $id_role;
									$value['year'] = $info[0]['year'];
									$value['state'] = $info[0]['state'];

									$controlIns = $Inscriptions->Update($value,$key);		

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
									$result['status_code'] = 409;
									$result['body']['message'] = "The user already has the selected role";	
								}


							}else{
								// Si sta tentando di passare permessi non consentiti
								/* Pemesso negato */
								$result['status_code'] = 403;
								$result['body']['message'] = "Permission denied";								
							}

						}else{
					       $result['status_code'] = 404;
						   $result['body']['message'] = "No information was found for this resource";
						}

					}else{
						// Si è verificato un errore durante l'inserimento
						$result['status_code'] = 404;
						$result['body']['message'] = "No information was found for this resource";
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
     * checkPermissions()
     *
     * @param $action string 
     * ASSIGNROLES,REMOVEROLES,ADDPALIO  - ACCEPTREGISTRATION,CREATEEVENTS,EVENTMODIFICATION,CANCELEVENTS
     * @param $id_borgo string
     * @param $id_user_action integer
     * @return array()
     *
     */ 

    public function checkPermissions($action,$id_user_action,$id_borgo=null){

    	$User = new Users();
    	$UsersManagement = new UsersManagementImpl();
    	$Inscriptions = new Inscriptions();
    	$result = null;
    	$result['bool'] = null;
    	$result['message'] = null;

    	/* Dall'id utente verifico la sua esistenza */
    	$infoUser = $UsersManagement->getInfoUser($id_user_action);

    	/* Verifico la correttezza dell'id */
    	if(isset($infoUser[0])){

	    	/* Verifico l'azione che l'utnte sta per lanciare */
	    	if($action == 'ASSIGNROLES' || $action == 'REMOVEROLES' || $action == 'ADDPALIO'){
		   		/* L'utente richiede un azione da admin */
				$check = $Inscriptions->Select(array('i.id_role','i.id_user'),'i')
									  ->LeftJoin('i',new Roles(), 'r' , 'i.id_role = r.id')
									  ->Where('i.id_user = :user && r.code = :code',
									  		  array(':user' => $infoUser[0]['id'],':code' => 100))
	                                  ->Result();

				if(isset($check[0])){
				    // Permessi verificati
				    $result['bool'] = true;
				    $result['message'] = "Validation occurred successfully";
				}else{
				    // Non si hanno i permessi
				    $result['bool'] = false;
				    $result['message'] = "Permission denied";	
				}	

	   		}else{

	   			if($action == 'ACCEPTREGISTRATION' || $action == 'CREATEEVENTS' || 
	   			   $action == 'EVENTMODIFICATION' || $action == 'CANCELEVENTS' || 
	   			   $action == 'VIEWLISTUSERS' || $action == 'ASSIGNROLESBORGO' ){

	    			if($id_borgo != null){

					   	/* Iscrizione esistente, verifico i permessi */
					    $check = $Inscriptions->Select(array('i.id_role','i.id_user','id_borgo'),'i')
											  ->LeftJoin('i',new Roles(), 'r' , 'i.id_role = r.id')
											  ->Where('i.id_user = :user && i.id_borgo = :borgo && r.code = :code ',
										         	  array(':user' => $infoUser[0]['id'],
										         	     	':borgo' => $id_borgo,
										         	     	':code' => 200))
											  ->Result();
	

						if(isset($check[0])){
							// Permessi verificati
							$result['bool'] = true;
							$result['message'] = "Validation occurred successfully";
						}else{
							// Non si hanno i permessi
							$result['bool'] = false;
							$result['message'] = "Permission denied";	
						}			   			


	    			}else{
	    				// Si vuole effettuare un azzione su un borgo ma non si ha l'id
					   $result['bool'] = false;
					   $result['message'] = "It is not possible to make the request because the Bordo id is missing";	    					
	    			}

	    		}else{
	    			// L'azione richiesta non esiste
					$result['bool'] = false;
					$result['message'] = "We are sorry, the requested action does not exist";
	    		}
	    			
	    	}


    	}else{
			// Non esiste una corrispondenza tra l'id fornito e quelli presenti nel database
			$result['bool'] = false;
			$result['message'] = "We are sorry, the user key entered is not valid";  		
    	}

    	return $result;

    }




}