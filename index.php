<?php
session_start();
include "db.php";


/* ---------- LANGUAGE ---------- */
$lang = $_GET['lang'] ?? 'en';


$T = [
    "en"=>[
        "restaurant"=>"M-RESTAURANT",
        "order_header"=>"Order Your Favorite Item",
        "order_subheader"=>"Authentic Ethiopian Flavors Delivered to You",
        "login"=>"Login",
        "logout"=>"Logout",
        "register"=>"Register",
        "admin_dashboard"=>"Admin Dashboard",
        "user_dashboard"=>"User Dashboard",
        "order_now"=>"Order Now",
        "login_to_order"=>"Login to Order",
    ],
    "am"=>[
        "restaurant"=>"M-ረስቶራንት",
        "order_header"=>"እባክዎ የወደዱትን እቃ መርጦ ይዘዙ!",
        "order_subheader"=>"እውነተኛ የኢትዮጵያ ጣዕም ወደ እርስዎ ይደርሳል",
        "login"=>"ግባ",
        "logout"=>"ውጣ",
        "register"=>"ይመዝገቡ",
        "admin_dashboard"=>"የአስተዳዳሪ ፓነል",
        "user_dashboard"=>"የተጠቃሚ ፓነል",
        "order_now"=>"አሁን ትዕዛዝ ይስጡ",
        "login_to_order"=>"ትዕዛዝ ለማድረግ ግባ",
    ],
    "om"=>[
        "restaurant"=>"M-RESTAURANT",
        "order_header"=>"Dhangalee Ethiopia Jaalatamoo fi Filatamoo ",
        "order_subheader"=>"Filadhaatii ajajadhaa yeroo gababa keessaa isiniif Ergama",
        "login"=>"Seeni",
        "logout"=>"Ba'i",
        "register"=>"Galmaa'i",
        "admin_dashboard"=>"Paanelii Bulchaa",
        "user_dashboard"=>"Paanelii Fayyadamaa",
        "order_now"=>"Amma Ajaji",
        "login_to_order"=>"Ajajaaf Seeni",
    ],
    "ar"=>[
        "restaurant"=>"M-ريستورانت",
        "order_header"=>"اطلب وجبتك المفضلة",
        "order_subheader"=>"نكهات إثيوبية أصلية تصل إليك",
        "login"=>"تسجيل الدخول",
        "logout"=>"تسجيل الخروج",
        "register"=>"تسجيل",
        "admin_dashboard"=>"لوحة الإدارة",
        "user_dashboard"=>"لوحة المستخدم",
        "order_now"=>"اطلب الآن",
        "login_to_order"=>"تسجيل الدخول للطلب",
    ]
];


if(!isset($T[$lang])) $lang="en";


function t($key){ global $T, $lang; return $T[$lang][$key]; }


$sql = "SELECT * FROM menu_items ORDER BY id DESC";
$result = mysqli_query($conn, $sql);
if (!$result) die("Database error: " . mysqli_error($conn));


