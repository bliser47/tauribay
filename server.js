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
var battlegroundTimeout;

var messages = [];
var battlegrounds = [];


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

function sendBattlegrounds()
{
    console.log("sending battlegrounds");
    request({
        url: 'http://51.15.212.167/api/receiveBattlegrounds',
        method: 'POST',
        json: true,
        body: JSON.stringify(battlegrounds)
    }, function(error, response, body){
        if(error) {
            console.log(error)
        } else {
            console.log(response.statusCode, body);
        }
    });
    battlegrounds = [];
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
    else if ( from === "T-etu-" && text.indexOf("[BGQueue]") !== -1)
    {
        console.log("Found battleground");
        battlegrounds.push(text);
        if ( battlegroundTimeout )
        {
            clearTimeout(battlegroundTimeout);
        }
        battlegroundTimeout = setTimeout(sendBattlegrounds,1000);
    }
});

bot.addListener('error', function(message) {
    console.log('error: ', message);
});

function requestBGqueue()
{
    console.log("sent");
    bot.say("T-etu","!bg");
}

function updateIlvls()
{
    request({
        url: 'http://51.15.212.167/ilvlupdate',
        method: 'POST',
        json: true,
        body: JSON.stringify(battlegrounds)
    }, function(error, response, body){
        if(error) {
            console.log(error)
        } else {
            console.log(response.statusCode, body);
        }
    });
}

setInterval(updateIlvls, 10000);
setInterval(requestBGqueue,30000);
setTimeout(requestBGqueue,3000);