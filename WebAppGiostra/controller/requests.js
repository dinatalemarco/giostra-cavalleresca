
class Requests {

	addBooleanParameter(json,status_code){

		var myObject = new Object();

		if(status_code == 200 || status_code == 201){
			myObject.boolean = true;
		}else{
			myObject.boolean = false;
		}

		myObject.message = json.message;
		myObject.response = json.response; 
		return myObject;

	}

	/* Request Register user */
	register(name,surname,email,password){

	    var data = $.ajax({ 
					url: 'http://localhost:8888/giostra/api/rest/v1/register', 
					type: "POST",
					data: '{"name": "'+name+'", "surname" : "'+surname+'", "email" : "'+email+'", "password" : "'+password+'"}',
					contentType: "application/json; charset=utf-8", 
					dataType: 'json', 
					async: false 
					});

	    var status_code = data.status;
	    var response = $.parseJSON(data.responseText);	   

	    return this.addBooleanParameter(response,status_code);

	}


	/* Request Login user */
	login(email,password){

	    var data = $.ajax({ 
						url: 'http://localhost:8888/giostra/api/rest/v1/login', 
						type: "POST",
						data: '{"email": "' + email + '", "password" : "' + password + '"}',
						contentType: "application/json; charset=utf-8", 
						dataType: 'json', 
						async: false 
						});

	    var status_code = data.status;
	    var response = $.parseJSON(data.responseText);	   

	    return this.addBooleanParameter(response,status_code);

	}

	/* Request Remove Account user */
	removeAccount(token){

	    var data = $.ajax({
	            url: 'http://localhost:8888/giostra/api/rest/v1/auth/users/remove',
	            type: 'DELETE',
	            beforeSend: function (xhr) {
	                xhr.setRequestHeader('Authorization', 'Bearer '+token);
	            },
	            data: {},
	            async: false
	        });

	    var status_code = data.status;
	    var response = $.parseJSON(data.responseText);	   

	    return this.addBooleanParameter(response,status_code);

	}

	/* Request Update Password user */
	updatePassword(oldPassword,password,token){

		var info = JSON.parse(getCookie("userInfo"));
        var token = info['token'];

		var dataObject = {"password" : password,
						  "oldpassword" : oldPassword};

	    var data = $.ajax({
	            url: 'http://localhost:8888/giostra/api/rest/v1/auth/users/password',
	            type: 'PATCH',
	            beforeSend: function (xhr) {
	                xhr.setRequestHeader('Authorization', 'Bearer '+token);
	                this.data += '&' + $.param(dataObject);
	            },
	            data: JSON.stringify(dataObject),
	            async: false
	        });

	    var status_code = data.status;
	    var response = $.parseJSON(data.responseText);	   

	    return this.addBooleanParameter(response,status_code);

	}


	/* Request Update Info user */
	updateInfo(name,surname,email,token){

		var dataObject = {"name" : name,
						  "surname" : surname,
						  "email" : email};


	    var data = $.ajax({
	        			url: 'http://localhost:8888/giostra/api/rest/v1/auth/users/info',
	        			type: 'PATCH',
	        			beforeSend: function (xhr) {
	                		xhr.setRequestHeader('Authorization', 'Bearer '+token);
	                		this.data += '&' + $.param(dataObject);
	        			},
	        			data: JSON.stringify(dataObject),
	        			async: false
	        			});

	    var status_code = data.status;
	    var response = $.parseJSON(data.responseText);	   

	    return this.addBooleanParameter(response,status_code);



	}

	/* Request registration of the Borgo */
	borgoRegistration(id_borgo,token){

	    var data = $.ajax({
	        			url: 'http://localhost:8888/giostra/api/rest/v1/auth/borghi/'+id_borgo+'/users/inscription',
	        			type: 'POST',
	        			beforeSend: function (xhr) {
	                		xhr.setRequestHeader('Authorization', 'Bearer '+token);
	        			},
	        			async: false
	       	 			});

	    var status_code = data.status;
	    var response = $.parseJSON(data.responseText);	   

	    return this.addBooleanParameter(response,status_code);



	}

	/* Request Cancellation of the Borgo */
	borgoCancellation(token){

	    var data = $.ajax({
	        		url: 'http://localhost:8888/giostra/api/rest/v1/auth/borghi/users/inscription',
	        		type: 'DELETE',
	        		beforeSend: function (xhr) {
	        	        xhr.setRequestHeader('Authorization', 'Bearer '+token);
	        		},
	        		data: {},
	        		async: false
	        		});

	    var status_code = data.status;
	    var response = $.parseJSON(data.responseText);	   

	    return this.addBooleanParameter(response,status_code);


	}


	/* Request List of Borghi */
	listofborghi(){

	    var data = $.ajax({ 
						url: 'http://localhost:8888/giostra/api/rest/v1/borghi', 
						type: "GET",
						contentType: "application/json", 
						dataType: 'json', 
						async: false  
					});

	    var status_code = data.status;
	    var response = $.parseJSON(data.responseText);	   

	    return this.addBooleanParameter(response,status_code);

	}

