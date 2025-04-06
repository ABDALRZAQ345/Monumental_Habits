<!DOCTYPE html>
<html lang="en" dir="ltr">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Habitly - Modern Habit Tracker</title>
    <meta name="description" content="Build new habits and achieve your goals with Habitly's extraordinary, modern interface." />

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">

    <style>
        :root {
            --primary-orange: #FF8C00;
            --primary-orange-hover: #E67A00;
            --background-light: #fff7e6;
            --text-light: #333;
            --background-dark: #1e1e1e;
            --text-dark: #ddd;
            --header-height: 80px;
            --border-light: rgba(0, 0, 0, 0.1);
            --border-dark: rgba(255, 255, 255, 0.1);
            --card-light: #fff;
            --card-dark: #2c2c2c;
            --shadow-light: 0 4px 12px rgba(0, 0, 0, 0.05);
            --shadow-dark: 0 4px 12px rgba(0, 0, 0, 0.2);
            --transition: all 0.3s ease;
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: 'Inter', sans-serif;
        }

        body {
            background-color: var(--background-light);
            color: var(--text-light);
            transition: var(--transition);
            padding-top: var(--header-height);
            line-height: 1.6;
        }

        body.dark {
            background-color: var(--background-dark);
            color: var(--text-dark);
        }

        /* Header Styles */
        header {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: var(--header-height);
            background: inherit;
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 0 2rem;
            z-index: 1000;
            transition: var(--transition);
            border-bottom: 1px solid var(--border-light);
        }

        body.dark header {
            border-bottom: 1px solid var(--border-dark);
        }

        header.scrolled {
            background: rgba(255, 247, 230, 0.8);
            backdrop-filter: blur(8px);
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
        }

        body.dark header.scrolled {
            background: rgba(30, 30, 30, 0.8);
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.3);
        }

        .logo {
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }

        .logo-icon {
            width: 40px;
            height: 40px;
            background: linear-gradient(to right, #FF8C00, #FFA500);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-weight: bold;
            font-size: 1.5rem;
        }

        .logo-text {
            font-weight: 600;
            font-size: 1.25rem;
        }

        .nav-links {
            display: flex;
            gap: 2rem;
        }

        .nav-links a {
            text-decoration: none;
            color: inherit;
            font-weight: 500;
            transition: var(--transition);
            position: relative;
        }

        .nav-links a:after {
            content: '';
            position: absolute;
            width: 0;
            height: 2px;
            bottom: -5px;
            left: 0;
            background-color: var(--primary-orange);
            transition: var(--transition);
        }

        .nav-links a:hover:after {
            width: 100%;
        }

        .nav-links a:hover {
            color: var(--primary-orange);
        }

        .toggle-container {
            display: flex;
            align-items: center;
            gap: 1rem;
        }

        .toggle-button {
            background: transparent;
            border: 1px solid currentColor;
            color: inherit;
            padding: 0.5rem 1rem;
            border-radius: 50px;
            cursor: pointer;
            transition: var(--transition);
            font-size: 0.9rem;
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }

        .toggle-button:hover {
            background: rgba(0, 0, 0, 0.05);
        }

        body.dark .toggle-button:hover {
            background: rgba(255, 255, 255, 0.05);
        }

        /* Hero Section */
        .hero {
            min-height: calc(100vh - var(--header-height));
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            text-align: center;
            padding: 4rem 2rem;
        }

        .hero-logo {
            width: 120px;
            height: 120px;
            background: linear-gradient(135deg, #FF8C00, #FFA500);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-weight: bold;
            font-size: 3rem;
            margin-bottom: 2rem;
            box-shadow: 0 8px 32px rgba(255, 140, 0, 0.3);
            animation: pulse 2s infinite;
        }

        @keyframes pulse {
            0% { transform: scale(1); }
            50% { transform: scale(1.05); }
            100% { transform: scale(1); }
        }

        .hero h1 {
            font-size: 3.5rem;
            font-weight: 700;
            margin-bottom: 1.5rem;
            background: linear-gradient(90deg, #FF8C00, #FFA500);
            -webkit-background-clip: text;
            background-clip: text;
            color: transparent;
        }

        .hero p {
            font-size: 1.25rem;
            max-width: 700px;
            margin: 0 auto 3rem;
            opacity: 0.9;
        }

        .cta-button {
            display: inline-block;
            background: linear-gradient(90deg, #FF8C00, #FFA500);
            color: white;
            text-decoration: none;
            padding: 1rem 2rem;
            border-radius: 50px;
            font-weight: 600;
            font-size: 1.1rem;
            transition: var(--transition);
            box-shadow: 0 4px 20px rgba(255, 140, 0, 0.3);
        }

        .cta-button:hover {
            transform: translateY(-3px);
            box-shadow: 0 6px 24px rgba(255, 140, 0, 0.4);
        }

        /* Features Section */
        .features {
            padding: 6rem 2rem;
            max-width: 1200px;
            margin: 0 auto;
        }

        .section-title {
            text-align: center;
            font-size: 2.5rem;
            margin-bottom: 4rem;
            position: relative;
        }

        .section-title:after {
            content: '';
            position: absolute;
            width: 100px;
            height: 4px;
            background: linear-gradient(90deg, #FF8C00, #FFA500);
            bottom: -15px;
            left: 50%;
            transform: translateX(-50%);
            border-radius: 2px;
        }

        .features-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
            gap: 2rem;
        }

        .feature-card {
            background: var(--card-light);
            border-radius: 12px;
            padding: 2rem;
            transition: var(--transition);
            box-shadow: var(--shadow-light);
            display: flex;
            flex-direction: column;
            height: 100%;
        }

        body.dark .feature-card {
            background: var(--card-dark);
            box-shadow: var(--shadow-dark);
        }

        .feature-card:hover {
            transform: translateY(-10px);
        }

        .feature-icon {
            width: 60px;
            height: 60px;
            background: linear-gradient(135deg, rgba(255, 140, 0, 0.1), rgba(255, 165, 0, 0.1));
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            margin-bottom: 1.5rem;
            color: #FF8C00;
            font-size: 1.5rem;
        }

        .feature-title {
            font-size: 1.5rem;
            margin-bottom: 1rem;
            font-weight: 600;
        }

        .feature-desc {
            opacity: 0.8;
            flex-grow: 1;
        }

        /* Footer */
        footer {
            background: rgba(0, 0, 0, 0.02);
            padding: 4rem 2rem;
            border-top: 1px solid var(--border-light);
        }

        body.dark footer {
            background: rgba(255, 255, 255, 0.02);
            border-top: 1px solid var(--border-dark);
        }

        .footer-content {
            max-width: 1200px;
            margin: 0 auto;
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            gap: 3rem;
        }

        .footer-column h3 {
            font-size: 1.2rem;
            margin-bottom: 1.5rem;
            position: relative;
            padding-bottom: 0.5rem;
        }

        .footer-column h3:after {
            content: '';
            position: absolute;
            width: 40px;
            height: 3px;
            background: var(--primary-orange);
            bottom: 0;
            left: 0;
        }

        html[dir="rtl"] .footer-column h3:after {
            left: auto;
            right: 0;
        }

        .social-links {
            display: flex;
            gap: 1rem;
            margin-top: 1rem;
        }

        .social-links a {
            color: inherit;
            text-decoration: none;
            width: 40px;
            height: 40px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            transition: var(--transition);
            background: rgba(0, 0, 0, 0.05);
        }

        body.dark .social-links a {
            background: rgba(255, 255, 255, 0.05);
        }

        .social-links a:hover {
            background: var(--primary-orange);
            color: white;
            transform: translateY(-3px);
        }

        .footer-links a {
            display: block;
            color: inherit;
            text-decoration: none;
            margin-bottom: 0.8rem;
            opacity: 0.8;
            transition: var(--transition);
        }

        .footer-links a:hover {
            opacity: 1;
            color: var(--primary-orange);
            transform: translateX(5px);
        }

        html[dir="rtl"] .footer-links a:hover {
            transform: translateX(-5px);
        }

        .developer-info p {
            margin-bottom: 0.5rem;
            opacity: 0.8;
        }

        .copyright {
            text-align: center;
            padding-top: 3rem;
            opacity: 0.6;
            font-size: 0.9rem;
        }

        /* RTL Support */
        html[dir="rtl"] .nav-links a:after {
            left: auto;
            right: 0;
        }

        html[dir="rtl"] .feature-card,
        html[dir="rtl"] .footer-column {
            text-align: right;
        }

        /* Responsive */
        .menu-toggle {
            display: none;
            flex-direction: column;
            gap: 6px;
            cursor: pointer;
            z-index: 1001;
        }

        .menu-toggle span {
            width: 30px;
            height: 3px;
            background: currentColor;
            border-radius: 3px;
            transition: var(--transition);
        }

        @media (max-width: 768px) {
            .menu-toggle {
                display: flex;
            }

            .nav-links {
                position: fixed;
                top: 0;
                right: -100%;
                width: 70%;
                height: 100vh;
                flex-direction: column;
                background: var(--background-light);
                padding: 100px 2rem 2rem;
                transition: var(--transition);
                align-items: flex-start;
                box-shadow: -10px 0 30px rgba(0, 0, 0, 0.1);
            }

            body.dark .nav-links {
                background: var(--background-dark);
                box-shadow: -10px 0 30px rgba(0, 0, 0, 0.3);
            }

            .nav-links.active {
                right: 0;
            }

            html[dir="rtl"] .nav-links {
                right: auto;
                left: -100%;
            }

            html[dir="rtl"] .nav-links.active {
                left: 0;
            }

            .toggle-container {
                margin-right: 60px;
            }

            html[dir="rtl"] .toggle-container {
                margin-right: 0;
                margin-left: 60px;
            }

            .hero h1 {
                font-size: 2.5rem;
            }

            .hero p {
                font-size: 1.1rem;
            }

            .section-title {
                font-size: 2rem;
            }

            .features {
                padding: 4rem 1.5rem;
            }

            .footer-content {
                grid-template-columns: 1fr;
                gap: 2rem;
            }
        }

        /* Animations */
        .fade-in {
            opacity: 0;
            transform: translateY(20px);
            transition: opacity 0.6s ease, transform 0.6s ease;
        }

        .fade-in.visible {
            opacity: 1;
            transform: translateY(0);
        }

        .fade-in-delay-1 { transition-delay: 0.1s; }
        .fade-in-delay-2 { transition-delay: 0.2s; }
        .fade-in-delay-3 { transition-delay: 0.3s; }
        .fade-in-delay-4 { transition-delay: 0.4s; }
        .fade-in-delay-5 { transition-delay: 0.5s; }
        .fade-in-delay-6 { transition-delay: 0.6s; }
    </style>
</head>
<body id="top">
<header id="header">
    <div class="logo">
        <img src="{{ config('app.url').'/images/logo.png' }}" alt="Habitly Logo" class="logo-text" style="width: 50px; height: auto;" />
        <div class="logo-text" id="appName">Habitly</div>
    </div>

    <div class="menu-toggle" id="menu-toggle">
        <span></span>
        <span></span>
        <span></span>
    </div>

    <nav class="nav-links" id="nav-links">
        <a href="#top" id="navHome">Home</a>
        <a href="#features" id="navFeatures">Features</a>
        <a href="#download" id="navDownloads">Download</a>
        <a href="#contact" id="navContact">Contact</a>
    </nav>

    <div class="toggle-container">
        <button class="toggle-button" id="theme-toggle">
            <i class="theme-icon">🌓</i>
        </button>
        <button class="toggle-button" id="lang-toggle">
            العربية
        </button>
    </div>
</header>

<section class="hero" id="hero">
    <img src="{{ config("app.url") .'/images/logo.png' }}" alt="Habitly Logo" class="hero-logo fade-in" />
    <h1 class="fade-in fade-in-delay-1" id="heroTitle">Habitly</h1>
    <p class="fade-in fade-in-delay-2" id="heroDescription">
        Build new habits and achieve your goals with an extraordinary, modern interfaces that inspires you to excel.
    </p>
    <img src="{{ config("app.url") .'/images/pic1.png' }}" alt="screens" class="fade-in fade-in-delay-3" id="downloadBtn" style="cursor: pointer; width: 950px; max-width: 100%; height: auto;">
</section>

<section class="features" id="features">
    <h2 class="section-title fade-in" id="featuresTitle">Features</h2>
    <div class="features-grid">
        <!-- Feature 1 -->
        <div class="feature-card fade-in fade-in-delay-1">
            <div class="feature-icon">📊</div>
            <h3 class="feature-title" id="feature1Title">Track & Monitor</h3>
            <p class="feature-desc" id="feature1Description">
                Keep a close eye on your daily routines with precise tracking and analytics.
            </p>
        </div>

        <!-- Feature 2 -->
        <div class="feature-card fade-in fade-in-delay-2">
            <div class="feature-icon">🗺️</div>
            <h3 class="feature-title" id="feature2Title">Roadmaps</h3>
            <p class="feature-desc" id="feature2Description">
                Plan your progress with detailed roadmaps and set clear milestones.
            </p>
        </div>

        <!-- Feature 3 -->
        <div class="feature-card fade-in fade-in-delay-3">
            <div class="feature-icon">💬</div>
            <h3 class="feature-title" id="feature3Title">Real-time Chat</h3>
            <p class="feature-desc" id="feature3Description">
                Connect instantly with friends and share your progress live.
            </p>
        </div>

        <!-- Feature 4 -->
        <div class="feature-card fade-in fade-in-delay-4">
            <div class="feature-icon">🏆</div>
            <h3 class="feature-title" id="feature4Title">Rewards & Achievements</h3>
            <p class="feature-desc" id="feature4Description">
                Earn rewards and celebrate every milestone as you conquer your goals.
            </p>
        </div>

        <!-- Feature 5 -->
        <div class="feature-card fade-in fade-in-delay-5">
            <div class="feature-icon">👥</div>
            <h3 class="feature-title" id="feature5Title">Shared Habits & Competition</h3>
            <p class="feature-desc" id="feature5Description">
                Compete with friends by sharing your habits and achievements to boost your motivation.
            </p>
        </div>

        <!-- Feature 6 -->
        <div class="feature-card fade-in fade-in-delay-6">
            <div class="feature-icon">⭐</div>
            <h3 class="feature-title" id="feature6Title">And Much More!</h3>
            <p class="feature-desc" id="feature6Description">
                Discover additional features like streaks and more that will drive you to excel.
            </p>
        </div>
    </div>
</section>

<section class="download-section" id="download">
    <div class="features">
        <h2 class="section-title fade-in" id="downloadTitle">Download Now</h2>
        <div class="download-container fade-in fade-in-delay-1" style="text-align: center; max-width: 600px; margin: 0 auto;">
            <p style="margin-bottom: 2rem;" id="downloadDescription">
                Get started with Habitly today and transform your habits into achievements.
            </p>
            <a href="#" class="cta-button" id="androidDownloadBtn">Download for Android</a>
        </div>
    </div>
</section>

<footer id="contact">
    <div class="footer-content">
        <div class="footer-column">
            <h3 id="footerAboutTitle">About Habitly</h3>
            <p id="footerAboutText">
                Habitly is a modern habit tracking app designed to help you build positive routines and achieve your goals.
            </p>
            <div class="social-links">
                <a href="https://github.com/ABDALRZAQ345/Habit-Tracker" aria-label="Github"><i class="backend_github">💻</i></a>
                <a href="https://github.com/Dada6x/Habitly" aria-label="Github"><i class="frontend_github">💻</i></a>
            </div>
        </div>

        <div class="footer-column">
            <h3 id="footerLinksTitle">Quick Links</h3>
            <div class="footer-links">
                <a href="#top" id="footerHome">Home</a>
                <a href="#features" id="footerFeatures">Features</a>
                <a href="#download" id="footerDownload">Download</a>
                <a href="#contact" id="footerContact">Contact</a>
            </div>
        </div>

        <div class="footer-column">
            <h3 id="devInfo">Developers</h3>
            <div class="developer-info">
                <h4>Backend</h4>
                <p id="devOne">Abd alrzaq najieb - abdlarzaqnajieb@gmail.com</p>
                <h4>FrontEnd</h4>
                <p id="devTwo">Yahea dada - dada.777.6x.outlook@gmail.com</p>
                <p id="devThree">Ward ikhtiar - wardekr@gmail.com</p>
                <p id="contactInfo">For more information, feel free to reach out!</p>
            </div>
        </div>
    </div>

    <div class="copyright">
        <p id="copyright">&copy; 2025 Habitly - All rights reserved</p>
    </div>
</footer>

<script>
    // Language toggle
    let currentLanguage = 'en';
    const translations = {
        en: {
            appName: "Habitly",
            navHome: "Home",
            navFeatures: "Features",
            navDownloads: "Download",
            navContact: "Contact",
            heroTitle: "Habitly",
            heroDescription: "Build new habits and achieve your goals with an extraordinary, modern interfaces that inspires you to excel.",
            downloadBtn: "Download for Android",
            featuresTitle: "Features",
            feature1Title: "Track & Monitor",
            feature1Description: "Keep a close eye on your daily routines with precise tracking and analytics.",
            feature2Title: "Roadmaps",
            feature2Description: "Plan your progress with detailed roadmaps and set clear milestones.",
            feature3Title: "Real-time Chat",
            feature3Description: "Connect instantly with friends and share your progress live.",
            feature4Title: "Rewards & Achievements",
            feature4Description: "Earn rewards and celebrate every milestone as you conquer your goals.",
            feature5Title: "Shared Habits & Competition",
            feature5Description: "Compete with friends by sharing your habits and achievements to boost your motivation.",
            feature6Title: "And Much More!",
            feature6Description: "Discover additional features like streaks and more that will drive you to excel.",
            downloadTitle: "Download Now",
            downloadDescription: "Get started with Habitly today and transform your habits into achievements.",
            androidDownloadBtn: "Download for Android",
            footerAboutTitle: "About Habitly",
            footerAboutText: "Habitly is a modern habit tracking app designed to help you build positive routines and achieve your goals.",
            footerLinksTitle: "Quick Links",
            footerHome: "Home",
            footerFeatures: "Features",
            footerDownload: "Download",
            footerContact: "Contact",
            devInfo: "Developers",
            devOne: "Abd alrzaq najieb - abdlarzaqnajieb@gmail.com",
            devTwo: "Yahea dada - dada.777.6x.outlook@gmail.com",
            contactInfo: "For more information, feel free to reach out!",
            copyright: "© 2025 Habitly - All rights reserved"
        },
        ar: {
            appName: "Habitly",
            navHome: "الرئيسية",
            navFeatures: "المميزات",
            navDownloads: "التحميل",
            navContact: "اتصل بنا",
            heroTitle: "Habitly",
            heroDescription: "ابدأ في بناء عادات جديدة وحقق أهدافك مع واجهات رائعة تلهب حماسك وتدفعك للتفوق.",
            downloadBtn: "تحميل لأندرويد",
            featuresTitle: "المميزات",
            feature1Title: "تتبع ومراقبة",
            feature1Description: "تابع روتينك اليومي بدقة مع تحليلات متكاملة.",
            feature2Title: "خطط طريقك",
            feature2Description: "خطط لتقدمك عبر خرائط طريق مفصلة وحدد معالمك.",
            feature3Title: "تواصل فوري",
            feature3Description: "تواصل مع أصدقائك وشارك تقدمك.",
            feature4Title: "مكافآت وإنجازات",
            feature4Description: "احصل على مكافآت واحتفل بكل نجاح تحققه.",
            feature5Title: "عادات مشتركة وتنافس",
            feature5Description: "تنافس مع أصدقائك بمشاركة عاداتك وإنجازاتك لتعزيز حماسك.",
            feature6Title: "وغيرها الكثير!",
            feature6Description: "اكتشف ميزات إضافية مثل تتبع السلاسل والمزيد التي ستدفعك للتفوق.",
            downloadTitle: "حمل الآن",
            downloadDescription: "ابدأ مع هابتلي اليوم وحول عاداتك إلى إنجازات.",
            androidDownloadBtn: "تحميل Android",
            footerAboutTitle: "عن Habitly",
            footerAboutText: "هابتلي هو تطبيق حديث لتتبع العادات مصمم لمساعدتك على بناء روتين إيجابي وتحقيق أهدافك.",
            footerLinksTitle: "روابط ",
            footerHome: "الرئيسية",
            footerFeatures: "المميزات",
            footerDownload: "التحميل",
            footerContact: "اتصل بنا",
            devInfo: "المطورون",
            devOne: "abd alrzaq najieb - abdlarzaqnajieb@gmail.com",
            devTwo: "yahea dada - dada.777.6x.outlook@gmail.com",
            devThree: "Ward ikhtiar - wardekr@gmail.com",
            contactInfo: "لمزيد من المعلومات، لا تتردد في التواصل معنا!",
            copyright: " Habitly 2025 © - جميع الحقوق محفوظة"
        }
    };

    document.addEventListener('DOMContentLoaded', function() {

        // Handle animations on scroll
        const fadeElements = document.querySelectorAll('.fade-in');

        function checkFade() {
            fadeElements.forEach(element => {
                const elementTop = element.getBoundingClientRect().top;
                const windowHeight = window.innerHeight;

                if (elementTop < windowHeight * 0.85) {
                    element.classList.add('visible');
                }
            });
        }

        // Initial check
        checkFade();
        window.addEventListener('scroll', checkFade);

        // Handle header background on scroll
        const header = document.getElementById('header');

        function checkScroll() {
            if (window.scrollY > 50) {
                header.classList.add('scrolled');
            } else {
                header.classList.remove('scrolled');
            }
        }

        // Initial check
        checkScroll();
        window.addEventListener('scroll', checkScroll);

        // Dark mode toggle
        const themeToggle = document.getElementById('theme-toggle');

        themeToggle.addEventListener('click', function() {
            document.body.classList.toggle('dark');
        });

        // Mobile menu toggle
        const menuToggle = document.getElementById('menu-toggle');
        const navLinks = document.getElementById('nav-links');

        menuToggle.addEventListener('click', function() {
            navLinks.classList.toggle('active');
            document.body.classList.toggle('menu-open');
        });

        // Close menu when clicking on links
        const links = navLinks.querySelectorAll('a');
        links.forEach(link => {
            link.addEventListener('click', function() {
                navLinks.classList.remove('active');
                document.body.classList.remove('menu-open');
            });
        });

        // Language toggle
        const langToggle = document.getElementById('lang-toggle');

        function updateLanguage() {
            document.documentElement.lang = currentLanguage;
            document.documentElement.dir = currentLanguage === 'en' ? 'ltr' : 'rtl';

            // Update language toggle button text
            langToggle.innerHTML = currentLanguage === 'en' ? 'العربية' : 'English';

            // Update all translated elements
            Object.keys(translations[currentLanguage]).forEach(key => {
                const element = document.getElementById(key);
                if (element) {
                    element.textContent = translations[currentLanguage][key];
                }
            });
        }
        updateLanguage();
        langToggle.addEventListener('click', function() {
            currentLanguage = currentLanguage === 'en' ? 'ar' : 'en';
            updateLanguage();
        });

        // Smooth scrolling for anchor links
        document.querySelectorAll('a[href^="#"]').forEach(anchor => {
            anchor.addEventListener('click', function(e) {
                e.preventDefault();
                const target = document.querySelector(this.getAttribute('href'));
                if (target) {
                    window.scrollTo({
                        top: target.offsetTop - 80,
                        behavior: 'smooth'
                    });
                }
            });
        });
    });
</script>
</body>
</html>
