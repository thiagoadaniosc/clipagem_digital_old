<?php

if ( !isset($security_flag) || $security_flag != true) {
    header('Location: /');
}

function mysqlCon() {
    $servidor = 'localhost';
    $usuario = 'root';
    $senha = 'root';
    $banco = 'clipagem_digital';
    
    $mysqli = new mysqli($servidor, $usuario, $senha, $banco);
    mysqli_set_charset($mysqli, "utf8");
    
    if(mysqli_connect_errno()) {
        echo ("Erro ao Conectar ao Banco de Dados");
        exit;
    }
    
    return $mysqli;
}

function cadastro_clipagem($conexao, $titulo, $veiculo, $editoria, $autor, $data, $pagina, $tipo, $tags) {
    $query = "INSERT INTO clipagens (titulo, veiculo, editoria, autor, data, pagina, tipo, tags) values ('$titulo', '$veiculo', '$editoria', '$autor', '$data', $pagina, '$tipo', '$tags')";
    
    $conexao->query($query);
    
    return $conexao;
}

function cadastro_arquivo($conexao, $id_clipagem, $nome) {
    $query = "INSERT INTO arquivos (id_clipagem, nome) values ($id_clipagem, '$nome')";
    $conexao->query($query);
}

function getNumRows($conexao){
    $query = "SELECT a.id_clipagem, c.titulo, c.veiculo, c.editoria, c.autor, c.data, c.pagina, c.tipo, c.tags, a.nome, a.ID FROM clipagens c, arquivos a where a.id_clipagem = c.ID";
    return $conexao->query($query)->num_rows;
}

function getNumRowsBusca($conexao, $valor, $pesquisar, $ano, $mes){
    $oderQuery = ' ORDER BY RIGHT(c.data, 4) DESC, SUBSTRING(c.data, 4, 3) DESC, SUBSTRING(c.data, 1, 2) DESC  ';
    
    if ($pesquisar == 'titulo') {
        $query = "SELECT a.id_clipagem, c.titulo, c.veiculo, c.editoria, c.autor, c.data, c.pagina, c.tipo, c.tags, a.nome, a.ID FROM clipagens c, arquivos a where a.id_clipagem = c.ID and c.titulo LIKE '%$valor%' and c.data LIKE '%$ano%' and c.data LIKE '%$mes%' $oderQuery";
    } elseif($pesquisar == 'data') {
        $query = "SELECT a.id_clipagem, c.titulo, c.veiculo, c.editoria, c.autor, c.data, c.pagina, c.tipo, c.tags, a.nome, a.ID FROM clipagens c, arquivos a where a.id_clipagem = c.ID and c.data LIKE '%$valor%' and c.data LIKE '%$ano%' and c.data LIKE '%$mes%'";
        
    } elseif($pesquisar == 'tags') {
        $query = "SELECT a.id_clipagem, c.titulo, c.veiculo, c.editoria, c.autor, c.data, c.pagina, c.tipo, c.tags, a.nome, a.ID FROM clipagens c, arquivos a where a.id_clipagem = c.ID and c.tags LIKE '%$valor%' and c.data LIKE '%$ano%' and c.data LIKE '%$mes%' $oderQuery";
    } elseif($pesquisar == 'autor') {
        $query = "SELECT a.id_clipagem, c.titulo, c.veiculo, c.editoria, c.autor, c.data, c.pagina, c.tipo, c.tags, a.nome, a.ID FROM clipagens c, arquivos a where a.id_clipagem = c.ID and c.autor LIKE '%$valor%' and c.data LIKE '%$ano%' and c.data LIKE '%$mes%' $oderQuery ";
    } elseif($pesquisar == 'veiculo') {
        $query = "SELECT a.id_clipagem, c.titulo, c.veiculo, c.editoria, c.autor, c.data, c.pagina, c.tipo, c.tags, a.nome, a.ID FROM clipagens c, arquivos a where a.id_clipagem = c.ID and c.veiculo LIKE '%$valor%' and c.data LIKE '%$ano%' and c.data LIKE '%$mes%' $oderQuery";
    } elseif($pesquisar == 'editoria') {
        $query = "SELECT a.id_clipagem, c.titulo, c.veiculo, c.editoria, c.autor, c.data, c.pagina, c.tipo, c.tags, a.nome, a.ID FROM clipagens c, arquivos a where a.id_clipagem = c.ID and c.editoria LIKE '%$valor%' and c.data LIKE '%$ano%' and c.data LIKE '%$mes%' $oderQuery";
    } else {
        $query = "SELECT a.id_clipagem, c.titulo, c.veiculo, c.editoria, c.autor, c.data, c.pagina, c.tipo, c.tags, a.nome, a.ID FROM clipagens c, arquivos a where a.id_clipagem = c.ID and (c.data LIKE '%$valor%' or c.titulo LIKE '%$valor%' or c.tags LIKE '%$valor%' or c.autor LIKE '%$valor%' or c.veiculo LIKE '%$valor%' or c.editoria LIKE '%$valor%') and c.data LIKE '%$ano%' and c.data  LIKE '%$mes%' $oderQuery";
    }

    return $conexao->query($query)->num_rows;
}

