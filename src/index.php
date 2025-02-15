<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>My Website</title>
  <link rel="stylesheet" href="styles.css">
</head>
<body>
<?php  include 'config.php'; ?>

<header>
  <nav>
    <ul>
      <li><a href="#home">Home</a></li>
      <li><a href="#about">About</a></li>
      <li><a href="#contact">Contact</a></li>
    </ul>
  </nav>
</header>

<div id="scrollIndicator"></div>
<div style="height: 2000px;">
  <p>Scroll down to see the indicator in action.</p>
  <main>
    <section id="home">
      <h1>Welcome to My Website</h1>
      <p>This is a simple website running in a Docker container.</p>
      <div id="dynamic-content"></div>
    </section>

    <section id="about">
      <h2>About Us</h2>
      <p>Learn more about our services and team.</p>
    </section>

    <section id="contact">
      <h2>Contact Us</h2>
      <form id="contact-form">
        <input type="text" id="name" placeholder="Your Name" required>
        <input type="email" id="email" placeholder="Your Email" required>
        <textarea id="message" placeholder="Your Message" required></textarea>
        <button type="submit">Send Message</button>
      </form>
    </section>
  </main>
</div>

<footer>
  <p>&copy; <?php echo Date('Y'); ?> <?php echo SITE_NAME; ?>. All rights reserved.</p>
</footer>

<script src="script.js"></script>
</body>
</html>
