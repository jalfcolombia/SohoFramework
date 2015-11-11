<!DOCTYPE html>
<html>
  <head>
    <meta charset="UTF-8">
    <title></title>
  </head>
  <body>
    <?php if(isset($_GET['error']) and $_GET['error'] === true): ?>
    Ocurrio un error
    <?php endif ?>
    <form action="index.php?step=3" method="POST">
      <input value="<?php echo (isset($_POST['host'])) ? $_POST['host'] : '' ?>" type="text" name="host" placeholder="Inserte el host de la base de datos" required><br>
      <select name="driver" required>
        <option value="">Seleccione un controlador</option>
        <option value="pgsql" <?php echo (isset($_POST['driver']) and $_POST['driver'] === 'pgsql') ? 'selected' : '' ?>>PostgreSQL</option>
        <option value="mysql" <?php echo (isset($_POST['driver']) and $_POST['driver'] === 'mysql') ? 'selected' : '' ?>>MySQL</option>
      </select><br>
      <input type="text" name="port" placeholder="Digite el puerto" required><br>
      <input type="text" name="dbName" placeholder="Nombre de la base de datos" required><br>
      <input type="text" name="dbUser" placeholder="Usuario de la base de datos" required><br>
      <input type="password" name="dbPass" placeholder="Contraseña de la base de datos" required><br>
      <input type="submit" value="Continuar">
    </form>
    <?php if(isset($_GET['error']) and $_GET['error'] === true): ?>
    <?php echo $_GET['error_message'] ?>
    <?php endif ?>
  </body>
</html>
