<?php

/** Параметры подключения. Подключаемся к базе.*/
$host = '';
$database = '';
$username = '';
$password = '';
$dbCon = new PDO('dblib:charset=UTF-8;host=' . $host . ';database=' . $database, $username, $password);

/** Запросы */

/** Здесь запрашиваем отправленные сообщения */
$querySendSMS = "SELECT
  COUNT(*) AS expr1

 ,prereservationstatus.name AS name

FROM dbo.prereservation
INNER JOIN dbo.prereservationstatus
  ON prereservation.prereservationstatusid = prereservationstatus.id
INNER JOIN dbo.recipient
  ON prereservation.humanid = recipient.id
";


?>
<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="robots" content="noindex, nofollow">
    <title>Отчеты</title>
    <!-- 1. Подключить CSS-файл Bootstrap 3 -->
    <link rel="stylesheet" href="css/bootstrap.min.css"/>
    <!-- 2. Подключить библиотеку jQuery -->
    <script src="https://code.jquery.com/jquery-3.4.1.min.js"
            integrity="sha256-CSXorXvZcTkaix6Yvo6HppcZGetbYMGWSFlBw8HfCJo=" crossorigin="anonymous"></script
            <!-- 3. Подключить библиотеку moment -->
    <script src="js/moment-with-locales.min.js"></script>
    <!-- 4. Подключить js-файл фреймворка Bootstrap 3 -->
    <script src="js/bootstrap.min.js"></script>
    <!-- 5. Подключить DataTable -->
    <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.10.20/css/jquery.dataTables.css">
    <script type="text/javascript" charset="utf8"
            src="https://cdn.datatables.net/1.10.20/js/jquery.dataTables.js"></script>
    <script src="https://code.jquery.com/ui/1.12.0/jquery-ui.min.js"
            integrity="sha256-eGE6blurk5sHj+rmkfsGYeKyZx3M4bG+ZlFyA7Kns7E=" crossorigin="anonymous"></script>
    <link rel="stylesheet" type="text/css"
          href="https://cdn.jsdelivr.net/bootstrap.daterangepicker/2/daterangepicker.css">
    <script src="https://cdn.datatables.net/buttons/1.2.2/js/buttons.html5.js"></script>
    <link rel="stylesheet" type="text/css"
          href="https://cdn.datatables.net/buttons/1.6.1/css/buttons.dataTables.css">
    <script src="https://cdn.datatables.net/select/1.3.1/css/select.dataTables.css"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.32/pdfmake.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.32/vfs_fonts.js"></script>

    <link href="https://cdn.datatables.net/buttons/1.5.1/css/buttons.dataTables.css" rel="stylesheet" type="text/css"/>
    <script src="https://cdn.datatables.net/buttons/1.5.1/js/dataTables.buttons.js"></script>
    <script src="https://cdn.datatables.net/buttons/1.5.1/js/buttons.colVis.min.js"></script>
    <script type="text/javascript" language="javascript"
            src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.1.3/jszip.min.js"></script>

    <script src="https://cdn.datatables.net/buttons/1.5.1/js/buttons.colVis.min.js"></script>
    <script src="https://cdn.datatables.net/buttons/1.5.2/js/buttons.print.min.js"></script>
    <link rel="stylesheet" href="//cdnjs.cloudflare.com/ajax/libs/morris.js/0.5.1/morris.css">
    <script src="//cdnjs.cloudflare.com/ajax/libs/raphael/2.1.0/raphael-min.js"></script>
    <script src="//cdnjs.cloudflare.com/ajax/libs/morris.js/0.5.1/morris.min.js"></script>

    <style>
        .container {
            width: 90%;
        }
    </style>

</head>
<body>
<div class="container">

    <h3 class="text-uppercase text-center">Отчет по продажам</h3>
    <form method="post" action="" id="deals_report" name="deals_report" target="_self">
        <div class="col-lg-2 col-md-12 col-xs-12">
            <div class="form-group">
                <h4 id="datefrom" class="text-center">Дата с</h4>
                <input type="text" class="form-control" name="dealsReportDateFrom" id="dealsReportDateFrom">
            </div>
        </div>
        <div class="col-lg-2 col-md-12 col-xs-12">
            <div class="form-group">
                <h4 id="dateto" class="text-center">Дата по</h4>
                <input type="text" class="form-control" name="dealsReportDateTo" id="dealsReportDateTo">
            </div>
        </div>

        <div class="row">
            <div class="col-lg-12 col-md-12 col-xs-12">
                <button id="submit" type="submit" class="btn btn-success center-block" name="submit_table">
                    Сформировать отчет
                </button>
            </div>
        </div>
        <div class="nullrow">
        </div>
        <style>
            .nullrow {
                height: 10px; /* Высота блока */
            }
        </style>
    </form>

    <script src="//cdn.jsdelivr.net/momentjs/latest/moment-with-locales.min.js"></script>
    <script src="//cdn.jsdelivr.net/bootstrap.daterangepicker/2/daterangepicker.js"></script>
    <script type="text/javascript" src="js/daterangepicker-style-and-post.js"></script>

    <?php

    /** Если нажали кнопку формирования таблицы - забираем все отправленное.*/
    if (isset($_POST['submit_table'])) {
        $begDate = $_POST['dealsReportDateFrom'];
        $endDate = $_POST['dealsReportDateTo'];

        /** Это все куски для запроса. Ниже условия, по которым склеивается sql запрос" */
        /** Этот if для локальной разработки. Конвертация дат для разных драйверов PDO разная */

        $andDateBegEnd = "\r\n WHERE prereservation.cdate BETWEEN '" . date('Y.m.d', strtotime($begDate)) . " 00:00:00' AND '" . date('Y.m.d', strtotime($endDate)) . " 23:59:59'";
        $orderBySql = "\r\n GROUP BY prereservationstatus.name";

        $querySendSMS .= $andDateBegEnd;
        $querySendSMS .= $orderBySql;

        /** Формируем HTML таблицу с результатами.*/
        $resultQuerySendSMS = $dbCon->prepare($querySendSMS);
        $resultQuerySendSMS->execute();
        $resultQuerySendSMS = $resultQuerySendSMS->fetchAll();
        echo '<div id="donut-example" class="morris-donut-inverse"></div>';
        echo '<script>'. PHP_EOL;
        echo 'Morris.Donut({';
        echo 'element: \'donut-example\','. PHP_EOL;
        echo 'resize: true,'. PHP_EOL;
        echo 'data: ['. PHP_EOL;
        foreach ($resultQuerySendSMS as $rowSMS) {
            echo '{label:"' . $rowSMS["name"] . '", value:' . $rowSMS["expr1"] . '},' . PHP_EOL;

        }
        echo ']'. PHP_EOL;
        echo '})'. PHP_EOL;
        echo '</script>';

    }



    unset($_POST);
    ?>


</div>
</body>
</html>