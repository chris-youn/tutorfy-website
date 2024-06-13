document.addEventListener('DOMContentLoaded', function() {
    const canvas = document.getElementById('whiteboard');
    const ctx = canvas.getContext('2d');
    let drawing = false;

    canvas.addEventListener('mousedown', (e) => {
        drawing = true;
        ctx.moveTo(e.clientX - canvas.offsetLeft, e.clientY - canvas.offsetTop);
    });

    canvas.addEventListener('mousemove', (e) => {
        if (drawing) {
            ctx.lineTo(e.clientX - canvas.offsetLeft, e.clientY - canvas.offsetTop);
            ctx.stroke();
        }
    });

    canvas.addEventListener('mouseup', () => {
        drawing = false;
    });

    canvas.addEventListener('mouseout', () => {
        drawing = false;
    });
});

const socket = new WebSocket('ws://your-server-address');

socket.onopen = function() {
    console.log('WebSocket is connected.');
};

socket.onmessage = function(event) {
    const data = JSON.parse(event.data);
    if (data.type === 'draw') {
        drawOnCanvas(data.x, data.y);
    }
};

canvas.addEventListener('mousemove', (e) => {
    if (drawing) {
        const data = {
            type: 'draw',
            x: e.clientX - canvas.offsetLeft,
            y: e.clientY - canvas.offsetTop
        };
        socket.send(JSON.stringify(data));
    }
});

function drawOnCanvas(x, y) {
    ctx.lineTo(x, y);
    ctx.stroke();
}

function saveSession() {
    const sessionData = canvas.toDataURL();
    fetch('../whiteboard/save_session.php', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json'
        },
        body: JSON.stringify({ sessionData })
    }).then(response => response.json())
    .then(data => {
        if (data.status === 'success') {
            alert('Session saved successfully');
        } else {
            alert('Error saving session: ' + data.message);
        }
    });
}

function loadSession() {
    fetch('../whiteboard/get_session.php')
    .then(response => response.json())
    .then(data => {
        if (data.status === 'success') {
            const img = new Image();
            img.src = data.sessionData;
            img.onload = () => {
                ctx.drawImage(img, 0, 0);
            };
        } else {
            alert('Error loading session: ' + data.message);
        }
    });
}
