<?php
/**
 *
 * @author Di Natale Marco <dinatalemarco90@gmail.com>
 * @author Ciavarro Cristina <cristina.ciavarro@gmail.com>
 * @package  business/impl
 */

require_once(dirname(__FILE__) .'/../PaliiManagement.php');	


class PaliiManagementImpl implements PaliiManagement{


    /*
     * getList()
     *
     * @param $id_borgo integer
     * @return array()
     *
     */ 

    public function getList($id_borgo){

		$Palii = new Palii();
		$result = null;
		$value = null;    	

		try {

			if($Palii->ValidateParameter('borgo',$id_borgo)){

				$list = $Palii->Select()
							  ->Where('borgo = :id_borgo',array(':id_borgo' => $id_borgo))
							  ->Result();


				if(isset($list[0])){
					$result['status_code'] = 200;
					$result['body']['message'] = "Information retrieval happened successfully";
					$result['body']['response'] = $list;
				}else{
					$result['status_code'] = 404;
					$result['body']['message'] = "No information was found for this resource";
				}

			}else{
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
     * getInfoBorgo()
     *
     * @param $id integer
     * @return array()
     *
     */ 

    public function getInfoPalio($id){


		$Palii = new Palii();
		$result = null;
		$value = null;    	

		try {

			if($Palii->ValidateParameter('id',$id)){
				// Validazione avvenuta con successo
				$info = $Palii->Select()
								 ->Where('id = :id',array(':id' => $id))
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
				// Validazione id non corretta
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
     * addPalio()
     *
     * @param $id_user_action integer
     * @param $id_borgo integer
     * @param $anno string
     * @param $autore string
     * @param $cavaliere string
     * @return array()
     *
     */ 

    public function addPalio($id_user_action,$id_borgo,$anno,$autore,$cavaliere){

    	$Palii = new Palii();
    	$InscriptionsManagement = new InscriptionsManagementImpl();
    	$result = null;
		$value = null;


		try {

	    	if($Palii->ValidateParameter('id',$id_user_action) && 
	           $Palii->ValidateParameter('borgo',$id_borgo) &&
	           $Palii->ValidateParameter('anno',$anno) &&
	           $Palii->ValidateParameter('autore',$autore) &&
	           $Palii->ValidateParameter('cavaliere',$cavaliere)){

	           	$ValidatePermits = $InscriptionsManagement->checkPermissions('ADDPALIO',$id_user_action);

		    	if($ValidatePermits['bool']){
		    		/* Validazione permessi eseguita */

					$value['anno'] = $anno;
					$value['autore'] = $autore;
					$value['cavaliere'] = $cavaliere;
					$value['borgo'] = $id_borgo;

					$controlIns = $Palii->Insert($value);

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



}