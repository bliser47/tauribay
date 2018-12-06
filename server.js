var config = {
    channels: ["#trade-tauri"],
    server: "irc.tauri.hu",
    botName: "Bro"
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
        url: 'http://51.15.212.167/api/receiveData',
        method: 'POST',
        json: true,
        body: JSON.stringify(messages)
    }, function(error, response, body){
        if(error) {
            console.log(error)
        } else {
            console.log(response.statusCode, body);
        }
    });
    messages = [];
}

bot.addListener("message", function(from, to, text, message) {
    console.log(text);
    if ( from === "T-etu" ) {
        if (message["args"][0] === "#trade-tauri") {
            messages.push(text);
            if (messageTimeout) {
                clearTimeout(messageTimeout);
            }
            messageTimeout = setTimeout(sendMessages, 1000);
        }
    }
});

bot.addListener('error', function(message) {
    console.log('error: ', message);
});

function callTauriBay(url, data)
{
    request({
        url: 'http://51.15.212.167/' + url,
        method: 'POST',
        json: true,
        data : data
    }, function(error, response, body){
        if(error) {
            console.log(error)
        } else {
            console.log(response.statusCode, body);
        }
    });
}

function updateIlvls()
{
    callTauriBay('ilvlupdate');
}

var currentRealmID = 0;
function updateRaids()
{
    ++currentRealmID;
    if ( currentRealmID > 2 )
    {
        currentRealmID = 0;
    }
    callTauriBay('raidupdate', currentRealmID);
}

setInterval(updateIlvls, 10000);
setInterval(updateRaids, 10000);
