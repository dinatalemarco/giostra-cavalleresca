
class Building {

	configurationPage(currentSlider, currentPage) {
        if(getCookie("currentSlider") == "" && getCookie("currentPage") == ""){      
            $("."+currentSlider).removeClass("hidden");
            $("."+currentPage).removeClass("hidden");
            setCookie("currentSlider",currentSlider,1);
            setCookie("currentPage",currentPage,1);
        }else{
            $("."+getCookie("currentSlider")).addClass("hidden");
            $("."+getCookie("currentPage")).addClass("hidden");        
            $("."+currentSlider).removeClass("hidden");
            $("."+currentPage).removeClass("hidden");
            setCookie("currentSlider",currentSlider,1);
            setCookie("currentPage",currentPage,1);
        }
    }

    user(){
        if(getCookie("userInfo") != ""){
            var info = JSON.parse(getCookie("userInfo"));
            $(".hiddenLogin").addClass("hidden");
            $(".showLogin").removeClass("hidden");
            $(".boxUsername").html("Welcome <b>"+info['name']+"</b>");

            // Verifico i permessi utente per mostrare le voci di menu opportune
            if(info['permission'] == 100){
                $(".linkPanel").removeClass("hidden");
            }else{
                $(".linkPanel").addClass("hidden");
            }
            if(info['permission'] == 200){
                $(".linkUsers").removeClass("hidden");
                $(".linkPublish").removeClass("hidden");
            }else{
                $(".linkUsers").addClass("hidden");
                $(".linkPublish").addClass("hidden");
            }

        }else{
            $(".hiddenLogin").removeClass("hidden");
            $(".showLogin").addClass("hidden");
            $(".boxUsername").html("");
        }
    }

    home() {
        this.user();
    }


    register(name,surname,email,password,rePassword) {
        this.user();
     
        var req = new Requests();

        var reg = /^([A-Za-z0-9_\-\.])+\@([A-Za-z0-9_\-\.])+\.([A-Za-z]{2,4})$/;
        if (!reg.test(email)){ 
            $(".alertPassword").removeClass("hidden");
            $(".alertPassword").html("The email entered does not appear to be correct"); 
        }else{ 
            if(password != '' && rePassword != ''){
                if(password != rePassword){
                    $("#passwordRegister").css("border", "1px solid #ffb6c1");
                    $("#rePasswordRegister").css("border", "1px solid #ffb6c1");
                    $(".alertPassword").removeClass("hidden");
                    $(".alertPassword").html("The passwords entered do not match");
                }else{
                    /* Validazione avvenuta con successo */
                    var result = req.register(name,surname,email,password);

                    if(result.boolean == true){
                        var response = req.login(email,password)
                        var info = {id:response.response.id,
                                    name:response.response.name, 
                                    surname:response.response.surname, 
                                    email:response.response.email, 
                                    borgo:response.response.borgo, 
                                    permission:response.response.permission, 
                                    token:response.response.token};

                        setCookie("userInfo",JSON.stringify(info),1);

                        this.configurationPage("home","home");
                        this.home();
                        $(".welcome").html(response.response.name+" welcome to the medieval Sulmona");
                    }else{
                        $(".registerMessage").removeClass("hidden");
                        $(".registerMessage").html("The email entered is already in use in the system"); 
                    }
                }
            }else{
                $("#passwordRegister").css("border", "1px solid #ffb6c1");
                $("#rePasswordRegister").css("border", "1px solid #ffb6c1");
                $(".alertPassword").removeClass("hidden");
                $(".alertPassword").html("Please enter the password");
            }
        }     

    }


    login(email,password) {
        this.user();
        var req = new Requests();
        var result = req.login(email,password);

        var reg = /^([A-Za-z0-9_\-\.])+\@([A-Za-z0-9_\-\.])+\.([A-Za-z]{2,4})$/;
        if (reg.test(email)){ 
            if(email != '' && password != ''){
                if(result.boolean == true){
                    var info = {id:result.response.id,
                                name:result.response.name, 
                                surname:result.response.surname, 
                                email:result.response.email,
                                borgo:result.response.borgo,
                                permission:result.response.permission, 
                                token:result.response.token};

                    setCookie("userInfo",JSON.stringify(info),1);

                    this.configurationPage("home","home");
                    this.home();

                    // Email eseguita svuoto la form
                    $('#email').val("");
                    $('#password').val("");
                    $(".loginMessage").addClass("hidden");

                }else{
                    $(".loginMessage").removeClass("hidden");
                    $(".loginMessage").html(result.message);        
                }
            }else{
                $(".loginMessage").removeClass("hidden");
                $(".loginMessage").html('You must enter your email and password');  
            }
        }else{
            $(".loginMessage").removeClass("hidden");
            $(".loginMessage").html('Please enter a valid email address');             
        }
		
    }


