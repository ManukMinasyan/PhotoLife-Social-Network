require('webrtc-adapter');
window.Cookies = require('js-cookie');

import Echo from "laravel-echo"

window.io = require('socket.io-client');

window.Echo = new Echo({
    broadcaster: 'socket.io',
    host: window.location.hostname + ':6001',
    authEndpoint: '/broadcasting/auth',
    auth: {
        headers: {
            'Authorization': 'Bearer ' + localStorage.getItem('token')
        }
    }
});



try {
    window.$ = window.jQuery = require('jquery');
} catch (e) {
}

/**
 * Echo exposes an expressive API for subscribing to channels and listening
 * for events that are broadcast by Laravel. Echo and event broadcasting
 * allows your team to easily build robust real-time web applications.
 */

// import Echo from 'laravel-echo'
//
// window.Pusher = require('pusher-js');
//
// window.Echo = new Echo({
//     broadcaster: 'pusher',
//     key: process.env.MIX_PUSHER_APP_KEY,
//     cluster: process.env.MIX_PUSHER_APP_CLUSTER,
//     // encrypted: true
//     wsHost: window.location.hostname,
//     wsPort: 6001,
//     authEndpoint: '/broadcasting/auth',
//     auth: {
//         headers: {
//             'Authorization': 'Bearer ' + localStorage.getItem('token')
//         }
//     }
// });