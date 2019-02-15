<?php
/**
 * Created by PhpStorm.
 * User: Nazar
 * Date: 01.11.2018
 * Time: 15:22
 */
require('../Login/session_check.php');
?>
<!doctype html>
<html lang="pl">

<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/css/bootstrap.min.css"
          integrity="sha384-MCw98/SFnGE8fJT3GXwEOngsV7Zt27NXFoaoApmYm81iuXoPkFOJwJ8ERdknLPMO" crossorigin="anonymous">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
    <script>
        $(document).ready(function () {
            $("#add-new").hide();

            $(".btn-dark").click(function () {
                $("#add-new").show("slow");
                $(".btn-dark").hide("slow");
            });

            $("#cancel").click(function () {
                $("#add-new").hide("slow");
                $(".btn-dark").show("slow");
            });

        });
    </script>
    <title>Użytkownicy</title>
    <style>
        body {
            margin-left: 10px;
            margin-right: 10px;
        }

        tbody:hover {
            background-color: #e6e6e6;
            transition: 1s;
        }

        button, #cancel, #edytuj {
            color: #2f2f2f;
            background: none;
            border: none;
            cursor: pointer;
            font-size: 20px;
        }

        button[name="delete"]:hover, #cancel:hover {
            color: red;
        }

        #edytuj:hover {
            color: #ffce1b;
        }

        button[name="dodaj"]:hover, button[name="edytuj"]:hover {
            color: #14ff23;
        }

        input[type='text'] {
            width: 90%;
            height: 35px;
        }

        table {
            text-align: center;
        }

        #add-new {
            position: fixed;
            left: 0;
            bottom: -15;
        }

        .modal_div {
            width: auto;
            height: auto; /* Размеры должны быть фиксированы */
            border-radius: 5px;
            background: #fff;
            position: fixed; /* чтобы окно было в видимой зоне в любом месте */
            margin-left: 30%; /* половина экрана слева */
            margin-right: 30%;
            margin-top: -15%;
            display: none; /* в обычном состоянии окна не должно быть */
            opacity: 0; /* полностью прозрачно для анимирования */
            z-index: 5; /* окно должно быть наиболее большем слое */
            padding: 20px 10px;
        }

        /* Кнопка закрыть для тех кто в танке) */
        /* Подложка */
        #overlay {
            z-index: 3; /* подложка должна быть выше слоев элементов сайта, но ниже слоя модального окна */
            position: fixed; /* всегда перекрывает весь сайт */
            background-color: #000; /* черная */
            opacity: 0.8; /* но немного прозрачна */
            width: 100%;
            height: 100%; /* размером во весь экран */
            top: 0;
            left: 0; /* сверху и слева 0, обязательные свойства! */
            cursor: pointer;
            display: none; /* в обычном состоянии её нет) */
        }

        #No {
            color: grey;
            margin-bottom: 50px;
        }
    </style>
</head>

<body>
<div id="users_table" style="margin-bottom: 90px;">
    <?php
    $username = 'root';
    $password = '';
    $server = 'localhost';
    $database = 'users';
    $br = '<br>';

    $conn = new mysqli($server, $username, $password, $database);
    if ($conn->connect_error)
        die($conn->connect_error);

    $rs = $conn->query('SELECT * FROM uzytkownicy');
    if (!$rs)
        die($conn->error);
    $conn->close();
    $num = $rs->num_rows;
    ?>
    <table class="table">
        <thead class="thead-dark">
        <tr>
            <th>№</th>
            <th>Imie</th>
            <th>Nazwisko</th>
            <th>Miejscowość</th>
            <th></th>
            <th>Edytuj</th>
            <th>Usuń</th>
        </tr>
        </thead>
        <?php
        $i = 0;
        while ($i < $num) {
            $No = $i + 1;
            $rs->data_seek($i);
            $row = $rs->fetch_assoc();
            $user_row =
                "<tbody>
                    <form>
                        <tr id='rowID" . $row["ID"] . "'>
			                <td title='№'>" . $No . "</td>
			                <td title='Imie' id='Imie" . $No . "'>" . $row["Imie"] . "</td>
			                <td title='Nazwisko' id='Nazwisko" . $No . "'>" . $row["Nazwisko"] . "</td>
			                <td title='Miejscowość' id='Miejsc" . $No . "'>" . $row["Miejcowoszc"] . "</td>
			                <td><input name='ID' id='ID" . $No . "' value='" . $row["ID"] . "' hidden></td>
			                <td><a name='edit' id='edytuj' title='Edytuj' onclick='open_ed(" . $No . ")'><i class='fa fa-edit'></a></td>
			                <td><button name='delete' type='button' onclick='deleteAjax(" . $row["ID"] . ")' title='Usuń'><i class='fa fa-trash-o'></button></td>
			            </tr>
			        </form>
			    </tbody>";

            echo $user_row;
            $i++;
        }
        ?>
    </table>