    logout(){
        this.user();
        setCookie("userInfo","",-1);
        $(".welcome").html("Welcome to the medieval Sulmona");
        this.user();
    }

    removeAccount(){
        var req = new Requests();

        if(getCookie("userInfo") != ""){
            var info = JSON.parse(getCookie("userInfo"));
            var token = info['token'];
            var result = req.removeAccount(token);
            if(result.boolean == true){
                this.logout();
                this.configurationPage("home","home");
                this.home();
            }else{
                $(".alertErrorEditProfile").removeClass("hidden");
                $(".alertSuccessEditProfile").addClass("hidden");
                $(".alertErrorEditProfile").html("It was not possible to remove the account"); 
            }


        }else{
            x = new Building();
            x.configurationPage("generic-slider","login");
        }

    }


    profile(){
        this.user();
        var str = new Struct();
        var req = new Requests();

        if(getCookie("userInfo") != ""){
            this.user();
            var info = JSON.parse(getCookie("userInfo"));
            $('#nameEditProf').val(info['name']);
            $('#surnameEditProf').val(info['surname']);
            $('#emailEditProf').val(info['email']);

            /* Recupero la lista dei borghi */
            var borghi = req.listofborghi();
            str.itemOption(borghi.response);

        }else{
            x = new Building();
            x.configurationPage("generic-slider","login");
        }
    }

    editProfile(name,surname,email){
        this.user();
        var req = new Requests();

        if(getCookie("userInfo") != ""){
            var info = JSON.parse(getCookie("userInfo"));
            var token = info['token'];


            var reg = /^([A-Za-z0-9_\-\.])+\@([A-Za-z0-9_\-\.])+\.([A-Za-z]{2,4})$/;
            if (!reg.test(email) || name == "" || surname == ""){ 
                $(".alertErrorEditProfile").removeClass("hidden");
                $(".alertSuccessEditProfile").addClass("hidden");
                $(".alertErrorEditProfile").html("Check all the parameters entered");                 
            }else{
                var result = req.updateInfo(name,surname,email,token);

                    if(result.boolean == true){
                        var info = {name:name, 
                                    surname:surname, 
                                    email:email, 
                                    token:token};

                        setCookie("userInfo",JSON.stringify(info),1);

                        $(".alertSuccessEditProfile").removeClass("hidden");
                        $(".alertErrorEditProfile").addClass("hidden");
                        $(".alertSuccessEditProfile").html(result.message); 
                        $('#editOldpassword').val("");
                        $('#editPassword').val("");
                        $('#editRetypePassword').val(""); 
                        this.user();
                    }else{
                        $(".alertErrorEditProfile").removeClass("hidden");
                        $(".alertSuccessEditProfile").addClass("hidden");
                        $(".alertErrorEditProfile").html(result.message); 
                    }

            }

        }else{
            x = new Building();
            x.configurationPage("generic-slider","login");            
        }

    }

    editPassword(oldPassword,currentPassword,repeatPassword){
        this.user();
        var req = new Requests();

        if(getCookie("userInfo") != ""){
            var info = JSON.parse(getCookie("userInfo"));
            var token = info['token'];

            var reg = /^([A-Za-z0-9_\-\.])+\@([A-Za-z0-9_\-\.])+\.([A-Za-z]{2,4})$/;
            if (oldPassword =='' || currentPassword =='' || repeatPassword ==''){ 
                $(".alertErrorEditPassword").removeClass("hidden");
                $(".alertSuccessEditPassword").addClass("hidden");
                $(".alertErrorEditPassword").html("Check the password format");                 
            }else{

                if(currentPassword == repeatPassword){

                    var result = req.updatePassword(oldPassword,currentPassword,token);
                    
                    if(result.boolean == true){
                        $(".alertSuccessEditPassword").removeClass("hidden");
                        $(".alertErrorEditPassword").addClass("hidden");
                        $(".alertSuccessEditPassword").html(result.message);  
                    }else{
                        $(".alertErrorEditPassword").removeClass("hidden");
                        $(".alertSuccessEditPassword").addClass("hidden");
                        $(".alertErrorEditPassword").html(result.message); 
                    }
                    
                }else{
                    $(".alertErrorEditPassword").removeClass("hidden");
                    $(".alertSuccessEditPassword").addClass("hidden");
                    $(".alertErrorEditPassword").html("The two passwords entered do not match"); 
                }

            }

        }else{
            x = new Building();
            x.configurationPage("generic-slider","login");            
        }

    }    