function listar($conexao, $inicio, $fim) {
    $query = "SELECT a.id_clipagem, c.titulo, c.veiculo, c.editoria, c.autor, c.data, c.pagina, c.tipo, c.tags, a.nome, a.ID FROM clipagens c, arquivos a where a.id_clipagem = c.ID order by a.id_clipagem DESC LIMIT $inicio, $fim";
    $results = $conexao->query($query);
    return $results;
}

function buscar($conexao,$pesquisar,$valor, $ano, $mes, $inicio, $fim){
    // echo 'Inicio: ' . $inicio . 'Fim: ' . $fim;
    $oderQuery = ' ORDER BY RIGHT(c.data, 4) DESC, SUBSTRING(c.data, 4, 3) DESC, SUBSTRING(c.data, 1, 2) DESC  ';
    
    
    if ($pesquisar == 'titulo') {
        $query = "SELECT a.id_clipagem, c.titulo, c.veiculo, c.editoria, c.autor, c.data, c.pagina, c.tipo, c.tags, a.nome, a.ID FROM clipagens c, arquivos a where a.id_clipagem = c.ID and c.titulo LIKE '%$valor%' and c.data LIKE '%$ano%' and c.data LIKE '%$mes%' $oderQuery LIMIT $inicio, $fim";
    } elseif($pesquisar == 'data') {
        $query = "SELECT a.id_clipagem, c.titulo, c.veiculo, c.editoria, c.autor, c.data, c.pagina, c.tipo, c.tags, a.nome, a.ID FROM clipagens c, arquivos a where a.id_clipagem = c.ID and c.data LIKE '%$valor%' and c.data LIKE '%$ano%' and c.data LIKE '%$mes%' LIMIT $inicio, $fim";
        
    } elseif($pesquisar == 'tags') {
        $query = "SELECT a.id_clipagem, c.titulo, c.veiculo, c.editoria, c.autor, c.data, c.pagina, c.tipo, c.tags, a.nome, a.ID FROM clipagens c, arquivos a where a.id_clipagem = c.ID and c.tags LIKE '%$valor%' and c.data LIKE '%$ano%' and c.data LIKE '%$mes%' $oderQuery  LIMIT $inicio, $fim";
    } elseif($pesquisar == 'autor') {
        $query = "SELECT a.id_clipagem, c.titulo, c.veiculo, c.editoria, c.autor, c.data, c.pagina, c.tipo, c.tags, a.nome, a.ID FROM clipagens c, arquivos a where a.id_clipagem = c.ID and c.autor LIKE '%$valor%' and c.data LIKE '%$ano%' and c.data LIKE '%$mes%' $oderQuery  LIMIT $inicio, $fim";
    } elseif($pesquisar == 'veiculo') {
        $query = "SELECT a.id_clipagem, c.titulo, c.veiculo, c.editoria, c.autor, c.data, c.pagina, c.tipo, c.tags, a.nome, a.ID FROM clipagens c, arquivos a where a.id_clipagem = c.ID and c.veiculo LIKE '%$valor%' and c.data LIKE '%$ano%' and c.data LIKE '%$mes%' $oderQuery LIMIT $inicio, $fim";
    } elseif($pesquisar == 'editoria') {
        $query = "SELECT a.id_clipagem, c.titulo, c.veiculo, c.editoria, c.autor, c.data, c.pagina, c.tipo, c.tags, a.nome, a.ID FROM clipagens c, arquivos a where a.id_clipagem = c.ID and c.editoria LIKE '%$valor%' and c.data LIKE '%$ano%' and c.data LIKE '%$mes%' $oderQuery  LIMIT $inicio, $fim";
    } else {
        $query = "SELECT a.id_clipagem, c.titulo, c.veiculo, c.editoria, c.autor, c.data, c.pagina, c.tipo, c.tags, a.nome, a.ID FROM clipagens c, arquivos a where a.id_clipagem = c.ID and (c.data LIKE '%$valor%' or c.titulo LIKE '%$valor%' or c.tags LIKE '%$valor%' or c.autor LIKE '%$valor%' or c.veiculo LIKE '%$valor%' or c.editoria LIKE '%$valor%' or a.id_clipagem LIKE '%$valor%') and c.data LIKE '%$ano%' and c.data  LIKE '%$mes%' $oderQuery LIMIT $inicio, $fim";
    }

    $results = $conexao->query($query);
    
    return $results;
}



