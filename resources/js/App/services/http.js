import axios from 'axios'

let baseDomain = window.location.protocol + '//' + window.location.hostname + '/api/v1';
const baseURL = `${baseDomain}`;

const instance = axios.create({
    baseURL,
    headers: {
        'Accept': 'application/json',
        'Content-Type': 'application/json',
        'X-CSRF-TOKEN': document.head.querySelector('meta[name="csrf-token"]').content,
        Authorization: localStorage.getItem('token_type') + ' ' + localStorage.getItem('token')
    }
});


// apply interceptor on response
instance.interceptors.response.use(function (response) {
    // Do something with response data
    return response;
}, function (error) {
    // Do something with response error
    return Promise.reject(error);
});

export default instance;