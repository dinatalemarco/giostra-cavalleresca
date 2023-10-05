<?php
/**
 *
 * @author Di Natale Marco <dinatalemarco90@gmail.com>
 * @author Ciavarro Cristina <cristina.ciavarro@gmail.com>
 * @package  business
 */


interface BorghiManagement{


    /*
     * getList()
     *
     * @return array()
     *
     */ 

    public function getList();


    /*
     * getInfoBorgo()
     *
     * @return array()
     *
     */ 

    public function getInfoBorgo($id);    





}