function logout(){
    Utility.main_ajax_with_call_back((xhttp)=>{
        let res = xhttp.responseText.trim();
        if(res == "yes"){
            location.href = "login.php";
        }else{
            console.log(res);
        }
    }, "logic/procedures/logout.php","", "GET");
}