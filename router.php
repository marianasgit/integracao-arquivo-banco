<?php

/*****************************************************************************
 * Objetivo: Arquivo de rota para seguimentar as ações encaminhadas pela View
 *           (dados de um form, listagem de dados, ação de excluir ou atualizar)
 *            Esse arquivo será responsável por encaminhar as solicitações para 
 *            a controller  
 * 
 * Autor: Mariana
 * Data: 04/03/2022
 * Versão: 1.0
 *******************************************/


$action = (string) null;
$component = (string) null;


// Validação para verifivar se a requisição é um POST de um formulário
if ($_SERVER['REQUEST_METHOD'] == 'POST' || $_SERVER['REQUEST_METHOD'] == 'GET') {

    // Recebendo dados via url para saber quem está solicitando e qual ação será realizada
    $component = strtoupper($_GET['component']);
    $action = strtoupper($_GET['action']);

    // Estrutura condicional para validar quem está solicitando algo para o router
    switch ($component) {

        case 'CONTATOS':

            // Import da controller contato
            require_once('controller/controllerContatos.php');

            //validacao para identificar o tipo de acao que sera realizada
            if ($action == 'INSERIR') 
            {

                // Validação para tratar se a imagem existe na chegada dos dados
                if (isset($_FILES) && !empty($_FILES))
                {

                    $arrayDados = array (
                        $_POST, 
                        "file" => $_FILES,
                    );

                    //chama a funcao de inserir na controller
                    $resposta = inserirContato($arrayDados);

                } else 
                {
                    $arrayDados = array (
                        $_POST, 
                        "file" => null,
                    );

                    $resposta = inserirContato($arrayDados);
                }

                //valida o tipo de dado que a controller retorna
                if (is_bool($resposta)) //se for booleano
                {
                    //verificar se o retorno foi verdadeiro
                    if ($resposta)
                        echo ("<script> 
                                alert('Registro inserido com sucesso!');
                                window.location.href = 'index.php'; 
                            </script>"); // essa funcao retorna a página inicial apos a execucao
                } elseif (is_array($resposta))

                    echo ("<script> 
                            alert('" . $resposta['message'] . "');
                            window.history.back(); 
                        </script>");
            } elseif ($action == 'DELETAR') 
            {
                //Recebe o id do registro que devera ser excluido, e foi enviado pela url no link da imagem do excluir que foi acionado na index
                $idcontato = $_GET['id'];

                $foto = $_GET['foto'];

                $arrayDados = array(
                    "id"   => $idcontato,
                    "foto" => $foto  // Criamos um array para encaminhar os dados do BD para a controller 
                    
                );

                //chama a funcao de excluir na controller
                $resposta = excluirContato($arrayDados);

                if (is_bool($resposta)) {
                    if ($resposta) {
                        echo ("<script> 
                                alert('Registro excluído com sucesso!');
                                window.location.href = 'index.php'; 
                            </script>");
                    }
                } elseif (is_array($resposta)) {
                    echo ("<script> 
                        alert('" . $resposta['message'] . "');
                        window.history.back(); 
                   </script>");
                }
            } elseif ($action == 'BUSCAR') 
            {
                // Recebe o id do registro que devera ser editado, e foi enviado pela url no link da imagem do editar que foi acionado na index
                $idcontato = $_GET['id'];

                // Chama a funcao de editar na controller
                $dados = buscarContato($idcontato);

                // Ativa a ultilização de variaveis de sessao no servidor
                session_start();

                // Guarda em uma variavel de sessao os dados que o BD retornou para a busca do ID. 
                // Obs: essa variavel de sessao sera utilizada na index.php, para colocar os dados nas caixas de texto 
                $_SESSION['dadosContato'] = $dados;

                // Utilizando o header tambem poderemos chamar a index.php, 
                // porem haverá uma ação de carregamento no navegador, piscando a tela novamente 
                //header('location: index.php');

                // Utilizando o require, iremos apenas importar a tela da index, assim não havendo um novo carregamento da página
                require_once('index.php');
            } elseif ($action == 'EDITAR')
            {
                //Recebe o id que foi encaminhado no action do form
                $idcontato = $_GET['id'];

                //Recebe o nome da foto que foi enviada pelo get do form
                $foto = $_GET['foto'];

                // Cria array contendo id e nome da foto para enviar para a controller
                $arrayDados = array (

                    "id"   => $idcontato,
                    "foto" => $foto,
                    "file" => $_FILES,
                    $_POST
                );

                //chama a funcao de editar na controller
                $resposta = atualizarContato($arrayDados);

                //valida o tipo de dado que a controller retorna
                if (is_bool($resposta)) //se for booleano
                {
                    //verificar se o retorno foi verdadeiro
                    if ($resposta)
                        echo ("<script> 
                                alert('Registro atualizado com sucesso!');
                                window.location.href = 'index.php'; 
                            </script>"); // essa funcao retorna a página inicial apos a execuca
                } elseif (is_array($resposta))

                    echo ("<script> 
                        alert('" . $resposta['message'] . "');
                        window.history.back(); 
                   </script>");
            }

        break;
    }
}
