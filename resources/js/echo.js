import Echo from 'laravel-echo';
import Pusher from 'pusher-js';
window.Pusher = Pusher;

window.Echo = new Echo({
    broadcaster: 'pusher',
    key: import.meta.env.VITE_PUSHER_APP_KEY,
    wsHost: import.meta.env.VITE_PUSHER_HOST,
    wsPort: import.meta.env.VITE_PUSHER_PORT,
    wssPort: import.meta.env.VITE_PUSHER_PORT,
    cluster:import.meta.env.VITE_PUSHER_APP_CLUSTER,
    forceTLS: false,
    encrypted: true,
    disableStats: true,
    enabledTransports: ['ws', 'wss'],
});
console.log(import.meta.env.VITE_PUSHER_HOST);
console.log(import.meta.env.VITE_PUSHER_HOST);
console.log(import.meta.env.VITE_PUSHER_HOST);
