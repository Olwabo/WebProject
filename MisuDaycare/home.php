<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Guardian Dashboard - Misukukhanya Daycare</title>
  <style>
    * {
      margin: 0;
      padding: 0;
      box-sizing: border-box;
    }
    
    body {
      font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
      background: linear-gradient(135deg, #f5f7fa 0%, #c3cfe2 100%);
      min-height: 100vh;
      color: #333;
      line-height: 1.6;
    }
    
    /* Navigation */
    nav {
      background: #ffffff;
      box-shadow: 0 2px 15px rgba(0,0,0,0.1);
      padding: 1rem 0;
      position: sticky;
      top: 0;
      z-index: 100;
    }
    
    .nav-container {
      max-width: 1200px;
      margin: 0 auto;
      padding: 0 2rem;
      display: flex;
      justify-content: space-between;
      align-items: center;
    }
    
    .logo {
      display: flex;
      align-items: center;
      gap: 1rem;
    }
    
    .logo-img {
      width: 50px;
      height: 50px;
      border-radius: 50%;
      object-fit: cover;
      border: 3px solid #4a6fa5;
    }
    
    .logo-text {
      font-size: 1.5rem;
      font-weight: 600;
      color: #4a6fa5;
    }
    
    nav ul {
      display: flex;
      list-style: none;
      gap: 1.5rem;
      flex-wrap: wrap;
    }
    
    nav a {
      color: #5d5d5d;
      text-decoration: none;
      padding: 0.75rem 1.5rem;
      border-radius: 8px;
      background: #f8f9fa;
      transition: all 0.3s ease;
      font-weight: 500;
      border: 1px solid #e9ecef;
    }
    
    nav a:hover {
      background: #4a6fa5;
      color: white;
      transform: translateY(-2px);
      box-shadow: 0 4px 12px rgba(74, 111, 165, 0.3);
    }
    
    /* Hero Section */
    .hero {
      max-width: 1200px;
      margin: 3rem auto;
      padding: 0 2rem;
      display: grid;
      grid-template-columns: 1fr 1fr;
      gap: 3rem;
      align-items: center;
    }
    
    .hero-content h1 {
      color: #2c3e50;
      font-size: 2.5rem;
      margin-bottom: 1rem;
      font-weight: 300;
      line-height: 1.2;
    }
    
    .hero-content p {
      color: #7f8c8d;
      font-size: 1.2rem;
      margin-bottom: 2rem;
    }
    
    .hero-image {
      position: relative;
      border-radius: 15px;
      overflow: hidden;
      box-shadow: 0 20px 40px rgba(0,0,0,0.1);
    }
    
    .hero-image img {
      width: 100%;
      height: 400px;
      object-fit: cover;
      transition: transform 0.3s ease;
    }
    
    .hero-image:hover img {
      transform: scale(1.05);
    }
    
    .image-overlay {
      position: absolute;
      bottom: 0;
      left: 0;
      right: 0;
      background: linear-gradient(transparent, rgba(0,0,0,0.7));
      color: white;
      padding: 2rem;
      text-align: center;
    }
    
    /* Features Grid */
    .features {
      max-width: 1200px;
      margin: 4rem auto;
      padding: 0 2rem;
    }
    
    .features h2 {
      text-align: center;
      color: #2c3e50;
      font-size: 2rem;
      margin-bottom: 3rem;
      font-weight: 300;
    }
    
    .features-grid {
      display: grid;
      grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
      gap: 2rem;
    }
    
    .feature-card {
      background: white;
      padding: 2rem;
      border-radius: 10px;
      text-align: center;
      box-shadow: 0 5px 15px rgba(0,0,0,0.08);
      transition: transform 0.3s ease;
      border-left: 4px solid #4a6fa5;
    }
    
    .feature-card:hover {
      transform: translateY(-5px);
    }
    
    .feature-icon {
      font-size: 2.5rem;
      color: #4a6fa5;
      margin-bottom: 1rem;
    }
    
    .feature-card h3 {
      color: #2c3e50;
      margin-bottom: 1rem;
      font-weight: 500;
    }
    
    .feature-card p {
      color: #7f8c8d;
    }
    
    /* Map Section */
    .map-section {
      max-width: 1200px;
      margin: 4rem auto;
      padding: 0 2rem;
    }
    
    .map-section h2 {
      text-align: center;
      color: #2c3e50;
      font-size: 2rem;
      margin-bottom: 2rem;
      font-weight: 300;
    }
    
    .map-container {
      background: white;
      border-radius: 15px;
      overflow: hidden;
      box-shadow: 0 10px 30px rgba(0,0,0,0.1);
    }
    
    .map-wrapper {
      position: relative;
      width: 100%;
      height: 400px;
    }
    
    #daycare-map {
      width: 100%;
      height: 100%;
      border: none;
    }
    
    .map-info {
      padding: 2rem;
      background: #f8f9fa;
      border-top: 1px solid #e9ecef;
    }
    
    .map-info h3 {
      color: #2c3e50;
      margin-bottom: 1rem;
    }
    
    .address-details {
      display: grid;
      grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
      gap: 1rem;
    }
    
    .address-item {
      display: flex;
      align-items: flex-start;
      gap: 1rem;
    }
    
    .address-icon {
      font-size: 1.5rem;
      color: #4a6fa5;
      margin-top: 0.25rem;
    }
    
    /* Quick Stats */
    .stats {
      background: #4a6fa5;
      color: white;
      padding: 3rem 2rem;
      margin: 4rem 0;
    }
    
    .stats-container {
      max-width: 1200px;
      margin: 0 auto;
      display: grid;
      grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
      gap: 2rem;
      text-align: center;
    }
    
    .stat-item h3 {
      font-size: 2.5rem;
      margin-bottom: 0.5rem;
      font-weight: 300;
    }
    
    /* Footer */
    footer {
      background: #2c3e50;
      color: white;
      text-align: center;
      padding: 2rem;
      margin-top: 4rem;
    }
    
    /* Responsive Design */
    @media (max-width: 768px) {
      .nav-container {
        flex-direction: column;
        gap: 1rem;
      }
      
      nav ul {
        justify-content: center;
      }
      
      .hero {
        grid-template-columns: 1fr;
        text-align: center;
      }
      
      .hero-content h1 {
        font-size: 2rem;
      }
      
      .features-grid {
        grid-template-columns: 1fr;
      }
      
      .map-wrapper {
        height: 300px;
      }
      
      nav a {
        padding: 0.5rem 1rem;
        font-size: 0.9rem;
      }
    }
    
    @media (max-width: 480px) {
      nav ul {
        flex-direction: column;
        align-items: center;
      }
      
      nav a {
        width: 200px;
        text-align: center;
      }
      
      .hero-image img {
        height: 300px;
      }
      
      .map-wrapper {
        height: 250px;
      }
    }
  </style>