$successMessage = "";
if (isset($_GET['added_message'])) {
    $successMessage = $_GET['added_message'];
}
?>
<!DOCTYPE html>
<html lang="<?= $lang ?>" dir="<?= ($lang=='ar')?'rtl':'ltr' ?>">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= t("restaurant") ?> | Home</title>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.6.0/css/all.min.css">

    <style>
        :root {
            --primary: #2e7d32;
            --primary-dark: #1b5e20;
            --accent: #81c784;
            --accent-glow: #a5d6a7;
            --dark: #1c1c1c;
            --light: #f9fafb;
            --white: #ffffff;
            --text: #333333;
            --text-muted: #666666;
            --danger: #d9534f;
            --danger-dark: #c93437;
            --blue: #00008b;
            --blue-light: #0000cd;
            --shadow-soft: 0 10px 30px rgba(0,0,0,0.12);
            --shadow-strong: 0 25px 70px rgba(0,0,0,0.35);
            --radius: 16px;
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Poppins', sans-serif;
            background:
                linear-gradient(135deg, rgba(14, 38, 26, 0.85), rgba(46, 105, 66, 0.8)),
                url('image/pexels-solliefoto-299348.jpg');
            background-size: cover;
            background-position: center;
            background-attachment: fixed;
            color: var(--text);
            min-height: 100vh;
        }

        /* Navbar */
        .navbar {
            display: flex;
            padding: 15px 40px;
            background: rgba(20, 45, 32, 0.95);
            justify-content: space-between;
            align-items: center;
            position: sticky;
            top: 0;
            z-index: 1000;
            box-shadow: 0 10px 30px rgba(0,0,0,0.5);
            border-bottom: 1px solid rgba(46, 204, 113, 0.2);
        }

        .brand {
            font-weight: 800;
            font-size: 1.8rem;
            color: aqua;
            text-transform: uppercase;
            letter-spacing: 1px;
            display: inline-flex;
            align-items: center;
            gap: 10px;
        }

        .brand i {
            color: var(--accent);
        }

        .nav-links {
            list-style: none;
            display: flex;
            gap: 12px;
            align-items: center;
        }

        .nav-links a {
            text-decoration: none;
            padding: 10px 14px;
            font-size: 0.95rem;
            color: var(--white);
            font-weight: 600;
            transition: all 0.3s ease;
            border-radius: 10px;
        }

        .nav-links a:hover {
            background: rgba(46, 125, 50, 0.3);
            color: var(--accent-glow);
        }

        .lang-links {
            display: flex;
            gap: 6px;
            align-items: center;
        }

        .lang-links a {
            text-decoration: none;
            color: var(--white);
            font-weight: 600;
            padding: 6px 10px;
            border-radius: 8px;
            transition: all 0.25s ease;
        }

        .lang-links a:hover {
            background: rgba(46, 204, 113, 0.25);
            color: var(--accent-glow);
        }

        /* Header */
        .header {
            background: rgba(20, 57, 143, 0.55);
            padding: 80px 40px;
            text-align: center;
            color: var(--white);
            position: relative;
            overflow: hidden;
        }

        .header::before {
            content: "";
            position: absolute;
            top: -60px;
            left: 0;
            width: 100%;
            height: 200px;
            background: radial-gradient(circle, rgba(46, 204, 113, 0.15), transparent 70%);
            filter: blur(40px);
            z-index: 0;
        }

        .header h1 {
            font-size: 3.2rem;
            margin: 0;
            font-weight: 800;
            text-shadow: 2px 2px 8px rgba(0,0,0,0.6);
            position: relative;
            z-index: 1;
        }

        .header p {
            font-size: 1.25rem;
            margin-top: 12px;
            color: #e8f5e9;
            position: relative;
            z-index: 1;
        }

        /* Success message */
        .message {
            text-align: center;
            background: #d4edda;
            color: #155724;
            padding: 16px 20px;
            border-radius: 12px;
            margin: 25px auto;
            width: 90%;
            border: 1px solid #c3e6cb;
            font-weight: 600;
            box-shadow: var(--shadow-soft);
        }

        /* Product grid */
        .product_card {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(260px, 1fr));
            gap: 25px;
            padding: 40px;
            justify-content: center;
        }

        /* Card */
        .card {
            background: var(--white);
            padding: 15px;
            border-radius: var(--radius);
            text-align: center;
            transition: all 0.35s ease;
            box-shadow: var(--shadow-soft);
            border-top: 4px solid var(--primary);
            position: relative;
            overflow: hidden;
        }

        .card::before {
            content: "";
            position: absolute;
            top: -40px;
            right: -40px;
            width: 120px;
            height: 120px;
            background: radial-gradient(circle, rgba(129, 199, 132, 0.15), transparent 70%);
            filter: blur(20px);
            z-index: 0;
        }

        .card > * {
            position: relative;
            z-index: 1;
        }

        .card:hover {
            transform: translateY(-8px);
            box-shadow: 0 18px 45px rgba(0,0,0,0.18);
        }

        .card img {
            width: 100%;
            height: 200px;
            object-fit: cover;
            border-radius: 12px;
            box-shadow: 0 8px 20px rgba(0,0,0,0.15);
        }

        .card h3 {
            color: var(--text);
            margin: 15px 0 5px;
            font-size: 1.4rem;
            font-weight: 700;
        }

        .card p {
            color: var(--primary);
            font-size: 1.2rem;
            font-weight: 700;
            margin-bottom: 15px;
        }

        .card a {
            display: inline-block;
            background: var(--blue);
            color: var(--white);
            padding: 12px 25px;
            border-radius: 10px;
            text-decoration: none;
            font-weight: 700;
            transition: all 0.3s ease;
            width: 90%;
            box-shadow: 0 8px 20px rgba(0,0,139, 0.3);
        }

        .card a:hover {
            background: var(--blue-light);
            transform: scale(1.05);
            box-shadow: 0 12px 28px rgba(0,0,139, 0.45);
        }

        .card a.login-btn {
            background: var(--danger);
            box-shadow: 0 8px 20px rgba(217, 83, 79, 0.3);
        }

        .card a.login-btn:hover {
            background: var(--danger-dark);
            box-shadow: 0 12px 28px rgba(217, 83, 79, 0.45);
        }

        /* Mobile */
        @media (max-width: 900px) {
            .navbar {
                flex-direction: column;
                gap: 10px;
                padding: 12px 20px;
            }

            .brand {
                font-size: 1.5rem;
            }

            .nav-links {
                flex-wrap: wrap;
                justify-content: center;
            }

            .product_card {
                padding: 20px;
            }
        }
    </style>
