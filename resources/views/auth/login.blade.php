<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - Tonny Cloth Store</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link rel="icon" type="image/x-icon" href="{{ asset('logo.jpg') }}">
    <style>
        body {
            font-family: "Inter", sans-serif;
            background: #fdfbf7;
            margin: 0;
            padding: 0;
            color: #2e3e52;
            display: flex;
            align-items: center;
            justify-content: center;
            min-height: 100vh;
            font-size: 16px;
        }

        .login-container {
            width: 100%;
            max-width: 420px;
            padding: 20px;
            box-sizing: border-box;
        }

        .login-card {
            background: white;
            border-radius: 16px;
            box-shadow: 0 10px 25px -5px rgba(18, 43, 73, 0.08), 0 8px 16px -6px rgba(18, 43, 73, 0.04);
            border: 1px solid #e5e0d4;
            padding: 40px 32px;
            text-align: center;
        }

        .logo-group {
            margin-bottom: 24px;
        }

        .logo-img {
            height: 72px;
            border-radius: 12px;
            border: 1px solid #e5e0d4;
            margin-bottom: 12px;
        }

        .login-card h2 {
            margin: 0;
            color: #122b49;
            font-size: 26px;
            font-weight: 700;
            letter-spacing: -0.02em;
        }

        .login-card p.subtitle {
            margin: 6px 0 0 0;
            color: #ad915a;
            font-size: 15px;
            font-weight: 500;
        }

        /* Forms */
        .form-group {
            text-align: left;
            margin-bottom: 20px;
        }

        .form-group label {
            display: block;
            margin-bottom: 6px;
            font-size: 14px;
            font-weight: 600;
            color: #2e3e52;
        }

        .input-wrapper {
            position: relative;
        }

        .input-icon {
            position: absolute;
            left: 14px;
            top: 50%;
            transform: translateY(-50%);
            color: #ad915a;
            width: 16px;
            height: 16px;
            pointer-events: none;
        }

        input[type="email"],
        input[type="password"] {
            width: 100%;
            box-sizing: border-box;
            font-family: "Inter", sans-serif;
            padding: 12px 14px 12px 42px;
            border: 1px solid #dcd7ca;
            border-radius: 8px;
            font-size: 15px;
            outline: none;
            transition: all 0.15s ease-in-out;
            background: #faf8f3;
        }

        input[type="email"]:focus,
        input[type="password"]:focus {
            border-color: #bfa36b;
            box-shadow: 0 0 0 3px rgba(191, 163, 107, 0.15);
            background: white;
        }

        .remember-forgot {
            display: flex;
            align-items: center;
            justify-content: space-between;
            margin-bottom: 24px;
            font-size: 14px;
        }

        .remember-me {
            display: flex;
            align-items: center;
            gap: 6px;
            cursor: pointer;
            user-select: none;
            color: #5c503b;
        }

        .remember-me input {
            accent-color: #122b49;
            cursor: pointer;
        }

        .btn-primary {
            width: 100%;
            font-family: "Inter", sans-serif;
            font-weight: 600;
            padding: 12px 18px;
            border: none;
            border-radius: 8px;
            font-size: 15px;
            cursor: pointer;
            transition: all 0.15s ease-in-out;
            background-color: #122b49;
            color: white;
            box-shadow: 0 4px 6px rgba(18, 43, 73, 0.1);
        }

        .btn-primary:hover {
            background-color: #0b1a2d;
            box-shadow: 0 4px 12px rgba(18, 43, 73, 0.15);
        }

        .register-link {
            margin-top: 24px;
            font-size: 14px;
            color: #5c503b;
        }

        .register-link a {
            color: #122b49;
            text-decoration: none;
            font-weight: 600;
        }

        .register-link a:hover {
            text-decoration: underline;
        }

        /* Error/Toast display */
        .alert-error {
            background-color: #fee2e2;
            border: 1px solid #fecaca;
            color: #dc2626;
            border-radius: 8px;
            padding: 12px 14px;
            margin-bottom: 20px;
            font-size: 14px;
            display: flex;
            align-items: center;
            gap: 8px;
            text-align: left;
        }
    </style>
</head>
<body>

<div class="login-container">
    <div class="login-card">
        <div class="logo-group">
            <img src="{{ asset('logo.jpg') }}" alt="Tonny Cloth Store Logo" class="logo-img">
            <h2>Tonny Cloth Store</h2>
            <p class="subtitle">Inventory Dashboard Login</p>
        </div>

        @if($errors->any())
            <div class="alert-error">
                <svg style="width: 20px; height: 20px; flex-shrink: 0;" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                </svg>
                <span>{{ $errors->first() }}</span>
            </div>
        @endif

        <form method="POST" action="/login">
            @csrf

            <div class="form-group">
                <label for="email">Email Address</label>
                <div class="input-wrapper">
                    <svg class="input-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 12a4 4 0 10-8 0 4 4 0 008 0zm0 0v1.5a2.5 2.5 0 005 0V12a9 9 0 10-9 9m4.5-1.206a8.959 8.959 0 01-4.5 1.206"></path>
                    </svg>
                    <input type="email" name="email" id="email" value="{{ old('email') }}" placeholder="admin@example.com" required autofocus>
                </div>
            </div>

            <div class="form-group">
                <label for="password">Password</label>
                <div class="input-wrapper">
                    <svg class="input-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"></path>
                    </svg>
                    <input type="password" name="password" id="password" placeholder="••••••••" required>
                </div>
            </div>

            <div class="remember-forgot">
                <label class="remember-me">
                    <input type="checkbox" name="remember" id="remember">
                    <span>Remember me</span>
                </label>
            </div>

            <button type="submit" class="btn-primary">Sign In</button>
        </form>

    </div>
</div>

</body>
</html>
