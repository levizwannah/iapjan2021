let email = document.getElementById('email');
let password = document.getElementById('password');
let loginBtn = document.getElementById("login-btn");
let signBtn = document.getElementById("signup-btn");

loginBtn.addEventListener("click", function(){
    if(Utility.verify(email, "email")){
        Utility.main_ajax_with_call_back(handleResponse, "logic/procedures/login.php", "e="+email.value+"&p="+password.value, "POST");
    }
});

signBtn.addEventListener("click", function(){
    location.href="signup.php";
});

 function handleResponse(xhttp){
    let res = xhttp.responseText.trim();
    switch(res){
        case "pwe":
            {
                password.style.borderColor = "red";
                Utility.showError("Wrong password");
                break;
            }
        case "uee":
            {
                email.style.borderColor = "red";
                Utility.showError("Didn't find username");
                break;
            }
        case "yes":
            {
                location.href = "chatting.php";
            }
        default:
            {
                console.log(res);
            }
    }
}