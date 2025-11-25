<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
    <title>Dry Zone Cantilan — About</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="styles/style.css">
    <style>
        body {
            background: white;
        }

        .overview-section-custom {
            padding: 70px 0;
            background: transparent;
        }

        .overview-content {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 36px;
            align-items: center;
            max-width: 1100px;
            margin: 0 auto;
        }

        .overview-image {
            border-radius: 12px;
            overflow: hidden;
            background: white;
            box-shadow: var(--shadow-lg);
        }

        .overview-image img {
            width: 100%;
            height: auto;
            display: block;
            object-fit: cover;
            transition: transform 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        }

        .overview-image:hover img {
            transform: scale(1.05);
        }

        .overview-text {
            font-size: 1rem;
            color: var(--text-light);
            background: transparent;
            padding: 6px 2px;
        }

        .overview-text p {
            margin-bottom: 1rem;
            color: var(--text-light);
            text-align: justify;
            text-justify: inter-word;
        }

        .rating-section-custom {
            padding-top: 10px;
            padding-bottom: 60px;
        }

        .rating-rows {
            display: flex;
            flex-direction: column;
            gap: 28px;
            max-width: 1100px;
            margin: 0 auto;
        }

        .rating-row {
            display: flex;
            gap: 0;
            align-items: stretch;
            background: white;
            border-radius: 12px;
            overflow: hidden;
            box-shadow: var(--shadow-md);
            border: 1px solid rgba(0, 0, 0, 0.06);
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        }

        .rating-row:hover {
            box-shadow: var(--shadow-lg);
        }

        .rating-image {
            flex: 0 0 40%;
            background: linear-gradient(135deg, #f0f9ff 0%, #e0f2fe 100%);
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 18px;
        }

        .rating-image img {
            width: 100%;
            height: auto;
            object-fit: cover;
            border-radius: 6px;
        }

        .rating-form {
            flex: 1;
            padding: 22px;
            display: flex;
            flex-direction: column;
            gap: 8px;
        }

        .question-number {
            color: var(--primary);
            font-weight: 700;
            font-size: 0.95rem;
        }

        .card-title {
            color: var(--dark);
            font-size: 1.15rem;
            margin-bottom: 6px;
            font-weight: 700;
        }

        .exit-factors-section {
            background: linear-gradient(135deg, #f0f9ff 0%, #e0f2fe 100%);
            padding-bottom: 50px;
        }

        .factors-grid {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 24px;
            max-width: 1100px;
            margin: 0 auto;
        }

        .factor-card {
            background: white;
            border-radius: 12px;
            padding: 28px;
            text-align: center;
            box-shadow: var(--shadow-md);
            border: 1px solid rgba(0, 0, 0, 0.05);
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        }

        .factor-card:hover {
            transform: translateY(-4px);
            box-shadow: var(--shadow-lg);
        }

        .factor-icon {
            font-size: 2.2rem;
            color: var(--primary);
            margin-bottom: 12px;
        }

        .factor-title {
            font-weight: 700;
            color: var(--dark);
            margin-bottom: 8px;
        }

        .section-title-custom {
            text-align: center;
            margin: 0 0 40px;
            color: var(--dark);
            position: relative;
            font-size: 2rem;
            font-weight: 800;
            letter-spacing: -0.5px;
        }

        .section-title-custom::after {
            content: '';
            display: block;
            width: 60px;
            height: 4px;
            background: var(--gradient-1);
            border-radius: 2px;
            margin: 12px auto 0;
        }

        .faq-answer {
            color: #555;
            font-size: 0.95rem;
            line-height: 1.5;
        }

        @media (max-width: 992px) {
            .overview-content {
                grid-template-columns: 1fr;
                gap: 22px;
            }
            .factors-grid {
                grid-template-columns: repeat(2, 1fr);
            }
            .rating-row {
                flex-direction: column;
            }
            .rating-image {
                flex-basis: auto;
                width: 100%;
                padding: 12px;
            }
        }

        @media (max-width: 768px) {
            .factors-grid {
                grid-template-columns: 1fr;
            }
        }

        @media (max-width: 600px) {
            .section-title-custom {
                font-size: 1.5rem;
            }
            .overview-section-custom {
                padding: 40px 0;
            }
        }
    </style>
</head>
<body>
<?php include 'header.php'; ?>

<section id="overview" class="overview-section-custom">
    <div class="container">
        <h2 class="section-title-custom">About Dry Zone Cantilan</h2>
        <div class="overview-content">
            <div class="overview-text">
                <p>Dry Zone Cantilan is a comprehensive online platform designed to transform the laundry experience for residents and students in Cantilan. We serve as the central hub connecting customers with trusted local laundry services, eliminating the time-consuming process of searching for reliable providers. Our mission is to simplify daily life by offering a seamless, one-stop solution where users can easily browse, compare, and book laundry services that fit their specific needs and schedules.</p>
                <p>Through our intuitive platform, customers can access detailed profiles of partner laundry shops, complete with service offerings, pricing, operating hours, and genuine customer reviews. We've partnered with the most reliable laundry establishments in Cantilan to ensure quality service, while our integrated booking system allows for convenient scheduling of pick-up and delivery services. Whether you're a busy professional, a student with limited time, or a family managing household chores, Dry Zone Cantilan provides the convenience of having fresh, clean clothes without the hassle of traditional laundry errands.</p>
                <p>Beyond convenience, we're committed to supporting local businesses and fostering economic growth within our community. By connecting customers with neighborhood laundry services, we help these businesses reach more clients while providing residents with access to quality, affordable laundry solutions. Dry Zone Cantilan represents our dedication to making everyday tasks easier, saving you valuable time, and contributing to a better work-life balance for everyone in our community.</p>
            </div>

            <div class="overview-image" aria-hidden="true">
                <img src="./images/cantilan.jpg" alt="Laundry and community">
            </div>
        </div>
    </div>
</section>

<section class="overview-section-custom rating-section-custom">
    <div class="container">
        <h2 class="section-title-custom">Frequently Asked Questions</h2>
        <div class="rating-rows">
            <div class="rating-row">
                <div class="rating-image">
                    <img src="https://images.unsplash.com/photo-1552664730-d307ca884978?ixlib=rb-4.0.3&auto=format&fit=crop&w=800&q=80" alt="Laundry Service">
                </div>
                <div class="rating-form">
                    <span class="question-number">QUESTION #1</span>
                    <h3 class="card-title">How can I choose the best laundry shop for my needs?</h3>
                    <div class="faq-answer">Our platform allows you to browse shop profiles, compare services offered, check customer ratings, and read reviews. You can filter by location, pricing, service types, and operating hours to find the perfect match for your laundry requirements.</div>
                </div>
            </div>

            <div class="rating-row">
                <div class="rating-image">
                    <img src="./images/purpose.webp" alt="Laundry Service">
                </div>
                <div class="rating-form">
                    <span class="question-number">QUESTION #2</span>
                    <h3 class="card-title">What is the purpose of Dry Zone Cantilan?</h3>
                    <div class="faq-answer">Dry Zone Cantilan connects residents and students in Cantilan with reliable laundry services. Our platform makes it easy to find, compare, and book laundry services that fit your schedule and budget, saving you time and effort.</div>
                </div>
            </div>

            <div class="rating-row">
                <div class="rating-image">
                    <img src="./images/offers.jpg" alt="Laundry Service">
                </div>
                <div class="rating-form">
                    <span class="question-number">QUESTION #3</span>
                    <h3 class="card-title">What special services do Cantilan laundry shops offer?</h3>
                    <div class="faq-answer">Many of our partner shops provide pickup and delivery services, use eco-friendly detergents, offer express same-day service, and provide special care for delicate items. Some also offer bulk discounts for students and regular customers.</div>
                </div>
            </div>

            <div class="rating-row">
                <div class="rating-image">
                    <img src="./images/booking.jpg" alt="Laundry Service">
                </div>
                <div class="rating-form">
                    <span class="question-number">QUESTION #4</span>
                    <h3 class="card-title">How does the online booking system work?</h3>
                    <div class="faq-answer">Simply select your preferred laundry shop, choose your services (washing, drying, folding, ironing), schedule a pickup time, and confirm your booking. You'll receive notifications about your order status and can track it through our platform.</div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Why Choose Us -->
<section class="overview-section-custom exit-factors-section">
    <div class="container">
        <h2 class="section-title-custom">Why Choose Us?</h2>
        <div class="factors-grid">
            <div class="factor-card">
                <div class="factor-icon"><i class="fas fa-dollar-sign"></i></div>
                <h3 class="factor-title">Affordable Pricing</h3>
                <p>Competitive rates that deliver exceptional value for quality laundry services.</p>
            </div>
            <div class="factor-card">
                <div class="factor-icon"><i class="fas fa-headset"></i></div>
                <h3 class="factor-title">Responsive Support</h3>
                <p>Our dedicated team is always ready to assist with your laundry needs.</p>
            </div>
            <div class="factor-card">
                <div class="factor-icon"><i class="fas fa-cogs"></i></div>
                <h3 class="factor-title">Premium Services</h3>
                <p>Comprehensive laundry solutions from washing to dry cleaning and express service.</p>
            </div>
            <div class="factor-card">
                <div class="factor-icon"><i class="fas fa-user-friends"></i></div>
                <h3 class="factor-title">Trusted Partners</h3>
                <p>Connect with the most reliable laundry shops in Cantilan, Surigao del Sur.</p>
            </div>
            <div class="factor-card">
                <div class="factor-icon"><i class="fas fa-chart-line"></i></div>
                <h3 class="factor-title">Easy Growth</h3>
                <p>Scale your laundry business with our seamless booking and management platform.</p>
            </div>
            <div class="factor-card">
                <div class="factor-icon"><i class="fas fa-lightbulb"></i></div>
                <h3 class="factor-title">Innovation</h3>
                <p>Modern features like real-time chat, order tracking, and advanced search filters.</p>
            </div>
        </div>
    </div>
</section>
</body>
</html>
