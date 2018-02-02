<?php

if ( !isset($security_flag) || $security_flag != true) {
    header('Location: /');
}

function mysqlCon() {
    $servidor = 'localhost';
    $usuario = 'root';
    $senha = '';
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

function listar($conexao) {
    $query = "SELECT a.id_clipagem, c.titulo, c.veiculo, c.editoria, c.autor, c.data, c.pagina, c.tipo, c.tags, a.nome, a.ID FROM clipagens c, arquivos a where a.id_clipagem = c.ID";
    $results = $conexao->query($query);
    return $results;
}

function buscar($conexao,$pesquisar,$valor, $ano, $mes){
    
    if ($pesquisar == 'titulo') {
        $query = "SELECT a.id_clipagem, c.titulo, c.veiculo, c.editoria, c.autor, c.data, c.pagina, c.tipo, c.tags, a.nome, a.ID FROM clipagens c, arquivos a where a.id_clipagem = c.ID and c.titulo LIKE '%$valor%' and c.data LIKE '%$ano%' and c.data LIKE '%$mes%';";
    } elseif($pesquisar == 'data') {
        $query = "SELECT a.id_clipagem, c.titulo, c.veiculo, c.editoria, c.autor, c.data, c.pagina, c.tipo, c.tags, a.nome, a.ID FROM clipagens c, arquivos a where a.id_clipagem = c.ID and c.data LIKE '%$valor%' and c.data LIKE '%$ano%' and c.data LIKE '%$mes%';";
        
    } elseif($pesquisar == 'tags') {
        $query = "SELECT a.id_clipagem, c.titulo, c.veiculo, c.editoria, c.autor, c.data, c.pagina, c.tipo, c.tags, a.nome, a.ID FROM clipagens c, arquivos a where a.id_clipagem = c.ID and c.tags LIKE '%$valor%' and c.data LIKE '%$ano%' and c.data LIKE '%$mes%';";
    } elseif($pesquisar == 'autor') {
        $query = "SELECT a.id_clipagem, c.titulo, c.veiculo, c.editoria, c.autor, c.data, c.pagina, c.tipo, c.tags, a.nome, a.ID FROM clipagens c, arquivos a where a.id_clipagem = c.ID and c.autor LIKE '%$valor%' and c.data LIKE '%$ano%' and c.data LIKE '%$mes%';";
    } elseif($pesquisar == 'veiculo') {
        $query = "SELECT a.id_clipagem, c.titulo, c.veiculo, c.editoria, c.autor, c.data, c.pagina, c.tipo, c.tags, a.nome, a.ID FROM clipagens c, arquivos a where a.id_clipagem = c.ID and c.veiculo LIKE '%$valor%' and c.data LIKE '%$ano%' and c.data LIKE '%$mes%';";
    } elseif($pesquisar == 'editoria') {
        $query = "SELECT a.id_clipagem, c.titulo, c.veiculo, c.editoria, c.autor, c.data, c.pagina, c.tipo, c.tags, a.nome, a.ID FROM clipagens c, arquivos a where a.id_clipagem = c.ID and c.editoria LIKE '%$valor%' and c.data LIKE '%$ano%' and c.data LIKE '%$mes%';";
    } else {
        $query = "SELECT a.id_clipagem, c.titulo, c.veiculo, c.editoria, c.autor, c.data, c.pagina, c.tipo, c.tags, a.nome, a.ID FROM clipagens c, arquivos a where a.id_clipagem = c.ID and (c.data LIKE '%$valor%' or c.titulo LIKE '%$valor%' or c.tags LIKE '%$valor%' or c.autor LIKE '%$valor%' or c.veiculo LIKE '%$valor%' or c.editoria LIKE '%$valor%') and c.data LIKE '%$ano%' and c.data LIKE '%$mes%'";
    }
    
    $results = $conexao->query($query);
    
    return $results;
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