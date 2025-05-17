function loadPersonnelOrders() {
    if (!window.db) {
        console.error("Firebase DB not initialized!");
        return;
    }

    const personnelTableDisplayElements = document.querySelectorAll('#personnelView .Body3-personnel');

    personnelTableDisplayElements.forEach(tableDisplayElement => {
        const tableNum = tableDisplayElement.getAttribute('data-table-personnel');
        const titleElement = tableDisplayElement.querySelector('.Body3Title-personnel');
        const slotsContainerElement = tableDisplayElement.querySelector('.Body3Body-personnel');
        
        if(titleElement) titleElement.textContent = `MASA ${tableNum}`;
        slotsContainerElement.innerHTML = ''; // Clear previous slots before re-rendering

        // Create 4 slot divs placeholders if they don't exist
        for (let i = 1; i <= 4; i++) {
            let slotDiv = slotsContainerElement.querySelector(`.personnel-table-slot[data-slot-key="slot_${i}"]`);
            if (!slotDiv) {
                slotDiv = document.createElement('div');
                slotDiv.classList.add('personnel-table-slot');
                slotDiv.setAttribute('data-slot-key', `slot_${i}`);
                slotDiv.innerHTML = `<div class="customer-name">Slot ${i} (Boş)</div><ul class="orders-list"></ul>`;
                slotsContainerElement.appendChild(slotDiv);
            }
        }

        // Listener for Table Slots Data from "Masalar"
        const tableSlotsRef = window.db.ref(`Masalar/${tableNum}/slots`);
        tableSlotsRef.on('value', slotsSnapshot => {
            const slotsData = slotsSnapshot.val() || {};

            for (let i = 1; i <= 4; i++) {
                const slotKey = `slot_${i}`;
                const slotDiv = slotsContainerElement.querySelector(`.personnel-table-slot[data-slot-key="${slotKey}"]`);
                if (!slotDiv) continue; // Should not happen if placeholders are made correctly
                
                const customerNameDiv = slotDiv.querySelector('.customer-name');
                const ordersListUl = slotDiv.querySelector('.orders-list');
                ordersListUl.innerHTML = ''; // Clear previous orders for this slot

                const slotInfo = slotsData[slotKey];

                if (slotInfo && slotInfo.status === "occupied" && slotInfo.Name) {
                    customerNameDiv.textContent = `${slotInfo.Name} (Slot ${i})`;
                    const slotSessionID = slotInfo.Order_ID; // This is the timestamp ID for the slot session

                    if (slotSessionID) {
                        // Query orders table for items matching this slot's session ID
                        const ordersQuery = window.db.ref('orders')
                                                .orderByChild('slot_session_id')
                                                .equalTo(slotSessionID);

                        ordersQuery.on('value', ordersSnapshot => {
                            ordersListUl.innerHTML = ''; // Clear before re-populating for this slot session
                            let activeOrdersForSlotFound = false;
                            ordersSnapshot.forEach(orderChildSnapshot => {
                                const order = orderChildSnapshot.val();
                                const uniqueOrderItemKey = orderChildSnapshot.key;

                                if (order.status === 'pending' && order.slot_key === slotKey && order.masa_id === tableNum) { // Additional check for slotKey and masa_id
                                    activeOrdersForSlotFound = true;
                                    const li = document.createElement('li');
                                    li.innerHTML = `
                                        ${order.coffee_name} 
                                        <button class="deliver-button"
                                            data-order-id="${uniqueOrderItemKey}" 
                                            data-masa-id="${order.masa_id}"
                                            data-slot-key="${order.slot_key}"
                                            data-musteri-adi="${order.musteri_adi || ''}"
                                            data-kahve-adi="${order.coffee_name || ''}"
                                            data-order-timestamp="${order.timestamp || ''}"
                                            >Teslim Edildi</button>
                                    `;
                                    ordersListUl.appendChild(li);
                                }
                            });

                            if (!activeOrdersForSlotFound) {
                                ordersListUl.innerHTML = '<li>Aktif sipariş yok.</li>';
                            }
                            addDeliverButtonListeners(); 
                        });
                    } else {
                         ordersListUl.innerHTML = '<li>Oturum ID yok, siparişler izlenemiyor.</li>';
                    }
                } else {
                    customerNameDiv.textContent = `Slot ${i} (Boş)`;
                    ordersListUl.innerHTML = ''; 

                    // --- BEGIN ORPHANED ORDER CLEANUP ---
                    const currentTableIdForQuery = tableNum; // `tableNum` is like "1", "2", etc.
                    const currentSlotKeyForQuery = slotKey;  // `slotKey` is like "slot_1", "slot_2"

                    const ordersRef = window.db.ref('orders');
                    // Query by masa_id, then filter slot_key and status on the client 
                    // as Firebase RTDB only allows one orderByChild per query directly.
                    // Ensure masa_id in orders table matches format like "1", "2" if tableNum is numeric.
                    // Based on your data, order.masa_id seems to be just the number, not "MASA" prefixed.
                    const orphanedOrdersQuery = window.db.ref('orders')
                                                .orderByChild('masa_id')
                                                .equalTo(currentTableIdForQuery); // tableNum is already string e.g. "1"

                    orphanedOrdersQuery.once('value').then(ordersSnapshot => {
                        const updates = {};
                        let orphanedFound = false;
                        ordersSnapshot.forEach(orderChildSnapshot => {
                            const order = orderChildSnapshot.val();
                            // Check if it matches the current EMPTY slot and is pending
                            if (order.slot_key === currentSlotKeyForQuery && order.status === 'pending') {
                                console.log(`Orphaned order found for MASA ${currentTableIdForQuery}, Slot ${currentSlotKeyForQuery}: ${orderChildSnapshot.key}. Marking for deletion.`);
                                updates[`orders/${orderChildSnapshot.key}`] = null;
                                orphanedFound = true;
                            }
                        });
                        if (orphanedFound) {
                            window.db.ref().update(updates).then(() => {
                                console.log(`Orphaned orders for MASA ${currentTableIdForQuery}, Slot ${currentSlotKeyForQuery} deleted.`);
                            }).catch(err => {
                                console.error(`Error deleting orphaned orders for MASA ${currentTableIdForQuery}, Slot ${currentSlotKeyForQuery}:`, err);
                            });
                        }
                    }).catch(error => {
                        console.error(`Error querying for orphaned orders for MASA ${currentTableIdForQuery}, Slot ${currentSlotKeyForQuery}:`, error);
                    });
                    // --- END ORPHANED ORDER CLEANUP ---
                }
            }
        });
    });
}

