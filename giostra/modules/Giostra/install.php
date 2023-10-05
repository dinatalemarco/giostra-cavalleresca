<?php
/**
 * Uuoden Php Web framework
 *
 * @author Di Natale Marco <dinatalemarco90@gmail.com>
 * @package  EtSystem
 */

  
  $Users = new Users();
  $Users->CreateTable();
  $Users->CreateFileManager();

  $Borghi = new Borghi();
  $Borghi->CreateTable();
  $Borghi->CreateFileManager();

  $Events = new Events();
  $Events->CreateTable();
  $Events->CreateFileManager();

  $Inscriptions = new Inscriptions();
  $Inscriptions->CreateTable();
  $Inscriptions->CreateFileManager();

  $Palii = new Palii();
  $Palii->CreateTable();
  $Palii->CreateFileManager();

  $Permissions = new Permissions();
  $Permissions->CreateTable();
  $Permissions->CreateFileManager();

  $Reservations = new Reservations();
  $Reservations->CreateTable();
  $Reservations->CreateFileManager();

  $Roles = new Roles();
  $Roles->CreateTable();
  $Roles->CreateFileManager();






			    