    registrationAtBorgo(id_borgo){
        this.user();
        var req = new Requests();

        if(getCookie("userInfo") != ""){
            var info = JSON.parse(getCookie("userInfo"));
            var token = info['token'];

            if(id_borgo != ""){
                var result = req.borgoRegistration(id_borgo,token);
                $(".alertRegistrationBorgo").removeClass("hidden");
                $(".alertRegistrationBorgo").html(result.message); 
            }else{
                var result = req.borgoCancellation(token);
                $(".alertRegistrationBorgo").removeClass("hidden");
                $(".alertRegistrationBorgo").html(result.message);                 
            }
            
        }else{

        }

    }



    createEventEditView(id){
        this.user();
        var req = new Requests();

        if(getCookie("userInfo") != ""){
            this.user();
            var info = JSON.parse(getCookie("userInfo"));

            /* Recupero la lista dei borghi */
            var event = req.infoEvents(id);

            if(event.boolean == true){


            $("#addeventbutton").html("Edit Event"); 
            $("#listeventbutton").removeClass("active");
            $("#listeventsb").removeClass("active");
            $("#listeventsb").removeClass("show");
            $("#addeventbutton").addClass("active");
            $("#addeventb").addClass("active");
            $("#addeventb").addClass("show");

            $("#buttonEventAdd").addClass("hidden");
            $("#buttonEventEdit").removeClass("hidden");

     
            $('#idevent').val(event.response.id);
            $('#dateEvent').val(event.response.date);
            $('#titleEvent').val(event.response.name);
            $('#descriptionEvent').val(event.response.descrizione);
            $('#placesEvent').val(event.response.places);

            if (event.response.state == 1) {
                $('#stateEvent').html('<option value="1">Active</option><option value="0">Inactive</option>');
            }else{
                $('#stateEvent').html('<option value="0">Inactive</option><option value="1">Active</option>');
            } 

            }else{
                $(".alertSuccessRemoveEvent").addClass("hidden");
                $(".alertErrorRemoveEvent").removeClass("hidden");
                $(".alertErrorRemoveEvent").html("Check all the parameters entered"); 
            }             


        }else{
            x = new Building();
            x.configurationPage("generic-slider","login");
        }

    }


    createEvent(date,name,description,places,state){
        this.user();
        var req = new Requests();

        $(".alertSuccessAddEvent").addClass("hidden");
        $(".alertErrorAddEvent").addClass("hidden");

        if(getCookie("userInfo") != ""){
            var info = JSON.parse(getCookie("userInfo"));
            var token = info['token'];
            var borgo = info['borgo'];


            if(date != "" && name != "" && description != "" && places != "" && state != ""){

                var result = req.addEvent(borgo,date,name,description,places,state,token);

                if(result.boolean == true){ 
                    $('#dateEvent').val("");
                    $('#titleEvent').val("");
                    $('#descriptionEvent').val("");
                    $('#placesEvent').val("");
                    $('#stateEvent').val("");   

                    $(".alertSuccessRemoveEvent").removeClass("hidden");
                    $(".alertErrorRemoveEvent").addClass("hidden");
                    $(".alertSuccessRemoveEvent").html(result.message); 
                    $("#listeventbutton").addClass("active show");
                    $("#listeventsb").addClass("active show");
                    $("#addeventbutton").removeClass("active show");
                    $("#addeventb").removeClass("active show");
                    this.configurationPage("generic-slider","publish");
                    this.publish();             
                }else{
                    $(".alertSuccessAddEvent").addClass("hidden");
                    $(".alertErrorAddEvent").removeClass("hidden");
                    $(".alertErrorAddEvent").html(result.message);                 
                }

            }else{
                $(".alertSuccessAddEvent").addClass("hidden");
                $(".alertErrorAddEvent").removeClass("hidden");
                $(".alertErrorAddEvent").html("Check all the parameters entered"); 
            }


        }else{
            x = new Building();
            x.configurationPage("generic-slider","login");
        }

    }


