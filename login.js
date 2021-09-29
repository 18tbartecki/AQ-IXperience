function checkLogin(event) {
	    //event.preventDefault();
		let username = document.querySelector("#username").value;
		let password = document.querySelector("#password").value;
		let error = document.querySelector("#errorMessage");
		
		if((!username) || (!password)) {
			error.style.display = "block";
			error.innerHTML = "Please enter username and password.";
			return false;
		}
		
		return true;
	}