<?php
/**
 *
 * @author Di Natale Marco <dinatalemarco90@gmail.com>
 * @author Ciavarro Cristina <cristina.ciavarro@gmail.com>
 * @package  business
 */


interface ReservationsManagement{


    /*
     * signUpForTheEvent()
     *
     * @param $id_borgo integer
     * @param $id_event integer
     * @param $n_places integer
     * @param $id_user integer
     * @return array()
     *
     */ 

    public function signUpForTheEvent($id_borgo,$id_event,$n_places,$id_user); 


    /*
     * getListReservations()
     *
     * @param $id_user integer
     * @return array()
     *
     */ 

    public function getListReservations($id_user);


    /*
     * getListReservationsEvent()
     *
     * @param $$id_borgo integer
     * @param $id_event integer
     * @param $id_user_actioninteger
     * @return array()
     *
     */ 

    public function getListReservationsEvent($id_borgo,$id_event,$id_user_action);



    /*
     * confirmationOfReservation()
     *
     * @param $$id_borgo integer
     * @param $id_event integer
     * @param $id_user integer
     * @param $id_user_action integer
     * @return array()
     *
     */ 

    public function confirmationOfReservation($id_borgo,$id_event,$id_user,$id_user_action);


}


