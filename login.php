<?php require_once 'includes' . DIRECTORY_SEPARATOR . 'header.php'; ?>
<div class="row col-lg-6 col-xl-4 col-md-10 baseBody justify-content-center" style="overflow:auto;">
    <img src="img/brasao.jpg" class="img-fluid" alt="" style="height:150px;">
    <h4 class="col-lg-12 text-center text-primary">Autenticação</h4>
    <?php if (isset($_GET['login']) && $_GET['login'] == 'false'){?>
        <small class="text-danger">Usuário ou senha incorretos !</small>
    <?php } else {?>
    <small>Autenticação necessária</small>
    <?php } ?>

    <form action="/logar" method="post" class="col-lg-12 m-4 mb-5">
        <div class="form-group">
            <input class="form-control" type="text" name="usuario" id="" placeholder="Usuário...">
            <input class="form-control mt-4" type="password" name="senha" id="" placeholder="Senha...">
            <input class="form-control mt-4 btn btn-primary" type="submit" name="" id="" value="ENTRAR" placeholder="Senha...">
        </div>
    </form>
    <p class="text-center" style="margin-top: 20px">
    <span class="text-center badge badge-primary">Versão: <?=$version?></span>
    <br>
    <span class="text-center badge badge-light">By: TI CMSJ</span>
    

    </p>
</div>
<?php require_once 'includes' . DIRECTORY_SEPARATOR . 'footer.php'; ?>