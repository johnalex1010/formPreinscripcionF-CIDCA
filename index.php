<?php 
  session_start();
  require_once("../crm/consultas/conexion.php");
  require_once("../crm/consultas/querys.php");

  error_reporting (E_ALL & ~E_NOTICE & ~E_DEPRECATED);

  //VARIABLES PARA REMPLAZO DE CARACTERES NO VALIDOS (Evitar los ataque de inyección)
  $caraterNoValido = "<|>|&lt;|&gt;|'|{|}|;|VALUES|SELECT|INSERT|INTO|FROM|http|%|www|LIKE|ZAP|example|cookie|any|foo|bar|)";
  $permitidos = '/^[A-Z üÜáéíóúÁÉÍÓÚñÑ]{1,50}$/i';
  $reemplazoCaracter = "...";

  if ($_POST){
    $nombre = strtoupper(trim(htmlspecialchars(eregi_replace($caraterNoValido, $reemplazoCaracter, $_POST["nombre"]))));
    $apellido = strtoupper(trim(htmlspecialchars(eregi_replace($caraterNoValido, $reemplazoCaracter, $_POST["apellido"]))));
    $numero_documento = trim(floor(htmlspecialchars($_POST["numero_documento"])));
    $email = strtolower(trim(htmlspecialchars(eregi_replace($caraterNoValido, $reemplazoCaracter, $_POST["email"]))));
    $celular = trim(floor(htmlspecialchars($_POST["celular"])));
    $telefono_fijo = trim(htmlspecialchars($_POST["telefono_fijo"]));
    $detalle_programa = ucwords(strtolower(trim(htmlspecialchars(eregi_replace($caraterNoValido, $reemplazoCaracter, $_POST["detalle_programa"])))));
    $detalle_ciudad = ucwords(strtolower(trim(htmlspecialchars(eregi_replace($caraterNoValido, $reemplazoCaracter, $_POST["detalle_ciudad"])))));
    $detalle_medio = ucwords(strtolower(trim(htmlspecialchars(eregi_replace($caraterNoValido, $reemplazoCaracter, $_POST["detalle_medio"])))));
    $nombre_de_referido = ucwords(strtolower(trim(htmlspecialchars(eregi_replace($caraterNoValido, $reemplazoCaracter, $_POST["nombre_de_referido"])))));
    $ccreferido = trim(htmlspecialchars($_POST["ccreferido"]));


    $ClassErrorNombre = "";
    $ClassErrorApellido = "";
    $ClassErrorNumeroDocumento = "";
    $ClassErrorEmail = "";
    $ClassErrorCelular = "";
    $ClassErrorTelFijo = "";
    $ClassErrorCiudad = "";
    $ClassErrorPrograma = "";
    $ClassErrorMedio = "";
    $ClassErrorNombreReferido = "";
    $ClassErrorCCReferido = "";

    //VALIDO NOMBRE
    if ($nombre == "") {
      $msjNombre = "Campo Vacio.";
      $ClassErrorNombre = "error";
    }else{
      if (preg_match($permitidos,$nombre)){
        if (strlen($nombre)>=7 & strlen($nombre)<=50){
          $ClassErrorNombre = "";
          $_SESSION['nombre']=$nombre;
        }else{
          $msjNombre = "Longitud NO permitida, para el campo debe ser mínimo 7 caracteres y un máximo 50 caracteres.";
          $ClassErrorNombre = "error";
        }
      }else{
        $msjNombre = "Caracteres NO validos para este campo.";
        $ClassErrorNombre = "error";
      }
    }   
     //==================

    //VALIDO APELLIDO
    if ($apellido == "") {
      $msjApellido = "Campo Vacio.";
      $ClassErrorApellido = "error";
    }else{
      if (preg_match($permitidos,$apellido)){
        if (strlen($apellido)>=7 & strlen($apellido)<=50){
          $ClassErrorApellido = "";
          $_SESSION['apellido']=$apellido;
        }else{
          $msjApellido = "Longitud NO permitida, para el campo debe ser mínimo 7 caracteres y un máximo 50 caracteres.";
          $ClassErrorApellido = "error";
        }
      }else{
        $msjApellido = "Caracteres NO validos para este campo.";
        $ClassErrorApellido = "error";
      }
    }   
     //==================


    //VALIDO NUMERO DOCUMENTO
    $repetidos= mysql_query("SELECT numero_documento FROM preinscrito WHERE numero_documento='".$numero_documento."'");

    if ($numero_documento == "") {
      $msjNumeroDocumento = "Campo Vacio";
      $ClassErrorNumeroDocumento = "error";
    }elseif (!is_numeric($numero_documento)) {
      $msjNumeroDocumento = "No es un numero.";
      $ClassErrorNumeroDocumento = "error";
    }elseif (strlen($numero_documento)>=6 & strlen($numero_documento)<=20) {
      if ($numero_documento > 0){
        if(mysql_num_rows($repetidos)>0){
          $msjNumeroDocumento = "Usted YA está preinscrito. Uno de nuestros asesores se pondrá en contacto contigo.";
          $ClassErrorNumeroDocumento = "error";
        }else{
          $ClassErrorNumeroDocumento = "";
          $_SESSION['numero_documento']=$numero_documento;
        }       
      }else{
        $msjNumeroDocumento = "El numero ingresado no puede ser negativo.";
        $ClassErrorNumeroDocumento = "error";
      }
    }else{
      $msjNumeroDocumento = "Longitud NO permitida, para el campo debe ser mínimo 6 caracteres y un máximo 20 caracteres.";
      $ClassErrorNumeroDocumento = "error";
    }
    //=================

    //VALIDO EMAIL
    if ($email == "") {
      $msjEmail = "Campo Vacio";
      $ClassErrorEmail = "error";
    }elseif (strlen($email)>=2 || strlen($email)<=60){
      if(preg_match("/^([a-zA-Z0-9])+([a-zA-Z0-9\._-])*@([a-zA-Z0-9_-])+([a-zA-Z0-9\._-]+)+$/", $email)) {
        $_SESSION['email']=$email;
        $ClassErrorEmail = "";
      }else{
        $msjEmail = "Email NO valido.";
        $ClassErrorEmail = "error";
      }
    }
    //=================


    //VALIDO  CELULAR
    if ($celular == "") {
      $msjCelular = "Campo Vacio";
      $ClassErrorCelular = "error";
    }elseif (!is_numeric($celular)) {
      $msjCelular = "No es un numero.";
      $ClassErrorCelular = "error";
    }elseif (strlen($celular)>=6 & strlen($celular)<=20) {
      if ($celular > 0){
        $_SESSION['celular']=$celular;
        $ClassErrorCelular = "";
      }else{
        $msjCelular = "El numero ingresado no puede ser negativo.";
        $ClassErrorCelular = "error";
      }
    }else{
      $msjCelular = "Longitud NO permitida, para el campo debe ser mínimo 6 caracteres y un máximo 20 caracteres.";
      $ClassErrorCelular = "error";
    }
    //=================


    //VALIDO  TELEFONO FIJO
    if ($telefono_fijo == "") {
      $msjTelFijo = "";
      $ClassErrorTelFijo = "";
      $_SESSION['telefono_fijo']=$telefono_fijo;
    }else{
      if (!is_numeric($telefono_fijo)){
        $msjTelFijo = "No es un numero.";
        $ClassErrorTelFijo = "error";
      }else{
        if ($telefono_fijo > 0){
          if (strlen($telefono_fijo)>=6 & strlen($telefono_fijo)<=20){
            $_SESSION['telefono_fijo']=$telefono_fijo;
            $ClassErrorTelFijo = "";
          }else{
            $msjTelFijo = "Longitud NO permitida, para el campo debe ser mínimo 6 caracteres y un máximo 20 caracteres.";
            $ClassErrorTelFijo = "error";
          }
        }else{
          $msjTelFijo = "El numero ingresado no puede ser negativo.";
          $ClassErrorTelFijo = "error";
        }
      }
    }
    //=================

    // //VALIDO PROGRAMA
    if ($detalle_programa == "") {
      $msjPrograma = "Seleccione una opció de la Lista.";
      $ClassErrorPrograma = "error";
    }elseif (strlen($detalle_programa)>=5 & strlen($detalle_programa)<=100) {
      $_SESSION['detalle_programa']=$detalle_programa;
      $ClassErrorPrograma = "";
    }else{
      $ClassErrorPrograma = "error";
      $msjPrograma = "Longitud NO permitida. Seleccione un Programa de la Lista.";
    }
    //=================


    // //VALIDO CIUDAD
    if ($detalle_ciudad == "") {
      $msjCiudad = "Seleccione una opció de la Lista.";
      $ClassErrorCiudad = "error";
    }elseif (strlen($detalle_ciudad)>=5 & strlen($detalle_ciudad)<=100) {
      $_SESSION['detalle_ciudad']=$detalle_ciudad;
      $ClassErrorCiudad = "";
    }else{
      $ClassErrorCiudad = "error";
      $msjCiudad = "Longitud NO permitida. Seleccione un Programa de la Lista.";
    }
    //=================


    // //VALIDO MEDIO
    if ($detalle_medio == "") {
      $msjMedio = "";
      $ClassErrorMedio = "";
      $_SESSION['detalle_medio']=$detalle_medio;
    }elseif (strlen($detalle_medio)>=5 & strlen($detalle_medio)<=100) {
      $_SESSION['detalle_medio']=$detalle_medio;
      $ClassErrorMedio = "";
    }else{
      $ClassErrorMedio = "error";
      $msjMedio = "Longitud NO permitida. Seleccione un Programa de la Lista.";
    }
    //=================


    //VALIDO NOMBRE REFERIDO
    if ($nombre_de_referido == "") {
      $msjNombreReferido = "";
      $ClassErrorNombreReferido = "";
      $_SESSION['nombre_de_referido']=$nombre_de_referido;
    }else{
      if (preg_match($permitidos,$nombre_de_referido)){
        if (strlen($nombre_de_referido)>=7 & strlen($nombre_de_referido)<=50){
          $_SESSION['nombre_de_referido']=$nombre_de_referido;
          $ClassErrorNombreReferido = "";
        }else{
          $msjNombreReferido = "Longitud NO permitida, para el campo debe ser mínimo 7 caracteres y un máximo 50 caracteres.";
          $ClassErrorNombreReferido = "error";
        }
      }else{
        $msjNombreReferido = "Caracteres NO validos para este campo.";
        $ClassErrorNombreReferido = "error";
      }
    }   
     //==================

    //VALIDO  DOCUMENTO REFERIDO
    if ($ccreferido == "") {
      $msjCCReferido = "";
      $ClassErrorCCReferido = "";
      $_SESSION['ccreferido']=$ccreferido;
    }else{
      if (!is_numeric($ccreferido)){
        $msjCCReferido = "No es un numero.";
        $ClassErrorCCReferido = "error";
      }else{
        if ($ccreferido > 0){
          if (strlen($ccreferido)>=6 & strlen($ccreferido)<=20){
            $_SESSION['ccreferido']=$ccreferido;
            $ClassErrorCCReferido = "";
          }else{
            $msjCCReferido = "Longitud NO permitida, para el campo debe ser mínimo 6 caracteres y un máximo 20 caracteres.";
            $ClassErrorCCReferido = "error";
          }
        }else{
          $msjCCReferido = "El numero ingresado no puede ser negativo.";
          $ClassErrorCCReferido = "error";
        }
      }
    }
    //=================



    if (isset($_SESSION['nombre']) && isset($_SESSION['apellido']) && isset($_SESSION['numero_documento']) && isset($_SESSION['email']) && isset($_SESSION['celular']) && isset($_SESSION['telefono_fijo']) && isset($_SESSION['detalle_programa']) && isset($_SESSION['detalle_ciudad']) && isset($_SESSION['detalle_medio']) && isset($_SESSION['nombre_de_referido']) && isset($_SESSION['ccreferido'])) {

      header('location:../php/emailPreinscripcion.php');
    }
  }