	/* Request Info Borgo */
	infoBorgo(idBorgo){

	    var data = $.ajax({ 
						url: 'http://localhost:8888/giostra/api/rest/v1/borghi/'+idBorgo, 
						type: "GET",
						contentType: "application/json", 
						dataType: 'json', 
						async: false  
						});

	    var status_code = data.status;
	    var response = $.parseJSON(data.responseText);	   

	    return this.addBooleanParameter(response,status_code);


	}

	/* Request List of Palii */
	listofPalii(id_borgo){

	    var data = $.ajax({ 
				url: 'http://localhost:8888/giostra/api/rest/v1/borghi/'+id_borgo+'/palii', 
				type: "GET",
				contentType: "application/json", 
				dataType: 'json', 
				async: false  
			});

	    var status_code = data.status;
	    var response = $.parseJSON(data.responseText);	   

	    return this.addBooleanParameter(response,status_code);


	}

	/* Request List Event */
	listEvents(){

	    var data = $.ajax({ 
				url: 'http://localhost:8888/giostra/api/rest/v1/events',
				type: "GET",
				contentType: "application/json", 
				dataType: 'json', 
				async: false  
			});

	    var status_code = data.status;
	    var response = $.parseJSON(data.responseText);	   

	    return this.addBooleanParameter(response,status_code);

	}	


	/* Request Event */
	infoEvents(id){

	    var data = $.ajax({ 
				url: 'http://localhost:8888/giostra/api/rest/v1/events/'+id,
				type: "GET",
				contentType: "application/json", 
				dataType: 'json', 
				async: false  
			});

	    var status_code = data.status;
	    var response = $.parseJSON(data.responseText);	   

	    return this.addBooleanParameter(response,status_code);	    

	}


	/* Request Add Event of the Borgo */
	addEvent(borgo,date,name,description,places,state,token){

		var dataObject = {"date" : date, 
						  "name" : name, 
						  "description" : description, 
						  "places" : places, 
						  "state" : state};

	    var data = $.ajax({
	        			url: 'http://localhost:8888/giostra/api/rest/v1/auth/borghi/'+borgo+'/events',
	        			type: 'POST',
	        			beforeSend: function (xhr) {
	                		xhr.setRequestHeader('Authorization', 'Bearer '+token);
	               			this.data += '&' + $.param(dataObject);
	        			},
	        			data: JSON.stringify(dataObject),
	        			async: false
	        			});

	    var status_code = data.status;
	    var response = $.parseJSON(data.responseText);	   

	    return this.addBooleanParameter(response,status_code);	    

	}

	/* Request Update Event of the Borgo */
	updateEvent(borgo,id,date,name,description,places,state,token){
		
		var dataObject = {"date" : date, 
						  "name" : name, 
						  "description" : description, 
						  "places" : places, 
						  "state" : state};

	    var data = $.ajax({
	        			url: 'http://localhost:8888/giostra/api/rest/v1/auth/borghi/'+borgo+'/events/'+id,
	        			type: 'PUT',
	        			beforeSend: function (xhr) {
	                		xhr.setRequestHeader('Authorization', 'Bearer '+token);
	                		this.data += '&' + $.param(dataObject);
	        			},
	        			data: JSON.stringify(dataObject),
	        			async: false
	        			});

	    var status_code = data.status;
	    var response = $.parseJSON(data.responseText);	   

	    return this.addBooleanParameter(response,status_code);	


	}


	/* Request Add Event of the Borgo */
	listEventsBorghi(borgo,token){

	    var data = $.ajax({
	       				url: 'http://localhost:8888/giostra/api/rest/v1/borghi/'+borgo+'/events',
	        			type: 'GET',
				        beforeSend: function (xhr) {
				                xhr.setRequestHeader('Authorization', 'Bearer '+token);
				        },
				        data: {},
				        async: false
				        });

	    var status_code = data.status;
	    var response = $.parseJSON(data.responseText);	  

	    return this.addBooleanParameter(response,status_code);

	}


	/* Request Add Event of the Borgo */
	removeEvent(borgo,id_event,token){

	    var data = $.ajax({
	        			url: 'http://localhost:8888/giostra/api/rest/v1/auth/borghi/'+borgo+'/events/'+id_event,
	        			type: 'DELETE',
	        			beforeSend: function (xhr) {
	                		xhr.setRequestHeader('Authorization', 'Bearer '+token);
	        			},
	        			data: {},
	        			async: false
	        			});

	    var status_code = data.status;
	    var response = $.parseJSON(data.responseText);	   

	    return this.addBooleanParameter(response,status_code);


	}


	/* Request Add Event of the Borgo */
	listUsersBorgo(borgo,token){

	    var data = $.ajax({
	        			url: 'http://localhost:8888/giostra/api/rest/v1/auth/borghi/'+borgo+'/users',
	        			type: 'GET',
	        			beforeSend: function (xhr) {
	                		xhr.setRequestHeader('Authorization', 'Bearer '+token);
	        			},
	        			data: {},
	        			async: false
	        			});

	    var status_code = data.status;
	    var response = $.parseJSON(data.responseText);	   

	    return this.addBooleanParameter(response,status_code);


	}


