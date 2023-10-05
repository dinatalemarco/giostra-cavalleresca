<?php
/**
 *
 * @author Di Natale Marco <dinatalemarco90@gmail.com>
 * @author Ciavarro Cristina <cristina.ciavarro@gmail.com>
 * @package  business
 */


interface InscriptionsManagement{


	/*
     * register()
     *
     * @param $id_borgo integer
     * @param $id_user integer
     * @return array()
     *
     */ 

    public function inscription($id_borgo,$id_user);


    /*
     * delete()
     *
     * @param $id_user integer
     * @return array()
     *
     */ 

    public function delete($id_user);


    /*
     * active()
     *
     * @param $id_borgo integer
     * @param $id_user integer
     * @param $id_user_action integer
     * @return array()
     *
     */ 

    public function active($id_borgo,$id_user,$id_user_action);    


    /*
     * deactivates()
     *
     * @param $id_borgo integer
     * @param $id_user integer
     * @param $id_user_action integer
     * @return array()
     *
     */ 

    public function deactivates($id_borgo,$id_user,$id_user_action);


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

    public function changePermissions($id_borgo,$id_user,$id_role,$id_user_action);


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

    public function checkPermissions($action,$id_user_action,$id_borgo);
  

}