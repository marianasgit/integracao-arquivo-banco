<?php

/*********************************************************************
 * Objetivo: Arquivo responsavel pela criação de variaveis e constantes do projeto
 * Autor: Mariana
 * Data: 25/04/2022
 * Versão: 1.0
 *********************************************************************/

/**************** Variáveis e constantes globais do projeto **************/


// Limitação de 5mb para upload de imagens
const MAX_FILE_UPLOAD = 5120;

// Definindo o tipo de extensão que vai ser aceita
const EXT_FILE_UPLOAD = array("image/jpg", "image/jpeg", "image/png", "image/gif");

const DIRETORIO_FILE_UPLOAD = "arquivos/";

define('SRC', $_SERVER['DOCUMENT_ROOT'].'/mariana/PWBE/AULA-07/');


/********* Funções globais para p projeto **************/

// função para converter o array em JSON

function createJSON ($arrayDados)
{

    // Validação de array vazio
    if (!empty($arrayDados))
    {
        // Configura o padrão da conversao para formato json
        header('Content-Type: application/json');
        $dadosJSON = json_encode($arrayDados); // converte um array para json, e o json_decode faz o processo contrario
        
        return $dadosJSON;

    } else 
    {
        return false;
    }

}

?>