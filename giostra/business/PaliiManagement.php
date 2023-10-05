<?php
/**
 *
 * @author Di Natale Marco <dinatalemarco90@gmail.com>
 * @author Ciavarro Cristina <cristina.ciavarro@gmail.com>
 * @package  business
 */


interface PaliiManagement{


    /*
     * getList()
     *
     * @param $id_borgo integer
     * @return array()
     *
     */ 

    public function getList($id_borgo);


    /*
     * getInfoPalio()
     *
     * @param $id integer
     * @return array()
     *
     */ 

    public function getInfoPalio($id);    



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

    public function addPalio($id_user_action,$id_borgo,$anno,$autore,$cavaliere); 




}