function loadCoffees(categoryFilter) {
    if (!window.db) {
        console.error("Firebase DB not initialized!");
        alert("Veritabanı bağlantı hatası!");
        return;
    }
    const coffeeRef = window.db.ref('2/data'); // Path to coffee data
    coffeeRef.once('value').then(snapshot => {
        const coffeesArray = snapshot.val(); // Data at '2/data' is an array
        const tbody = document.querySelector("#coffeeTable tbody");
        
        if (!tbody) {
            console.error("#coffeeTable tbody not found!");
            return;
        }
        tbody.innerHTML = ""; // Clear previous data

        if (coffeesArray && Array.isArray(coffeesArray)) {
            let itemsFound = false;
            coffeesArray.forEach(coffee => {
                if (coffee && coffee.page === categoryFilter) { // Filter by coffee.page
                    itemsFound = true;
                    const coffeeItemId = coffee.id; // Use the id from your data
                    const escapedCoffeeName = coffee.name ? coffee.name.replace(/'/g, "\\'").replace(/"/g, "&quot;") : "İsimsiz Ürün";

                    const row = document.createElement("tr");
                    row.innerHTML = `
                        <td><img src="${coffee.image_url || 'Gorsel/placeholder.png'}" alt="${escapedCoffeeName}" width="50" onerror="this.onerror=null;this.src='Gorsel/placeholder.png';"></td>
                        <td>${coffee.name || 'İsimsiz Ürün'}</td>
                        <td>${coffee.fiyat !== undefined ? coffee.fiyat : 'N/A'} TL</td>
                        <td><button class="SipBut" onclick="orderCoffee('${coffeeItemId}', '${escapedCoffeeName}')">Sipariş Ver</button></td>
                    `;
                    tbody.appendChild(row);
                }
            });

            if (!itemsFound) {
                tbody.innerHTML = '<tr><td colspan="4">Bu kategoride ürün bulunmamaktadır.</td></tr>';
            }
        } else {
            console.log("No coffee data found or data is not an array.", coffeesArray);
            tbody.innerHTML = '<tr><td colspan="4">Ürün bulunmamaktadır veya veri formatı hatalı.</td></tr>';
        }
    }).catch(error => {
        console.error('Kahveler Firebase\'den yüklenirken hata oluştu:', error);
        alert('Ürünler yüklenirken bir hata oluştu.');
        const tbodyOnError = document.querySelector("#coffeeTable tbody");
        if (tbodyOnError) tbodyOnError.innerHTML = '<tr><td colspan="4">Ürünler yüklenemedi.</td></tr>';
    });
}