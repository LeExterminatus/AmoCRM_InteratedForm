<?php
require_once 'config.php';

$dataToken = file_get_contents($token_file);
$dataToken = json_decode($dataToken, true);

if ($dataToken["endTokenTime"] - 60 < time()) 
{

    $link = "https://$subdomain.amocrm.ru/oauth2/access_token";

    $data = [
        'client_id'     => $client_id,
        'client_secret' => $client_secret,
        'grant_type'    => 'refresh_token',
        'refresh_token' => $dataToken["refresh_token"],
        'redirect_uri'  => $redirect_uri,
    ];

    $curl = curl_init();
    curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($curl, CURLOPT_USERAGENT, 'amoCRM-oAuth-client/1.0');
    curl_setopt($curl, CURLOPT_URL, $link);
    curl_setopt($curl, CURLOPT_HTTPHEADER, ['Content-Type:application/json']);
    curl_setopt($curl, CURLOPT_HEADER, false);
    curl_setopt($curl, CURLOPT_CUSTOMREQUEST, 'POST');
    curl_setopt($curl, CURLOPT_POSTFIELDS, json_encode($data));
    curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, 1);
    curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, 2);
    $out = curl_exec($curl);
    $code = curl_getinfo($curl, CURLINFO_HTTP_CODE);
    curl_close($curl);

    $response = json_decode($out, true);

    $arrParamsAmo = [
        "access_token"  => $response['access_token'],
        "refresh_token" => $response['refresh_token'],
        "token_type"    => $response['token_type'],
        "expires_in"    => $response['expires_in'],
        "endTokenTime"  => $response['expires_in'] + time(),
    ];

    $arrParamsAmo = json_encode($arrParamsAmo);

    $f = fopen($token_file, 'w');
    fwrite($f, $arrParamsAmo);
    fclose($f);

    $access_token = $response['access_token'];

} 
else 
{
    $access_token = $dataToken["access_token"];
}
?>