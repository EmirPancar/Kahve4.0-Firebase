// Initialize Firebase
const firebaseConfig = {
  apiKey: "AIzaSyCzOskUf-1C64jzKpN79CEpu_lLQZI726c",
  authDomain: "kahve-4.firebaseapp.com",
  databaseURL: "https://kahve-4-default-rtdb.europe-west1.firebasedatabase.app",
  projectId: "kahve-4",
  storageBucket: "kahve-4.appspot.com",
  messagingSenderId: "588984824423",
  appId: "1:588984824423:web:565bc9dc750533c3c6dac2"
};

// Initialize Firebase
firebase.initializeApp(firebaseConfig);

// Get a reference to the database
const db = firebase.database();

// Make db available globally
window.db = db;
