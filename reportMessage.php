<?php

$host = 'sql.turbosms.ua';
$database = '';
$username = '';
$password = '';
$dbConSMS = new PDO('mysql:host=' . $host . ';dbname=' . $database . ';charset=UTF8', $username, $password);
/** Запросы */

/** Здесь запрашиваем отправленные сообщения */
$querySendSMS = "SELECT
                      vitalytour.number AS number,
                      vitalytour.balance AS balance,
                      vitalytour.added AS `add`,
                      vitalytour.received AS received,
                      vitalytour.status AS status,
                      vitalytour.sended AS send,
                      vitalytour.id AS id,
                      vitalytour.message AS text
                    FROM vitalytour";

?>
<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="robots" content="noindex, nofollow">
    <title>Отчет по SMS</title>
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
        <div class="col-lg-2 col-md-12 col-xs-12">
            <h4 class="text-center">Баланс</h4>
            <?php
            $url = 'https://api.turbosms.ua/user/balance.json?token=9a29272c4a1773e3187deb8043f20b8354f5c780';
            # Получаем json
            $content = file_get_contents($url);
            # Декодируем. На выходе получили массив
            $obj = json_decode($content, true);

            $test = $obj['response_result']['balance'];
            echo '<h4 class="text-center" style="color:Red; font-weight:bold">' . $test . '</h4>' . PHP_EOL;
            ?>
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


    <h3 class="text-uppercase text-center">Отчет по SMS, отправленным клиентам</h3>
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

        $andDateBegEnd = "\r\n WHERE vitalytour.added BETWEEN '" . date('Y.m.d', strtotime($begDate)) . " 00:00:00' AND '" . date('Y.m.d', strtotime($endDate)) . " 23:59:59'";
        $orderBySql = "\r\n ORDER BY id";

        $querySendSMS .= $andDateBegEnd;
        $querySendSMS .= $orderBySql;

        /** Формируем HTML таблицу с результатами.*/
        $resultQuerySendSMS = $dbConSMS->prepare($querySendSMS);
        $resultQuerySendSMS->execute();


        echo '<table data-page-length=\'50\' id="airtable"  class="display table table-bordered table-bordered table-hover table-condensed table-responsive ">' .
            '<caption style="padding: 2px; vertical-align: top; text-align: center;"><H3>Отправленные SMS c ' . date('d.m.Y', strtotime($begDate)) . ' по ' . date('d.m.Y', strtotime($endDate)) . '</H3></caption>' .
            '<thead>' .
            '<tr>' .
            '<th colspan="3" style="padding: 2px; vertical-align: top; text-align: center;" >Дата</th>' .
            '<th rowspan="2" style="padding: 2px; vertical-align: top; text-align: center;" >Номер</th>' .
            '<th rowspan="2" style="padding: 2px; vertical-align: top; text-align: center;" >Текст SMS</th>' .
            '<th rowspan="2" style="padding: 2px; vertical-align: top; text-align: center;" >Баланс</th>' .
            '<th rowspan="2" style="padding: 2px; vertical-align: top; text-align: center;" >Статус</th>' .
            '</tr>' .
            '<tr>' .
            '<th rowspan="1" style="padding: 2px; vertical-align: top; text-align: center;" >Поставлено</th>' .
            '<th rowspan="1" style="padding: 2px; vertical-align: top; text-align: center;" >Отпправлено</th>' .
            '<th rowspan="1" style="padding: 2px; vertical-align: top; text-align: center;" >Получено</th>' .
            '</tr>' .
            '</thead>' .
            '<tbody>';


        foreach ($resultQuerySendSMS as $rowSMS) {

            $spanStyle = '<span "padding: 2px; vertical-align: top; text-align: center;"';
            $status = '';
            switch ($rowSMS['status']) {
                case 'NULL':
                    $status = 'Сообщение ещё не обработано';
                    break;
                case 'ACCEPTD':
                    $status = 'Сообщение принято в обработку';
                    break;
                case 'ENROUTE':
                    $status = 'Сообщение отправлено в мобильную сеть';
                    break;
                case 'DELIVRD':
                    $status = 'Сообщение доставлено получателю';
                    break;
                case 'EXPIRED':
                    $status = 'Истек срок сообщения';
                    break;
                case 'DELETED':
                    $status = 'Удалено оператором';
                    break;
                case 'UNDELIV':
                    $status = 'Не доставлено';
                    break;
                case 'REJECTD':
                    $status = 'Сообщение отклонено';
                    break;
                case 'UNKNOWN':
                    $status = 'Неизвестный статус';
                    break;

            }

            echo '<tr>' .
                '<td class="text-center">' . $rowSMS['add'] . '</span></td>' .
                '<td class="text-center">' . $rowSMS['send'] . '</span></td>' .
                '<td class="text-center">' . $rowSMS['received'] . '</span></td>' .
                '<td class="text-center">' . $rowSMS['number'] . '</span></td>' .
                '<td class="text-left">' . $rowSMS['text'] . '</span></td>' .
                '<td class="text-center">' . $rowSMS['balance'] . '</span></td>' .
                '<td class="text-center">' . $status . '</span></td>' . '</tr>';
        }

        echo '</tbody>';
        echo '</table>';
    }

    /** Закрывайем подключение к базе */
    $dbConSMS = null;
    ?>

    <!--Работа с таблицами, сортировка, поиск, пагинация, перевод-->
    <script>


        $(document).ready(function () {
            var eventFired = function (type) {
                var n = $('#demo_info')[0];
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
                dom: 'lBfrtip',
                buttons: [
                    {
                        extend: 'print', exportOptions:
                            {columns: ':visible'}
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
                            {columns: [0, 1, 2, 3, 4]}
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
                        copy: 'Буфер обмена',
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