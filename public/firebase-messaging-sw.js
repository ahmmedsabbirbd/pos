// Give the service worker access to Firebase Messaging.
// Note that you can only use Firebase Messaging here. Other Firebase libraries
importScripts('https://www.gstatic.com/firebasejs/7.23.0/firebase-app.js');
// importScripts('https://www.gstatic.com/firebasejs/8.3.2/firebase-app.js');
importScripts('https://www.gstatic.com/firebasejs/8.3.2/firebase-messaging.js');
/*
Initialize the Firebase app in the service worker by passing in the messagingSenderId.
*/
firebase.initializeApp({
    apiKey: "AIzaSyCkj1gm3aYruqct9F6DvkshEMOBIkyECrY",
    authDomain: "notification-3b471.firebaseapp.com",
    projectId: "notification-3b471",
    storageBucket: "notification-3b471.appspot.com",
    messagingSenderId: "528992337610",
    appId: "1:528992337610:web:2872ce8029c36ffbc07e33",
    measurementId: "G-8HJ6Y9RGG1"
});

// Retrieve an instance of Firebase Messaging so that it can handle background
// messages.
const messaging = firebase.messaging();
messaging.setBackgroundMessageHandler(function(payload) {
    console.log("Message received.", payload);
    const title = payload.title;
    const options = {
        body: payload.body,
        icon: "/firebase-logo.png",
        data: {
            url: payload.data.url || "https://example.com/", // If the URL is not provided in the payload, fallback to a default URL.
        },
    };
    return self.registration.showNotification(
        title,
        options,
    );
});

self.addEventListener('notificationclick', event => {
    const urlToOpen = event.notification.data.url || "https://example.com/"; // If the URL is not provided in the notification data, fallback to a default URL.
    event.notification.close();
    event.waitUntil(clients.openWindow(urlToOpen));
});
