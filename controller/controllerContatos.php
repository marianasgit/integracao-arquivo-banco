<?php

/********************************************************************
 * Objetivo: Arquivo responsável pela manipulação de dados de contato 
 * Obs: Este arquivo fará a ponte entre a View e a Model
 * 
 * Autor: Mariana
 * Data: 04/03/2022
 * Versão: 1.0
 *******************************************************************/

//função para receber dados da View e encaminhar para a model (Inserir)
function inserirContato($dadosContato)
{
    //validacao para verificar se o objeto esta vazio
    if (!empty($dadosContato)) {

        //validacao de caixa vazia dos elementos: nome, celular e email pois são obrugatorias no BD
        if (!empty($dadosContato['txtNome']) && !empty($dadosContato['txtCelular']) && !empty($dadosContato['txtEmail'])) {
            //criacao do array de dados que sera encaminhado para a model para inserir no BD,
            // é importante criar esse array conforme a necessidade de manipulação do BD
            //obs: criar as chaves do array conforme os nomes dos atributos do banco de dados 
            $arrayDados = array(
                "nome" => $dadosContato['txtNome'],
                "telefone" => $dadosContato['txtTelefone'],
                "celular" => $dadosContato['txtCelular'],
                "email" => $dadosContato['txtEmail'],
                "obs" => $dadosContato['txtObs'],
            );

            require_once('model/bd/contato.php');

            insertContato($arrayDados);
        } else
            echo ('Dados inválidos');
    }
}


//função para receber dados da View e encaminhar para a model (Atualizar)
function atualizarContato()
{
}


//função para realizar a exclusão de um contato 
function excluirContato()
{
}


//função para solicitar os dados da model e encaminhar a lista de contatos para a view
function listarContato()
{
}
