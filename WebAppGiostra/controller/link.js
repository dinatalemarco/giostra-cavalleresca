
$(document).ready(function() {
  var req = new Requests();
  var str = new Struct();
  var result = req.listofborghi();
  str.itemBorghi(result.response);
        
  $(".form_datetime").datetimepicker({format: 'yyyy-mm-dd hh:ii:ss'});

});



$(".linkHome").click(function() {
	x = new Building();
	x.configurationPage("home","home");
	x.home();
});

$(".linkProfile").click(function() {
	x = new Building();
	x.configurationPage("generic-slider","profile");
	x.profile();
});


$(".linkLogin").click(function() {
	x = new Building();
	x.configurationPage("generic-slider","login");
});

$(".linkLogout").click(function() {
	x = new Building();
	x.configurationPage("home","home");
	x.logout();
});


$(".linkRegister").click(function() {
	x = new Building();
	x.configurationPage("generic-slider","register");
});

$(".linkRegisterSubmission").click(function() {

	name = document.getElementById("nameRegister").value;
	surname = document.getElementById("surnameRegister").value;
	email = document.getElementById("emailRegister").value;
	password = document.getElementById("passwordRegister").value;
	rePassword = document.getElementById("rePasswordRegister").value;

	x = new Building();
	x.register(name,surname,email,password,rePassword);
});

$(".linkLoginSubmission").click(function() {
	email = document.getElementById("email").value;
    password = document.getElementById("password").value
	x = new Building();
	x.login(email,password);
});

$(".linkEditProfileSubmission").click(function() {
	name = document.getElementById("nameEditProf").value;
    surname = document.getElementById("surnameEditProf").value
    email = document.getElementById("emailEditProf").value
	x = new Building();
	x.configurationPage("generic-slider","profile");
	x.editProfile(name,surname,email);
});

$(".linkEditPasswordSubmission").click(function() {
	oldPasssword = document.getElementById("editOldpassword").value;
    password = document.getElementById("editPassword").value
    repeatPassword = document.getElementById("editRetypePassword").value
	x = new Building();
	x.configurationPage("generic-slider","profile");
	x.editPassword(oldPasssword,password,repeatPassword);
});

$(".linkAddEventSubmission").click(function() {

	date = document.getElementById("dateEvent").value;
	name = document.getElementById("titleEvent").value;
	description = document.getElementById("descriptionEvent").value;
	places = document.getElementById("placesEvent").value;
	state = document.getElementById("stateEvent").value;

	x = new Building();
	x.createEvent(date,name,description,places,state);
});


$(".linkEditEventSubmission").click(function() {

	id = document.getElementById("idevent").value;
	date = document.getElementById("dateEvent").value;
	name = document.getElementById("titleEvent").value;
	description = document.getElementById("descriptionEvent").value;
	places = document.getElementById("placesEvent").value;
	state = document.getElementById("stateEvent").value;

	x = new Building();
	x.editEvent(id,date,name,description,places,state);
});



$(".linkRemoveAccount").click(function() {
	x = new Building();
	x.removeAccount();
});


$(".linkEventi").click(function() {
	x = new Building();
	x.configurationPage("generic-slider","eventi");
	x.events();
});


$(".linkUsers").click(function() {
	x = new Building();
	x.configurationPage("generic-slider","users");
	x.listUsersBorgo();
});

$(".linkPublish").click(function() {
	x = new Building();
	x.configurationPage("generic-slider","publish");
	x.publish();
});


$("#linkBorghiList").change(function(){
	x = new Building();
	x.registrationAtBorgo($("#linkBorghiList").val());
});



