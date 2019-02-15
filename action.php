<?php
/**
 * Created by PhpStorm.
 * User: Nazar
 * Date: 01.11.2018
 * Time: 16:56
 */

$username = 'root';
$password = '';
$server = 'localhost';
$database = 'users';
$conn = new mysqli($server, $username, $password, $database);

if (isset($_POST['delete_id'])) {
    $query = "DELETE FROM uzytkownicy WHERE ID = " . $_POST['delete_id'];
    $conn->query($query);
}
if (isset($_POST['Imie'])) {
    $query = "INSERT INTO uzytkownicy(ID, Imie, Nazwisko, Miejcowoszc) 
              VALUES(NULL, '" . $_POST['Imie'] . "', '" . $_POST['Nazwisko'] . "','" . $_POST['Miejscowosc'] . "')";
    $conn->query($query);

}
if (isset($_POST['inputImie'])) {
    $query = "UPDATE uzytkownicy SET Imie = '" . $_POST['inputImie'] . "', Nazwisko = '" . $_POST['inputNazwisko'] . "',
              Miejcowoszc = '" . $_POST['inputMiejscowosc'] . "' WHERE ID = '" . $_POST['inputID'] . "'";
    $conn->query($query);
}

$rs = $conn->query('SELECT * FROM uzytkownicy');
if (!$rs)
    die($conn->error);
$conn->close();
$num = $rs->num_rows;

echo '<table class="table">
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
        </thead>';

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

echo '</table>';
