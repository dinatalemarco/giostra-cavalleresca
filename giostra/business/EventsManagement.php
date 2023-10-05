<?php
/**
 *
 * @author Di Natale Marco <dinatalemarco90@gmail.com>
 * @author Ciavarro Cristina <cristina.ciavarro@gmail.com>
 * @package  business
 */


interface EventsManagement{


    /*
     * getList()
     *
     * @return array()
     *
     */ 

    public function getList();


    /*
     * getEvent()
     *
     * @param $id_event integer
     * @return array()
     *
     */ 

    public function getEvent($id_event);



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

    public function addEvent($id_user_action,$id_borgo,$date,$name,$description,$places,$state);



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

    public function updateEvent($id_user_action,$id_borgo,$id_event,$date,$name,$description,$places,$state);



    /*
     * removeEvent()
     *
     * @param $id_user_action integer
     * @param $id_borgo integer
     * @param $id_event integer
     * @return array()
     *
     */ 

    public function removeEvent($id_user_action,$id_borgo,$id_event); 


}