var data = new Object();
data.email = sessionStorage.getItem("email");
data.token = sessionStorage.getItem("token");

var conn = new WebSocket(
    "ws://127.0.0.1:8080?user=" + data.email + "&token=" + data.token
);

conn.onmessage = function(e) {
    var date = new Date();
    msg = '<p>' + date + ' : ' + JSON.parse(e.data).msg + '</p>';
    var newDiv = document.createElement("p");
    var newLine = document.createElement("br");
    var newContent = document.createTextNode(date + ' : ' + JSON.parse(e.data).msg);
    document.getElementById('chat-frame').appendChild(newContent);
    document.getElementById('chat-frame').appendChild(newLine);
};

conn.onopen = function(e) {
    var button = document.getElementById('envoyer');
    button.onclick = sendMessage;
};

function sendMessage(event)
{
    event.preventDefault();
    var contenu = document.getElementById('msg').value;
    var to = document.getElementById('to').value;
    data.to = to;
    data.msg = contenu;
    msg = JSON.stringify(data);        
    conn.send(msg);
}
