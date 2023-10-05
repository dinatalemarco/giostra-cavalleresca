
x = new Building();

if(getCookie("currentSlider") == "" && getCookie("currentPage") == ""){
	x.configurationPage("home","home");
	x.home();
}else{

	$("."+getCookie("currentSlider")).removeClass("hidden");
	$("."+getCookie("currentPage")).removeClass("hidden");	

	if(getCookie("currentPage") == 'home'){
		x.configurationPage("home","home");
		x.home();
	}

	if(getCookie("currentPage") == 'login'){
		x.configurationPage("generic-slider","login");
		x.login();
	}

	if(getCookie("currentPage") == 'register'){
		x.configurationPage("generic-slider","register");
		x.register();
	}

	if(getCookie("currentPage") == 'about'){
		x.configurationPage("generic-slider","about");
		x.about(null);
	}

	if(getCookie("currentPage") == 'eventi'){
		x.configurationPage("generic-slider","eventi");
		x.events();
	}

	if(getCookie("currentPage") == 'viewevent'){
		x.configurationPage("generic-slider","viewevent");
		x.viewevent();
	}

	if(getCookie("currentPage") == 'publish'){
		x.configurationPage("generic-slider","publish");
		x.publish();
	}

	if(getCookie("currentPage") == 'users'){
		x.configurationPage("generic-slider","users");
		x.listUsersBorgo();
	}

	if(getCookie("currentPage") == 'profile'){
		x.configurationPage("generic-slider","profile");
		x.profile();
	}	

}