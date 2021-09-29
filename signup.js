function checkSignUp(event) {
	    //event.preventDefault();
		let username = document.querySelector("#username").value.trim();
		let password = document.querySelector("#password").value.trim();
		let confirm = document.querySelector("#confirmPassword").value.trim();
		let error = document.querySelector("#errorMessage");
		
		if((!username) || (!password) || (!confirm)) {
			error.style.display = "block";
			error.innerHTML = "Please complete all fields.";
			return false;
		}
		if(password != confirm) {
			error.style.display = "block";
			error.innerHTML = "Passwords do not match.";
			return false;
		} 
		
		return true;
	}