	/* Request Add Event of the Borgo */
	activeInscription(borgo,user,token){

	    var data = $.ajax({
	        			url: 'http://localhost:8888/giostra/api/rest/v1/auth/borghi/'+borgo+'/users/'+user+'/active',
	        			type: 'PATCH',
	        			beforeSend: function (xhr) {
	                		xhr.setRequestHeader('Authorization', 'Bearer '+token);
	        			},
	        			data: {},
	        			async: false
	        			});

	    var status_code = data.status;
	    var response = $.parseJSON(data.responseText);	   

	    return this.addBooleanParameter(response,status_code);


	}


	/* Request Add Event of the Borgo */
	deactivatesInscription(borgo,user,token){

	    var data = $.ajax({
	        			url: 'http://localhost:8888/giostra/api/rest/v1/auth/borghi/'+borgo+'/users/'+user+'/deactivates',
	        			type: 'PATCH',
	        			beforeSend: function (xhr) {
	                		xhr.setRequestHeader('Authorization', 'Bearer '+token);
	        			},
	        			data: {},
	        			async: false
	        			});

	    var status_code = data.status;
	    var response = $.parseJSON(data.responseText);	   

	    return this.addBooleanParameter(response,status_code);

	}	


	/* Request List Roles */
	listRoles(token){

	    var data = $.ajax({ 
						url: 'http://localhost:8888/giostra/api/rest/v1/auth/roles', 
						type: "GET",
		        		beforeSend: function (xhr) {
	                		xhr.setRequestHeader('Authorization', 'Bearer '+token);
	        			},					
						data: {}, 
						async: false  
					});

	    var status_code = data.status;
	    var response = $.parseJSON(data.responseText);	   

	    return this.addBooleanParameter(response,status_code);

	}


	/* Request Add Event of the Borgo */
	changePermissions(borgo,user,role,token){

	    var data = $.ajax({
	        			url: 'http://localhost:8888/giostra/api/rest/v1/auth/borghi/'+borgo+'/users/'+user+'/roles/'+role,
	        			type: 'PATCH',
	        			beforeSend: function (xhr) {
	                		xhr.setRequestHeader('Authorization', 'Bearer '+token);
	        			},
	        			data: {},
	        			async: false
	        			});

	    var status_code = data.status;
	    var response = $.parseJSON(data.responseText);	   

	    return this.addBooleanParameter(response,status_code);

	}	


	signUpForTheEvent(borgo,event,places,token){

		var dataObject = {"places" : places};

	    var data = $.ajax({
	        			url: 'http://localhost:8888/giostra/api/rest/v1/auth/borghi/'+borgo+'/users/inscription/events/'+event,
	        			type: 'POST',
	        			beforeSend: function (xhr) {
	                		xhr.setRequestHeader('Authorization', 'Bearer '+token);
	                		this.data += '&' + $.param(dataObject);
	        			},
	        			data: JSON.stringify(dataObject),
	        			async: false
	        			});

	    var status_code = data.status;
	    var response = $.parseJSON(data.responseText);	   

	    return this.addBooleanParameter(response,status_code);

	}



	getListReservations(token){

	    var data = $.ajax({
	        			url: 'http://localhost:8888/giostra/api/rest/v1/auth/users/inscription/events',
	        			type: 'GET',
	        			beforeSend: function (xhr) {
	                		xhr.setRequestHeader('Authorization', 'Bearer '+token);
	        			},
	        			data: {},
	        			async: false
	        			});

	    var status_code = data.status;
	    var response = $.parseJSON(data.responseText);	   

	    return this.addBooleanParameter(response,status_code);

	}	


	getListReservationsEvent(borgo,event,token){
	
	    var data = $.ajax({
	        			url: 'http://localhost:8888/giostra/api/rest/v1/auth/borghi/'+borgo+'/events/'+event+'/users',
	        			type: 'GET',
	        			beforeSend: function (xhr) {
	                		xhr.setRequestHeader('Authorization', 'Bearer '+token);
	        			},
	        			data: {},
	        			async: false
	        			});

	    var status_code = data.status;
	    var response = $.parseJSON(data.responseText);	   

	    return this.addBooleanParameter(response,status_code);

	}


	confirmationOfReservation(borgo,event,user,token){
	
	    var data = $.ajax({
	        			url: 'http://localhost:8888/giostra/api/rest/v1/auth/borghi/'+borgo+'/events/'+event+'/users/'+user+'/accept',
	        			type: 'PATCH',
	        			beforeSend: function (xhr) {
	                		xhr.setRequestHeader('Authorization', 'Bearer '+token);
	        			},
	        			data: {},
	        			async: false
	        			});

	    var status_code = data.status;
	    var response = $.parseJSON(data.responseText);	   

	    return this.addBooleanParameter(response,status_code);

	}





}


