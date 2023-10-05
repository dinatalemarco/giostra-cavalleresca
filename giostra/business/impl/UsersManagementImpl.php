<?php
/**
 *
 * @author Di Natale Marco <dinatalemarco90@gmail.com>
 * @author Ciavarro Cristina <cristina.ciavarro@gmail.com>
 * @package  business/impl
 */

require_once(dirname(__FILE__) .'/../UsersManagement.php');	


class UsersManagementImpl implements UsersManagement{



    /*
     * getInfoUser()
     *
     * @param $id integer
     * @return array()
     *
     */ 

    public function getInfoUser($id){

    	$Users = new Users();
    	$infoUser = null;

    	if($Users->ValidateParameter('id',$id)){

			$infoUser = $Users->Select()
							  ->Where('id = :id',array(':id' => $id))
							  ->Result();
		}

		return $infoUser;

    }


    /*
     * checkPassword()
     *
     * @param $password string
     * @param $asswordDB string
     * @param $encryption_key string
     * @return boolean
     *
     */ 

    public function checkPassword($password,$asswordDB,$encryption_key){

    	$passwordDec = decryptsPassword($password,$encryption_key);

		/* Verifico la corrispondenza inserita e la vecchia */
		if($passwordDec == $asswordDB)
			return true;
		else return false;


    } 



	/*
     * register()
     *
     * @param $name string
     * @param $surname string     
     * @param $email string
     * @param $password string
     * @return array()
     *
     */ 

