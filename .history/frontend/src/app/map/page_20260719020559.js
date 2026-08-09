import axios from 'axios';

const performMapMatchingWithAxios = async (apiKey, pathString) => {
  try {
    const response = await axios.post(
      'https://api.neshan.org/v3/map-matching',
      { path: pathString },
      {
        headers: {
          'Api-Key': ,
          'Content-Type': 'application/json',
        },
      }
    );
    
    return response.data;
  } catch (error) {
    console.error('خطا در Map Matching:', error.response?.data || error.message);
    throw error;
  }
};