?>
<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta http-equiv="x-ua-compatible" content="ie=edge">
    <title>Preinscripciones | Fundación CIDCA</title>
    <meta name="description" content="Preinscripciones | Fundación CIDCA" />
    <meta name="keywords" content="Preinscripciones, Fundación CIDCA, admisiones" />
    <meta name="author" content="Peter Finlan and Taty Grassini Codrops | John Fandiño" />
    <link rel="shortcut icon" href="img/favicon/favicon.ico">
    <meta name="msapplication-TileColor" content="#9C0D15">
    <meta name="theme-color" content="#9C0D15">
    <link rel="stylesheet" href="css/landio.css">
  </head>

  <body>
    <!-- Navegación
    ================================================== -->
    <nav id="totop" class="navbar navbar-dark bg-inverse bg-inverse-custom navbar-fixed-top">
      <div class="container">
        <a class="navbar-brand" href="#">
          <span class="icon-cidca"></span>
          <span class="sr-only">Fundación CIDCA</span>
        </a>
        <a class="navbar-toggler hidden-md-up pull-xs-right" data-toggle="collapse" href="#collapsingNavbar" aria-expanded="false" aria-controls="collapsingNavbar">
        &#9776;
      </a>
        
        <div id="collapsingNavbar" class="collapse navbar-toggleable-custom" role="tabpanel" aria-labelledby="collapsingNavbar">
          <ul class="nav navbar-nav pull-xs-right">
            <li class="nav-item nav-item-toggable active">
              <a class="nav-link" href="#programasOfertados">Programas ofertados</a>
            </li>
            <li class="nav-item nav-item-toggable">
              <a class="nav-link" href="#beneficios">Beneficios</a>
            </li>
          </ul>
        </div>
      </div>
    </nav>

    <!-- Formulario
    ================================================== -->
    <!-- <header id="landioCarousel" class="carousel carousel-header slide bg-inverse" data-ride="carousel" data-interval="0" role="banner"> -->
    <header id="landioCarousel" class="carousel-header jumbotron bg-inverse text-xs-center center-vertically" role="banner">
      <div class="carousel-inner" role="listbox">
        <div class="carousel-item active" style="">
          <!-- <div class="carousel-item active"> -->
          <div class="carousel-caption">
            <div class="grilla">
              <div class="celda">
                <h1 class="title-preinscripcion">INSCRIPCIONES ABIERTAS 2019</h1>
                <p class="txt-preinscripcion">Hola. Los datos básicos que diligenciarás, son unicamente para poder contactarte y poder guiarte en cualquier duda o información que necesites. La oficina de Admisiones es tu primer contacto con la Fundación CIDCA, por ello estamos prestos a apoyarte en este proceso, un Asesor Educativo se pondrá en contacto contigo.</p>
                <?php 
                  if (isset($_SESSION['exitoMsj'])) {
                    print $_SESSION['exitoMsj'];
                  }
                ?>
              </div>
            </div>
            <form action="#" class="formPreinscribete" method="POST">
              <p>Los campos con <b style="color: red">*</b> son obligatorios.</p>
              <div class="grilla">
                <div class="celda celdax4">
                  <span class="<?php print $ClassErrorNombre; ?>"><?php print $msjNombre ?></span>
                  <input type="text" name="nombre" id="nombre" value="<?php print $nombre?>" placeholder="(*) Nombres" >
                </div>
                <div class="celda celdax4">
                  <span class="<?php print $ClassErrorApellido; ?>"><?php print $msjApellido ?></span>
                  <input type="text" name="apellido" id="apellido" value="<?php print $apellido?>" placeholder="(*) Apellidos" >
                </div>
                <div class="celda celdax4">
                  <span class="<?php print $ClassErrorNumeroDocumento; ?>"><?php print $msjNumeroDocumento ?></span>
                  <input type="text" name="numero_documento" id="numero_documento" value="<?php print $numero_documento?>" placeholder="(*) No. Documento" >
                </div>
                <div class="celda celdax4">
                  <span class="<?php print $ClassErrorEmail ?>"><?php print $msjEmail ?></span>
                  <input type="text"  id="email" name="email"  value="<?php print $email ?>" placeholder="(*) Email" >
                </div>
              </div>
              <div class="grilla">                
                <div class="celda celdax4">
                  <span class="<?php print $ClassErrorCelular ?>"><?php print $msjCelular ?></span>
                  <input type="text" name="celular" id="celular" value="<?php print $celular ?>" placeholder="(*) No. Celular" >
                </div>
                <div class="celda celdax4">
                  <span class="<?php print $ClassErrorTelFijo ?>"><?php print $msjTelFijo ?></span>
                  <input type="text" name="telefono_fijo" id="telefono_fijo" value="<?php print $telefono_fijo ?>" placeholder="No. Telefono Fijo">
                </div>
                <div class="celda celdax4">
                  <span class="<?php print $ClassErrorCiudad ?>"><?php print $msjCiudad ?></span>
                  <select name="detalle_ciudad" id="detalle_ciudad" >
                    <option value="" disabled selected>Ciudad en la que quiere estudiar</option>
                    <?php while($result_cidudad=mysql_fetch_array($res_detalle_ciudad)){ ?>
                    <option value="<?php print $result_cidudad['detalle_ciudad'] ?>"><?php print $result_cidudad['detalle_ciudad'] ?></option>
                    <?php } ?>
                  </select>
                </div>
                <div class="celda celdax4">
                  <span class="<?php print $ClassErrorPrograma ?>"><?php print $msjPrograma ?></span>
                  <select name="detalle_programa" id="detalle_programa" >
                    <option value="" disabled selected>Programa que desea cursar</option>
                    <?php while($result_detalle_programa=mysql_fetch_array($res_detalle_programa)){ ?>
                    <option value="<?php print $result_detalle_programa['detalle_programa'] ?>"><?php print $result_detalle_programa['detalle_programa'] ?></option>
                    <?php } ?>
                  </select>
                </div>
              </div>
              <div class="grilla">
                <div class="celda celdax3">
                  <span class="<?php print $ClassErrorMedio ?>"><?php print $msjMedio ?></span>
                  <select name="detalle_medio" id="detalle_medio">
                    <option value="" disabled selected>¿Por que medio se entero?</option>
                    <?php while($result_detalle_medio=mysql_fetch_array($res_detalle_medio)){ ?>
                    <option value="<?php print $result_detalle_medio['detalle_medio'] ?>"><?php print $result_detalle_medio['detalle_medio'] ?></option>
                    <?php } ?>
                  </select>
                </div>
                <div class="celda celdax3">
                  <span class="<?php print $ClassErrorNombreReferido ?>"><?php print $msjNombreReferido ?></span>
                  <input type="text" name="nombre_de_referido" id="nombre_de_referido" value="<?php print $nombre_de_referido ?>" placeholder=" Nombre de quien lo refiere">
                </div>
                <div class="celda celdax3">
                  <span class="<?php print $ClassErrorCCReferido ?>"><?php print $msjCCReferido ?></span>
                  <input type="text" name="ccreferido" id="ccreferido" value="<?php print $ccreferido ?>" placeholder="No. Cedula de quien lo refiere">
                </div>
              </div>
              <div class="grilla">
                <div class="celda"><input class="boton" type="submit" value="Enviar"></div>
              </div>
            </form>
          </div>
        </div>
      </div>
    </header>

    <!-- Intro
    ================================================== -->
    <section class="section-intro bg-faded text-xs-center">
      <div class="container">
        <h3 class="wp wp-1">Visitanos en nuestro <a href="../">web site principal</a></h3>
        <p class="lead wp wp-2">¡En CIDCA hacemos nuestros, tus propósitos!</p>
        <img src="img/mock.png" alt="iPad mock" class="img-fluid wp wp-3">
      </div>
    </section>

    <!-- Programas Ofertados
    ================================================== -->
    <section id="programasOfertados" class="section-pricing bg-faded text-xs-center">
      <div class="container">
        <h3>Nuestros programas ofertados</h3>
        <div class="row p-y-3">
          <div class="col-md-4 p-t-md wp wp-5">
            <div class="card pricing-box">
              <div class="card-header text-uppercase">
                Pereria
              </div>
              <ul class="list-group list-group-flush p-x">
                <li class="list-group-item"><a href="" target="_blank">Tecnología en Finanzas y Sistemas Contables</a></li>
                <li class="list-group-item"><a href="" target="_blank">Tecnología en Gestión de Producción y Calidad</a></li>
                <li class="list-group-item"><a href="" target="_blank">Tecnicá Profesional en Operaciones Aduaneras</a></li>
              </ul>
              <a href="#" class="btn btn-primary-outline">Solicitar información</a>
            </div>
          </div>
          <div class="col-md-4 stacking-top">
            <div class="card pricing-box pricing-best p-x-0">
              <div class="card-header text-uppercase">
                Bogotá
              </div>
              <ul class="list-group list-group-flush p-x">
                <li class="list-group-item"><a href="" target="_blank">Tecnicá Profesional en Procesos Organizacionales</a></li>
                <li class="list-group-item"><a href="" target="_blank">Tecnología en Gestión de Sistemas Electromecánicos</a></li>
                <li class="list-group-item"><a href="" target="_blank">Tecnología en Gestión de Sistemas Mecatrónicos</a></li>
                <li class="list-group-item"><a href="" target="_blank">Tecnología en Seguridad Informatica</a></li>
                <li class="list-group-item"><a href="" target="_blank">Tecnicá Profesional en Operaciones Aduaneras</a></li>
                <li class="list-group-item"><a href="" target="_blank">Tecnología en Gestión de la Producción y Calidad</a></li>
                <li class="list-group-item"><a href="" target="_blank">Tecnología en Finanzas y Sistemas Contables</a></li>
                <li class="list-group-item"><a href="" target="_blank">Tecnología en Desarrollo de Sistemas Informáticos</a></li>
                <li class="list-group-item"><a href="" target="_blank">Tecnología en Servicios de Teleconmunicaciones</a></li>
              </ul>
              <a href="#" class="btn btn-primary">Solicitar información</a>
            </div>
          </div>
          <div class="col-md-4 p-t-md wp wp-6">
            <div class="card pricing-box">
              <div class="card-header text-uppercase">
                Villavicencio
              </div>
              <ul class="list-group list-group-flush p-x">
                <li class="list-group-item"><a href="" target="_blank">Tecnicá Profesional en Mantenimiento Electromecánico</a></li>
              </ul>
              <a href="#" class="btn btn-primary-outline">Solicitar información</a>
            </div>
          </div>
        </div>
      </div>
    </section>

    <!-- Beneficios
    ================================================== -->
    <section id="beneficios" class="section-testimonials text-xs-center bg-inverse">
      <div class="container">
        <h3 class="sr-only">Testimonials</h3>
        <div id="carousel-testimonials" class="carousel slide" data-ride="carousel" data-interval="0">
          <div class="carousel-inner" role="listbox">
            <div class="carousel-item active">
              <blockquote class="blockquote">
                <img src="img/face1.jpg" height="80" width="80" alt="Avatar" class="img-circle">
                <p class="h3">Financiaciión sin interes</p>
              </blockquote>
            </div>
            <div class="carousel-item">
              <blockquote class="blockquote">
                <img src="img/face2.jpg" height="80" width="80" alt="Avatar" class="img-circle">
                <p class="h3">Estudiar un carrera profesional con precios asequibles</p>
              </blockquote>
            </div>
            <div class="carousel-item">
              <blockquote class="blockquote">
                <img src="img/face3.jpg" height="80" width="80" alt="Avatar" class="img-circle">
                <p class="h3">Laboratorios innovadores</p>
              </blockquote>
            </div>
            <div class="carousel-item">
              <blockquote class="blockquote">
                <img src="img/face4.jpg" height="80" width="80" alt="Avatar" class="img-circle">
                <p class="h3">Practicas empresariales a partir de tu segundo semestre</p>
              </blockquote>
            </div>
            <div class="carousel-item">
              <blockquote class="blockquote">
                <img src="img/face5.jpg" height="80" width="80" alt="Avatar" class="img-circle">
                <p class="h3">Presencia en tres ciudades de colombia</p>
              </blockquote>
            </div>
          </div>
          <ol class="carousel-indicators">
            <li class="active"><img src="img/1.png" alt="Navigation avatar" data-target="#carousel-testimonials" data-slide-to="0" class="img-fluid img-circle"></li>
            <li><img src="img/2.png" alt="Navigation avatar" data-target="#carousel-testimonials" data-slide-to="1" class="img-fluid img-circle"></li>
            <li><img src="img/3.png" alt="Navigation avatar" data-target="#carousel-testimonials" data-slide-to="2" class="img-fluid img-circle"></li>
            <li><img src="img/4.png" alt="Navigation avatar" data-target="#carousel-testimonials" data-slide-to="3" class="img-fluid img-circle"></li>
            <li><img src="img/5.png" alt="Navigation avatar" data-target="#carousel-testimonials" data-slide-to="4" class="img-fluid img-circle"></li>
          </ol>
        </div>
      </div>
    </section>

    <!-- Footer
    ================================================== -->
    <footer class="section-footer bg-inverse" role="contentinfo">
      <div class="container">
        <div class="row">
          <div class="col-md-6 col-lg-5">
            <div class="media">
              <div class="media-left">
                <span class="media-object icon-logo display-1"></span>
              </div>
              <small class="media-body media-bottom">
                <br>
                &copy; Fundación CIDCA 2018.
                </small>
            </div>
          </div>
          <div class="col-md-6 col-lg-7">
            <ul class="nav nav-inline">
              <li class="nav-item active">
                <a class="nav-link" href="#">Preinscripción</a>
              </li>
              <li class="nav-item"><a class="nav-link" href="#programasOfertados">Programas ofertados</a></li>
              <li class="nav-item"><a class="nav-link" href="#beneficios">Beneficios</a></li>
              <li class="nav-item"><a class="nav-link scroll-top" href="#totop">Regresar arriba <span class="icon-arrow-up"></span></a></li>
            </ul>
          </div>
        </div>
      </div>
    </footer>

    <script src="js/jquery.min.js"></script>
    <script src="js/landio.min.js"></script>
  </body>
</html>