    public function register($name,$surname,$email,$password){

		$Users = new Users();
		$result = null;
		$value = null;

		try {

		// Procedo alla validazione dei campi
		if($Users->ValidateParameter('name',$name) && 
		   $Users->ValidateParameter('surname',$surname) &&
		   $Users->ValidateParameter('email',$email) &&
		   $Users->ValidateParameter('password',$password)){

			$infoUser = $Users->Select()->Where('email = :e',array(':e' => $email))->Result();

			// Verifico l'inesistenza dell'utente
			if(!isset($infoUser[0])){
				// L'utente non esiste, posso crearlo
				/* Cripto la password inserita dall'utente */
				$infoPassword = cryptPassword($password);
				$value['name'] = $name;
				$value['surname'] = $surname;
				$value['email'] = $email;
				$value['password'] = $infoPassword['password'];
				$value['encryption_key'] = $infoPassword['key'];

				$controlIns = $Users->Insert($value);

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
				// L'utente è già registrato al sistema
				$result['status_code'] = 409;
				$result['body']['message'] = "The inserted email is already userd in the system";  
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
     * login()
     *
     * @param $email string
     * @param $password string
     * @return array()
     *
     */ 

    public function login($email, $password){
		$Users = new Users();
		$result = null;


		try {

		// Procedo alla validazione del campo mail
		if($Users->ValidateParameter('email',$email)){

			$infoUser = $Users->Select(array('u.id',
										     'u.name',
										     'u.surname',
										     'u.email',
										     'u.password',
										     'u.encryption_key',
										     'i.id_borgo',
										     'r.code'),'u')
							  ->LeftJoin('u',new Inscriptions(),'i','u.id = i.id_user')
							  ->LeftJoin('i',new Roles(),'r','i.id_role = r.id')
							  ->Where('u.email = :e',array(':e' => $email))
							  ->Result();

			// L'utente esiste verifico la correttezza dei dati
			if(isset($infoUser[0])){

				// Verifico che la pasword passata in input sia uguale a quella nel db
				if($this->checkPassword($password,$infoUser[0]['password'],$infoUser[0]['encryption_key'])){
					// La password è corretta, restituisco in output le informazioni utente 
					$response =null;
					$response['id'] = $infoUser[0]['id'];
					$response['name'] = $infoUser[0]['name'];
					$response['surname'] = $infoUser[0]['surname'];
					$response['email'] = $infoUser[0]['email'];
					$response['borgo'] = $infoUser[0]['id_borgo'];
					$response['permission'] = $infoUser[0]['code'];

					$result['status_code'] = 200;
					$result['body']['message'] = "Validation occurred successfully";
					$result['body']['response'] = $response;
				}else{
					// La password passata in input non corrisponde a quella salvata nel db
					$result['status_code'] = 401;
					$result['body']['message'] = "An error occurred during the credentials validation";
				}

			}else{
				// L'utente non esiste
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
     * updatePassword()
     *
     * @param $id integer
     * @return array()
     *
     */ 

    public function removeAccount($id){
		$Users = new Users();
		$result = null;
		$value = null;

		try {

			$infoUser = $this->getInfoUser($id);

			// Verifico l'esistenza dell'utente
			if(isset($infoUser[0])){
				/* L'utente esiste */

				$key['id'] = $infoUser[0]['id'];

				$controlDel = $Users->Delete($key);		

				if($controlDel->getBoolean()){
					// Inserimento avvenuto con successo
					$result['status_code'] = 200;
					$result['body']['message'] = "Cancellation occurred successfully";
				}else{
					// Si è verificato un errore durante l'inserimento
					$result['status_code'] = 422;
					$result['body']['message'] = "Unprocessable entity";
				}


			}else{
				// Non esiste una corrispondenza tra l'id fornito e quelli presenti nel db
				$result['status_code'] = 403;
				$result['body']['message'] = "User key is not valid";  
			}

		} catch (Exception $e) {
			$result['status_code'] = 500;
			$result['body']['message'] = "There was an unexpected error"; 			
		}


    	return $result;
    }




	/*
     * updatePassword()
     *
     * @param $password string
     * @param $id integer
     * @return array()
     *
     */ 

    public function updatePassword($password,$oldpassword,$id){
		$Users = new Users();
		$result = null;
		$value = null;

		try {

		// Procedo alla validazione dei campi
		if($Users->ValidateParameter('password',$password) &&
		   $Users->ValidateParameter('password',$oldpassword)){

			$infoUser = $this->getInfoUser($id);


			// Verifico l'esistenza dell'utente
			if(isset($infoUser[0])){
				/* L'utente esiste */

				if($this->checkPassword($oldpassword,$infoUser[0]['password'],$infoUser[0]['encryption_key'])){
					// La password coincide procedo all'aggiornamento

					$infoPassword = cryptPassword($password);
					$key['id'] = $infoUser[0]['id'];
					$value['name'] = $infoUser[0]['name'];
					$value['surname'] = $infoUser[0]['surname'];
					$value['email'] = $infoUser[0]['email'];
					$value['password'] = $infoPassword['password'];
					$value['encryption_key'] = $infoPassword['key'];

					$controlIns = $Users->Update($value,$key);		

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
					// La password non coincide
					$result['status_code'] = 401;
					$result['body']['message'] = "The old Password is wrong";  			
				}


			}else{
				// Non esiste una corrispondenza tra l'id fornito e quelli presenti nel db
				$result['status_code'] = 403;
				$result['body']['message'] = "User key is not valid";  
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
     * updateUserInfo()
     *
     * @param $name string
     * @param $surname string
     * @param $email string
     * @param $id integer
     * @return array()
     *
     */ 

    public function updateUserInfo($name,$surname,$email,$id){
		$Users = new Users();
		$result = null;
		$value = null;


		try {

		// Procedo alla validazione dei campi
		if($Users->ValidateParameter('name',$name) &&
		   $Users->ValidateParameter('surname',$surname) &&
		   $Users->ValidateParameter('email',$email)){

			$infoUser = $this->getInfoUser($id);

			// Verifico l'esistenza dell'utente
			if(isset($infoUser[0])){
				/* L'utente esiste */


				$key['id'] = $infoUser[0]['id'];
				$value['name'] = $name;
				$value['surname'] = $surname;
				$value['email'] = $email;
				$value['password'] = $infoUser[0]['password'];
				$value['encryption_key'] = $infoUser[0]['encryption_key'];

				$controlIns = $Users->Update($value,$key);		

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
				// Non esiste una corrispondenza tra l'id fornito quelli presenti nel db
				$result['status_code'] = 403;
				$result['body']['message'] = "User key is not valid";  
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
     * listUsersBorgo()
     *
     * @param $id_borgo integer
     * @param $id_user_action integer
     * @return array()
     *
     */ 

    public function listUsersBorgo($id_borgo,$id_user_action){
		$Users = new Users();
		$Inscriptions = new Inscriptions();
		$InscriptionsManagement = new InscriptionsManagementImpl();

		$result = null;
		$value = null;  

		try {
	
			// Procedo alla validazione dei campi
			if($Inscriptions->ValidateParameter('id_borgo',$id_borgo) &&
			   $Users->ValidateParameter('id',$id_user_action) ){

				$ValidatePermits = $InscriptionsManagement->checkPermissions('VIEWLISTUSERS',
																		     $id_user_action,
																		     $id_borgo);
			
				if($ValidatePermits['bool']){


					$list = $Users->Select(array('u.id',
												 'u.name',
												 'u.surname',
												 'u.email',
												 'i.year',
												 'i.id_role',
												 'i.state'),'u')
								  ->LeftJoin('u',new Inscriptions(),'i','u.id = i.id_user')
							      ->Where('i.id_borgo = :borgo',array(':borgo' => $id_borgo))
								  ->Result();

					if(isset($list[0])){
						// Recupero informazioni avvenuto con successo
						$result['status_code'] = 200;
						$result['body']['message'] = "Information retrieval happened successfully";
						$result['body']['response'] = $list;
					}else{
						// La risorsa cercata non esiste
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


}