<?php
$descricao = $_POST["descricao"];
$qty = $_POST["quantidade"];
$valorun = $_POST["valor"];

$email = 'pamelabrittocardoso@gmail.com';
$token = '7E20D961DE4D44F69B44990C34AD3FD5';
$url = 'https://ws.sandbox.pagseguro.uol.com.br/v2/checkout/?email=' . $email . '&token=' . $token;

//variaveis itens
//$descricao = 'Camiseta Branca';
//$qty = '2';
//$tamanho = 'P';
//$cor = 'branca';
//$valorun = '15.00';
//$weight = '1000';

$xml = "<?xml version='1.0' encoding='UTF-8' standalone='yes'>";
    $xml .= '<checkout>';
        $xml .= '<currency>BRL</currency>';
        $xml .= '<redirectURL>http://www.minhaloja.com.br/paginaDeRedirecionamento</redirectURL>';
        $xml .= '<items>';
            $xml .= '<item>';
                $xml .= '<id>0001</id>';
                $xml .= '<description>' . $descricao . '</description>';
                $xml .= '<amount>' . $valorun . '</amount>';
                $xml .= '<quantity>' . $qty . '</quantity>';
                $xml .= '<weight>1000</weight>';
            $xml .= '</item>';
        $xml .= '</items>';
        $xml .= '<reference>REF1234</reference>';
        $xml .= '<sender>';
            $xml .= '<name>José Comprador</name>';
            $xml .= '<email>sounoob@sandbox.pagseguro.com.br</email>';
            $xml .= '<phone>';
                $xml .= '<areaCode>11</areaCode>';
                $xml .= '<number>55663377</number>';
            $xml .= '</phone>';
        $xml .= '</sender>';
        $xml .= '<shipping>';
            $xml .= '<type>1</type>';
            $xml .= '<address>';
                $xml .= '<street>Rua sem nome</street>';
                $xml .= '<number>1384</number>';
                $xml .= '<complement>5o andar</complement>';
                $xml .= '<district>Jardim Paulistano</district>';
                $xml .= '<postalCode>01452002</postalCode>';
                $xml .= '<city>Sao Paulo</city>';
                $xml .= '<state>SP</state>';
                $xml .= '<country>BRA</country>';
            $xml .= '</address>';
        $xml .= '</shipping>';
    $xml .= '</checkout>';

$curl = curl_init($url);
curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
curl_setopt($curl, CURLOPT_HTTPHEADER, Array('Content-Type: application/xml; charset=ISO-8859-1'));
curl_setopt($curl, CURLOPT_POSTFIELDS, $xml);
$xml= curl_exec($curl);

if($xml == 'Unauthorized'){
    //Insira seu código avisando que o sistema está com problemas, sugiro enviar um e-mail avisando para alguém fazer a manutenção 

    header('Location: paginaDeErro.php');
    exit;//Mantenha essa linha
}

curl_close($curl);

$xml= simplexml_load_string($xml);

if(count($xml -> error) > 0){
    //Insira seu código avisando que o sistema está com problemas, sugiro enviar um e-mail avisando para alguém fazer a manutenção, talvez seja útil enviar os códigos de erros.
    header('Location: paginaDeErro.php');
    exit;
}

header('Location: https://sandbox.pagseguro.uol.com.br/v2/checkout/payment.html?code=' . $xml -> code);
