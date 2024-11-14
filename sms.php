<?php
$host = 'sql.turbosms.ua';
$database = '';
$username = '';
$password = '';
$dbConSMS = new PDO('mysql:host=' . $host . ';dbname=' . $database . ';charset=UTF8', $username, $password);

$myArray = $_POST;
$filename = 'array.txt';

$cleanData = array_pop($myArray);
$cleanData = strip_tags($cleanData);
$cleanData = str_replace('"', '', $cleanData);
$cleanData = str_replace("\\", '', $cleanData);
$cleanData = str_replace("[[", '[', $cleanData);
$cleanData = str_replace("]]", ']', $cleanData);
$cleanData = str_replace("],[", PHP_EOL, $cleanData);
$cleanData = str_replace("[", '', $cleanData);
$cleanData = str_replace("]", '', $cleanData);

$Data = str_getcsv($cleanData, "\n"); //parse the rows
$Data = array_unique($Data);

foreach ($Data as $key) {
    $oneData = str_getcsv($key, ",");

    for ($i = 0; $i < 1; $i++) {
        $flight = $oneData[3];
        $departure = $oneData[4];
        $arrival = $oneData[5];
        $airport = $oneData[6];
        $terminal = $oneData[7];
        $city = $oneData[8];
        $manager = $oneData[14];
        $phone = $oneData[12];
        $messageSelect = $oneData[15];

        if ($terminal = 'KBP - D') {
            $terminal = 'Борисполь D';
        }

        $date = date("Y-m-d H:i:s",strtotime(date("Y-m-d H:i:s")." +1 minutes"." -1 hours"));

        file_put_contents($filename, print_r($date));
        switch ($messageSelect) {
            case 1:
                $textForSMS = "Деньги получены, тур забронирован, ожидаем подтверждение";
                break;
            case 2:
                $textForSMS = 'Тур подтвержден, ожидайте звонок по готовности путевки';
                break;
            case 3:
                $textForSMS = 'Инфо по вылету: '. $departure . ', ' . $terminal . ', рейс ' .$flight;
                break;
            case 4:
                $textForSMS = 'Инфо о трансфере из отеля необходимо уточнить на ресепшн';
                break;
        }
        file_put_contents($filename, print_r($textForSMS));
        $textForSQL = "INSERT INTO vitalytour (number, sign, message, is_flash) VALUES (" . '\''. $phone . '\'' . ', ' . '\''. 'VitalyTour' . '\'' . ', \'' . $textForSMS . '\','  . '\'' . '0' . '\'' . ')';
        $insertSMS = $dbConnect->prepare($textForSQL);
        $insertSMS->execute();
        file_put_contents($filename, print_r($textForSQL));
    }
}
