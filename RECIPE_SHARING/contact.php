<?php
$salesPhone = "+233206564514";
$supportEmail = "g8tetteh@gmail.com";
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Contact Us - GameLink: Trade, Play, Connect!</title>
    <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">

<style>
    /* General Reset */
    * {
        margin: 0;
        padding: 0;
        box-sizing: border-box;
        font-family: 'Poppins', sans-serif;
    }

    body {
        background-color: #f5f7fa;
        color: #2c3e50;
        line-height: 1.6;
        background-image: url('https://www.transparenttextures.com/patterns/cubes.png');
    }

    /* Header Styles */
    header {
        background: linear-gradient(135deg, #4834d4, #686de0);
        padding: 1.2rem 5%;
        position: fixed;
        width: 100%;
        top: 0;
        z-index: 1000;
        box-shadow: 0 2px 15px rgba(0, 0, 0, 0.1);
        display: flex;
        justify-content: space-between;
        align-items: center;
    }

    header .logo a {
        color: #fff;
        text-decoration: none;
        font-size: 2rem;
        font-weight: 700;
        letter-spacing: 1px;
    }

    header nav ul {
        list-style: none;
        display: flex;
        gap: 2rem;
    }

    header nav ul li a {
        color: #fff;
        text-decoration: none;
        font-size: 1.1rem;
        font-weight: 500;
        padding: 0.5rem 1rem;
        border-radius: 25px;
        transition: all 0.3s ease;
    }

    header nav ul li a:hover {
        background: rgba(255, 255, 255, 0.2);
        backdrop-filter: blur(5px);
    }

    /* Contact Header */
    .contact-header {
        background: linear-gradient(135deg, #4834d4, #686de0);
        color: #fff;
        padding: 8rem 2rem 4rem;
        text-align: center;
        position: relative;
        overflow: hidden;
        background-image: url('https://images.unsplash.com/photo-1511512578047-dfb367046420?ixlib=rb-1.2.1&auto=format&fit=crop&w=1350&q=80');
        background-size: cover;
        background-position: center;
        background-blend-mode: overlay;
    }

    .contact-header::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        background: rgba(72, 52, 212, 0.8);
    }

    .contact-header h1 {
        font-size: 3.5rem;
        font-weight: 700;
        margin-bottom: 1rem;
        position: relative;
        z-index: 1;
        text-shadow: 2px 2px 4px rgba(0, 0, 0, 0.2);
    }

    .contact-header p {
        font-size: 1.3rem;
        max-width: 800px;
        margin: 0 auto;
        position: relative;
        z-index: 1;
        opacity: 0.9;
    }

    /* Contact Container */
    .contact-container {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
        gap: 2rem;
        padding: 4rem 5%;
        max-width: 1200px;
        margin: 0 auto;
    }

    .contact-card {
        background: rgba(255, 255, 255, 0.9);
        backdrop-filter: blur(10px);
        border-radius: 20px;
        padding: 2.5rem;
        text-align: center;
        transition: all 0.3s ease;
        position: relative;
        overflow: hidden;
        border: 1px solid rgba(255, 255, 255, 0.2);
        box-shadow: 0 10px 30px rgba(0, 0, 0, 0.1);
    }

    .contact-card::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background: url('https://www.transparenttextures.com/patterns/diagmonds-light.png');
        opacity: 0.1;
        z-index: 0;
    }

    .contact-card:hover {
        transform: translateY(-10px);
        box-shadow: 0 15px 35px rgba(72, 52, 212, 0.2);
    }

    .contact-card i {
        font-size: 3rem;
        background: linear-gradient(135deg, #4834d4, #686de0);
        -webkit-background-clip: text;
        -webkit-text-fill-color: transparent;
        margin-bottom: 1.5rem;
        display: inline-block;
        position: relative;
        z-index: 1;
    }

    .contact-card h3 {
        font-size: 1.8rem;
        color: #2c3e50;
        margin-bottom: 1rem;
        position: relative;
        z-index: 1;
    }

    .contact-card p {
        color: #666;
        font-size: 1.1rem;
        margin-bottom: 1.5rem;
        position: relative;
        z-index: 1;
    }

    .contact-card a {
        display: inline-flex;
        align-items: center;
        gap: 0.5rem;
        background: linear-gradient(135deg, #4834d4, #686de0);
        color: #fff;
        text-decoration: none;
        padding: 0.8rem 1.5rem;
        border-radius: 25px;
        font-weight: 500;
        transition: all 0.3s ease;
        position: relative;
        z-index: 1;
        box-shadow: 0 4px 15px rgba(72, 52, 212, 0.2);
    }

    .contact-card a:hover {
        transform: translateY(-2px);
        box-shadow: 0 8px 25px rgba(72, 52, 212, 0.3);
    }

    .card-image {
        width: 100%;
        height: 150px;
        object-fit: cover;
        border-radius: 10px;
        margin-bottom: 1.5rem;
        box-shadow: 0 5px 15px rgba(0, 0, 0, 0.1);
        transition: all 0.3s ease;
    }

    .card-image:hover {
        transform: scale(1.03);
    }

    /* Additional Contact Section */
    .additional-contact {
        background: rgba(255, 255, 255, 0.9);
        backdrop-filter: blur(10px);
        padding: 4rem 5%;
        border-radius: 20px;
        margin: 2rem 5%;
        text-align: center;
        box-shadow: 0 10px 30px rgba(0, 0, 0, 0.1);
    }

    .additional-contact h2 {
        font-size: 2.5rem;
        color: #2c3e50;
        margin-bottom: 2rem;
    }

    .social-links {
        display: flex;
        justify-content: center;
        gap: 2rem;
        flex-wrap: wrap;
    }

    .social-links a {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        width: 50px;
        height: 50px;
        background: linear-gradient(135deg, #4834d4, #686de0);
        border-radius: 50%;
        color: white;
        font-size: 1.5rem;
        transition: all 0.3s ease;
        box-shadow: 0 5px 15px rgba(72, 52, 212, 0.2);
    }

    .social-links a:hover {
        transform: translateY(-5px) rotate(360deg);
        box-shadow: 0 10px 20px rgba(72, 52, 212, 0.3);
    }

    /* Responsive Design */
    @media (max-width: 1024px) {
        .contact-header h1 {
            font-size: 3rem;
        }

        .contact-container {
            padding: 3rem 5%;
        }
    }

    @media (max-width: 768px) {
        header {
            padding: 1rem 5%;
        }

        header .logo a {
            font-size: 1.8rem;
        }

        header nav ul {
            gap: 1rem;
        }

        header nav ul li a {
            font-size: 1rem;
            padding: 0.4rem 0.8rem;
        }

        .contact-header {
            padding: 6rem 1rem 3rem;
        }

        .contact-header h1 {
            font-size: 2.5rem;
        }

        .contact-header p {
            font-size: 1.1rem;
        }

        .contact-card {
            padding: 2rem;
        }

        .social-links {
            gap: 1rem;
        }
    }

    @media (max-width: 480px) {
        .contact-header h1 {
            font-size: 2rem;
        }

        .contact-card h3 {
            font-size: 1.5rem;
        }

        .contact-card p {
            font-size: 1rem;
        }

        .social-links a {
            width: 40px;
            height: 40px;
            font-size: 1.2rem;
        }

        .additional-contact h2 {
            font-size: 2rem;
        }
    }
</style>
</head>
<body>
    <!-- Header remains the same -->

    <section class="contact-header">
        <h1>Get in Touch</h1>
        <p>Have a question or need assistance? We're here to help you with your gaming exchanges.</p>
    </section>

    <section class="contact-container">
        <div class="contact-card">
            <img src="https://images.unsplash.com/photo-1556745757-8d76bdb6984b" alt="Sales Team" class="card-image">
            <i class='bx bx-phone'></i>
            <h3>Talk to Us</h3>
            <p>Have a question about our platform? Our sales team is ready to assist you 24/7.</p>
            <a href="tel:<?= $salesPhone; ?>"><i class='bx bx-phone-call'></i><?= $salesPhone; ?></a>
        </div>

        <div class="contact-card">
            <img src="https://images.unsplash.com/photo-1557200134-90327ee9fafa" alt="Support Team" class="card-image">
            <i class='bx bx-support'></i>
            <h3>Email Support</h3>
            <p>Technical issues or account-related queries? Our support team is here to help!</p>
            <a href="mailto:<?= $supportEmail; ?>"><i class='bx bx-envelope'></i><?= $supportEmail; ?></a>
        </div>
    </section>

    <section class="additional-contact">
        <h2>Connect With Us</h2>
        <div class="social-links">
            <a href="#" class="bx bxl-facebook" title="Follow us on Facebook"></a>
            <a href="#" class="bx bxl-twitter" title="Follow us on Twitter"></a>
            <a href="#" class="bx bxl-instagram" title="Follow us on Instagram"></a>
            <a href="#" class="bx bxl-discord" title="Join our Discord"></a>
            <a href="#" class="bx bxl-youtube" title="Subscribe to our YouTube"></a>
        </div>
    </section>
</body>
</html> 