<?php use soho\shRouting as routing ?>
<?php use soho\shView as view ?>
<?php use soho\shSession as session ?>
<div class="container container-fluid">

  <form class="form-signin" role="form" action="<?php echo routing::getInstance()->getUrlWeb('shfSecurity', 'login') ?>" method="POST">
    <h2 class="form-signin-heading">Identificación</h2>
    <label for="inputUser" class="sr-only">Email address</label>
    <input type="text" id="inputUser" name="inputUser" class="form-control" placeholder="Usuario" required autofocus>
    <label for="inputPassword" class="sr-only">Password</label>
    <input type="password" id="inputPassword" name="inputPassword" class="form-control" placeholder="Contraseña" required>
    <div class="checkbox">
      <label>
        <input type="checkbox" value="true" name="chkRememberMe"> Recordar me
      </label>
    </div>
    <button class="btn btn-lg btn-primary btn-block" type="submit">Entrar</button>
    <?php if (session::getInstance()->hasError() or session::getInstance()->hasInformation() or session::getInstance()->hasSuccess() or session::getInstance()->hasWarning()): ?>
    <?php view::includeHandlerMessage() ?>
    <?php endif ?>
  </form>

</div> <!-- /container -->