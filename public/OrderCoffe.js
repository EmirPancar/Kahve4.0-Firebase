function orderCoffee(coffeeId, coffeeName) {
    if (!window.db) {
        console.error("Firebase DB not initialized for ordering!");
        alert("Sipariş verilirken veritabanı bağlantı hatası!");
        return;
    }
    if (!window.activeMasaNumarasi || !window.activeSlotKey) { 
        alert("Lütfen önce bir masa ve slot seçin!");
        return;
    }
    if (!window.activeSlotOrderID) { // This is the Slot Session ID (timestamp)
        alert("Aktif Slot Oturum ID bulunamadı. Lütfen masayı tekrar seçin.");
        return;
    }
    if (!window.activeMusteriAdi) { 
        alert("Müşteri adı eksik. Lütfen masayı tekrar seçin.");
        return;
    }

    const newOrderRef = window.db.ref('orders').push(); // Generate a new unique key for each order item

    newOrderRef.set({
        masa_id: window.activeMasaNumarasi, 
        slot_key: window.activeSlotKey, 
        slot_session_id: window.activeSlotOrderID, // Link to the customer's session in the slot
        coffee_id: coffeeId,
        coffee_name: coffeeName,
        musteri_adi: window.activeMusteriAdi, 
        timestamp: firebase.database.ServerValue.TIMESTAMP, // Actual order time
        status: "pending"
    }).then(() => {
        alert(`Sipariş (${coffeeName}) Firebase'e eklendi (Yeni Sipariş ID: ${newOrderRef.key}).`);
    }).catch(error => {
        console.error("Sipariş Firebase'e eklenirken hata oluştu: ", error);
        alert("Sipariş sırasında hata oluştu: " + error.message);
    });
}