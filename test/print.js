const socket = new WebSocket('ws://localhost:8080');

socket.onopen = () => {
    console.log('Connected to print server');
    socket.send(JSON.stringify({
        business: {
            name: "Demo Store",
            address: "123 Main St, City",
            phone: "0917-123-4567",
            email: "info@demostore.com",
            website: "www.demostore.com",
            tax_id: "TIN-123456789",
            vat_id: "VAT-987654321"
        },
        receipt: {
            cashier: {
                id: "001",
                name: "Jane Doe"
            },
            products: [
                { quantity: 2, name: "Apple", cost_price: 25.00 },
                { quantity: 1, name: "Banana", cost_price: 15.50 },
                { quantity: 3, name: "Orange Juice", cost_price: 45.00 }
            ],
            total: 201.50,
            total_payment: 250.00,
            total_change: 48.50,
            total_discount: 10.00,
            total_taxes: 12.00,
            mode_of_payment: "Cash",
            reference_number: "REF123456",
            transaction_number: "TXN20240524-001",
            id: 123
        }
    }));
};
socket.onmessage = (event) => {
    console.log('Server says:', event.data);
    socket.close();
};

socket.onerror = (error) => {
    console.error('WebSocket Error:', error);
};
