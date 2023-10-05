<?php
/**
 * Uuoden Php Web framework
 *
 * @author Di Natale Marco <dinatalemarco90@gmail.com>
 * @package  Language
 */

class Language{

	//Iizio Messaggi Generici
	public static $success = "Operation performed successfully";
	public static $error = "An error occurred, please try again";
	public static $error403 = "403 Permission Denied";
	public static $error404 = "404 Page Not Found";
	public static $error_method_file = "Per consetire la gestione dei file è necessario integrare la classe \"FileManagement\" nella modellazione";
	//Fine

	//Inizio Messaggi Utenti
	public static $errorLogin = "Nome utente o password errati, si prega di riprovare";
	public static $errorRegister = "Si è verificato un errore nella registrazione dell'utente, si prega di riprovare";
	public static $successRegister = "Creazione utente avvenuta con successo";
	//Fine

	//Inizio Messaggi Email
	public static $welcomeUser = "Benvenuto";
	public static $messageWelcomeUser = "Ti diamo il ben venuto sul nostro sistema, ti inviamo in allegato il nome utente e la password per eseguire l'accesso <br><br>";	
	public static $disabledAccount = "Account Disabilitato";
	public static $messageDisabledAccount = "Per tutelare i vostri dati l'account è stato disabilitato in quanto sono stati superati i tentativi massimi consentiti dall'applicazione, per procedere con lo sblocco del vostro account vi preghiamo di cliccare nel link riportato qui sotto.";	
	//Fine

	//Inizio Messi restituzione parametri
	public static $errorResult = "Si è verificato un errore nella restituzione dei parametri";
	//Fine

	//Inizio Messaggi Validazione
	public static $checkParameter = "Controlla il parametro in imput"; 
	public static $errorFormValidation = "Si è verificato un problema nella validazione della form";
	public static $incorrectInput = "Incorrect Input";
	//Fine

	//Inizio Messaggi gestione file system
	public static $largeFile = "Spiacente, il file è troppo grande";
	public static $uploaded = "File caricato con successo";
	public static $errorUploaded = "Spiacente, si è verificato un errore nel caricamento del file.";
	public static $deleteFile = "Cancellazione file avvenuta con successo";
	public static $fileDoesNotExist = "Il file che si desidera eliminare non esiste:";
	public static $errorDownload = "Errore nel Download del file";
	public static $errorShowFile = "Errore caricamento file";
	public static $notFoundFolder = "Cartella non trovata";
	//Fine

	//Inizio Messaggi sottomissione form
	public static $submissionControl = "Controllo sottomissione non attivo!";
	public static $controlSubmission = "Errore controllo sottomissione!";
	public static $succesSubmission = "Controllo sottomissione avvenuto con successo!";
	public static $timeSubmissionExpired = "Siamo spiacenti il tempo per la sottomissione della form è scaduto";
	public static $errorAuthorizationSubmission = "La form che si è cercato di sottomettere non ha le autorizzazioni!";
	//Fine

	//Inizio Messaggi per il programmatore
	public static $errorNonexistentFunction = "La funzione non esiste";
	public static $errorNotArray = "Il parametro in input non corrisponde ad un array:";
	public static $formArray = "L'array deve essere della forma:";	
	//Fine	

	
	


}