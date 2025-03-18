/* globals: axios */

const ajaxDefaults = {
    headers: {
        'X-Requested-With': 'XMLHttpRequest',
        'Content-Type': 'application/json',
        'Accept': 'application/json',
        // 'Authorization': 'Bearer ' + localStorage.getItem('token'),
        // 'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
        // 'X-XSRF-TOKEN': decodeURIComponent(document.cookie.match('XSRF-TOKEN=([^;]+)')[1]),
        // 'Access-Control-Allow-Origin': '*', // CORS
        // 'Access-Control-Allow-Methods': 'GET, POST, PUT, DELETE, OPTIONS', // CORS
        // 'Access-Control-Allow-Headers': 'X-Requested-With, Content-Type, Accept, Authorization', // CORS
        // 'Access-Control-Allow-Credentials': 'true', // CORS
        // 'Access-Control-Max-Age': '86400', // CORS
        // 'Access-Control-Expose-Headers': 'Authorization', // CORS
    }
};

const ajax = {
    get(url, params = {}) {
        return axios.get(url, { params })
            .then(response => response.data)
            .catch(error => console.error('Axios GET error:', error));
    },

    post(url, params = {}) {
        return axios.post(url, params)
            .then(response => response.data)
            .catch(error => console.error('Axios POST error:', error));
    }
};

// Example usage
// ajax.get('https://jsonplaceholder.typicode.com/todos/1')
//     .then(response => console.log(response));
//
// ajax.post('https://jsonplaceholder.typicode.com/posts', { title: 'Test', body: 'Hello' })
//     .then(response => console.log(response));