function buscarClipagem($conexao, $id) {
    $query_clipagem = "SELECT * from clipagens c where c.ID = $id;";
    $query_arquivo = "SELECT a.id_clipagem, a.nome from arquivos a where a.id_clipagem = $id;";
    $resultClipagem = $conexao->query($query_clipagem);
    $clipagem;
    $arquivo;
    
    if ($resultClipagem->num_rows < 1) {
        header('Location: /clipagens');
    } else {
        foreach($resultClipagem as $c) {
            $clipagem = $c;
        }
        
        $conexao = mysqlCon();
        
        $resultArquivo = $conexao->query($query_arquivo);
        
        foreach($resultArquivo as $a) {
            $arquivo = $a;
        }
        
        $results = array_merge($clipagem, $arquivo);
        
        return $results;
    }
}

function deletar($conexao, $id) {
    $query = "DELETE FROM clipagens WHERE ID = $id";
    $conexao->query($query);
}

function checkLogin($conexao, $user, $pass) {
    $query = "SELECT * FROM usuarios where login =  '$user' and senha = '$pass';";
    $results = $conexao->query($query);
    if ($results->num_rows == 1) {
        return $results->fetch_assoc();
    } else {
        return false;
    }
    
    
    
}

function buscarArquivo($conexao, $id_clipagem){
    $query = "SELECT * FROM arquivos where id_clipagem = $id_clipagem";
    $results = $conexao->query($query);
    return $results->fetch_assoc();
    
}

function atualizarArquivo($conexao, $id, $fileName){
    echo $fileName;
    $query = "UPDATE arquivos a SET a.nome = '$fileName' where id = $id";
    $results = $conexao->query($query);
    return $conexao->affected_rows;

}

function atualizarClipagem($conexao,$id, $titulo, $veiculo, $editoria, $autor, $data, $pagina, $tipo, $tags) {
    //echo 'ID: '.  $id;
    $query = "UPDATE clipagens c SET c.titulo = '$titulo', c.veiculo = '$veiculo', c.editoria = '$editoria', c.autor = '$autor', c.data = '$data', c.pagina =" . intval($pagina) . ", c.tipo = '$tipo', c.tags = '$tags' where c.ID = $id";
    $conexao->query($query);
    //echo 'Linhas Afetadas' . $conexao->affected_rows;
   // echo $conexao->error;
}