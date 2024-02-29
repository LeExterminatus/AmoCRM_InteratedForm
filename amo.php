<?php
header('content-type: application/json');
require_once 'access.php';

$custom_field_value_two = (bool) $_POST['Timer'];
$name = $_POST['UserName'];
$phone = $_POST['UserPhone'];
$email = $_POST['UserMail'];
$price = $_POST['Price'];
$company = 'Компания'.mt_rand(5, 15);
$custom_field_id_two = "728863";
$pipeline_id = 7858038;

$data = [
    [
        
        "name" => $phone,
        "price" => (int) $price,
        "pipeline_id" => (int) $pipeline_id,
        "custom_fields_values" => [
            [
                "field_id"=> (int) $custom_field_id_two,
                "values"=> [
                    [
                        "value"=> $custom_field_value_two
                    ]
                ]
            ]
        ],
        "_embedded" => [
            "contacts" => [
                [
                    "first_name" => $name,
                    "custom_fields_values" => [
                        [
                            "field_code" => "EMAIL",
                            "values" => [
                                [
                                    "enum_code" => "WORK",
                                    "value" => $email
                                ]
                            ]
                        ],
                        [
                            "field_code" => "PHONE",
                            "values" => [
                                [
                                    "enum_code" => "WORK",
                                    "value" => $phone
                                ]
                            ]
                        ]
                    ]
                ]
            ],
            "companies" => [
                [
                    "name" => $company
                ]
            ]
        ]
    ]
];
/*echo json_encode(var_dump($data));
exit;
*/
$method = "/api/v4/leads/complex";

$headers = [
    'Content-Type: application/json',
    'Authorization: Bearer ' . $access_token,
];

$curl = curl_init();
curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
curl_setopt($curl, CURLOPT_USERAGENT, 'amoCRM-API-client/1.0');
curl_setopt($curl, CURLOPT_URL, "https://".$subdomain.".amocrm.ru".$method);
curl_setopt($curl, CURLOPT_POSTFIELDS, json_encode($data));
curl_setopt($curl, CURLOPT_HTTPHEADER, $headers);
curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, 0);
curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, 0);
$out = curl_exec($curl);
$code = curl_getinfo($curl, CURLINFO_HTTP_CODE);
curl_close($curl);

$errors = [
	400 => 'Отправленные данные не соответствуют требованияю формы',
	401 => 'Требуется авторизация',
	403 => 'Доступ запрещен',
	404 => 'Сервис не найден',
	500 => 'Внутренняя ошибка сервиса',
	502 => 'Проблемы с шлюзом',
	503 => 'Сервис не доступен',
];
if ($code < 200 || $code > 204) 
{
    echo json_encode($code.$errors[$code]);
}
else
{
    echo json_encode(1);
}
?>