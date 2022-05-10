<?php

/********************************************************************
 * Objetivo: Arquivo responsável pela manipulação de dados de estados 
 * Obs: Este arquivo fará a ponte entre a View e a Model
 * 
 * Autor: Mariana
 * Data: 10/05/2022
 * Versão: 1.0
 *******************************************************************/

// Import do arquivo de configuração do projeto
require_once('modulo/config.php');

//função para solicitar os dados da model e encaminhar a lista de estados para a view
function listarEstado()
{
    //import do arquivo que vai buscar os dados
    require_once('model/bd/estado.php');

    //chama a funcao que vai buscar os dados no bd
    $dados = selectAllEstados();

    //
    if (!empty($dados))
        return $dados;
    else
        return false;
}


?>