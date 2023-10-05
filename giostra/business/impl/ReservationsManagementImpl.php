<?php

/**
 *
 * @author Di Natale Marco <dinatalemarco90@gmail.com>
 * @author Ciavarro Cristina <cristina.ciavarro@gmail.com>
 * @package  business/impl
 */

require_once(dirname(__FILE__) .'/../ReservationsManagement.php');  


class ReservationsManagementImpl implements ReservationsManagement{


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

    public function signUpForTheEvent($id_borgo,$id_event,$n_places,$id_user){
        $Events = new Events();
        $Reservations = new Reservations();
        $Inscriptions = new Inscriptions();
        $date = date('Y-m-d H:i:s');
        $result = null;
        $value = null;   

        try {

            $infoEvent = $Events->Select()
                                ->Where('id = :id && borgo = :borgo',
                                        array(':id' => $id_event,':borgo' => $id_borgo))
                                ->Result();

            // Verifico l'esistenza dell'evento
            if(isset($infoEvent[0])){
                /* L'evento esiste */

                /* Verifico la presenza di una prenotazione già inserita per questo evento */
                $checkEntry = $Reservations->Select()
                                           ->Where('id_user = :id && id_event = :event',
                                                    array(':id' => $id_user,':event' => $id_event))
                                           ->Result();

                if (!isset($checkEntry[0])) {
                    // L'utente non si è ancora prenotato all'evento

                    /* Recupero tutte le iscrizioni per verificare la presenza dei posti */
                    $listInscription = $Reservations->Select(array('places'))
                                                    ->Where('id_event = :id',array(':id' => $id_event))
                                                    ->Result();
                    $places = 0;
                    if(isset($listInscription[0])){
                        for ($i=0; $i < count($listInscription) ; $i++) { 
                            $places += $listInscription[$i]['places'];
                        }
                    }

                    if (($infoEvent[0]['places']-$places-$n_places) > 0) {
                        // Posso effettuare la prenotazione

                        // Voglio verificare se si tratta di un tesserato o no
                        $infoUser = $Inscriptions->Select('state')
                                                 ->Where('id_user = :id && id_borgo = :borgo',
                                                    array(':id' => $id_user,':borgo' => $id_borgo))
                                                 ->Result();
                        $state = 0;                  
                        if (isset($infoUser[0])) {
                            // L'utente è iscritto verifico lo stato della sua iscrizione
                            if ($infoUser[0]['state'] == 1) {
                                $state = 1;
                            }
                        }


                        $value['id_event'] = $id_event;
                        $value['id_user'] = $id_user;
                        $value['places'] = $n_places;
                        $value['date'] = $date;
                        $value['state'] = $state;

                        $controlDel = $Reservations->Insert($value);        

                        if($controlDel->getBoolean()){
                            // Inserimento avvenuto con successo
                            $result['status_code'] = 201;
                            $result['body']['message'] = "Reservation occurred successfully";
                        }else{
                            // Si è verificato un errore durante l'inserimento
                            $result['status_code'] = 422;
                            $result['body']['message'] = "Unprocessable entity";
                        }


                    }else{
                        // Il numero di posti richiesti è superiore del numero totale di posti restanti
                        $result['status_code'] = 422;
                        $result['body']['message'] = "We are sorry is not possible to reserv because the requested seats are higher than those requested";                      
                    }


                }else{
                    // L'utente si è già prenotato all'evento
                    $result['status_code'] = 409;
                    $result['body']['message'] = "We are sorry it is not possible to complete the request because you are already reserved at the event";                 
                }



            }else{
                // Non esiste una corrispondenza tra l'id fornito e quelli presenti nel db
                $result['status_code'] = 404;
                $result['body']['message'] = "No information was found for this resource";  
            }

        } catch (Exception $e) {
            $result['status_code'] = 500;
            $result['body']['message'] = "There was an unexpected error";            
        }


        return $result;

    } 



    /*
     * getListReservations()
     *
     * @param $id_user integer
     * @return array()
     *
     */ 

