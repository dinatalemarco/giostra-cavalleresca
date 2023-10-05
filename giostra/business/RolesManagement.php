<?php
/**
 *
 * @author Di Natale Marco <dinatalemarco90@gmail.com>
 * @author Ciavarro Cristina <cristina.ciavarro@gmail.com>
 * @package  business
 */


interface RolesManagement{


    /*
     * getList()
     *
     * @param $id_user_action integer
     * @return array()
     *
     */ 

    public function getList($id_user_action);


}