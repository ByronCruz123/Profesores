<!DOCTYPE html>
<html>
<head>
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <title>NoteClass - Ingresar</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.0/css/bootstrap.min.css" integrity="sha384-9aIt2nRpC12Uk9gS9baDl411NQApFmC26EwAOH8WgZl5MYYxFfc+NcPb1dKGj7Sk" crossorigin="anonymous">
    <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.0.8/css/solid.css">
    <link rel="stylesheet" type="text/css" href="resources/css/login.css">
    <link rel="stylesheet" type="text/css" href="resources/css/styles.css">
  </head>
<body>
  <div class="modal-dialog text-center">
    <div class="col-sm-9 main-section">
      <div class="modal-content">

        <div class="col-12 user-img">
          <img src="resources/img/face.png">
        </div>

        <div class="col-12 form-input">
          <form id="formulariologin">
            <div class="form-group">
              <input type="text" class="form-control" name="user" placeholder="Usuario" required>
            </div>
            <div class="form-group">
              <input type="password" class="form-control" name="pass" placeholder="Contraseña" required>
            </div>
            <a id="login-button" class="btn btn-primary" type="button">
                <span class="px-4">Entrar</span>
            </a>
            <input type="hidden" value="<?php  echo isset($_GET['cod']) ? $_GET['cod'] : ''; ?>" id="mensaje">
          </form>
        </div>
      </div>
    </div>
  </div>
</body>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
<script src="resources/js/functions.js"></script>

<script>
  $(document).ready(function(){
    var cod = $('#mensaje').val();

    if(cod.length > 0 ){
      if(cod == 1){
        alert('El inicio de sesion es obligatorio');
      }else if(cod == 2){
        alert('');
      }
    }
  });
</script>
</html>

