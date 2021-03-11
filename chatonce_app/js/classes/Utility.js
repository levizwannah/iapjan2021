class Utility{
    static nameReg = /^[A-Za-z'-\.]+$/;
    static phoneReg = /^\+\d{9,}$/;
    static descReg = /^[\w\d\s-',\.]+$/;
    static emailReg = /^[a-z0-9.!#$%&*+/=?^_{|}~-]+@[a-z0-9-]+(\.[a-z0-9-]+)*$/;
    static error = document.getElementById("error");
    static success = document.getElementById("success");

    /**
     * The value of type can be: name, phone, desc
     * @param {HTMLElement} what 
     * @param {string} type 
     * @param {HTMLElement} error
     * when the error is specified, then this error gets displayed in that error element
     */
    static verify(what, type, error = null){
        what.style.borderColor = "";
        this.hideError(error);
        switch(type){
            case "name":
                {
                    if(this.nameReg.test(what.value)){
                        return true;
                    }else{
                        what.style.borderColor = "red";
                        this.showError("Name can only contain A-Z or a-z", error);
                    }
                    break;
                }
            case "phone":
                {
                    if(this.phoneReg.test(what.value)){
                        return true;
                    }else{
                        what.style.borderColor = "red";
                        this.showError("Your Phone number is invalid e.g. +234898888999", error);
                    }
                    break;
                }
            case "email":
                {
                    if(this.emailReg.test(what.value)){
                        return true;
                    }else{
                        what.style.borderColor = "red";
                        this.showError("email invalid.", error);
                    }
                    break;
                }
            case "desc":
                {
                    if(this.descReg.test(what.value)){
                        return true;
                    }else{
                        what.style.borderColor = "red";
                        this.showError("Invalid input or character", error);
                    }
                    break;
                }
            default:
                {
                    return;
                }
        }
    }

    /**
     * 
     * @param {string} error 
     * @param {HTMLElement} error_div 
     */
    static showError(error, error_div = null){
        if(error_div){
            error_div.style.display = "";
            error_div.innerHTML = error;
        }
        else{
            this.error.style.display = "";
            this.error.innerHTML = error;
        }
        setTimeout(() => {
            this.hideError(error_div);
        }, 3000);
        return;
    }


    /**
     * 
     * @param {HTMLElement} error_div 
     */
    static hideError(error_div){
        if(error_div){
            error_div.innerHTML = "";
            error_div.style.display = "none";
        }
        else{
            this.error.innerHTML = "";
            this.error.style.display = "none";
        }

        return;
    }

    /**
     * 
     * @param {String} data 
     */
    static showSuccess(data){
        this.success.innerHTML = data;
        this.success.style.display = "";
        setTimeout(() => {
            this.hideSuccess();
        }, 3000);
        return;
    }

    static hideSuccess(){
        this.success.innerHTML = "";
        this.success.style.display = "none";
        return;
    }

    /**
     * Verifies that password met the set standards
     * @param {HTMLElement} password 
     * @param {HTMLElement} error 
     * @returns {boolean}
     */
    static checkPassword(password, error=null){
        password.style.borderColor = "";
        if(password.value.length < 9){
            password.style.borderColor = "red";
            this.showError("Password must be longer than 9 characters", error);
            return false;
        }

        if(!password.value.match(/[A-Z]/)){
            password.style.borderColor = "red";
            this.showError("Password must include uppercase letters", error);
            return false;
        }

        if(!password.value.match(/[a-z]/)){
            password.style.borderColor = "red";
            this.showError("Password must include lowercase letters", error);
            return false;
        }

        if(!password.value.match(/\W/)){
            password.style.borderColor = "red";
            this.showError("Password must include a special characterrs", error);
            return false;
        }

        if(!password.value.match(/\d/)){
            password.style.borderColor = "red";
            this.showError("Password must include number", error);
            return false;
        }
        
       
        return true;
    }

    static displayImage(input, imageHolder){
        var file = input.files[0];
        if (file) {
            const reader= new FileReader();
             reader.addEventListener('load', function(){
             imageHolder.src = this.result;
            });
           reader.readAsDataURL(file);
        }
    }


    //universal ajax
    /**
     * Ajax that changes the innerhtml of an element upon response
     * @param {HTMLElement} element_to_change 
     * @param {string} url 
     * @param {string} data 
     * @param {boolean} send_format 
     */
    static main_ajax_with_element(element_to_change, url, data, send_format){
        send_format = send_format.toLowerCase();
        var xhttp;
        if(window.XMLHttpRequest){
            xhttp = new XMLHttpRequest();
        }else{
            xhttp = new ActiveXObject("Microsoft.XMLHTTP"); 
        }
        xhttp.onreadystatechange = function(){
            if(this.readyState == 4 && this.status == 200){
                element_to_change.classList.remove("center_content");
                element_to_change.className = element_to_change.className.replace(/\bcenter_content\b/g,"");
                element_to_change.innerHTML = this.responseText;
            }else{
                let classes = element_to_change.className.split(" ");
                if(classes.indexOf("center_content") == -1){
                    element_to_change.className += " center_content";
                }
                element_to_change.innerHTML = "<img src='resources/Spinner-1s-200px.gif' alt='loading' class='loading_img' >";
            }
        }
    
        if(send_format == 'post'){
            xhttp.open("POST",url, true);
            xhttp.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
            xhttp.send(data);
    
        }else{
            xhttp.open("GET", url+"/?"+data, true);
            xhttp.send();
        }
    }
    
    /**
     * 
     * @param {function} call_back 
     * @param {string} url 
     * @param {string} data 
     * @param {boolean} send_format 
     * @param {boolean} set_header 
     */
    static main_ajax_with_call_back(call_back, url, data, send_format, set_header = true){
        send_format = send_format.toLowerCase();
        var xhttp;
        if(window.XMLHttpRequest){
            xhttp = new XMLHttpRequest();
        }else{
            xhttp = new ActiveXObject("Microsoft.XMLHTTP"); 
        }
        xhttp.onreadystatechange = function(){
            if(this.readyState == 4 && this.status == 200){
                call_back(this);
            }
        }
    
        if(send_format == 'post'){
            xhttp.open("POST",url, true);
            if(set_header){
                xhttp.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
            }  
            xhttp.send(data);
    
        }else{
            xhttp.open("GET", url+"/?"+data, true);
            xhttp.send();
        }
    }

    
}

Utility.hideError();
Utility.hideSuccess();