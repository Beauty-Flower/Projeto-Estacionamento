<?php
    /**************************************************************
     * Objetivo: Arquivo principal da API que irá receber a url 
     * requisitada e redirecionar para as APIs. (papel da router)
     * Autores: Florbela e Samea
     * Data: 01/06/2022
     * Versão: 1.0
     *************************************************************/

    //Ativa os endereços de sites que poderão fazer requisições
    header('Access-Control-Allow-Origin: *');
    //Ativa os métodos do protocolo HTTP que irão requisitar a API
    header('Access-Control-Allow-Methods: GET, DELETE, POST, PUT, OPTIONS');
    //Ativa o Content-Type das requisições
    header('Access-Control-Allow-Header: Content-Type');
    //Libera quais Content-type serão usados na API
    header('Content-Type: application/json');

    //Recebe a url digitada na requisição
    $urlHTTP = (string) $_GET['url'];

    //converte a url requisitada em um array para dividir as opções de busca, que é separada pela "/"
    $url = explode('/', $urlHTTP);

    //VERIFICA QUAL A API SERÁ ENCAMINHADA A REQUISIÇÃO
    switch(strtoupper($url[0])){
        case 'PRECOS';
            require_once('precoAPI/index.php');
            break;
        case 'REGISTROS';
            require_once('registrosAPI/index.php');

            break;
    }
?>