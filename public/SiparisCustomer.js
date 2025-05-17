function initializeCustomerPanel() {
    console.log("Initializing customer panel UI and event listeners...");
    const tables = document.querySelectorAll("#customerView .Body1"); 
    const orderForm = document.getElementById("orderForm"); 
    const goBackButton = document.getElementById("goBackButton"); 

    tables.forEach(table => {
        table.addEventListener("click", (event) => {
            event.preventDefault();
            const clickedMasaNumarasi = table.getAttribute("data-table");
            const currentUser = firebase.auth().currentUser;

            if (currentUser) {
                const userId = currentUser.uid;
                firebase.database().ref('users/' + userId).once('value').then(userSnapshot => {
                    const userData = userSnapshot.val();
                    const customerName = userData ? userData.name : (currentUser.displayName || "Bilinmeyen Kullanıcı");
                    
                    const tableSlotsRef = firebase.database().ref(`Masalar/${clickedMasaNumarasi}/slots`);
                    tableSlotsRef.once('value').then(slotsSnapshot => {
                        let assignedSlotKey = null;
                        let slotsData = slotsSnapshot.val() || {}; // Initialize if no slots exist yet

                        // Ensure all 4 slots are defined for iteration, default to available if not present
                        for (let i = 1; i <= 4; i++) {
                            const slotKey = `slot_${i}`;
                            if (!slotsData[slotKey]) {
                                slotsData[slotKey] = { status: "available" }; 
                            }
                        }

                        for (let i = 1; i <= 4; i++) {
                            const slotKey = `slot_${i}`;
                            if (slotsData[slotKey] && (slotsData[slotKey].status === "available" || slotsData[slotKey].status === null || !slotsData[slotKey].Name)) {
                                assignedSlotKey = slotKey;
                                break;
                            }
                        }

                        if (assignedSlotKey) {
                            window.activeMasaNumarasi = clickedMasaNumarasi;
                            window.activeMusteriAdi = customerName;
                            window.activeMusteriId = userId; // Still useful for client-side logic
                            window.activeSlotKey = assignedSlotKey;
                            window.activeSlotOrderID = Date.now(); // Timestamp for this slot session

                            console.log(`Masa ${window.activeMasaNumarasi}, Slot ${window.activeSlotKey} seçildi. Müşteri: ${window.activeMusteriAdi}. Slot Order_ID: ${window.activeSlotOrderID}`);

                            const slotRef = firebase.database().ref(`Masalar/${window.activeMasaNumarasi}/slots/${window.activeSlotKey}`);
                            slotRef.set({
                                Name: window.activeMusteriAdi,
                                Order_ID: window.activeSlotOrderID,
                                status: "occupied"
                            }).then(() => {
                                console.log(`Masalar/${window.activeMasaNumarasi}/slots/${window.activeSlotKey} güncellendi.`);
                                // Update overall table status (optional)
                                firebase.database().ref(`Masalar/${window.activeMasaNumarasi}/overall_status`).set("partially_occupied"); 
                            }).catch(error => {
                                console.error(`Masalar slot güncellenirken hata: `, error);
                            });

                            tables.forEach(t => t.classList.add("hidden"));
                            if (orderForm) orderForm.classList.add("active");
                            if (goBackButton) goBackButton.classList.add("visible");
                            if (typeof loadCoffees === 'function') loadCoffees('Sıcak Kahve');
                        } else {
                            alert("Bu masa şu anda tamamen dolu.");
                        }
                    }).catch(error => {
                        console.error("Masa slotları okunurken hata: ", error);
                        alert("Masa durumu kontrol edilirken bir hata oluştu.");
                    });
                }).catch(error => {
                    console.error("Kullanıcı bilgileri alınırken hata: ", error);
                    alert("Kullanıcı bilgileri alınamadı.");
                });
            } else {
                alert("Lütfen giriş yapınız.");
                window.location.href = 'Giris.html';
            }
        });
    });

    if (goBackButton) {
        goBackButton.addEventListener("click", () => {
            if(window.activeMasaNumarasi && window.activeSlotKey){
                 const slotRefToClear = firebase.database().ref(`Masalar/${window.activeMasaNumarasi}/slots/${window.activeSlotKey}`);
                 slotRefToClear.set({
                    Name: null, 
                    Order_ID: null, 
                    status: "available"
                 }).then(() => {
                    console.log(`Masalar/${window.activeMasaNumarasi}/slots/${window.activeSlotKey} temizlendi (geri tuşu).`);
                    // Optionally, check if all slots are now available and update overall_status
                 }).catch(error => {
                    console.error(`Slot temizlenirken hata: `, error);
                 });
            }
            tables.forEach(t => t.classList.remove("hidden"));
            if (orderForm) orderForm.classList.remove("active");
            if (goBackButton) goBackButton.classList.remove("visible");
            window.activeMasaNumarasi = null;
            window.activeMusteriId = null; 
            window.activeMusteriAdi = null;
            window.activeSlotKey = null;
            window.activeSlotOrderID = null; 
        });
    }
}