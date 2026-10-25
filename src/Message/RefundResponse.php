<?php

namespace Omnipay\Ceca\Message;

use Omnipay\Common\Message\AbstractResponse;

/**
 * Ceca (Redsys) Refund Response
 */
class RefundResponse extends AbstractResponse
{

    /**
     * La anulación se acepta si el XML trae el atributo valor="OK" (con
     * OPERACION tipo="400"); un atributo Valor="" (con RESULTADOOK, ERROR_DESC
     * y ERROR_MAS_DATOS) es un rechazo.
     */
    public function isSuccessful()
    {
        $data =  (array)$this->data;

        if (isset($data['@attributes']['Valor']) and $data['@attributes']['Valor'] === '') {
            return false;
        }
        if (isset($data['@attributes']['valor']) and $data['@attributes']['valor'] === 'OK') {
            return true;
        }
        return false;
    }
}
