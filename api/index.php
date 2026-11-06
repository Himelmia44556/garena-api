<?php

// ১. ইউআরএল থেকে ডাইনামিক uid গ্রহণ করা
$raw_uid = isset($_GET['uid']) ? $_GET['uid'] : "2413951517";

// Regular Expression ব্যবহার করে সংখ্যা ছাড়া অন্য সব অক্ষর (যেমন: l7, space বা টেক্সট) স্বয়ংক্রিয়ভাবে মুছে ফেলা
$uid = preg_replace('/[^0-9]/', '', $raw_uid);

// ফিল্টার করার পর যদি কোনো সংখ্যাই না থাকে, তবে এরর দেখাবে
if (empty($uid)) {
    header('Content-Type: application/json');
    echo json_encode(["error" => "A valid numeric UID is required."]);
    exit;
}

// ২. পোস্ট রিকোয়েস্টের জন্য পে-লোড প্রস্তুত করা (এখন এটি শুধুমাত্র বিশুদ্ধ সংখ্যা পাঠাবে)
$payload = json_encode([
    'app_id' => 100067,
    'login_id' => (string)$uid
]);

// আপনার নতুন সংগৃহীত datadome কুকির মান
$new_datadome = 'DDLRqLEhLM6~m7loNoV7iqy8RSIM68Hd91rvk216ECA7bZ7ISGMDDIeJZtMzN6mu3xkuOdDyL4Paviu3lVqDSd2~aGfwGPXPFFKmzlLq1DHtMfFS6ilZKfKYVqNHbLZT';

// আপনার স্ক্রিনশটে থাকা নতুন session_key মান
$new_session_key = 't1zr09ei6wbcluh1v1hge1asyzor6rb';

$curl = curl_init();

curl_setopt_array($curl, [
  CURLOPT_URL => 'https://shop.garena.my/api/auth/player_id_login',
  CURLOPT_RETURNTRANSFER => true,
  CURLOPT_ENCODING => '',
  CURLOPT_MAXREDIRS => 10,
  CURLOPT_TIMEOUT => 30,
  CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
  CURLOPT_CUSTOMREQUEST => 'POST',
  CURLOPT_POSTFIELDS => $payload,
  
  // আপডেট করা কুকি স্ট্রিং (নতুন datadome এবং session_key সহ)
  CURLOPT_COOKIE => 'source=mb; region=MY; language=en; mspid2=a6d640fc56b0f8fd53b9f91b43705b72; _fbp=fb.1.1786382142052.146849409287216618; session_key=' . $new_session_key . '; datadome=' . $new_datadome,
  
  CURLOPT_HTTPHEADER => [
    'User-Agent: Mozilla/5.0 (Linux; Android 16; Infinix X6858 Build/BP2A.250605.031.A3; wv) AppleWebKit/537.36 (KHTML, like Gecko) Version/4.0 Chrome/150.0.7871.181 Mobile Safari/537.36',
    'Accept: application/json, text/plain, */*',
    'Accept-Encoding: gzip, deflate, br, zstd',
    'Content-Type: application/json',
    'sec-ch-ua-platform: "Android"',
    'sec-ch-ua: "Not;A=Brand";v="8", "Chromium";v="150", "Android WebView";v="150"',
    'sec-ch-ua-mobile: ?1',
    'Origin: https://shop.garena.my',
    'X-Requested-With: mark.via.gp',
    'Sec-Fetch-Site: same-origin',
    'Sec-Fetch-Mode: cors',
    'Sec-Fetch-Dest: empty',
    'Referer: https://shop.garena.my/?app=100067&channel=202953',
    'Accept-Language: en-US,en;q=0.9',
    
    // DataDome যাচাইকরণের জন্য ক্লায়েন্ট আইডি হেডার
    'x-datadome-clientid: ' . $new_datadome,
  ],
]);

$response = curl_exec($curl);
$err = curl_error($curl);

if (PHP_VERSION_ID < 80500) {
  curl_close($curl);
}

header('Content-Type: application/json');

if ($err) {
  echo json_encode(["error" => "cURL Error #: " . $err]);
} else {
  echo $response;
}
