// dbWorker.js
let apiBaseUrl = '';

// Message handler for receiving messages from the main thread
self.onmessage = async function(e) {
  const { type, payload } = e.data;

  switch (type) {
    case 'INIT':
      apiBaseUrl = payload.apiUrl;
      self.postMessage({ type: 'INITIALIZED' });
      break;

    case 'FETCH_DATA':
      try {
        const data = await fetchFromDatabase(payload.query, payload.params);
        self.postMessage({
          type: 'DATA_RECEIVED',
          payload: data,
          requestId: payload.requestId
        });
      } catch (error) {
        self.postMessage({
          type: 'ERROR',
          payload: error.message,
          requestId: payload.requestId
        });
      }
      break;

    default:
      self.postMessage({
        type: 'ERROR',
        payload: 'Unknown message type'
      });
  }
};

// Function to fetch data from the database through API
async function fetchFromDatabase(query, params) {
  try {
    const response = await fetch(`${apiBaseUrl}/query`, {
      method: 'POST',
      headers: {
        'Content-Type': 'application/json'
      },
      body: JSON.stringify({
        query,
        params
      })
    });

    if (!response.ok) {
      throw new Error(`HTTP error! status: ${response.status}`);
    }

    const data = await response.json();
    return data;
  } catch (error) {
    throw new Error(`Database fetch failed: ${error.message}`);
  }
}
