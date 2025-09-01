<?php include "../inc/dbinfo.inc"; ?>
<html>
<body>
<h1>Sample page</h1>
<?php

  /* conecta ao MySQL e seleciona o banco de dados. */
  $connection = mysqli_connect(DB_SERVER, DB_USERNAME, DB_PASSWORD);

  if (mysqli_connect_errno()) echo "Falha ao conectar ao MySQL: " . mysqli_connect_error();

  $database = mysqli_select_db($connection, DB_DATABASE);

  VerifyGamesTable($connection, DB_DATABASE);   // tabela principal: jogos

  /* se os campos do formulário estiverem preenchidos, adiciona um jogo na tabela GAMES. */
  $home_team  = isset($_POST['HOME_TEAM'])  ? htmlentities($_POST['HOME_TEAM'])  : '';
  $away_team  = isset($_POST['AWAY_TEAM'])  ? htmlentities($_POST['AWAY_TEAM'])  : '';
  $match_date = isset($_POST['MATCH_DATE']) ? htmlentities($_POST['MATCH_DATE']) : '';
  $stadium    = isset($_POST['STADIUM'])    ? htmlentities($_POST['STADIUM'])    : '';

  if (strlen($home_team) || strlen($away_team) || strlen($match_date) || strlen($stadium)) {
    AddGame($connection, $home_team, $away_team, $match_date, $stadium);
  }
?>

<!-- forms de criação de jogos -->
<form action="<?PHP echo $_SERVER['SCRIPT_NAME'] ?>" method="POST">
  <table border="0">
    <tr>
      <td>Time da Casa</td>
      <td>Time Visitante</td>
      <td>Data da Partida (Brasileirão 2025)</td>
      <td>Estádio (opcional)</td>
    </tr>
    <tr>
      <td><input type="text" name="HOME_TEAM" maxlength="60" size="20" required /></td>
      <td><input type="text" name="AWAY_TEAM" maxlength="60" size="20" required /></td>
      <td><input type="date" name="MATCH_DATE" min="2025-01-01" max="2025-12-31" required /></td>
      <td><input type="text" name="STADIUM" maxlength="100" size="25" /></td>
      <td><input type="submit" value="Adicionar Jogo" /></td>
    </tr>
  </table>
</form>

<!-- listagem dos jogos cadastrados -->
<table border="1" cellpadding="2" cellspacing="2">
  <tr>
    <td>ID</td>
    <td>Time da Casa</td>
    <td>Time Visitante</td>
    <td>Data da Partida</td>
    <td>Estádio</td>
  </tr>

<?php

$result = mysqli_query($connection, "SELECT GAME_ID, HOME_TEAM, AWAY_TEAM, MATCH_DATE, STADIUM FROM GAMES ORDER BY MATCH_DATE DESC, GAME_ID DESC");

while($query_data = mysqli_fetch_row($result)) {
  echo "<tr>";
  echo "<td>",$query_data[0], "</td>",
       "<td>",$query_data[1], "</td>",
       "<td>",$query_data[2], "</td>",
       "<td>",$query_data[3], "</td>",
       "<td>",$query_data[4], "</td>";
  echo "</tr>";
}
?>

</table>

<!-- Limpeza -->
<?php

  mysqli_free_result($result);
  mysqli_close($connection);

?>

</body>
</html>


<?php

/* Adiciona um jogo na tabela GAMES. */
function AddGame($connection, $home, $away, $date, $stadium) {
   $h = mysqli_real_escape_string($connection, $home); // adiciona o time da casa
   $a = mysqli_real_escape_string($connection, $away); // adiciona o time visitante
   $d = mysqli_real_escape_string($connection, $date);  // adiciona a data do jogo
   $s = mysqli_real_escape_string($connection, $stadium); // adiciona o estádio se for enviado

   /* requisito para entrar na tabela de jogos do Brasileirão 2025 é que o jogo deve ser realizado em 2025.  */
   if (!preg_match('/^\d{4}-\d{2}-\d{2}$/', $d) || substr($d, 0, 4) !== '2025') {
     echo "<p>Erro: a data deve ser do ano de 2025 (Campeonato Brasileiro 2025).</p>";
     return;
   }

   $query = "INSERT INTO GAMES (HOME_TEAM, AWAY_TEAM, MATCH_DATE, STADIUM)
             VALUES ('$h', '$a', '$d', " . (strlen($s) ? "'$s'" : "NULL") . ");";

   if(!mysqli_query($connection, $query)) echo("<p>Erro ao adicionar jogo.</p>");
}

/* Cria a tabela GAMES caso não exista
*/
function VerifyGamesTable($connection, $dbName) {
  if(!TableExists("GAMES", $connection, $dbName))
  {
     $query = "CREATE TABLE GAMES (
         GAME_ID INT(11) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
         HOME_TEAM VARCHAR(60) NOT NULL,
         AWAY_TEAM VARCHAR(60) NOT NULL,
         MATCH_DATE DATE NOT NULL,
         STADIUM VARCHAR(100) NULL,
         CREATED_AT TIMESTAMP DEFAULT CURRENT_TIMESTAMP
       )";

     if(!mysqli_query($connection, $query)) echo("<p>Erro ao criar a tabela GAMES.</p>");
  }
}

/* verifica a existência de uma tabela */
function TableExists($tableName, $connection, $dbName) {
  $t = mysqli_real_escape_string($connection, $tableName);
  $d = mysqli_real_escape_string($connection, $dbName);

  $checktable = mysqli_query($connection,
      "SELECT TABLE_NAME FROM information_schema.TABLES WHERE TABLE_NAME = '$t' AND TABLE_SCHEMA = '$d'");

  if(mysqli_num_rows($checktable) > 0) return true;

  return false;
}
?>
