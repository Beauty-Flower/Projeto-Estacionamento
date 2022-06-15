<?php
    /****************************************************************************
     * Objetivo: Arquivo responsável pela manipulação de dados de preço.
     * Autora: Florbela e Samea
     * Data: 02/06/2022
     * Versão: 1.0
     ****************************************************************************/

    //Função para receber dados da Wiew e encaminhar para a Model (inserir)
    function inserirPreco($dadosPreco) {

        //Validação para verificar se o objeto está vazio
        if(!empty($dadosPreco)){

            //Validação de caixa vazia dos elementos que são obrigatórios no BD
            if(!empty($dadosPreco['valorprimeirahora']) && !empty($dadosPreco['valordemaishoras'])) {

                //Criação de um array de dados que será encaminhado à model para inserir no BD 
                $arrayDados = array(
                    "valorprimeirahora"  => $dadosPreco['valorprimeirahora'],
                    "valordemaishoras"   => $dadosPreco['valordemaishoras']
                );

                //Import do arquivo de modelagem para manipular o BD
                require_once('../model/bd/preco.php');

                //Chamando a função da model que fará o insert no BD
                if(insertPreco($arrayDados))
                    return true;
                else {
                    return array('idErro'   => 1,
                                'message'   => 'Não foi possível inserir os dados no Banco de Dados.'
                    );
                }
            } else {
                return array('idErro'   => 2,
                            'message'   => 'Existem campos obrigatórios que não foram preenchidos.'
                );
            }
        }
    }

    //Função para receber dados da Wiew e encaminhar para a Model (atualizar)
    function atualizarPreco($dadosPreco) {

        //Recebe o id enviado pelo arrayDados
        $id = $dadosPreco['idpreco'];

        //Validação para verificar se o objeto está vazio
        if(!empty($dadosPreco)){

            //Validação de caixa vazia dos elementos que são obrigatórios no BD
            if(!empty($dadosPreco[0]['valorprimeirahora']) && !empty($dadosPreco[0]['valordemaishoras'])) {

                //Validação para garantir que o id seja válido
                if(!empty($id) && $id != 0 && is_numeric($id)) {

                    //Criação de um array de dados que será encaminhado à model para inserir no BD 
                    $arrayDados = array(
                        "id"            => $id,
                        "valorprimeirahora"  => $dadosPreco[0]['valorprimeirahora'],
                        "valordemaishoras"   => $dadosPreco[0]['valordemaishoras']
                    );

                    //Import do arquivo de modelagem para manipular o BD
                    require_once(SRC.'model/bd/preco.php');

                    //Chamando a função da model que fará o update no BD
                    if(updatePreco($arrayDados)) {
                        return true;
                    } else {
                        return array('idErro' => 1, 
                                    'message' => 'Não foi possível atualizar os dados no Banco de Dados.'
                        );
                    }
                } else {
                    return array('idErro' => 3, 
                                "message" => "Não é possível editar um registro sem informar um id válido."        
                    );
                }
            } else {
                return array('idErro'   => 2,
                            'message'   => 'Existem campos obrigatórios que não foram preenchidos.'
                );
            }
        }
    }

    //Função para solicitar os dados da model e encaminhar a lista de precos para a View
    function listarPreco() {

        //Import do arquivo que vai buscar os dados
        require_once('../model/bd/preco.php');

        //Chama a função que vai listar os dados no BD
        $dados = selectAllPrecos();

        //Valida se existe dados para serem devolvidos
        if(!empty($dados))
            return $dados;
        else
            return false;
    }

    //Função para buscar um preco através do id do registro
    function buscarPreco($id) {

        //Validação para verificar se o id é um número válido
        if($id != 0 && !empty($id) && is_numeric($id)) {

            //Import do arquivo de preco - model
            require_once('../model/bd/preco.php');

            //Chama a função na model que vai buscar no BD
            $dados = selectByIdPreco($id);

            //Valida se existe dados para serem devolvidos
            if(!empty($dados))
                return $dados;
            else
                return false;

        } else {
            return array('idErro' => 3, 
                        "message" => "Não é possível buscar um registro sem informar um id válido.");
        }
    }
?>