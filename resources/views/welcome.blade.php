<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Loading...</title>

    <style>
        body {
            margin: 0;
            padding: 0;
            background: #f8fafc;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            font-family: Arial, sans-serif;
        }

        .loader-container {
            text-align: center;
        }

        .logo {
            width: 120px;
            animation: pulse 1.5s infinite;
        }

        @keyframes pulse {
            0% { transform: scale(1); opacity: 0.7; }
            50% { transform: scale(1.1); opacity: 1; }
            100% { transform: scale(1); opacity: 0.7; }
        }

        /* 🔥 TEXT ANIMATION */
        .text {
            margin-top: 15px;
            font-size: 16px;
            color: #555;
            opacity: 0;
            animation: fadeIn 1s ease forwards;
        }

        @keyframes fadeIn {
            to { opacity: 1; }
        }

        /* 🔥 DOTS ANIMATION */
        .dots::after {
            content: '';
            animation: dots 1.5s infinite;
        }

        @keyframes dots {
            0%   { content: ''; }
            25%  { content: '.'; }
            50%  { content: '..'; }
            75%  { content: '...'; }
            100% { content: ''; }
        }

        /* 🔥 PAGE FADE OUT */
        .fade-out {
            animation: fadeOut 0.5s ease forwards;
        }

        @keyframes fadeOut {
            to {
                opacity: 0;
                transform: scale(1.05);
            }
        }
    </style>
</head>
<body>

<div class="loader-container" id="loader">
    <img src="{{ asset('img/logo.png') }}" class="logo" alt="Logo">
    <div class="text">
        Loading<span class="dots"></span>
    </div>
</div>

<script>
    setTimeout(function () {
        document.getElementById('loader').classList.add('fade-out');

        setTimeout(function () {
            window.location.href = "{{ route('login') }}";
        }, 500);

    }, 2500);
</script>

</body>
</html>