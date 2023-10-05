<?php
/**
 *
 * @author Di Natale Marco <dinatalemarco90@gmail.com>
 * @author Ciavarro Cristina <cristina.ciavarro@gmail.com>
 * @package  business/impl
 */

require_once(dirname(__FILE__) .'/../BorghiManagement.php');	


class BorghiManagementImpl implements BorghiManagement{


    /*
     * getList()
     *
     * @return array()
     *
     */ 

    public function getList(){

		$Borghi = new Borghi();
		$result = null;
		$value = null;    	

		try {

			$list = $Borghi->Select()
						   ->Result();

			if(isset($list[0])){
				$result['status_code'] = 200;
				$result['body']['message'] = "Information retrieval happened successfully";
				$result['body']['response'] = $list;
			}else{
				$result['status_code'] = 404;
				$result['body']['message'] = "No information was found for this resource";
				$result['body']['response'] = $list;				
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
     * @return array()
     *
     */ 

    public function getInfoBorgo($id){


		$Borghi = new Borghi();
		$result = null;
		$value = null;    	

		try {

			if($Borghi->ValidateParameter('id',$id)){
				// Validazione avvenuta con successo
				$info = $Borghi->Select()
								 ->Where('id = :id',array(':id' => $id))
								 ->Result();				
	

				if(isset($info[0])){
					$result['status_code'] = 200;
					$result['body']['message'] = "Information retrieval happened successfully";	
					$result['body']['response'] = $info[0];
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


}