<?php

namespace Omnipay\Ceca\Message;

use Omnipay\Common\Message\AbstractResponse;
use Omnipay\Common\Message\RedirectResponseInterface;

/**
 * Ceca (Redsys) Purchase Response
 */
class RefundResponse extends AbstractResponse //implements RedirectResponseInterface
{

    public function isSuccessful()
    {
        // object(SimpleXMLElement) {
        //     @attributes => [
        //         'valor' => 'OK',
        //         'numeroOperacion' => '41',
        //         'fecha' => '04/01/2019 22:15:02 '
        //     ]
        //     OPERACION => object(SimpleXMLElement) {
        //         @attributes => [
        //             'tipo' => '400'
        //         ]
        //         importe => '7,70 '
        //     }
        // }


        // object(SimpleXMLElement) {
        //     @attributes => [
        //         'Valor' => ''
        //     ]
        //     RESULTADOOK => 'false'
        //     ERROR_DESC => 'No existe registro en la tabla de mensajes.'
        //     ERROR_MAS_DATOS => 'id_proceso:2019010422153643700'
        // }
        
        $data =  (array)$this->data;
        \Cake\Log\Log::info($data, 'debug');           

        if (isset($data['@attributes']['Valor']) and $data['@attributes']['Valor'] === '') {
            return false;
        }
        if (isset($data['@attributes']['valor']) and $data['@attributes']['valor'] === 'OK') {
            return true;
        }
        return false;
    }

    public function isRedirect()
    {
        return true;
    }

    public function getRedirectUrl()
    {
        return $this->getRequest()->getEndpoint();
    }

    public function getRedirectMethod()
    {
        return 'POST';
    }

    public function getRedirectData()
    {
        return $this->data;
    }
}