    editEvent(id,date,name,description,places,state){
        this.user();
        var req = new Requests();

        if(getCookie("userInfo") != ""){
            var info = JSON.parse(getCookie("userInfo"));
            var token = info['token'];
            var borgo = info['borgo'];


            if(id != "" && date != "" && name != "" && description != "" && places != "" && state != ""){

                var result = req.updateEvent(borgo,id,date,name,description,places,state,token);

                if(result.boolean == true){
                    $("#addeventbutton").html("Add Event"); 
                    $("#listeventbutton").addClass("active");
                    $("#listeventsb").addClass("active");
                    $("#listeventsb").addClass("show");
                    $("#addeventbutton").removeClass("active");
                    $("#addeventb").removeClass("active");
                    $("#addeventb").removeClass("show");

                    $("#buttonEventAdd").removeClass("hidden");
                    $("#buttonEventEdit").addClass("hidden");

                    $('#idevent').val(""); 
                    $('#dateEvent').val("");
                    $('#titleEvent').val("");
                    $('#descriptionEvent').val("");
                    $('#placesEvent').val("");
                    $('#stateEvent').val("");   


                    $(".alertSuccessRemoveEvent").removeClass("hidden");
                    $(".alertErrorRemoveEvent").addClass("hidden");
                    $(".alertSuccessRemoveEvent").html(result.message); 
                    this.configurationPage("generic-slider","publish");
                    this.publish();            
                }else{
                    $(".alertSuccessAddEvent").addClass("hidden");
                    $(".alertErrorAddEvent").removeClass("hidden");
                    $(".alertErrorAddEvent").html(result.message);                 
                }

            }else{
                $(".alertSuccessAddEvent").addClass("hidden");
                $(".alertErrorAddEvent").removeClass("hidden");
                $(".alertErrorAddEvent").html("Check all the parameters entered"); 
            }


        }else{
            x = new Building();
            x.configurationPage("generic-slider","login");
        }

    }


    removeEvent(id_event){
        this.user();
        var req = new Requests();

        if(getCookie("userInfo") != ""){
            var info = JSON.parse(getCookie("userInfo"));
            var token = info['token'];
            var borgo = info['borgo'];


            if(id_event != ""){

                var result = req.removeEvent(borgo,id_event,token);

                if(result.boolean == true){
                    $(".alertSuccessRemoveEvent").removeClass("hidden");
                    $(".alertErrorRemoveEvent").addClass("hidden");
                    $(".alertSuccessRemoveEvent").html(result.message);   
                    this.publish();            
                }else{
                    $(".alertSuccessRemoveEvent").addClass("hidden");
                    $(".alertErrorRemoveEvent").removeClass("hidden");
                    $(".alertErrorRemoveEvent").html(result.message);               
                }

            }else{
                $(".alertSuccessRemoveEvent").addClass("hidden");
                $(".alertErrorRemoveEvent").removeClass("hidden");
                $(".alertErrorRemoveEvent").html("Check all the parameters entered"); 
            }


        }else{
            x = new Building();
            x.configurationPage("generic-slider","login");
        }

    }

    signUpForTheEvent(borgo,event,places){

        this.user();
        var req = new Requests();

        if(getCookie("userInfo") != ""){
            var info = JSON.parse(getCookie("userInfo"));
            var token = info['token'];
    


            if(event != "" && places != ""){

                var result = req.signUpForTheEvent(borgo,event,places,token);
                x = new Building();
                x.configurationPage("generic-slider","eventi"); 
                x.events();

            }else{
                $(".alertSuccessRemoveEvent").addClass("hidden");
                $(".alertErrorRemoveEvent").removeClass("hidden");
                $(".alertErrorRemoveEvent").html("Check all the parameters entered"); 
            }


        }else{
            x = new Building();
            x.configurationPage("generic-slider","login");
        }     

    }


    about(id) {
        this.user();
        
        var req = new Requests();
        var str = new Struct();


        if(id == null && getCookie("aboutPage") != ""){
            id = getCookie("aboutPage");
        }else{
            setCookie("aboutPage",id,1);
        }

        var result = req.infoBorgo(id);
        var palii = req.listofPalii(id);
        str.listPalii(palii.response);

        $('#stemma').attr('src',result.response.stemma)
        $("#nomeBorgo").html(result.response.nome);
        $("#motto").html(result.response.motto);
        $("#descrizione").html(result.response.descrizione);
        $("#capitano").html(result.response.capitano);
        $("#cavaliere").html(result.response.cavaliere);

    }            

    events() {
        this.user();
        var req = new Requests();
        var str = new Struct();
        var listEvent = req.listEvents();

        if(getCookie("userInfo") != ""){
            var info = JSON.parse(getCookie("userInfo"));
            var token = info['token'];
            var listReservations = req.getListReservations(token);

            str.itemEvent(listEvent.response,listReservations.response);
        }else{
            str.itemEvent(listEvent.response,null);
        }
		
    }

