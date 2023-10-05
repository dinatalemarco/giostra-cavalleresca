<?php
/**
 * Uuoden Php Web framework
 *
 * @author Di Natale Marco <dinatalemarco90@gmail.com>
 * @package  Email
 */

date_default_timezone_set('Etc/UTC');

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require 'vendor/autoload.php';


class SmtpMail{

   private $FromName = "Uuoden PHP Web Framework";
   private $Theme = "email";
   private $mail;


   /**
   * __construct()
   * @return void
   *
   */ 

   public function __construct() {
      $this->mail = new PHPMailer;
   }

   /**
   * SetLanguage()
   * @param $Language String 
   * @return void
   *
   */ 

   public function SetLanguage($Language){
      $this->mail->setLanguage($Language, '/lib/language/');
   }

   /**
   * SetPage()
   * @param $theme string 
   * @return void
   *
   */ 

   public function SetPage($theme){
      $this->Theme = $theme;
   }


   /**
   * SetFromName()
   * @param $name string 
   * @return void
   *
   */ 

   public function SetFromName($name){
      $this->FromName = $name;
   }

   /**
   * SetServer()
   * @param $Server string
   * @param $Port int
   * @param $Secure string
   * @param $Auth bool         
   * @return void
   *
   */ 

   public function SetServer($Server,$Port,$Secure,$Auth=false){
      $this->mail->isSMTP();                   
      $this->mail->SMTPDebug     = 0;              
      $this->mail->Debugoutput   = 'html';       
      $this->mail->Host          = $Server;                  
      $this->mail->Port          = $Port;                    
      $this->mail->SMTPSecure    = $Secure;            
      $this->mail->SMTPAuth      = $Auth;               
   }

   /**
   * SetAccount()
   * @param $email string
   * @param $password string  
   * @return void
   *
   */ 

   public function SetAccount($email,$password){
      $this->mail->Username = $email;                                 
      $this->mail->Password = $password; 
      $this->mail->setFrom($email, $this->FromName);                                         
   }

   /**
   * SetReplyTo()
   * @param $email string
   * @param $object string   
   * @return void
   *
   */ 

   public function SetReplyTo($email,$object){
      $this->mail->addReplyTo($email, $object);           
   }

   /**
   * AddAttachment()
   * @param $attachment String
   * @return void
   *
   */ 

   public function AddAttachment($attachment){
      $this->mail->AddAttachment($attachment);          
   }

   /**
   * AddAddress()
   * @param $to Array[]
   * @return void
   *
   */ 

   public function AddAddress($to){

      if (is_array($to)) {
         foreach($to as $to_add){
            $this->mail->AddAddress($to_add);                  
         }
      }else $this->mail->AddAddress($to); 
                                  
   }

   /**
   * AddAddressCcn()
   * @param $to Array[]
   * @return void
   *
   */ 

   public function AddAddressCcn($to){

      if (is_array($to)) {
         foreach($to as $to_add){
            $this->mail->addBCC($to_add);                 
         }
      }else $this->mail->addBCC($to); 
                                        
   }

   /**
   * AddAddressCc()
   * @param $to Array[]
   * @return void
   *
   */ 

   public function AddAddressCc($to){

      if (is_array($to)) {
         foreach($to as $to_add){
            $this->mail->addCC($to_add);                 
         }
      }else $this->mail->addCC($to); 
                                        
   }


   /**
   * AddMessage()
   * @param $object String
   * @param $message String
   * @param $newOutLine String   
   * @return void
   *
   */ 

   public function AddMessage($object,$message,$newOutLine=null){
      $this->mail->Subject = $object;                                          
     
      $emailTemplate = new OpenOutLine(__System__);
      if ($newOutLine != null) 
         $emailTemplate->setOutline("email/".$newOutLine);
      else $emailTemplate->setOutline("email/".$this->Theme);
      
      $emailTemplate->injectCode("mittente", $this->FromName);
      $emailTemplate->injectCode("messaggio", $message);
      
      $this->mail->msgHTML($emailTemplate->get());                                                   
   }


   /**
   * Send()
   * @return Bool
   *
   */ 

   public function Send(){

	try {
		
      //send the message, check for errors
      if (!$this->mail->send()){ 
         Log::Error($this->mail->ErrorInfo); 
         return new Result(false,$this->mail->ErrorInfo);
      }else{ 
         return new Result(true,'success');   
      }    
      	

	} catch (Exception $e) {
		Log::Error($e);
		return new Result(false, $e->getMessage());
	}       

   }


}