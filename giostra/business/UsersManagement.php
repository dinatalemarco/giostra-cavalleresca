<?php
/**
 *
 * @author Di Natale Marco <dinatalemarco90@gmail.com>
 * @author Ciavarro Cristina <cristina.ciavarro@gmail.com>
 * @package  business
 */


interface UsersManagement{


    /*
     * getInfoUser()
     *
     * @param $id integer
     * @return array()
     *
     */ 

    public function getInfoUser($id);


    /*
     * checkPassword()
     *
     * @param $password string
     * @param $oldPassword string
     * @param $encryption_key string
     * @return boolean
     *
     */ 

    public function checkPassword($password,$oldPassword,$encryption_key);    


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

    public function register($name,$surname,$email,$password);


    /*
     * login()
     *
     * @param $email string
     * @param $password string
     * @return array()
     *
     */ 

    public function login($email, $password);


    /*
     * removeAccount()
     *
     * @param $id integer
     * @return array()
     *
     */ 

    public function removeAccount($id);


	/*
     * updatePassword()
     *
     * @param $password string
     * @param $id integer
     * @return array()
     *
     */ 

    public function updatePassword($password,$oldpassword,$id);


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

    public function updateUserInfo($name,$surname,$email,$id);



    /*
     * listUsersBorgo()
     *
     * @param $id_borgo integer
     * @param $id_user_action integer
     * @return array()
     *
     */ 

    public function listUsersBorgo($id_borgo,$id_user_action);



}