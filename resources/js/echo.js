import Echo from 'laravel-echo';
import Pusher from 'pusher-js';

window.Pusher = Pusher;

// Initialize Laravel Echo instance
window.Echo = new Echo({
    broadcaster: 'pusher',
    key: 'ab556ecb9e3f5f07f3c2',
    cluster: 'eu',
    forceTLS: true
});

// Use the Echo instance to listen to a channel
const channel = window.Echo.channel('my-channel');
channel.listen('.my-event', function(data) {
    alert(JSON.stringify(data));
});
