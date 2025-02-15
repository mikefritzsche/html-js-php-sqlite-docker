// Example usage in main thread:
const dbWorker = new Worker('dbWorker.js');

// Initialize the worker with API URL
// dbWorker.postMessage({
//   type: 'INIT',
//   payload: {
//     apiUrl: 'https://api.example.com/db'
//   }
// });

// Listen for messages from the worker
dbWorker.onmessage = function(e) {
  const { type, payload, requestId } = e.data;

  switch (type) {
    case 'INITIALIZED':
      console.log('Worker initialized');
      break;

    case 'DATA_RECEIVED':
      console.log('Received data:', payload);
      break;

    case 'ERROR':
      console.error('Error:', payload);
      break;
  }
};

// Example query
// dbWorker.postMessage({
//   type: 'FETCH_DATA',
//   payload: {
//     query: 'SELECT * FROM users WHERE age > $1',
//     params: [18],
//     requestId: 'query-1'
//   }
// });
function debounce(func, delay) {
  let timeoutId;
  return function(...args) {
    clearTimeout(timeoutId);
    timeoutId = setTimeout(() => {
      func.apply(this, args);
    }, delay);
  };
}
function handleScroll() {
  // Function to execute after scrolling stops
  console.log('Scrolling stopped');
}

const debouncedScroll = debounce(handleScroll, 250); // Delay of 250 milliseconds

// window.addEventListener('scroll', debouncedScroll);
document.addEventListener('DOMContentLoaded', function() {
  // Dynamic content loading
  // const dynamicContent = document.getElementById('dynamic-content');
  // fetch('https://api.example.com/content')
  //   .then(response => response.json())
  //   .then(data => {
  //     dynamicContent.innerHTML = data.content;
  //   })
  //   .catch(error => {
  //     console.error('Error:', error);
  //   });

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
        alert('Message sent successfully!: ', data);
        contactForm.reset();
      })
      .catch(error => {
        console.error('Error:', error);
        alert('Error sending message. Please try again.');
      });
  });

  // window.addEventListener('scroll', function(event) {
    // Code to be executed on scroll
    // console.log('Window was scrolled');
  // });

  const scrollContainer = document.querySelector('main').getBoundingClientRect()
  console.log('scrollContainer: ', scrollContainer.height, scrollContainer.top, scrollContainer.y)

  const scrollIndicator = document.getElementById('scrollIndicator');
  window.onscroll = (event) => {
    updateScrollIndicator(scrollIndicator)
  }
  function updateScrollIndicator(scrollIndicator) {
    const scrollTop = document.documentElement.scrollTop || document.body.scrollTop;
    const scrollHeight = document.documentElement.scrollHeight - document.documentElement.clientHeight;
    const scrolled = (scrollTop / scrollHeight) * 100;

    scrollIndicator.style.width = scrolled + '%';
  }
});
