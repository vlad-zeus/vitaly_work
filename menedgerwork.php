<?php

/** Параметры подключения. Подключаемся к базе.*/
$host = '';
$database = '';
$username = '';
$password = '';
$dbCon = new PDO('dblib:charset=UTF-8;host=' . $host . ';database=' . $database, $username, $password);

/** Запросы */

/** $queryManager - здесь мы выбираем менеджеров. Убраны старые/неиспользуемые менеджеры, выстроены в том порядке, в котором я хочу.*/
$queryManager = "
SELECT mng.id
     , mng.name
FROM dbo.recipient rc
         INNER JOIN dbo.recipient mng
                    ON rc.managerid = mng.id
WHERE mng.id NOT IN (991, 1, 10, 12, 21, 991, 7612, 7953, 7983, 8094, 8820, 9195, 25, 16, 20, 8107, 344, 10451, 10449, 10450)
GROUP BY mng.name
       , mng.id
ORDER BY CASE mng.id
             WHEN '2922' THEN 1
             WHEN '18' THEN 2
             WHEN '3978' THEN 3
             WHEN '2922' THEN 4
             WHEN '8446' THEN 5
             ELSE 8
             END";

/** Выбираем номера предварительніх заявок из базы */
$queryClaim = "
SELECT prereservation.id     AS id
     , prereservation.number AS number
     , prereservation.cdate
FROM dbo.prereservation
WHERE prereservation.cdate > DATEADD(DAY, -365, GETDATE())
ORDER BY id DESC
";

/** Выбираем статус заявки */
$queryStatus = "
SELECT prereservationstatus.id
     , prereservationstatus.name
FROM dbo.prereservationstatus
ORDER BY prereservationstatus.id DESC
";