function addDeliverButtonListeners() {
    document.querySelectorAll('#personnelView .deliver-button').forEach(button => {
        const newButton = button.cloneNode(true); 
        button.parentNode.replaceChild(newButton, button);

        newButton.addEventListener('click', () => {
            const uniqueOrderItemKey = newButton.dataset.orderId;
            const masaId = newButton.dataset.masaId; 
            const slotKey = newButton.dataset.slotKey;
            const musteriAdi = newButton.dataset.musteriAdi;
            const kahveAdi = newButton.dataset.kahveAdi;
            const orderTimestamp = newButton.dataset.orderTimestamp ? parseInt(newButton.dataset.orderTimestamp) : Date.now();

            console.log(`Teslim ediliyor: Sipariş Kalemi ID ${uniqueOrderItemKey} for Masa ${masaId}, Slot ${slotKey}`);

            const pastOrdersRef = window.db.ref('past_orders').push();
            const pastOrderData = {
                original_order_id: uniqueOrderItemKey, 
                masa_id: masaId,
                slot_key: slotKey,
                musteri_adi: musteriAdi,
                kahve_adi: kahveAdi,
                siparis_tarihi_saati: new Date(orderTimestamp).toLocaleString('tr-TR', { timeZone: 'Europe/Istanbul' }),
                teslim_tarihi_saati: new Date().toLocaleString('tr-TR', { timeZone: 'Europe/Istanbul' }) 
            };

            pastOrdersRef.set(pastOrderData)
            .then(() => {
                console.log(`Sipariş kalemi ${uniqueOrderItemKey} geçmiş siparişlere taşındı.`);
                return window.db.ref(`orders/${uniqueOrderItemKey}`).remove(); 
            })
            .then(() => {
                console.log(`Sipariş kalemi ${uniqueOrderItemKey} 'orders' tablosundan silindi.`);
            })
            .catch(error => {
                console.error("Sipariş teslim edilirken veya silinirken hata: ", error);
                alert("Sipariş işlenirken bir hata oluştu.");
            });
        });
    });
}