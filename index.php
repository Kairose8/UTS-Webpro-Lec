<!DOCTYPE html>
<html>
    <head>
        <title>Homepage</title>
        <link rel="stylesheet" href="style.css">
    </head>

    <body>
        <button class="open-button" onclick="openForm">Login</button>

        <div class="form-popup" id="login">
            <form action="/login.php" class="form-container">
                <h2>Login</h2>

                <?php if(isset($_GET['error'])) { ?>
                    <p class="error"> <?php echo $_GET['error']; ?></p>
                <?php } ?>
                <label for="email"><b>Username</b></label>
                <input type="text" name="email" placeholder="Enter Email" required><br>
                <label for="password"><b>Password</b></label>
                <input type="password" name="password" placeholder="Enter Password" required><br>

                <button type="submit" class="btn">Login</button>
                <button type="button" class="btn cancel" onclick="closeForm()">Close</button>
            </form>
        </div>

        <script>
            function openForm() {
            document.getElementById("login").style.display = "block";
            }

            function closeForm() {
            document.getElementById("login").style.display = "none";
            }
        </script>
    </body>
</html>