/** Выбираем предварительную заявку.*/
$queryCustomer = "
SELECT *
FROM (SELECT TOP 10000 prereservation.number                                 as number
                     , prereservation.cdate                                  as precdate
                     , recipient.name                                        as tourist
                     , recipient.phone                                       as touristPhone
                     , recipient.email                                       as touristEmail
                     , country.name                                          as preclaimCountry
                     , town.name                                             as preclaimTown
                     , relationdocument.added                                as date
                     , RANK() OVER
        (PARTITION BY prereservation.number ORDER BY relationdocument.added) AS Rank
      FROM dbo.prereservation
               INNER JOIN dbo.recipient
                          ON prereservation.recipientid = recipient.id
               LEFT OUTER JOIN dbo.country
                               ON prereservation.countryid = country.id
               LEFT OUTER JOIN dbo.town
                               ON prereservation.townid = town.id
               LEFT OUTER JOIN dbo.relationdocument
                               ON prereservation.number = relationdocument.documentnumber
                                        ";


function whileForPreClaimRalation($claimIdNumber, $datePreClaim)
{

    /** Параметры подключения.*/
    $dbCon = new PDO('dblib:charset=UTF-8; host=91.222.246.89:4869;database=Agent_7_2', 'vgorodetsky', '1715804226Gor');

    /** Выбираем общение с туристом. */
    $queryTourists = "
SELECT TOP 1 relationdocument.documentnumber AS preClaimNumber
           , relation.peoplename             AS touristName
           , relation.phone                  AS touristPhone
           , relation.email                  AS touristEmail
           , relation.datetime               AS date4
           , (SELECT TOP 1 relation.datetime AS date1
              FROM dbo.relationdocument
                       INNER JOIN dbo.relation
                                  ON relationdocument.relationid = relation.id
                       INNER JOIN dbo.recipient
                                  ON relation.humanid = recipient.id
                       INNER JOIN dbo.prereservation
                                  ON relationdocument.documentnumber = prereservation.number
                       LEFT OUTER JOIN dbo.meal
                                       ON prereservation.mealid = meal.id
                       INNER JOIN dbo.prereservationstatus
                                  ON prereservation.prereservationstatusid = prereservationstatus.id
                       LEFT OUTER JOIN dbo.reclamatype
                                       ON relation.reclamaid = reclamatype.id
              WHERE prereservation.number = :claimIdNumber
              ORDER BY date1 DESC)
                                             AS date0
           , (SELECT TOP 1 task.datebeg AS date
              FROM dbo.task
                       INNER JOIN dbo.relationdocument
                                  ON task.documentid = relationdocument.relationid
                       INNER JOIN dbo.relation
                                  ON relationdocument.relationid = relation.id
              WHERE relationdocument.documentnumber = :claimIdNumber
                AND relation.relationsubjectid = 232
              ORDER BY task.datetime DESC
)                                            AS date1
           , (SELECT TOP 1 task.timebeg AS date
              FROM dbo.task
                       INNER JOIN dbo.relationdocument
                                  ON task.documentid = relationdocument.relationid
                       INNER JOIN dbo.relation
                                  ON relationdocument.relationid = relation.id
              WHERE relationdocument.documentnumber = :claimIdNumber
                AND relation.relationsubjectid = 232
              ORDER BY task.datetime DESC
)                                            AS time1
           , (SELECT TOP 1 task.datebeg AS date
              FROM dbo.task
                       INNER JOIN dbo.relationdocument
                                  ON task.documentid = relationdocument.relationid
                       INNER JOIN dbo.relation
                                  ON relationdocument.relationid = relation.id
              WHERE relationdocument.documentnumber = :claimIdNumber
                AND relation.relationsubjectid = 236
              ORDER BY task.datetime DESC
)
                                             AS date2
           , (SELECT TOP 1 task.timebeg AS date
              FROM dbo.task
                       INNER JOIN dbo.relationdocument
                                  ON task.documentid = relationdocument.relationid
                       INNER JOIN dbo.relation
                                  ON relationdocument.relationid = relation.id
              WHERE relationdocument.documentnumber = :claimIdNumber
                AND relation.relationsubjectid = 236
              ORDER BY task.datetime DESC
)
                                             AS time2
           , (SELECT TOP 1 task.datebeg AS date
              FROM dbo.task
                       INNER JOIN dbo.relationdocument
                                  ON task.documentid = relationdocument.relationid
                       INNER JOIN dbo.relation
                                  ON relationdocument.relationid = relation.id
              WHERE relationdocument.documentnumber = :claimIdNumber
                AND relation.relationsubjectid = 237
              ORDER BY task.datetime DESC
)
                                             AS date3
           , (SELECT TOP 1 task.timebeg AS date
              FROM dbo.task
                       INNER JOIN dbo.relationdocument
                                  ON task.documentid = relationdocument.relationid
                       INNER JOIN dbo.relation
                                  ON relationdocument.relationid = relation.id
              WHERE relationdocument.documentnumber = :claimIdNumber
                AND relation.relationsubjectid = 237
              ORDER BY task.datetime DESC
)
                                             AS time3
           , recipient.name                  AS manager
           , prereservationstatus.name       AS Status
           , prereservation.number
           , relationdocument.added          AS preClaimDate
           , country.name                    AS country
           , prereservation.datebegfrom
           , prereservation.datebegdelta
           , town.name                       AS town
           , reclamatype.name                AS reclama
           , prereservation.nightsfrom
           , prereservation.nightsto
           , star.name                       AS star
FROM dbo.relationdocument
         INNER JOIN dbo.relation
                    ON relationdocument.relationid = relation.id
         INNER JOIN dbo.recipient
                    ON relation.humanid = recipient.id
         INNER JOIN dbo.prereservation
                    ON relationdocument.documentnumber = prereservation.number
         INNER JOIN dbo.prereservationstatus
                    ON prereservation.prereservationstatusid = prereservationstatus.id
         INNER JOIN dbo.country
                    ON prereservation.countryid = country.id
         LEFT OUTER JOIN dbo.town
                         ON prereservation.townid = town.id
         LEFT OUTER JOIN dbo.reclama
                         ON prereservation.reclamaid = reclama.id
         LEFT OUTER JOIN dbo.reclamatype
                         ON reclama.reclamatypeid = reclamatype.id
         LEFT OUTER JOIN dbo.star
                         ON prereservation.hotelstarid = star.id
WHERE prereservation.number = :claimIdNumber
ORDER BY preClaimDate ASC
";
    $queryTask = "
SELECT TOP 1 datebeg as date
FROM dbo.task
         INNER JOIN dbo.relationdocument
                    ON task.documentid = relationdocument.relationid
WHERE relationdocument.documentnumber = :claimIdNumber
  AND task.complete = 0
ORDER BY task.datetime DESC
            ";

    /** Формируем HTML таблицу с результатами.*/
    $resultQueryTourists = $dbCon->prepare($queryTourists, array(PDO::ATTR_CURSOR => PDO::CURSOR_FWDONLY));
    $resultQueryTourists->execute(array('precdate' => $datePreClaim, 'claimIdNumber' => $claimIdNumber));
    $rowResultQueryTourists = $resultQueryTourists->fetchAll();


    foreach ($rowResultQueryTourists as $rowTourist) {
        $date0 = '';
        $date1 = '';
        $date2 = '';
        $date3 = '';
        $time1 = '';
        $time2 = '';
        $time3 = '';
        $dateStart = '';
        $dateDelta = '';
        $nights = '';

        if (!empty($rowTourist['date0'])) {
            $date0 = date('d.m.Y G:i', strtotime($rowTourist['date0']));
        }
        if (!empty($rowTourist['date1'])) {
            $date1 = date('d.m.Y', strtotime($rowTourist['date1']));
        }
        if (!empty($rowTourist['date2'])) {
            $date2 = date('d.m.Y', strtotime($rowTourist['date2']));
        }
        if (!empty($rowTourist['date3'])) {
            $date3 = date('d.m.Y', strtotime($rowTourist['date3']));
        }

        if (!empty($rowTourist['time1'])) {
            $time1 = date('G:i', strtotime($rowTourist['time1']));
        }
        if (!empty($rowTourist['time2'])) {
            $time2 = date('G:i', strtotime($rowTourist['time2']));
        }
        if (!empty($rowTourist['time3'])) {
            $time3 = date('G:i', strtotime($rowTourist['time3']));
        }
        if (!empty($rowTourist['datebegfrom'])) {
            $dateStart = date('d.m.Y', strtotime($rowTourist['datebegfrom']));
        }
        if (!empty($rowTourist['datebegdelta'])) {
            $dateDelta = '±' . $rowTourist['datebegdelta'];
        }
        if ($rowTourist['nightsfrom'] == $rowTourist['nightsto']) {
            $nights = $rowTourist['nightsfrom'];
        } elseif (empty($rowTourist['nightsto'])) {
            $nights = $rowTourist['nightsfrom'];
        } else $nights = $rowTourist['nightsfrom'] . '-' . $rowTourist['nightsto'];
        /*        var_dump($rowTourist['date1']);
                var_dump($date1);*/
        /*var_dump($rowTourist['touristName']);*/
        echo '<tr>' .
            '<td style="padding: 2px; vertical-align: top; text-align: center;">' . $rowTourist['preClaimNumber'] . '</td>' .
            '<td style="padding: 2px; vertical-align: top; text-align: center;">' . date('d.m.Y G:i', strtotime($rowTourist['preClaimDate'])) . '</td>' .
            '<td style="padding: 2px; vertical-align: top; text-align: left;">' . $rowTourist['touristName'] . '</td>' .
            '<td style="padding: 2px; vertical-align: top; text-align: center;">' . $date0 . '</td>' .
            '<td style="padding: 2px; vertical-align: top; text-align: center;">' . $date1 . ' ' . $time1 . '</td>' .
            '<td style="padding: 2px; vertical-align: top; text-align: center;">' . $date2 . ' ' . $time2 . '</td>' .
            '<td style="padding: 2px; vertical-align: top; text-align: center;">' . $date3 . ' ' . $time3 . '</td>' .
            '<td style="padding: 2px; vertical-align: top; text-align: center;">' . $rowTourist['country'] . '</td>' .
            '<td style="padding: 2px; vertical-align: top; text-align: center;">' . $rowTourist['town'] . '</td>' .
            '<td style="padding: 2px; vertical-align: top; text-align: center;">' . $dateStart . $dateDelta . '</td>' .
            '<td style="padding: 2px; vertical-align: top; text-align: center;">' . $nights . '</td>' .
            '<td style="padding: 2px; vertical-align: top; text-align: center;">' . $rowTourist['star'] . '</td>' .
            '<td style="padding: 2px; vertical-align: top; text-align: center;">' . $rowTourist['reclama'] . '</td>' .
            '<td style="padding: 2px; vertical-align: top; text-align: center;">' . $rowTourist['Status'] . '</td>' .
            '<td style="padding: 2px; vertical-align: top; text-align: center;">' . substr($rowTourist['manager'], 0, strripos($rowTourist['manager'], ' ')) . '</td>' . '</tr>';

    }

    /** Закрывайем подключение к базе */
    $dbCon = null;
}

?>
<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="robots" content="noindex, nofollow">
    <title>Работа менеджеров</title>
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


    <style>
        .container {
            width: 90%;
        }
    </style>

</head>
<body>
<div class="container">
    <style>
        .menu {
            margin: 1em 0em;
        }

        .box {
            position: absolute;
            top: -1200px;
            width: 100%;
            color: #fff;
            margin: auto;
            padding: 0px;
            z-index: 999999;
            text-align: right;
            left: 3em;
        }

        a.boxclose {
            cursor: pointer;
            text-align: center;
            display: block;
            position: absolute;
            top: 5px;
            right: 320px;
        }

        .menu_box_list {
            display: inline-block;
            float: left;
            margin-left: 1em;
        }

        .menu_box_list ul li {
            display: inline-block;
        }

        .menu_box_list li a {
            color: #0c0c0c;
            font-size: 1.2em;
            font-weight: 400;
            display: block;
            padding: 0.5em 0.5em;
            text-decoration: none;
            text-transform: uppercase;
            -webkit-transition: all 0.5s ease-in-out;
            -moz-transition: all 0.5s ease-in-out;
            -o-transition: all 0.5s ease-in-out;
            transition: all 0.5s ease-in-out;
            letter-spacing: 0.1em;
        }

        .menu_box_list li a:hover, .menu_box_list ul li.active a {
            color: #E74C3C;
        }

        .menu_box_list ul {
            background: transparent;
            padding: 9px;
        }

        .menu_box_list li a > i > img {
            vertical-align: middle;
            padding-right: 10px;
        }
    </style>
    <div class="col-lg-12 col-md-12 col-xs-12">
        <div class="col-lg-10 col-md-12 col-xs-12">
            <div class="menu">
                <a href="#" id="activator"><img src="menu.png" alt=""/></a>
                <div class="box" id="box">
                    <div class="box_content">
                        <div class="menu_box_list">
                            <ul>
                                <li><a href="http://vitalytour.com.ua/smsviber/sendMessage.php">Отправка SMS</a></li>
                                <li><a href="http://vitalytour.com.ua/smsviber/reportMessage.php">Отчет по SMS</a></li>
                                <li><a href="http://vitalytour.com.ua/smsviber/menedgerwork.php">Работа менеджеров</a>
                                </li>
                                <div class="clearfix"></div>
                            </ul>
                        </div>
                        <a class="boxclose" id="boxclose"><img src="close.png" alt=""/></a>
                    </div>
                </div>
            </div>
        </div>
    </div>


    <script>
        var $ = jQuery.noConflict();
        $(function () {
            $('#activator').click(function () {
                $('#box').animate({'top': '0px'}, 500);
            });
            $('#boxclose').click(function () {
                $('#box').animate({'top': '-700px'}, 500);
            });
        });
    </script>


    <h3 class="text-uppercase text-center">Предварительные заявки.</h3>
    <form method="post" action="" id="deals_report" name="deals_report" target="_self">
        <div class="col-lg-2 col-md-12 col-xs-12">
            <div class="form-group">
                <h4 class="text-center">Дата создания с</h4>
                <input type="text" class="form-control" name="dealsReportDateFrom" id="dealsReportDateFrom">
            </div>
        </div>
        <div class="col-lg-2 col-md-12 col-xs-12">
            <div class="form-group">
                <h4 class="text-center">Дата создания по</h4>
                <input type="text" class="form-control" name="dealsReportDateTo" id="dealsReportDateTo">
            </div>
        </div>
        <div class="col-lg-2 col-md-12 col-xs-12">
            <div class="form-group">
                <h4 class="text-center">Менеджер</h4>
                <select name="managerSelect" class="form-control" id="managerSelect">
                    <option value="">Выбор менеджера</option>
                    <?php
                    $resultManagerSelect = $dbCon->query($queryManager);
                    /** Выводим результаты по менеджерам */
                    while ($rowManagerSelect = $resultManagerSelect->fetch()) echo "<option value='" . $rowManagerSelect['id'] . "'>" . substr($rowManagerSelect['name'], 0, strripos($rowManagerSelect['name'], ' ')) . "</option>" . PHP_EOL;
                    ?>
                </select>
            </div>
        </div>
        <div class="col-lg-2 col-md-12 col-xs-12">
            <div class="form-group">
                <h4 class="text-center">Заявка</h4>
                <select name="claimSelect" class="form-control" id="claimSelect">
                    <option value="">Выбор номера заявки</option>
                    <?php
                    $resultClaimSelect = $dbCon->query($queryClaim);
                    /** Выводим результаты по заявкам*/
                    while ($rowClaimSelect = $resultClaimSelect->fetch()) echo "<option value='" . $rowClaimSelect['id'] . "'>" . $rowClaimSelect['number'] . "</option>" . PHP_EOL;
                    ?>
                </select>
            </div>
        </div>
        <div class="col-lg-2 col-md-12 col-xs-12">
            <div class="form-group">
                <h4 class="text-center">Статус</h4>
                <select multiple class="form-control" name="statusSelect[]" id="statusSelect">
                    <option value="">Выбор статуса заявки</option>
                    <?php
                    $resultStatusSelect = $dbCon->query($queryStatus);
                    /** Выводим результаты по статусам*/
                    while ($rowStatusSelect = $resultStatusSelect->fetch()) echo "<option value='" . $rowStatusSelect['id'] . "'>" . $rowStatusSelect['name'] . "</option>" . PHP_EOL;
                    ?>
                </select>
            </div>
        </div>
        <div class="row">
            <div class="col-lg-12 col-md-12 col-xs-12">
                <button id="submit" type="submit" class="btn btn-success center-block" name="submit_table">Сформировать
                    таблицу туристов
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
        $managerId = $_POST['managerSelect'];
        $claimId = $_POST['claimSelect'];
        $statusId = '';
        if (!empty($_POST['statusSelect'])) {
            foreach ($_POST['statusSelect'] as $selectedOption) {
                $statusId .= $selectedOption . ', ';
            }
        }
        $statusId = substr($statusId, 0, -2);

        /*        var_dump($statusId);*/

        /** Это все куски для запроса. Ниже условия, по которым склеивается sql запрос" */
        /** Этот if для локальной разработки. Конвертация дат для разных драйверов PDO разная */
        if ($_SERVER['SERVER_NAME'] <> 'myproject.loc') {
            $andDateBegEnd = "\r\n WHERE prereservation.cdate BETWEEN CAST('" . date('Y.m.d', strtotime($begDate)) . " 00:00:00' as datetime) AND CAST('" . date('Y.m.d', strtotime($endDate)) . " 23:59:59' as datetime) ";
        } else
            $andDateBegEnd = "\r\n WHERE prereservation.cdate BETWEEN CAST('" . $begDate . " 00:00:00' as datetime) AND CAST('" . $endDate . " 23:59:59' as datetime) ";

        $andMangerIdSql = "\r\n AND prereservation.humanid = $managerId";
        $andStatusIdSql = "\r\n AND prereservation.prereservationstatusid in ($statusId)";
        $andClaimIdSql = "\r\n WHERE prereservation.number = '$claimId'";
        $orderBySql1 = "\r\n ORDER BY date ASC";
        $orderBySql = ") ranked \r\nWHERE Rank = 1 \r\nORDER BY date";


        /** Это для передачи в процедуру. Так мы будем опрделять, надо ли подклеивать дату в запрос. */
        if (!empty($claimId)) {
            $andDateBegEnd = 0;
        }
        /** Если выбрали заявку - добавляем условие по заявке.*/
        if (!empty($claimId)) {
            $queryCustomer .= $andClaimIdSql;
        }


        /** Если не выбирали номер заявки - добавляем условие по дате. А если выбрали - дату игнорируем. */
        if (empty($claimId)) {
            $queryCustomer .= $andDateBegEnd;
        }


        /** Если выбрали менеджера - добавляем условие по менеджеру, если не выбран номер заявки. НЕ ДОБАВЛЯЕМ менеджера, если выбрали номер заявки.*/
        if (!empty($managerId) and empty($claimId)) {
            $queryCustomer .= $andMangerIdSql;
        }

        /** Если выбрали статус - добавляем условие по статусу.*/
        if (!empty($statusId)) {
            $queryCustomer .= $andStatusIdSql;
        }

        /** В конце добавляем сортировку скрипта.*/
        $queryCustomer .= $orderBySql1;
        $queryCustomer .= $orderBySql;

        /** Формируем HTML таблицу с результатами.*/
        $resultQueryCustomer = $dbCon->prepare($queryCustomer);
        $resultQueryCustomer->execute();

        echo '<table data-page-length=\'50\' id="airtable"  class="display table table-bordered table-bordered table-hover table-condensed table-responsive ">' .
            '<caption style="padding: 2px; vertical-align: top; text-align: center;"><H3>Список туристов с ' . date('d.m.Y', strtotime($begDate)) . ' по ' . date('d.m.Y', strtotime($endDate)) . '</H3></caption>' .
            '<thead>' .
            '<tr>' .
            '<th colspan="2" style="padding: 2px; vertical-align: top; text-align: center;" >Предзаявка</th>' .
            '<th rowspan="2" style="padding: 2px; vertical-align: top; text-align: center" >ФИО клиента</th>' .
            '<th rowspan="2" style="padding: 2px; vertical-align: top; text-align: center; width: 5%" >Последнее общение</th>' .
            '<th colspan="3" style="padding: 2px; vertical-align: top; text-align: center;" >Запланированное общение</th>' .
            '<th colspan="5" style="padding: 2px; vertical-align: top; text-align: center;" >Характеристики тура</th>' .
            '<th rowspan="2" style="padding: 2px; vertical-align: top; text-align: center;" >Реклама</th>' .
            '<th rowspan="2" style="padding: 2px; vertical-align: top; text-align: center;" >Статус</th>' .
            '<th rowspan="2" style="padding: 2px; vertical-align: top; text-align: center;" >Менеджер</th>' .
            '</tr>' .
            '<tr>' .
            '<th style="padding: 2px; vertical-align: top; text-align: center; width: 5%" >№</th>' .
            '<th style="padding: 2px; vertical-align: top; text-align: center; width: 5%" >Дата</th>' .
            '<th style="padding: 2px; vertical-align: top; text-align: center; width: 5%" >1</th>' .
            '<th style="padding: 2px; vertical-align: top; text-align: center; width: 5%" >2</th>' .
            '<th style="padding: 2px; vertical-align: top; text-align: center; width: 5%" >3</th>' .
            '<th style="padding: 2px; vertical-align: top; text-align: center;" >Страна</th>' .
            '<th style="padding: 2px; vertical-align: top; text-align: center; width: 12%" >Город</th>' .
            '<th style="padding: 2px; vertical-align: top; text-align: center;" >Дата начала</th>' .
            '<th style="padding: 2px; vertical-align: top; text-align: center; width: 4%" >Ночей</th>' .
            '<th style="padding: 2px; vertical-align: top; text-align: center; width: 4%" >Отель</th>' .
            '</tr>' .
            '</thead>' .
            '<tbody>';

        foreach ($resultQueryCustomer as $rowCustomer) {

            /** Вызываем функцию, которая добавит в таблицу клиентов */

            whileForPreClaimRalation($rowCustomer['number'], $rowCustomer['precdate']);


        }
    }
    echo '</tbody>';
    echo '</table>';
    /** Закрывайем подключение к базе */
    $dbCon = null;
    ?>

    <!--Работа с таблицами, сортировка, поиск, пагинация, перевод-->
    <script>
        $(document).ready(function () {

            var eventFired = function (type) {
                var n = $('#airtable')[0];
                n.innerHTML += '<div>' + type + ' event - ' + new Date().getTime() + '</div>';
                n.scrollTop = n.scrollHeight;

            };


        });


        $.fn.dataTable.ext.type.detect.unshift(
            function (d) {
                return d === 'Low' || d === 'Medium' || d === 'High' ?
                    'salary-grade' :
                    null;
            }
        );

        $.fn.dataTable.ext.type.order['salary-grade-pre'] = function (d) {
            switch (d) {
                case 'Low':
                    return 1;
                case 'Medium':
                    return 2;
                case 'High':
                    return 3;
            }
            return 0;
        };

        $(document).ready(function () {
            let table = $('#airtable').DataTable({
                "order": [[1, "asc"]],
                dom: 'lBfrtip',
                buttons: [
                    {
                        extend: 'print', exportOptions:
                            {columns: ':visible'},
                        customize: function (win) {
                            $(win.document.body)
                                .css('font-size', '10pt')

                            $(win.document.body).find('table')
                                .addClass('compact')
                                .css('font-size', 'inherit');
                        }
                    },
                    {
                        extend: 'copy', exportOptions:
                            {columns: ':visible'}
                    },
                    {
                        extend: 'excel', exportOptions:
                            {columns: ':visible'}
                    },
                    {
                        extend: 'pdf', exportOptions:
                            {columns: ':visible'},
                        pageSize: 'A4',
                        pageMargins: [5, 5, 5, 5],
                        orientation: 'landscape'
                    },
                    {extend: 'colvis', postfixButtons: ['colvisRestore']}
                ],
                select: true,
                language: {
                    processing: "Подождите...",
                    search: "Поиск:",
                    lengthMenu: "Показать _MENU_ записей",
                    info: "Записи с _START_ до _END_ из _TOTAL_ записей",
                    infoEmpty: "Записи с 0 до 0 из 0 записей",
                    infoFiltered: "(отфильтровано из _MAX_ записей)",
                    infoPostFix: "",
                    loadingRecords: "Загрузка записей...",
                    zeroRecords: "Записи отсутствуют.",
                    emptyTable: "В таблице отсутствуют данные",
                    paginate: {
                        first: "Первая",
                        previous: "Предыдущая",
                        next: "Следующая",
                        last: "Последняя"
                    },
                    buttons: {
                        print: 'Печать',
                        copy: 'Копия',
                        colvis: 'Видимость колонок',
                        colvisRestore: 'Все колонки'
                    }, //buttons
                    aria: {
                        sortAscending: ": активировать для сортировки столбца по возрастанию",
                        sortDescending: ": активировать для сортировки столбца по убыванию"

                    }
                },
                "lengthMenu": [[10, 15, 25, 50, -1], [10, 15, 25, 50, "All"]]
            });


            $('#airtable tbody').on('click', 'tr', function () {
                $(this).toggleClass('selected');
            });

        });

    </script>
    <?php
    unset($_POST);
    ?>

</div>
</body>
</html>
