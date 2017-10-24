var data = new Object();
data.email = sessionStorage.getItem("email");
data.token = sessionStorage.getItem("token");
//TODO: Put variable
data.to = 'token@gmail.com';
var conn = new WebSocket(
    "ws://127.0.0.1:8080?user=" + data.email + "&token=" + data.token
);
conn.onmessage = function(e) {
    console.log(e.data); 
};

conn.onopen = function(e) {
    var button = document.getElementById('envoyer');
    button.onclick = sendMessage;
};

function sendMessage(event)
{
    event.preventDefault();
    var contenu = document.getElementById('msg').value;
    data.contenu = contenu;
    msg = JSON.stringify(data);        
    conn.send(msg);
}