    listUsersBorgo(){
        this.user();
        var req = new Requests();
        var str = new Struct();

        if(getCookie("userInfo") != ""){
            var info = JSON.parse(getCookie("userInfo"));
            var token = info['token'];
            var borgo = info['borgo'];

            var resRoles = req.listRoles(token);
            var result = req.listUsersBorgo(borgo,token);
            str.itemListUsers(result.response,resRoles.response);  
        }else{
            x = new Building();
            x.configurationPage("generic-slider","login");   
        }
    }

    activateInscription(id){
        this.user();
        var req = new Requests();

        if(getCookie("userInfo") != ""){
            var info = JSON.parse(getCookie("userInfo"));
            var token = info['token'];
            var borgo = info['borgo'];
 
            var result = req.activeInscription(borgo,id,token);

                if(result.boolean == true){
                    $(".activateAccountSuccess").removeClass("hidden");
                    $(".activateAccountError").addClass("hidden");
                    $(".activateAccountSuccess").html(result.message);  
                }else{
                    $(".activateAccountError").removeClass("hidden");
                    $(".activateAccountSuccess").addClass("hidden");
                    $(".activateAccountError").html(result.message); 
                }
                this.configurationPage("generic-slider","users"); 
                this.listUsersBorgo();
        }else{
            x = new Building();
            x.configurationPage("generic-slider","login");   
        }
    }

    deactivatesInscription(id){
        this.user();
        var req = new Requests();

        if(getCookie("userInfo") != ""){
            var info = JSON.parse(getCookie("userInfo"));
            var token = info['token'];
            var borgo = info['borgo'];
 
            var result = req.deactivatesInscription(borgo,id,token);

                if(result.boolean == true){
                    $(".activateAccountSuccess").removeClass("hidden");
                    $(".activateAccountError").addClass("hidden");
                    $(".activateAccountSuccess").html(result.message);  
                }else{
                    $(".activateAccountError").removeClass("hidden");
                    $(".activateAccountSuccess").addClass("hidden");
                    $(".activateAccountError").html(result.message); 
                }
                this.configurationPage("generic-slider","users"); 
                this.listUsersBorgo();
        }else{
            x = new Building();
            x.configurationPage("generic-slider","login");   
        }        
    }

    changePermissions(id_user,id_role){

        this.user();
        var req = new Requests();

        if(getCookie("userInfo") != ""){
            var info = JSON.parse(getCookie("userInfo"));
            var token = info['token'];
            var borgo = info['borgo'];
 
            var result = req.changePermissions(borgo,id_user,id_role,token);

                if(result.boolean == true){
                    $(".activateAccountSuccess").removeClass("hidden");
                    $(".activateAccountError").addClass("hidden");
                    $(".activateAccountSuccess").html(result.message);  
                }else{
                    $(".activateAccountError").removeClass("hidden");
                    $(".activateAccountSuccess").addClass("hidden");
                    $(".activateAccountError").html(result.message); 
                }
                this.configurationPage("generic-slider","users"); 
                this.listUsersBorgo();
        }else{
            x = new Building();
            x.configurationPage("generic-slider","login");   
        }         

    }

    publish() {
        this.user();
        var req = new Requests();
        var str = new Struct();

        if(getCookie("userInfo") != ""){
            var info = JSON.parse(getCookie("userInfo"));
            var token = info['token'];
            var borgo = info['borgo'];
 
            var result = req.listEventsBorghi(borgo,token);
            str.itemListEvent(result.response);  
        }else{
            x = new Building();
            x.configurationPage("generic-slider","login");   
        }
		
    }

    infoEvents(event,item){
        this.user();
        var req = new Requests();
        var str = new Struct();

        if(getCookie("userInfo") != ""){
            var info = JSON.parse(getCookie("userInfo"));
            var token = info['token'];
            var borgo = info['borgo'];
 
            var result = req.getListReservationsEvent(borgo,event,token);
            str.infoItemEvent(event,result.response,item);  

        }else{
            x = new Building();
            x.configurationPage("generic-slider","login");   
        }

    }

    confirmationOfReservation(event,user,item){
        this.user();
        var req = new Requests();

        if(getCookie("userInfo") != ""){
            var info = JSON.parse(getCookie("userInfo"));
            var token = info['token'];
            var borgo = info['borgo'];
 
            var result = req.confirmationOfReservation(borgo,event,user,token);


            x = new Building();
            x.infoEvents(event,item);

        }else{
            x = new Building();
            x.configurationPage("generic-slider","login");   
        }        
    }



}


