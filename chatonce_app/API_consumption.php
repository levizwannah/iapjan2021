<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="css/main.css">
    <title>Login</title>
</head>
<body>
    <header>
        <h1 class="logo">ChatOnce</h1>
    </header>
    <main>
        
        <div class="flex full_width container">
            <section class='box_shadow login_section'>
                <div class='content_holder'>
                    <h1>Make An Order</h1>
                    <div class="error" id="error">
                    </div>
                    <div class="success" id="success">
                    </div>
                    <div class="form_input mb20">
                        <label for="">Item name</label>
                        <input type="text" id="food-item">
                    </div>

                    <div class="form_input mb20">
                        <label for="">Number of Items</label>
                        <input type="number" id="number-of-items">
                    </div>

                    <h1>Check status</h1>
                    <div class="form_input mb20">
                        <label for="">Order ID</label>
                        <input type="number" id="order-id">
                    </div>

                    <button class="btn_normal mb20" id="login-btn">Go ahead</button>
                </div>
            </section>
        </div> 
    </main>
    <footer>
        <script src="js/classes/Utility.js"></script>
    </footer>
</body>
</html>