</head>

<body>

<nav class="navbar">
    <a href="index.php?lang=<?= $lang ?>" class="brand">
        <i class="fa-solid fa-utensils"></i> <?= t("restaurant") ?>
    </a>

    <ul class="nav-links">
        <?php if (isset($_SESSION['user_id'])): ?>
            <?php if ($_SESSION['user_type'] == 'admin'): ?>
                <li><a href="admin/admin_dashboard.php?lang=<?= $lang ?>"><?= t("admin_dashboard") ?></a></li>
            <?php else: ?>
                <li><a href="user_dashboard.php?lang=<?= $lang ?>"><?= t("user_dashboard") ?></a></li>
            <?php endif; ?>
            <li><a href="logout.php"><?= t("logout") ?></a></li>
        <?php else: ?>
            <li><a href="login.php"><?= t("login") ?></a></li>
            <li><a href="register.php"><?= t("register") ?></a></li>
        <?php endif; ?>

        <li class="lang-links">
            <a href="?lang=en">EN</a> |
            <a href="?lang=am">AM</a> |
            <a href="?lang=om">OR</a> |
            <a href="?lang=ar">AR</a>
        </li>
    </ul>
</nav>

<?php if ($successMessage): ?>
    <div class="message"><?= htmlspecialchars($successMessage) ?></div>
<?php endif; ?>

<header class="header">
    <h1><?= t("order_header") ?></h1>
    <p><?= t("order_subheader") ?></p>
</header>

<section class="product_card">
    <?php while ($row = mysqli_fetch_assoc($result)): ?>
        <div class="card">
            <img src="image/<?= htmlspecialchars($row['image']) ?>" alt="<?= htmlspecialchars($row['name']) ?>">
            <h3><?= htmlspecialchars($row['name']) ?></h3>
            <p><?= number_format($row['price'], 2) ?> Birr</p>

            <?php if (isset($_SESSION['user_id'])): ?>
                <a href="orders_items.php?menu_id=<?= $row['id'] ?>&lang=<?= $lang ?>">
                    <?= t("order_now") ?>
                </a>
            <?php else: ?>
                <a href="login.php" class="login-btn">
                    <?= t("login_to_order") ?>
                </a>
            <?php endif; ?>
        </div>
    <?php endwhile; ?>
</section>

</body>
</html>