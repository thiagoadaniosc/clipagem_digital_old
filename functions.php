<?php
class FUNCTIONS {
    
    public static function cadastrarClipagem() {
        
        $conexao = mysqlCon();
        
        $titulo = $_POST['titulo'];
        $veiculo = $_POST['veiculo'];
        $editoria = $_POST['editoria'];
        $autor = $_POST['autor'];
        $data = $_POST['data'];
        $date = new DateTime($data);
        $data = $date->format('d/m/Y');
        $pagina = $_POST['pagina'];
        $tipo = $_POST['tipo'];
        $tags = $_POST['tags'];
        
        $conexao = cadastro_clipagem($conexao, $titulo, $veiculo, $editoria, $autor, $data, $pagina, $tipo, $tags);
        $id_clipagem = $conexao->insert_id;
        $date = new DateTime($data);
        $data = $date->format('d-m-Y');
        $fileName = strtolower($titulo). '_' . strtolower($veiculo). '-' . $data . '.pdf';
        $fileName = str_replace(' ', '_', $fileName);
        
        FUNCTIONS::uploadArquivos($conexao, $id_clipagem, $fileName);
        
        /*$id_clipagem = $conexao->insert_id;
        
        $fileName =  $id_clipagem. '-' .$_FILES['file']['name'];
        
        $tempFile = $_FILES['file']['tmp_name'];     
        
        $targetPath = dirname( __FILE__ ) . DIRECTORY_SEPARATOR. 'uploads'. DIRECTORY_SEPARATOR;
        
        $targetFile =  $targetPath. $fileName;
        
        move_uploaded_file($tempFile,$targetFile);
        
        cadastro_arquivo($conexao, $id_clipagem, $fileName);
        */
    }
    
    public static function uploadArquivos($conexao, $id_clipagem, $fileName){
        require_once 'vendor' . DIRECTORY_SEPARATOR . 'autoload.php';
        $pdf = new \Clegginabox\PDFMerger\PDFMerger;
        
        //$fileName =  $id_clipagem. '-' .$_FILES['file']['name'];
        //$targetPath = dirname( __FILE__ ) . DIRECTORY_SEPARATOR. 'uploads'. DIRECTORY_SEPARATOR;
        //$targetFile =  $targetPath. $fileName;
        //move_uploaded_file($tempFile,$targetFile);
        
        foreach ($_FILES['file']['tmp_name'] as $tempFile){
            $pdf->addPDF($tempFile, 'all');        
        }
        
        $pdf->merge('file', 'uploads'. DIRECTORY_SEPARATOR . $fileName, 'P');
        
        cadastro_arquivo($conexao, $id_clipagem, $fileName);
        
    }
    
    public static function editarClipagem() {
        
    }
    
    public static function listarClipagens() {
        $conexao = mysqlCon();
        $lista = listar($conexao);
        return $lista;
    }
    
    public static function buscarClipagens() {
        $pesquisar = $_GET['pesquisar'];
        $valor = $_GET['valor'];
        $ano = isset($_GET['ano']) && !empty($_GET['ano']) ? $_GET['ano'] : '';
        $mes = isset($_GET['mes']) && !empty($_GET['mes']) ? '/' . $_GET['mes'] . '/' : '';
        $conexao = mysqlCon();
        $lista = buscar($conexao,$pesquisar, $valor, $ano, $mes);
        return $lista;
    }
    
    public static function deletarClipagem() {
        $conexao = mysqlCon();
        $id = $_GET['id'];
        deletar($conexao,$id);
        header('Location: clipagens');
    }
    
    public static function downloadPesquisa(){
        require_once 'vendor' . DIRECTORY_SEPARATOR . 'autoload.php';
        $pdf = new \Clegginabox\PDFMerger\PDFMerger;
        
        if (isset($_SESSION['arquivos'])) {
            $fileName = $_SESSION['usuario']. '_' . 'pesquisa.pdf';
            foreach($_SESSION['arquivos'] as $arquivo){
                echo $arquivo;
                $pdf->addPDF('uploads'. DIRECTORY_SEPARATOR . $arquivo, 'all');
            }
            $pdf->merge('file', 'pesquisas'. DIRECTORY_SEPARATOR . $fileName);
            header('Location: pesquisas/' . $fileName);
            
            //$pdf->merge('donwload', 'uploads'. DIRECTORY_SEPARATOR . 'testedownload.pdf');
            //unset($_SESSION['arquivos']);
        } else {
            header('Location: /clipagens');   
        }
    }
    
    public static function login(){
       // $conexao = mysqlCon();
        $user = $_POST['usuario'];
        $pass = $_POST['senha'];
        /*
        $checkLogin = checkLogin($conexao, $user, $pass);
        
        
        if ($checkLogin != false) {
            $_SESSION['login'] = true;
            $_SESSION['nome'] = $checkLogin['nome'];
            $_SESSION['usuario'] = $checkLogin['login'];
            header('Location:/');
        } else {
            header('Location: /login?login=false');
        }*/
        
        $ldap_server = 'ad.cmsj.sc.gov.br';
        $ldapport = 389;
        $dn="DC=ad,DC=cmsj,DC=sc,DC=gov,DC=br";
        // Tenta se conectar com o servidor
        //try {
            $connect = ldap_connect($ldap_server, $ldapport) or die('erro');
            ldap_set_option($connect, LDAP_OPT_PROTOCOL_VERSION, 3);
            ldap_set_option($connect, LDAP_OPT_REFERRALS, 0);
            
            if($connect != null) {
                echo 'Conectado ao Servidor <br>';
                if ($result = ldap_bind($connect, 'AD-CMSJ\\' . $user, $pass)) {
                    
                    $_SESSION['login'] = true;
                    $_SESSION['usuario'] = $user;
                    
                    
                    $filter="(samaccountname=$user)";
                    
                    $res = ldap_search($connect, $dn, $filter);
                    
                    $entries = ldap_get_entries($connect, $res);

                    $_SESSION['nome'] = $entries[0]['cn'][0];
                    
                    $isComunicacao = preg_grep("/^.*Comunicação.*/", $entries[0]['memberof']);
                    

                    if ($isComunicacao != null) {
                        $_SESSION['admin'] = true;
                        echo 'É Comunicação';
                    } else {
                        $_SESSION['admin'] = false;
                    }
                    echo 'Logado';
                    header('Location: /');
                } else {
                    echo 'deslogado';
                    header('Location: /login?login=false');
                }
            } else {
                echo 'Problema na Conexão';
                exit;
            }
            /* } catch(Exception $e){
                echo 'Erro ao Conectar';
                exit;
            }*/
            
            
            //var_dump($entries);
            
            //var_dump($entries[0]['department'][0]);
            //var_dump($entries[0]['cn'][0]);
            //var_dump($entries[0]['memberof']);
            
            //var_dump(in_array('CN=Comunicação', $entries[0]['memberof']));
            
            
            
            
            // var_dump($result);
            /*
            if ($_POST['usuario'] == 'cmsj' && $_POST['senha'] == 'cmsj@2018') {
                $_SESSION['login'] = true;
                $_SESSION['usuario'] = 'CMSJ';
                echo $_SESSION['usuario'];
                header('Location:/');
            } else {
                header('Location: /login?login=false');
            }
            */
        }
        
        public static function logon(){
            session_destroy();
        }

        public static function getHeader(){
            require_once 'includes' . DIRECTORY_SEPARATOR . 'header.php';
        }

        public static function getFooter(){
            require_once 'includes' . DIRECTORY_SEPARATOR . 'footer.php';
        }
    } 
    
    ?>