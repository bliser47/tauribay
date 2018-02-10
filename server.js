var config = {
    channels: ["#trade-tauri"],
    server: "irc.tauri.hu",
    botName: "Bliserb"
};

var irc = require("irc");
var request = require('request');

// Create the bot name
var bot = new irc.Client(config.server, config.botName, {
    channels: config.channels
});

var messageTimeout;
var messages = [];


function sendMessages()
{
    request({
        url: 'http://http://51.15.212.167/api/receiveData',
        method: 'POST',
        json: true,
        body: JSON.stringify(messages)
    }, function(error, response, body){
        if(error) {
            console.log("Error inserting!!!");
        } else {
            console.log(response.statusCode, body);
        }
    });
    messages = [];
}

bot.addListener("message", function(from, to, text, message) {
    if ( from == "T-etu" && message["args"][0] == "#trade-tauri" )
    {
        messages.push(text);
        if ( messageTimeout )
        {
            clearTimeout(messageTimeout);
        }
        messageTimeout = setTimeout(sendMessages,1000);
    }
});