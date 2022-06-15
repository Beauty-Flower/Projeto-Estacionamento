<?php
  /******************************************************************************************
    * Objetivo: Arquivo responsável pela criação das constantes e funções globais do projeto.
    * Autores: Florbela e Samea
    * Data: 01/06/2022
    * Versão: 1.0
  *******************************************************************************************/

    define('SRC', $_SERVER['DOCUMENT_ROOT'].'/Projeto-Estacionamento/');

    //Função para converter um array em um formato JSON
    function createJSON($arrayDados){

      //Validação para tratar array sem dados
      if(!empty($arrayDados)){  //Configura o padrão da conversao para o formato Json
          header('Content-Type: application/json');
          $dadosJson = json_encode($arrayDados);
          return $dadosJson;
        }else{
            return false;
        }
      }
?>