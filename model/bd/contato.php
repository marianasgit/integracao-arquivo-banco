<?php

/*********************************************************************
 * Objetivo: arquivo responsavel por manipular os dados dentro do BD(insert, select, update, delete)
 * Autor: Mariana
 * Data: 11/03/2022
 * Versão: 1.0
 *********************************************************************/

//funcao para realizar o insert no bando de dados

//import do arquivo que estabelece a conexao com o BD
require_once('conexaoMysql.php');

function insertContato($dadosContato)
{
    //abre a conexao com o BD
    $conexao = conexaoMysql();

    //monta o script para enviar para o BD
    $sql = "insert into tblcontatos
                (nome,
                telefone,
                celular,
                email,
                obs)
            values
                ('" . $dadosContato['nome'] . "',
                 '" . $dadosContato['telefone'] . "',
                 '" . $dadosContato['celular'] . "',
                 '" . $dadosContato['email'] . "',
                 '" . $dadosContato['obs'] . "');";

    //executa o script no BD
    mysqli_query($conexao, $sql);
}

//funcao para realizar o update no banco de dados
function updateContato()
{
}

//funcao para excluir no banco de dados
function deleteContato()
{
}

//funcao para listar todos os contatos no BD
function selectAllCOntatos()
{
}
