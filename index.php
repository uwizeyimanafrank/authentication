<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Animated Login System</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: 'Arial', sans-serif;
        }

        body {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            height: 100vh;
            display: flex;
            justify-content: center;
            align-items: center;
            overflow: hidden;
        }

        .container {
            position: relative;
            width: 400px;
            background: rgba(255, 255, 255, 0.9);
            border-radius: 10px;
            box-shadow: 0 15px 35px rgba(0, 0, 0, 0.2);
        }

        .form-container {
            padding: 40px;
            position: relative;
        }

        .form-container h2 {
            color: #333;
            font-size: 30px;
            margin-bottom: 30px;
            text-align: center;
            position: relative;
        }

        .form-container h2::after {
            content: '';
            position: absolute;
            bottom: -10px;
            left: 50%;
            transform: translateX(-50%);
            width: 50px;
            height: 3px;
            background: #764ba2;
            border-radius: 3px;
        }

        .input-group {
            position: relative;
            margin-bottom: 25px;
        }

        .input-group input {
            width: 100%;
            padding: 10px 0;
            font-size: 16px;
            color: #333;
            border: none;
            border-bottom: 1px solid #999;
            outline: none;
            background: transparent;
            transition: 0.3s;
        }

        .input-group label {
            position: absolute;
            top: 10px;
            left: 0;
            font-size: 16px;
            color: #666;
            pointer-events: none;
            transition: 0.3s;
        }

        .input-group input:focus ~ label,
        .input-group input:valid ~ label {
            top: -15px;
            left: 0;
            color: #764ba2;
            font-size: 12px;
        }

        .input-group input:focus {
            border-bottom: 1px solid #764ba2;
        }

        .btn {
            display: inline-block;
            padding: 12px 30px;
            background: #764ba2;
            color: #fff;
            border: none;
            border-radius: 30px;
            font-size: 16px;
            cursor: pointer;
            transition: all 0.3s;
            width: 100%;
            margin-top: 10px;
        }

        .btn:hover {
            background: #5d3a8a;
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.2);
        }

        .toggle-form {
            text-align: center;
            margin-top: 20px;
            color: #666;
        }

        .toggle-form a {
            color: #764ba2;
            text-decoration: none;
            font-weight: 600;
            cursor: pointer;
        }

        .toggle-form a:hover {
            text-decoration: underline;
        }
    </style>
</head>
<body>
    <div class="container">
        
        <div class="form-container" id="login-form">
            <h2>Login</h2>
            <form id="loginForm" action="login.php" method="POST">
                <div class="input-group">
                    <input type="text" name="username" id="login-username" required>
                    <label for="login-username">Username or Email</label>
                </div>
                <div class="input-group">
                    <input type="password" name="password" id="login-password" required>
                    <label for="login-password">Password</label>
                </div>
                <button type="submit" class="btn">Login</button>
            </form>
            <div class="toggle-form">
                Don't have an account? <a href="#" id="show-signup">Sign Up</a>
            </div>
        </div>

        
        <div class="form-container" id="signup-form" style="display: none;">
            <h2>Sign Up</h2>
            <form id="signupForm" action="signup.php" method="POST">
                <div class="input-group">
                    <input type="text" name="username" id="signup-username" required>
                    <label for="signup-username">Username</label>
                </div>
                <div class="input-group">
                    <input type="email" name="email" id="signup-email" required>
                    <label for="signup-email">Email</label>
                </div>
                <div class="input-group">
                    <input type="password" name="password" id="signup-password" required>
                    <label for="signup-password">Password</label>
                </div>
                <div class="input-group">
                    <input type="password" name="confirm_password" id="signup-confirm-password" required>
                    <label for="signup-confirm-password">Confirm Password</label>
                </div>
                <button type="submit" class="btn">Sign Up</button>
            </form>
            <div class="toggle-form">
                Already have an account? <a href="#" id="show-login">Login</<>
            </div>
        </div>
    </div>

    <script>
        const loginForm = document.getElementById('login-form');
        const signupForm = document.getElementById('signup-form');

        

        document.getElementById('show-signup').addEventListener('click', () => {
            loginForm.style.display = 'none';
            signupForm.style.display = 'block';
        });

        // Show Login Form from Signup
        document.getElementById('show-login').addEventListener('click', () => {
            signupForm.style.display = 'none';
            loginForm.style.display = 'block';
        });
    </script>
</body>
</html>
