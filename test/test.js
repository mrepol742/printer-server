const socket = new WebSocket('ws://localhost:8080');

socket.onopen = () => {
    console.log('Connected to print server');
    socket.send(JSON.stringify({
        type: 'print',
        data: {
            text: 'Hello, World!',
            fontSize: 12,
            fontFamily: 'Arial',
            color: '#000000',
            backgroundColor: '#FFFFFF',
            width: 80,
            height: 40,
            x: 10,
            y: 10
        }
    }));
};

socket.onmessage = (event) => {
    console.log('Server says:', event.data);
};

socket.onerror = (error) => {
    console.error('WebSocket Error:', error);
};
