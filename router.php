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


    //Validação para verifivar se a requisição é um POST de um formulário
    if ($_SERVER['REQUEST_METHOD'] == 'POST') {
        echo('Requisição do form');
    }
