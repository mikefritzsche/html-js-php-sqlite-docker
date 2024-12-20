document.addEventListener('DOMContentLoaded', function() {
  // Dynamic content loading
  const dynamicContent = document.getElementById('dynamic-content');
  fetch('https://api.example.com/content')
    .then(response => response.json())
    .then(data => {
      dynamicContent.innerHTML = data.content;
    })
    .catch(error => {
      console.error('Error:', error);
    });

  // Contact form handling
  const contactForm = document.getElementById('contact-form');
  contactForm.addEventListener('submit', function(e) {
    e.preventDefault();

    const formData = {
      name: document.getElementById('name').value,
      email: document.getElementById('email').value,
      message: document.getElementById('message').value
    };

    // Send form data to server
    fetch('/submit-form.php', {
      method: 'POST',
      headers: {
        'Content-Type': 'application/json'
      },
      body: JSON.stringify(formData)
    })
      .then(response => response.json())
      .then(data => {
        alert('Message sent successfully!');
        contactForm.reset();
      })
      .catch(error => {
        console.error('Error:', error);
        alert('Error sending message. Please try again.');
      });
  });
});
