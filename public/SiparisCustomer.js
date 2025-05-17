let customerOrdersListenerRef = null; // Store listener reference globally within this scope

function displayCustomerActiveOrders(slotSessionID) {
    const ordersListUl = document.getElementById('customerOrdersList');
    const activeOrdersContainer = document.getElementById('customerActiveOrders');

    if (!ordersListUl || !activeOrdersContainer) {
        console.error("Customer orders display elements not found.");
        return;
    }

    // Detach any previous listener for customer orders
    if (customerOrdersListenerRef) {
        customerOrdersListenerRef.off();
    }

    console.log(`Müşteri için aktif siparişler izleniyor: Slot Oturum ID ${slotSessionID}`);
    customerOrdersListenerRef = window.db.ref('orders')
                                .orderByChild('slot_session_id')
                                .equalTo(slotSessionID);

    customerOrdersListenerRef.on('value', snapshot => {
        ordersListUl.innerHTML = ''; // Clear previous list
        let hasPendingOrders = false;
        snapshot.forEach(childSnapshot => {
            const order = childSnapshot.val();
            if (order.status === 'pending') {
                hasPendingOrders = true;
                const li = document.createElement('li');
                li.textContent = `${order.coffee_name} - Durum: Bekleniyor`; // Customize as needed
                ordersListUl.appendChild(li);
            }
        });

        if (hasPendingOrders) {
            activeOrdersContainer.style.display = 'block';
        } else {
            ordersListUl.innerHTML = '<li>Aktif siparişiniz bulunmamaktadır.</li>';
            // Keep container visible to show "no orders" message, or hide it:
            // activeOrdersContainer.style.display = 'none'; 
        }
    });
}

function clearSlotAndCancelOrders(callback) {
    const slotSessionIDToCancel = window.activeSlotOrderID;
    const masaNumToClear = window.activeMasaNumarasi;
    const slotKeyToClear = window.activeSlotKey;

    const activeOrdersContainer = document.getElementById('customerActiveOrders');
    const ordersListUl = document.getElementById('customerOrdersList');

    // Detach customer's own order listener
    if (customerOrdersListenerRef) {
        customerOrdersListenerRef.off();
        customerOrdersListenerRef = null;
    }
    if(activeOrdersContainer) activeOrdersContainer.style.display = 'none';
    if(ordersListUl) ordersListUl.innerHTML = '';

    if (!masaNumToClear || !slotKeyToClear || !slotSessionIDToCancel) {
        console.log("Aktif masa/slot/oturum ID yok, sipariş iptali/slot temizleme atlanıyor.");
        if(callback) callback();
        return;
    }

    console.log(`Slot ${slotKeyToClear} (Oturum ID: ${slotSessionIDToCancel}) için bekleyen siparişler iptal ediliyor.`);
    const ordersQuery = window.db.ref('orders')
                            .orderByChild('slot_session_id')
                            .equalTo(slotSessionIDToCancel);

    ordersQuery.once('value').then(snapshot => {
        const updates = {};
        snapshot.forEach(childSnapshot => {
            const order = childSnapshot.val();
            if (order.status === 'pending') {
                updates[`orders/${childSnapshot.key}`] = null; // Mark for deletion
            }
        });
        return window.db.ref().update(updates); // Perform bulk deletion
    }).then(() => {
        console.log(`Bekleyen siparişler (Oturum ID: ${slotSessionIDToCancel}) silindi.`);
        // Now clear the slot in Masalar
        const slotRef = firebase.database().ref(`Masalar/${masaNumToClear}/slots/${slotKeyToClear}`);
        return slotRef.set({
            Name: null,
            Order_ID: null,
            status: "available"
        });
    }).then(() => {
        console.log(`Masalar/${masaNumToClear}/slots/${slotKeyToClear} temizlendi.`);
        
        // ---- NEW: Additional cleanup for any other orphaned orders in this specific slot ----
        console.log(`Performing additional check for orphaned orders in Masalar/${masaNumToClear}/slots/${slotKeyToClear}...`);
        const orphanedOrdersQuery = firebase.database().ref('orders')
                                    .orderByChild('masa_id') // Query by masa_id
                                    .equalTo(masaNumToClear); // masaNumToClear is already the table number like "1"

        return orphanedOrdersQuery.once('value').then(ordersSnapshot => {
            const updates = {};
            let orphanedFound = false;
            ordersSnapshot.forEach(orderChildSnapshot => {
                const order = orderChildSnapshot.val();
                // Must match the specific slot_key AND be pending
                if (order.slot_key === slotKeyToClear && order.status === 'pending') {
                    console.log(`Additional orphaned order found for MASA ${masaNumToClear}, Slot ${slotKeyToClear}: ${orderChildSnapshot.key}. Marking for deletion.`);
                    updates[`orders/${orderChildSnapshot.key}`] = null;
                    orphanedFound = true;
                }
            });
            if (orphanedFound) {
                return firebase.database().ref().update(updates).then(() => {
                    console.log(`Additional orphaned orders for MASA ${masaNumToClear}, Slot ${slotKeyToClear} deleted.`);
                });
            }
            // Ensure a promise is returned even if no orphaned orders are found to continue the chain
            return Promise.resolve(); 
        });
        // ---- END NEW ----
    }).then(() => { // This .then() is chained after the additional cleanup or the slot clearing if no additional cleanup was needed.
        if(callback) callback();
    }).catch(error => {
        console.error("Sipariş iptali veya slot temizleme sırasında hata: ", error);
        if(callback) callback(); // Ensure callback is called even on error
    });
}

