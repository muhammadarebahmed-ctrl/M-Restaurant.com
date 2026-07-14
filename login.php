<?php
session_start();
include "db.php";

$translations = [
    'en' => [
        'title' => 'Log in',
        'email' => 'Email',
        'password' => 'Password',
        'login' => 'Log in',
        'no_account' => "Don't have an account?",
        'register' => 'Register',
        'user_not_found' => "User not found!",
        'wrong_pass' => "Incorrect password!",
        'language' => 'Language'
    ],
    'am' => [
        'title' => 'ግባ',
        'email' => 'ኢ-ሜይል',
        'password' => 'የይለፍ ቃል',
        'login' => 'ግባ',
        'no_open' => "መለያ የለም?",
        'register' => 'ይመዝገቡ',
        'user_not_found' => "ተጠቃሚ አልተገኘም!",
        'wrong_pass' => "የይለፍ ቃል ትክክል አይደለም!",
        'language' => 'ቋንቋ'
    ],
    'ar' => [
        'title' => 'تسجيل الدخول',
        'email' => 'البريد الإلكتروني',
        'password' => 'كلمة المرور',
        'login' => 'دخول',
        'no_account' => "ليس لديك حساب؟",
        'register' => 'سجّل',
        'user_not_found' => "المستخدم غير موجود!",
        'wrong_pass' => "كلمة المرور غير صحيحة!",
        'language' => 'اللغة'
    ],
    'om' => [
        'title' => 'Seeni',
        'email' => 'I-meeli',
        'password' => 'Jecha iccitii',
        'login' => 'Seeni',
        'no_account' => "Akaawuntii hin qabdu?",
        'register' => 'Galmaa\'i',
        'user_not_found' => "Fayyadamaan hin argamne!",
        'wrong_pass' => "Jecha iccitii sirrii miti!",
        'language' => 'Odeeffannoo'
    ]
];

if (isset($_GET['lang'])) {
    $_SESSION['lang'] = $_GET['lang'];
}

$lang = $_SESSION['lang'] ?? 'en';
if (!isset($translations[$lang])) {
    $lang = 'en';
}
$text = $translations[$lang];

$error = "";

if (isset($_POST['submit'])) {
    $email = mysqli_real_escape_string($conn, $_POST['email']);
    $password = $_POST['password'];

    $sql = "SELECT * FROM users WHERE email='$email' LIMIT 1";
    $result = mysqli_query($conn, $sql);

    if ($result && mysqli_num_rows($result) === 1) {
        $row = mysqli_fetch_assoc($result);
        $passwordMatches = (strlen($row['password']) >= 60 && password_verify($password, $row['password']))
            || $row['password'] === $password;

        if ($passwordMatches) {
            $_SESSION['user_id'] = $row['id'];
            $_SESSION['user_type'] = $row['type'];

            if ($row['type'] === 'admin') {
                header("Location: admin/admin_dashboard.php");
                exit;
            } else {
                header("Location: user_dashboard.php");
                exit;
            }
        } else {
            $error = $text['wrong_pass'];
        }
    } else {
        $error = $text['user_not_found'];
    }
}
?>