</div>
<table class="table" id="add-new">
    <thead class="thead-light">
    <form id="add_form" method="post">
        <tr>
            <th><i class="fa fa-address-card-o" style="font-size:34px"></i>
            </th>
            <th>
                <input type="text" class="form-control" name="Imie" placeholder="Imię"
                       pattern="^[A-ZŚĆŻÓŃĘĄŁ][a-zćżńóęął]{1,60}$"
                       title="Prosimy podać imię bez spacji, z dużej litery" required>
            </th>
            <th>
                <input type="text" class="form-control" name="Nazwisko" placeholder="Nazwisko"
                       pattern="^[A-ZŻĆŃĄĘÓŁ][a-zżćńóąęł]{1,30}(?:[-]*[A-ZŻĆŃĄĘÓŁ][a-zżćńóąęł]{1,30})*$"
                       title="Bez spacji, z dużej litery, podwójne nazwiska prosimy oddzielić myślnikiem"
                       required>
            </th>
            <th>
                <input type="text" class="form-control" name="Miejscowosc" placeholder="Miejscowość"
                       pattern="^[a-zżćńóąęłA-ZŻĆŃĄĘÓŁ]+(?:[\s-][a-zżćńóąęłA-ZŻĆŃĄĘÓŁ]+)*$"
                       title="Prosimy wprowadzić obecną nazwę miasta" required>
            </th>
            <th><button name='dodaj' type='button' onclick="addAjax()" title='Dodaj nowego użytkownika'><i
                            class='fa fa-check'></button></th>
            <th><a id="cancel" title='Anuluj'><i class='fa fa-close'></a></th>
        </tr>
        <tr>
            <th></th>
            <th></th>
            <th></th>
            <th></th>
            <th></th>
            <th></th>
        </tr>
    </form>
    </thead>
</table>
<button type="button" class="btn btn-dark" style="position: fixed; bottom: 5px; left: 5px;">Dodaj nowego użytkownika
</button>
<div id="modal1" class="modal_div">
    <form action="action.php" method="post" id="edit_form">
        <b id="No"></b>
        <div style="margin-top: 20px;" align="center" class="form-row">
            <div class="form-group col-6">
                <label for="inputName">Imię</label>
                <input type="text" class="form-control" id="inputName" placeholder="Name" name="inputImie"
                       pattern="^[A-ZŚĆŻÓŃĘĄŁ][a-zćżńóęął]{1,60}$"
                       title="Prosimy podać imię bez spacji, z dużej litery" required>
            </div>
            <div class="form-group col-6">
                <label for="inputSurname">Nazwisko</label>
                <input type="text" class="form-control" id="inputSurname" placeholder="Surname" name="inputNazwisko"
                       pattern="^[A-ZŻĆŃĄĘÓŁ][a-zżćńóąęł]{1,30}(?:[-]*[A-ZŻĆŃĄĘÓŁ][a-zżćńóąęł]{1,30})*$"
                       title="Bez spacji, z dużej litery, podwójne nazwiska prosimy oddzielić myślnikiem" required>
            </div>
            <div class="form-group col-12">
                <label for="inputCity">Miejscowość</label>
                <input type="text" class="form-control" id="inputCity" placeholder="City" minlength="3" maxlength="60"
                       name="inputMiejscowosc" pattern="^[a-zżćńóąęłA-ZŻĆŃĄĘÓŁ]+(?:[\s-][a-zżćńóąęłA-ZŻĆŃĄĘÓŁ]+)*$"
                       title="Prosimy wprowadzić obecną nazwę miasta" required>
            </div>
            <input id="inputID" name="inputID" hidden title="id">
        </div>
        <table style="margin-top: 20px;" align="center">
            <thead>
            <th width="150px">
                <button style="font-size: 30px;" name='edytuj' type='button' onclick="editAjax()" title='Edytuj'><i
                            class='fa fa-check'></button></th>
            <th width="150px"><a style="font-size: 30px;" id="cancel" class="modal_close" title='Anuluj'><i
                            class='fa fa-close'></a></th>
            </thead>
        </table>
    </form>
</div>

<div id="overlay"></div>

</body>
<script>
    function deleteAjax(id) {
        if (confirm('Czy na pewno chcesz usunąć tego użytkownika? \nTa operacja jest nieodwracalna')) {
            $.ajax({
                type: 'post',
                url: 'action.php',
                data: {delete_id: id},
                success: function (d) {
                    $('#users_table').html(d);
                }
            });
        }
    }

    function editAjax() {
        $.ajax({
            url: 'action.php',
            type: 'post',
            data: $('#edit_form').serialize(),
            success: function (d) {
                alert('Informacja o użytkowniku została edytowana');
                $('#users_table').html(d);
                $('.modal_close').click();
            }

        });
    }

    function addAjax() {
        $.ajax({
            url: 'action.php',
            type: 'post',
            data: $('#add_form').serialize(),
            success: function (d) {
                $('#users_table').html(d);
                $('#add_form')[0].reset();
                alert('Dodano nowego użytkownika');
            }

        });
    }

    var overlay = $('#overlay'); // подложка, должна быть одна на странице
    var close = $('.modal_close, #overlay'); // все, что закрывает модальное окно, т.е. крестик и оверлэй-подложка
    var modal = $('.modal_div'); // все скрытые модальные окна

    function open_ed(nr) {
        event.preventDefault(); // вырубаем стандартное поведение
        overlay.fadeIn(400, //показываем оверлэй
            function () { // после окончания показывания оверлэя
                $('#modal1') // берем строку с селектором и делаем из нее jquery объект
                    .css('display', 'block')
                    .animate({opacity: 1, top: '50%'}, 200); // плавно показываем
                $('#No').html('<i class="fa fa-edit"> &nbsp; Edytowanie użytkownika №' + nr);
                $('#inputName').val($('#Imie' + nr + '').html());
                $('#inputSurname').val($('#Nazwisko' + nr + '').html());
                $('#inputCity').val($('#Miejsc' + nr + '').html());
                $('#inputID').val($('#ID' + nr + '').val());
            });
    }

    close.click(function () { // ловим клик по крестику или оверлэю
        modal // все модальные окна
            .animate({opacity: 0, top: '45%'}, 200, // плавно прячем
                function () { // после этого
                    $(this).css('display', 'none');
                    overlay.fadeOut(400); // прячем подложку
                }
            );
    });
</script>
</html>