function initializeCustomerPanel() {
    console.log("Initializing customer panel UI and event listeners...");
    const tables = document.querySelectorAll("#customerView .Body1.custom-table-layout"); 
    const orderForm = document.getElementById("orderForm"); 
    const goBackButton = document.getElementById("goBackButton"); 
    const customerActiveOrdersContainer = document.getElementById('customerActiveOrders');

    // Hide active orders container initially until a table is chosen and orders are loaded
    if (customerActiveOrdersContainer) customerActiveOrdersContainer.style.display = 'none';

    tables.forEach(table => {
        const tableNum = table.getAttribute("data-table");
        const slotsElements = {
            slot_1: table.querySelector('.slot-top[data-slot-key="slot_1"]'),
            slot_2: table.querySelector('.slot-bottom[data-slot-key="slot_2"]'),
            slot_3: table.querySelector('.slot-left[data-slot-key="slot_3"]'),
            slot_4: table.querySelector('.slot-right[data-slot-key="slot_4"]')
        };

        // Set up Firebase listener for slots of this table
        const tableSlotsRef = firebase.database().ref(`Masalar/${tableNum}/slots`);
        tableSlotsRef.on('value', slotsSnapshot => {
            const slotsData = slotsSnapshot.val() || {};
            ['slot_1', 'slot_2', 'slot_3', 'slot_4'].forEach(slotKey => {
                const slotDiv = slotsElements[slotKey];
                if (!slotDiv) return; // Should not happen if HTML is correct

                const slotInfo = slotsData[slotKey];
                if (slotInfo && slotInfo.status === "occupied" && slotInfo.Name) {
                    slotDiv.textContent = slotInfo.Name;
                    slotDiv.classList.add('occupied');
                    slotDiv.classList.remove('available');
                } else {
                    slotDiv.textContent = "BOŞ";
                    slotDiv.classList.add('available');
                    slotDiv.classList.remove('occupied');
                }
            });
        });

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
                        let slotsData = slotsSnapshot.val() || {};
                        for (let i = 1; i <= 4; i++) {
                            const slotKey = `slot_${i}`;
                            if (!slotsData[slotKey]) slotsData[slotKey] = { status: "available" }; 
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
                            window.activeMusteriId = userId; 
                            window.activeSlotKey = assignedSlotKey;
                            window.activeSlotOrderID = Date.now(); 

                            console.log(`Masa ${window.activeMasaNumarasi}, Slot ${window.activeSlotKey} seçildi. Müşteri: ${window.activeMusteriAdi}. Slot Order_ID: ${window.activeSlotOrderID}`);
                            // Display active orders for this new session
                            if (customerActiveOrdersContainer) customerActiveOrdersContainer.style.display = 'block'; // Show it now
                            displayCustomerActiveOrders(window.activeSlotOrderID); 

                            const slotRef = firebase.database().ref(`Masalar/${window.activeMasaNumarasi}/slots/${window.activeSlotKey}`);
                            slotRef.set({
                                Name: window.activeMusteriAdi,
                                Order_ID: window.activeSlotOrderID,
                                status: "occupied"
                            }).then(() => {
                                console.log(`Masalar/${window.activeMasaNumarasi}/slots/${window.activeSlotKey} güncellendi.`);
                                firebase.database().ref(`Masalar/${window.activeMasaNumarasi}/overall_status`).set("partially_occupied"); 
                            }).catch(error => console.error(`Masalar slot güncellenirken hata: `, error));

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
            clearSlotAndCancelOrders(() => {
                tables.forEach(t => t.classList.remove("hidden"));
                if (orderForm) orderForm.classList.remove("active");
                if (goBackButton) goBackButton.classList.remove("visible");
                if (customerActiveOrdersContainer) customerActiveOrdersContainer.style.display = 'none'; // Hide when going back
                
                window.activeMasaNumarasi = null;
                window.activeMusteriId = null; 
                window.activeMusteriAdi = null;
                window.activeSlotKey = null;
                window.activeSlotOrderID = null; 
            });
        });
    }
}