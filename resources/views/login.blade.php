<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Form Login</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }
        
        body {
            background: linear-gradient(135deg, #6a11cb 0%, #2575fc 100%);
            display: flex;
            justify-content: center;
            align-items: center;
            min-height: 100vh;
            padding: 20px;
        }
        
        .login-container {
            background-color: white;
            border-radius: 15px;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.2);
            width: 100%;
            max-width: 450px;
            padding: 40px;
            transition: transform 0.3s ease;
        }
        
        .login-container:hover {
            transform: translateY(-5px);
        }
        
        .login-header {
            text-align: center;
            margin-bottom: 30px;
        }
        
        .login-header h2 {
            color: #333;
            font-size: 28px;
            margin-bottom: 10px;
        }
        
        .login-header p {
            color: #666;
            font-size: 16px;
        }
        
        .form-group {
            margin-bottom: 20px;
        }
        
        .form-control-label {
            display: block;
            margin-bottom: 8px;
            font-weight: 600;
            color: #444;
        }
        
        .form-control {
            width: 100%;
            padding: 12px 15px;
            border: 2px solid #e1e5ee;
            border-radius: 8px;
            font-size: 16px;
            transition: all 0.3s;
        }
        
        .form-control:focus {
            outline: none;
            border-color: #4d90fe;
            box-shadow: 0 0 0 3px rgba(77, 144, 254, 0.2);
        }
        
        .form-control::placeholder {
            color: #aaa;
        }
        
        .error-message {
            color: crimson;
            font-size: 14px;
            margin-top: 5px;
            display: block;
            min-height: 20px;
        }
        
        .btn {
            background: linear-gradient(to right, #6a11cb, #2575fc);
            color: white;
            border: none;
            border-radius: 8px;
            padding: 14px;
            font-size: 16px;
            font-weight: 600;
            cursor: pointer;
            width: 100%;
            transition: all 0.3s;
        }
        
        .btn:hover {
            background: linear-gradient(to right, #5a0db9, #1c6ae4);
            box-shadow: 0 5px 15px rgba(38, 117, 252, 0.4);
        }
        
        .btn:active {
            transform: translateY(2px);
        }
        
        .login-footer {
            text-align: center;
            margin-top: 20px;
            color: #666;
            font-size: 14px;
        }
        
        .login-footer a {
            color: #2575fc;
            text-decoration: none;
        }
        
        .login-footer a:hover {
            text-decoration: underline;
        }
        
        @media (max-width: 480px) {
            .login-container {
                padding: 30px 20px;
            }
            
            .login-header h2 {
                font-size: 24px;
            }
        }
    </style>
</head>
<body>
    <div class="login-container">
        <div class="login-header">
            <h2>Selamat Datang</h2>
            <p>Silakan masuk ke akun Anda</p>
        </div>
        
        <form action="{{ route('login_proses') }}" method="post">
            @csrf
            <div class="form-group">
                <label class="form-control-label">Username : </label>
                <input type="text" name="username" class="form-control" placeholder="Enter your username">
                <div>
                    @error('username')
                    <span class="error-message">{{ $message }}</span>
                    @enderror
                </div>
            </div>

            <div class="form-group">
                <label class="form-control-label">Password :</label>
                <input type="password" name="password" class="form-control" placeholder="Enter your password">
                <div>
                    @error('password')
                    <span class="error-message">{{ $message }}</span>
                    @enderror
                </div>
            </div>
            <br />
            <button type="submit" class="btn">Login</button>
        </form>
        
        <div class="login-footer">
            <p>Lupa password? <a href="#">Klik di sini</a></p>
        </div>
    </div>
</body>
</html>