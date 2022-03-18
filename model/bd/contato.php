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

    //validacao para verificar se o script sql esta correto
    if (mysqli_query($conexao, $sql)) {
        if (mysqli_affected_rows($conexao))
            return true;
        else
            return false;
    } else
        return false;
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
    //abre a conexao com o banco de dados
    $conexao = conexaoMysql();

    //script para listar todos os dados do banco de dados 
    $sql = 'select * from tblcontatos';

    //executa o script sql no BD e guarda o retorno dos dados se houver
    $result = mysqli_query($conexao, $sql);                                  //quando mandados um script para o banco do tipo insert, delete, etc.
    // o script nao tera retorno, ao contrario do select que precisa retornal algo

    //valida se o BD retorna registros
    if ($result) {

        $cont = 0;
        //nesta repeticao estamos convertendo os dados do banco de daos do BD em um array ($rsDados),
        // alem de o proprio while conseguir gerenciar a quantidade de vezes que dveria ser feita a repeticao
        while ($rsDados = mysqli_fetch_assoc($result)) {
            //cria um array com os dados do banco de dados 
            $arrayDados[$cont] = array(
                "nome"       =>   $rsDados['nome'],
                "telefone"   =>   $rsDados['telefone'],
                "celular"    =>   $rsDados['celular'],
                "email"      =>   $rsDados['email'],
                "obs"        =>   $rsDados['obs']
            );
            $cont++;
        }

        return $arrayDados;
    }
}
