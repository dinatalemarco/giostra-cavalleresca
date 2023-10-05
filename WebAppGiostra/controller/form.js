function getListUsers(id_event,Itme){

	if(getCookie("userInfo") == ""){
		// procedo con l'iscrizione
		x = new Building();
		x.configurationPage("generic-slider","login");
	}else{
		x = new Building();
		x.infoEvents(id_event,Itme);
	}

}


function confirmationOfReservation(event,user,item){

	if(getCookie("userInfo") == ""){
		// procedo con l'iscrizione
		x = new Building();
		x.configurationPage("generic-slider","login");
	}else{
		x = new Building();
		x.confirmationOfReservation(event,user,item);
	}

}





function inscription(id_borgo,id_event,places){
	x = new Building();
	if(getCookie("userInfo") == ""){
		// procedo con l'iscrizione
		x.configurationPage("generic-slider","login");
	}else{
		// L'utente non si è loggato
		x.signUpForTheEvent(id_borgo,id_event,places);
	}

}


function roleChar(id_user,id_role){
	
	if(getCookie("userInfo") == ""){
		// L'utente non ha fatto l'accesso
		x = new Building();
		x.configurationPage("generic-slider","login");
	}else{
		// Procedo all'attivazione
		x = new Building();
		x.configurationPage("generic-slider","users");
		x.changePermissions(id_user,id_role);
	}

}


function activateAccount(id){

	if(getCookie("userInfo") == ""){
		// L'utente non ha fatto l'accesso
		x = new Building();
		x.configurationPage("generic-slider","login");
	}else{
		// Procedo all'attivazione
		x = new Building();
		x.configurationPage("generic-slider","users");
		x.activateInscription(id);
	}

}

function deactivatesAccount(id){
	if(getCookie("userInfo") == ""){
		// L'utente non ha fatto l'accesso
		x = new Building();
		x.configurationPage("generic-slider","login");
	}else{
		// Procedo all'attivazione
		x = new Building();
		x.configurationPage("generic-slider","users");
		x.deactivatesInscription(id);
	}	
}


function about(id){
	x = new Building();
	x.configurationPage("generic-slider","about");
	x.about(id);
}


function createEventEditView(id){
	x = new Building();
	x.createEventEditView(id);
}

function removeEvent(id){
	x = new Building();
	x.removeEvent(id);	
}

