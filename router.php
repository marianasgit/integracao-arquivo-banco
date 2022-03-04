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


//Validação para verifivar se a requisição é um POST de um formulário
if ($_SERVER['REQUEST_METHOD'] == 'POST') {

    //recebendo dados via url para saber quem está solicitando e qual ação será realizada
    $component = strtoupper($_GET['component']);
    $action = $_GET['action'];

    //estrutura condicional para validar quem está solicitando algo para o router
    switch ($component) {
        case 'CONTATOS':
            echo ('chamando a controler de contato');
            break;
    }
}
