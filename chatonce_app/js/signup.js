
let firstname = document.getElementById("firstname");
let lastname = document.getElementById("lastname");
let email = document.getElementById("email");
let phone = document.getElementById("phone");
let password = document.getElementById("password");
let retypePassword = document.getElementById("retype-password");
let signupBtn = document.getElementById('signup');
let profilePicture = document.getElementById("add-profile-pic");
let pageAction = 'new';
let changePassword = "";
//If this is set, then we are on the editing account page
if(document.getElementById("action")){
    pageAction = 'edit';
    changePassword = document.getElementById("old-password");
}

firstname.addEventListener('input', function(){
    Utility.verify(this, "name")
});

lastname.addEventListener('input', function(){
    Utility.verify(this, "name")
});

email.addEventListener('input', function(){
    Utility.verify(this, "email")
});

phone.addEventListener('input', function(){
    Utility.verify(this, "phone")
});

password.addEventListener('input', function(){
    Utility.checkPassword(this);
});

retypePassword.addEventListener('input', function(){
    Utility.hideError();
    this.style.borderColor = "";
    if(this.value != password.value){
        this.style.borderColor = "red";
        Utility.showError("Password not match");
    }
});

signupBtn.addEventListener('click', ()=>{
    //validate all inputs
    if(Utility.verify(firstname, "name") && Utility.verify(lastname, "name") &&
    Utility.verify(email, "email") &&
    Utility.verify(phone, "phone") &&
    Utility.checkPassword(password)){
        retypePassword.style.borderColor = "";
        if(retypePassword.value != password.value){
            retypePassword.style.borderColor = "red";
            Utility.showError("Password not match");
        }
        else if(!profilePicture.value){
            Utility.showError("Please add a profile picture");
        }
        else{
            //send to the signup page
            let form = new FormData();
            form.append("firstname", firstname.value);
            form.append("lastname", lastname.value);
            form.append("email", email.value);
            form.append("phone", phone.value);
            form.append("profile_picture", profilePicture.files[0]);
            form.append("password", password.value);
            form.append("old-password", changePassword.value);
            form.append("action=", pageAction);

            Utility.main_ajax_with_call_back(handleResponse, "logic/procedures/signup.php", form, "POST",false);
        }
    }
});

function handleResponse(xhttp){
    let res = xhttp.responseText.trim();
    switch(res){
        case "aie":
            {
                Utility.showError("Add an Your profile picture");
                break;
            }
        case "ie":
            {
                Utility.showError("Invalid Image (.jpg, .jpeg, .png, .bmp");
                break;
            }
        case "fne":
            {
                Utility.verify(firstname, "name");
                break;
            }
        case "lne":
            {
                Utility.verify(lastname, "name");
                break;
            }
        case "pne":
            {
                Utility.verify(phone, "phone");
                break;
            }
        case "eme":
            {
                Utility.verify(email, "name");
                break;
            }
        case "emee":
            {
                email.style.borderColor = "red";
                Utility.showError("This email already exist");
                break;
            }
        case "ple":
            {
                Utility.checkPassword(password);
                break;
            }
        case "puce":
            {
                Utility.checkPassword(password);
                break;
            }
        case "plce":
            {
                Utility.checkPassword(password);
                break;
            }
        case "pwne":
            {
                Utility.checkPassword(password);
                break;
            }
        case "yes":
            {
                Utility.showSuccess("You successfully signed up. Proceeding to login...");
                setTimeout(() => {
                    location.href = "login.php";
                }, 1500);
                break;
            }
        case "no":
            {
                break;
            }
        default:
            {
                console.log(res);
            }
    }
}


