// Function to fetch and display coffee cards
function fetchCoffeeCards(containerId) {
    // Fetch all coffee info from data
    db.ref('2/data').once('value').then(snapshot => {
        const coffees = snapshot.val();
        const container = document.getElementById(containerId);
        
        if (coffees) {
            let html = '';
            Object.values(coffees).forEach(coffee => {
                html += `
                    <div style="border:1px solid #ccc; margin:10px 0; padding:10px;">
                        <strong>Name:</strong> ${coffee.name}<br>
                        <strong>Category:</strong> ${coffee.category}<br>
                        <strong>Description:</strong> ${coffee.description}<br>
                        <strong>Price:</strong> ${coffee.fiyat}<br>
                        <strong>Stock:</strong> ${coffee.stok}<br>
                        <strong>Image:</strong> <img src="${coffee.image_url}" alt="${coffee.name}" style="max-width:200px;"><br>
                    </div>
                `;
            });
            container.innerHTML = html;
        } else {
            container.innerText = 'No coffee data found.';
        }
    });
}

// Make the function available globally
window.fetchCoffeeCards = fetchCoffeeCards;
