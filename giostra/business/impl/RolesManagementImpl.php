<?php
/**
 *
 * @author Di Natale Marco <dinatalemarco90@gmail.com>
 * @author Ciavarro Cristina <cristina.ciavarro@gmail.com>
 * @package  business/impl
 */

require_once(dirname(__FILE__) .'/../RolesManagement.php');	


class RolesManagementImpl implements RolesManagement{


    /*
     * getList()
     *
     * @param $id_user_action integer
     * @return array()
     *
     */ 

    public function getList($id_user_action){

		$Roles = new Roles();
		$Inscriptions = new Inscriptions();
		$result = null;
		$value = null;    	

		try {

			if ($Inscriptions->ValidateParameter('id_user',$id_user_action)) {

				$role = $Inscriptions->Select(array('r.code'),'i')
									 ->LeftJoin('i',new Roles(),'r','i.id_role = r.id')
									 ->Result();

				if (isset($role[0])) {

					$list = $Roles->Select(array('id','name'))
					              ->Where('code >= :code',array(':code' => $role[0]['code']))
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


}