</head>
<body>
  <nav>
    <div class="nav-container">
      <div class="logo">
        <img src="images/kezamile bathong.png" alt="Misukukhanya Daycare Logo" class="logo-img">
        <div class="logo-text">Misukukhanya</div>
      </div>
      <ul>
        
        <li><a href="#hours">Staff & Hours</a></li>
        <li><a href="#hours">Contacts</a></li>
        <li><a href="login.php">Apply</a></li>
      </ul>
    </div>
  </nav>
  
  <section class="hero">
    <div class="hero-content">
      <h1>Welcome to Misukukhanya Daycare</h1>
      <p>Your trusted partner in early childhood education and care. We provide a safe, nurturing environment where children can learn, play, and grow.</p>
      <div style="display: flex; gap: 1rem; flex-wrap: wrap;">
        <a href="login.php" style="background: #4a6fa5; color: white; padding: 1rem 2rem; text-decoration: none; border-radius: 8px; transition: all 0.3s ease;">Enroll Now</a>
        <a href="login.php" style="background: transparent; color: #4a6fa5; padding: 1rem 2rem; text-decoration: none; border-radius: 8px; border: 2px solid #4a6fa5; transition: all 0.3s ease;">My Dashboard</a>
      </div>
    </div>
    <div class="hero-image">
      <img src="images/kiddies.jpg" alt="Happy children at Misukukhanya Daycare">
      <div class="image-overlay">
        <h3>Where Every Child Shines</h3>
      </div>
    </div>
  </section>
  
  <section class="features">
    <h2>Why Choose Misukukhanya?</h2>
    <div class="features-grid">
      <div class="feature-card">
        <div class="feature-icon">👶</div>
        <h3>Safe Environment</h3>
        <p>Secure, child-friendly facilities with 24/7 monitoring and safety protocols</p>
      </div>
      <div class="feature-card">
        <div class="feature-icon">📚</div>
        <h3>Quality Education</h3>
        <p>Age-appropriate learning programs designed by early childhood experts</p>
      </div>
      <div class="feature-card">
        <div class="feature-icon">🍎</div>
        <h3>Healthy Meals</h3>
        <p>Nutritionally balanced meals prepared daily by our professional kitchen staff</p>
      </div>
      <div class="feature-card">
        <div class="feature-icon">👩‍🏫</div>
        <h3>Expert Staff</h3>
        <p>Qualified and experienced caregivers dedicated to your child's development</p>
      </div>
    </div>
  </section>

  <!-- Map Section -->
  <section class="map-section">
    <h2>Find Our Daycare</h2>
    <div class="map-container">
      <div class="map-wrapper">
        <iframe 
          id="daycare-map"
          src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3310.234567890123!2d27.74268041444287!3d-32.93703427962885!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x0%3A0x0!2zMzLCsDU2JzEzLjMiUyAyN8KwNDQnMzMuNiJF!5e0!3m2!1sen!2sza!4v1755542487447!5m2!1sen!2sza"
          allowfullscreen=""
          loading="lazy"
          referrerpolicy="no-referrer-when-downgrade">
        </iframe>
      </div>
      <section id="hours">
      <div class="map-info">
        <h3>Misukukhanya Daycare Location</h3>
        <div class="address-details">
          <div class="address-item">
            <div class="address-icon">📍</div>
            
            <div>
              <strong>Address:</strong><br>
              1326 Spine Road, Unit 5<br>
              Mdantsane, East London<br>
              Eastern Cape, 5219
            </div>
          </div>
          
          <div class="address-item">
            <div class="address-icon">⏰</div>
            <div>
              <strong>Operating Hours:</strong><br>
              Monday - Friday: 7:00 AM - 5:00 PM<br>
              Sunday: Closed
            </div>
          </div>
          
          <div class="address-item">
            <div class="address-icon">📞</div>
            <div>
              <strong>Contact Info:</strong><br>
              Phone: +27 71 234 5678<br>
              Email: info@misukukhanyadaycare.co.za<br>
              Emergency: +27 82 345 6789
            </div>
          </div>
        </div>
      </div>
    </div>
  </section>
  
  <section class="stats">
    <div class="stats-container">
      <div class="stat-item">
        <h3>15+</h3>
        <p>Years of Experience</p>
      </div>
      <div class="stat-item">
        <h3>200+</h3>
        <p>Happy Children</p>
      </div>
      <div class="stat-item">
        <h3>25+</h3>
        <p>Qualified Staff</p>
      </div>
      <div class="stat-item">
        <h3>98%</h3>
        <p>Parent Satisfaction</p>
      </div>
    </div>
  </section>
  
  <footer>
    <p>&copy; 2025 Misukukhanya Daycare. All rights reserved.</p>
    <p>Providing quality childcare since 2010 | Located in Mdantsane, East London</p>
  </footer>

  <script>
    // Enhanced map functionality
    function initMap() {
      // This function can be expanded for custom map features
      console.log('Map loaded successfully');
      
      // Add interactive features to the map
      const mapFrame = document.getElementById('daycare-map');
      
      // Add loading indicator
      mapFrame.addEventListener('load', function() {
        console.log('Google Maps frame loaded');
      });
    }
    
    // Initialize map when page loads
    document.addEventListener('DOMContentLoaded', initMap);
    
    // Add directions functionality
    function getDirections() {
      const address = "1326 Spine Road, Unit 5, Mdantsane, East London, 5219";
      const encodedAddress = encodeURIComponent(address);
      const directionsUrl = `https://www.google.com/maps/dir/?api=1&destination=${encodedAddress}`;
      window.open(directionsUrl, '_blank');
    }
    
    // Add click event to address for directions
    document.addEventListener('DOMContentLoaded', function() {
      const addressElement = document.querySelector('.address-item:nth-child(1)');
      addressElement.style.cursor = 'pointer';
      addressElement.title = 'Click to get directions';
      addressElement.addEventListener('click', getDirections);
    });
  </script>
</body>
</html>