    public function getListReservations($id_user){

        $Reservations = new Reservations();
        $date = date('Y-m-d H:i:s');
        $result = null;
        $value = null;      

        try {

            if($Reservations->ValidateParameter('id_user',$id_user)){

                $list = $Reservations->Select(array('r.id_event','r.places','r.date','r.state'),'r')
                                     ->LeftJoin('r',new Events(),'e','r.id_event = e.id')
                                     ->Where('r.id_user = :id && e.date > :current',
                                        array(':id' => $id_user,':current' => $date))
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
                $result['status_code'] = 412;
                $result['body']['message'] = "The input parameters are not correct";
            }

        } catch (Exception $e) {
        
            $result['status_code'] = 500;
            $result['body']['message'] = "There was an unexpected error"; 
        }

        return $result;

    }




    /*
     * getListReservationsEvent()
     *
     * @param $$id_borgo integer
     * @param $id_event integer
     * @param $id_user_actioninteger
     * @return array()
     *
     */ 

    public function getListReservationsEvent($id_borgo,$id_event,$id_user_action){

        $InscriptionsManagement = new InscriptionsManagementImpl();
        $Reservations = new Reservations();
        $date = date('Y-m-d H:i:s');
        $result = null;
        $value = null;      

        try {

            if($Reservations->ValidateParameter('id_event',$id_event)){


                $ValidatePermits = $InscriptionsManagement->checkPermissions('VIEWLISTUSERS',
                                                                             $id_user_action,
                                                                             $id_borgo);
            
                if($ValidatePermits['bool']){


                    $list = $Reservations->Select(array('u.id',
                                                        'u.name',
                                                        'u.surname',
                                                        'u.email',
                                                        'r.places',
                                                        'r.state'),'r')
                                         ->LeftJoin('r',new Events(),'e','r.id_event = e.id')
                                         ->LeftJoin('r',new Users(),'u','r.id_user = u.id')
                                         ->Where('r.id_event = :id',
                                            array(':id' => $id_event))
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
                    /* Pemesso negato */
                    $result['status_code'] = 403;
                    $result['body']['message'] = $ValidatePermits['message'];

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

    public function confirmationOfReservation($id_borgo,$id_event,$id_user,$id_user_action){

        $InscriptionsManagement = new InscriptionsManagementImpl();
        $Reservations = new Reservations();
        $result = null;
        $value = null;      

        try {

            if($Reservations->ValidateParameter('id_user',$id_user) &&
               $Reservations->ValidateParameter('id_event',$id_event) &&
               $Reservations->ValidateParameter('id_event',$id_borgo) &&
               $Reservations->ValidateParameter('id_event',$id_user_action)){


                $ValidatePermits = $InscriptionsManagement->checkPermissions('VIEWLISTUSERS',
                                                                             $id_user_action,
                                                                             $id_borgo);
            
                if($ValidatePermits['bool']){


                    $inforeservation = $Reservations->Select()
                                                    ->Where('id_event = :event && id_user = :user',
                                                            array(':event' => $id_event,':user' => $id_user))
                                                    ->Result();


                    if(isset($inforeservation[0])){

                        $key['id_event'] = $inforeservation[0]['id_event'];
                        $key['id_user'] = $inforeservation[0]['id_user'];
                        $value['places'] = $inforeservation[0]['places'];
                        $value['date'] = $inforeservation[0]['date'];
                        $value['state'] = 1;


                        $controlIns = $Reservations->Update($value,$key);      

                        if($controlIns->getBoolean()){
                            // Inserimento avvenuto con successo
                            $result['status_code'] = 200;
                            $result['body']['message'] = "Modify occurred successfully";
                        }else{
                            // Si è verificato un errore durante l'inserimento
                            $result['status_code'] = 422;
                            $result['body']['message'] = "Unprocessable entity";
                        }


                    }else{
                        $result['status_code'] = 404;
                        $result['body']['message'] = "No information was found for this resource";
                    }


                }else{
                    /* Pemesso negato */
                    $result['status_code'] = 403;
                    $result['body']['message'] = $ValidatePermits['message'];

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