<!DOCTYPE html>
<html lang="<?= $lang ?>" dir="<?= ($lang == 'ar' ? 'rtl' : 'ltr') ?>">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title><?= $text['title'] ?> - Restaurant</title>
    <link rel="preconnect" href="https://fonts.gstatic.com">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800;900&display=swap" rel="stylesheet">
    <style>
        :root {
            --primary: #0b63d6;
            --primary-dark: #083d8a;
            --accent: #2e7d32;
            --surface: #ffffff;
            --bg-overlay: rgba(255, 255, 255, 0.92);
            --text: #111827;
            --text-muted: #6b7280;
            --error-bg: #fef2f2;
            --error-text: #b91c1c;
            --shadow-xl: 0 24px 80px rgba(0, 0, 0, 0.22);
            --shadow-lg: 0 16px 50px rgba(0, 0, 0, 0.15);
            --shadow-sm: 0 8px 24px rgba(0, 0, 0, 0.1);
        }

        * {
            box-sizing: border-box;
        }

        body {
            margin: 0;
            padding: 0;
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            font-family: 'Inter', 'Segoe UI', Arial, sans-serif;
            background-image: url('image/pexels-valentin-ilas-2154050328-35210172.jpg');
            background-repeat: no-repeat;
            background-size: cover;
            background-position: center;
            background-color: #f5f7f6;
        }

        .page-overlay {
            position: fixed;
            inset: 0;
            background: var(--bg-overlay);
            z-index: -1;
        }

        .login-wrapper {
            display: flex;
            align-items: center;
            justify-content: center;
            width: 100%;
            padding: 30px 20px;
        }

        .login-card {
            background: var(--surface);
            width: 100%;
            max-width: 420px;
            padding: 48px 40px;
            border-radius: 28px;
            box-shadow: var(--shadow-xl);
            position: relative;
            overflow: hidden;
            animation: fadeInUp 0.6s ease-out;
        }

        @keyframes fadeInUp {
            from {
                opacity: 0;
                transform: translateY(20px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .login-card::before {
            content: "";
            position: absolute;
            top: -60px;
            right: -60px;
            width: 200px;
            height: 200px;
            background: radial-gradient(circle, rgba(11, 99, 214, 0.15), transparent 60%);
            pointer-events: none;
        }

        .login-card::after {
            content: "";
            position: absolute;
            bottom: -80px;
            left: -80px;
            width: 240px;
            height: 240px;
            background: radial-gradient(circle, rgba(46, 125, 50, 0.12), transparent 60%);
            pointer-events: none;
        }

        .lang-wrapper {
            position: absolute;
            top: 18px;
            right: 18px;
            z-index: 2;
        }

        [dir="rtl"] .lang-wrapper {
            right: auto;
            left: 18px;
        }

        .lang-select {
            appearance: none;
            background: var(--surface);
            border: 1px solid #e5e7eb;
            border-radius: 14px;
            padding: 10px 36px 10px 14px;
            font-size: 13px;
            font-weight: 700;
            color: var(--primary);
            cursor: pointer;
            box-shadow: var(--shadow-sm);
            transition: border-color 0.2s ease, box-shadow 0.2s ease, transform 0.15s ease;
            background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='14' height='14' viewBox='0 0 20 20'%3E%3Cpath fill='%230b63d6' d='M5 7l5 5 5-5z'/%3E%3C/svg%3E");
            background-repeat: no-repeat;
            background-position: right 12px center;
        }

        .lang-select:hover {
            border-color: var(--primary);
            box-shadow: 0 10px 28px rgba(11, 99, 214, 0.18);
            transform: translateY(-1px);
        }

        .lang-select:focus {
            outline: none;
            border-color: var(--primary);
            box-shadow: 0 10px 28px rgba(11, 99, 214, 0.22);
        }

        [dir="rtl"] .lang-select {
            padding: 10px 14px 10px 36px;
            background-position: left 12px center;
        }

        .login-title {
            text-align: center;
            margin: 0 0 32px;
            font-size: 32px;
            font-weight: 900;
            color: var(--primary-dark);
            letter-spacing: -0.5px;
            position: relative;
        }

        .login-title::after {
            content: "";
            display: block;
            width: 60px;
            height: 4px;
            background: linear-gradient(90deg, var(--primary), var(--accent));
            margin: 10px auto 0;
            border-radius: 2px;
        }

        .error {
            background: var(--error-bg);
            color: var(--error-text);
            padding: 12px 14px;
            border-radius: 14px;
            margin-bottom: 20px;
            text-align: center;
            font-size: 14px;
            border: 1px solid rgba(185, 28, 28, 0.12);
            box-shadow: 0 6px 18px rgba(185, 28, 28, 0.08);
        }

        label {
            display: block;
            font-size: 14px;
            font-weight: 700;
            color: var(--text);
            margin-top: 20px;
            margin-bottom: 6px;
        }

        input[type="email"],
        input[type="password"] {
            width: 100%;
            padding: 14px 16px;
            border-radius: 14px;
            border: 1px solid #e5e7eb;
            background: #f9fafb;
            font-size: 14px;
            color: var(--text);
            transition: border-color 0.2s ease, background 0.2s ease, box-shadow 0.2s ease;
        }

        input[type="email"]:focus,
        input[type="password"]:focus {
            outline: none;
            border-color: var(--primary);
            background: #fff;
            box-shadow: 0 8px 24px rgba(11, 99, 214, 0.12);
        }

        .password-field {
            position: relative;
            width: 100%;
        }

        .password-toggle {
            position: absolute;
            top: 50%;
            right: 12px;
            transform: translateY(-50%);
            background: transparent;
            border: none;
            padding: 6px 8px;
            cursor: pointer;
            color: var(--text-muted);
            font-size: 15px;
            display: flex;
            align-items: center;
            justify-content: center;
            transition: color 0.2s ease;
        }

        .password-toggle:hover {
            color: var(--primary);
            /* No transform here, so no zoom */
        }

        [dir="rtl"] .password-toggle {
            right: auto;
            left: 12px;
        }

        .login-button {
            width: 100%;
            padding: 14px 18px;
            margin-top: 28px;
            border: none;
            border-radius: 14px;
            background: linear-gradient(135deg, var(--primary), var(--primary-dark));
            color: #fff;
            font-size: 16px;
            font-weight: 800;
            cursor: pointer;
            transition: transform 0.15s ease, box-shadow 0.2s ease;
            box-shadow: 0 10px 28px rgba(11, 99, 214, 0.3);
        }

        .login-button:hover {
            transform: translateY(-2px);
            box-shadow: 0 14px 36px rgba(11, 99, 214, 0.35);
        }

        .register {
            text-align: center;
            margin-top: 28px;
            font-size: 14px;
            color: var(--text);
        }

        .register a {
            color: var(--primary);
            font-weight: 800;
            text-decoration: none;
            margin-left: 6px;
            transition: color 0.2s ease;
        }

        [dir="rtl"] .register a {
            margin-left: 0;
            margin-right: 6px;
        }

        .register a:hover {
            color: var(--primary-dark);
        }

        @media (max-width: 460px) {
            .login-card {
                padding: 38px 26px;
            }
            .login-title {
                font-size: 28px;
            }
            .lang-wrapper {
                position: static;
                margin-bottom: 18px;
            }
        }
    </style>
</head>
<body>

<div class="page-overlay"></div>

<div class="login-wrapper">
    <div class="login-card">
        <div class="lang-wrapper">
            <select class="lang-select" onchange="if(this.value){window.location.href='?lang='+this.value;}">
                <option value="en" <?= $lang === 'en' ? 'selected' : '' ?>>English</option>
                <option value="am" <?= $lang === 'am' ? 'selected' : '' ?>>አማርኛ</option>
                <option value="ar" <?= $lang === 'ar' ? 'selected' : '' ?>>العربية</option>
                <option value="om" <?= $lang === 'om' ? 'selected' : '' ?>>Afaan Oromo</option>
            </select>
        </div>

        <h1 class="login-title"><?= $text['title'] ?></h1>

        <?php if ($error): ?>
            <div class="error"><?= $error ?></div>
        <?php endif; ?>

        <form method="post" action="?lang=<?= $lang ?>">
            <label for="email"><?= $text['email'] ?></label>
            <input id="email" type="email" name="email" required>

            <label for="password"><?= $text['password'] ?></label>
            <div class="password-field">
                <input id="password" type="password" name="password" required>
                <button type="button" class="password-toggle" id="passwordToggle" aria-label="Toggle password visibility">
                    <span id="passwordIcon">👁</span>
                </button>
            </div>

            <button class="login-button" type="submit" name="submit">
                <?= $text['login'] ?>
            </button>
        </form>

        <div class="register">
            <?= $text['no_account'] ?>
            <a href="register.php?lang=<?= $lang ?>"><?= $text['register'] ?></a>
        </div>
    </div>
</div>

<script>
    const passwordInput = document.getElementById('password');
    const passwordToggle = document.getElementById('passwordToggle');
    const passwordIcon = document.getElementById('passwordIcon');

    passwordToggle.addEventListener('click', () => {
        const isText = passwordInput.type === 'text';
        passwordInput.type = isText ? 'password' : 'text';
        passwordIcon.textContent = isText ? '👁' : '👁️‍🗨';
    });
</script>

</body>
</html>