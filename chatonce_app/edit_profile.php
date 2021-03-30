<?php
    session_start();
    require_once("logic/classes/utility.class.php");
    require_once("logic/interfaces/account.interface.php");
    require_once("logic/classes/user.class.php");

    if(!isset($_SESSION['user_id'])){
        header("Location: login.php");
    }

    $conn = Utility::makeConnection();

    $user = new User($_SESSION['user_id'], $conn);
    
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="css/main.css">
    <title>Update account</title>
</head>
<body>
    <header>
        <h1 class="logo">ChatOnce</h1>
    </header>
    <main>
        <input type="hidden" name="action" id="action">
        <section class='box_shadow signup_section'>
            <div class='img_div'>
                <img src="resources/profile.jpeg" alt="" class="signup profile" id="profile-picture">

                <button class="btn_normal" onclick="document.getElementById('add-profile-pic').click()">Choose profile picture</button>
                <input type="file" oninput="Utility.displayImage(this, document.getElementById('profile-picture'))" id="add-profile-pic" class="hidden">
            </div>
            <div>
                <div class="error" id="error">
                </div>
                <div class="success" id="success">
                </div>
                
                <div class="horizontal_align">
                    <div class="form_input mb20">
                        <label for="">First Name</label>
                        <input type="text" class="input_normal" value="<?php echo $user->getFirstname(); ?>" id="firstname">
                    </div>
                    <div class="form_input mb20">
                        <label for="">Last Name</label>
                        <input type="text" class="input_normal" value="<?php echo $user->getLastname(); ?>" id="lastname">
                    </div>
                </div>
                    <div class="form_input mb20">
                        <label for="">Email</label>
                        <input type="email" class="input_normal" value="<?php echo $user->getEmail(); ?>" id="email">
                    </div>
                    <div class="form_input mb20">
                        <label for="">Phone</label>
                        <input type="text" class="input_normal" value="<?php echo $user->getPhone(); ?>" id="phone">
                    </div>
                    <div class="form_input mb20">
                        <label for="">Old Password</label>
                        <input type="password" class="input_normal" id="old-password">
                    </div>
                    <div class="form_input mb20">
                        <label for="">New Password</label>
                        <input type="password" class="input_normal" id="password">
                    </div>
                    <div class="form_input mb20">
                        <label for="">Retype Password</label>
                        <input type="password" class="input_normal" id="retype-password">
                    </div>
                    <button class="btn_normal btn_big" id="signup">Update</button>

            </div>
        </section>
    </main>
    <footer>
        <script src="js/classes/Utility.js"></script>
        <script src="js/signup.js"></script>
    </footer>
</body>
</html>