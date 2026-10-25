<?php

namespace Omnipay\Ceca\Message;

use Omnipay\Common\Message\AbstractRequest;

/**
 * Ceca (Redsys) Purchase Request
 *
 * @author Javier Sampedro <jsampedro77@gmail.com>
 * @author NitsNets Studio <github@nitsnets.com>
 */
class PurchaseRequest extends AbstractRequest
{

    protected $liveEndpoint = 'https://pgw.ceca.es/tpvweb/tpv/compra.action';
    protected $testEndpoint = 'https://tpv.ceca.es/tpvweb/tpv/compra.action';




    //Set merchanID - required
    public function setMerchantID($MerchantID)
    {
        return $this->setParameter('MerchantID', $MerchantID);
    }
    //Set AcquirerBIN - required
    public function setAcquirerBIN($AcquirerBIN)
    {
        return $this->setParameter('AcquirerBIN', $AcquirerBIN);
    }   
    //Set AcquirerBIN - required
    public function setTerminalID($TerminalID)
    {
        return $this->setParameter('TerminalID', $TerminalID);
    }       
    //Set TipoMoneda - required
    public function setTipoMoneda($TipoMoneda)
    {
        return $this->setParameter('TipoMoneda', $TipoMoneda);
    }
    //Set Exponente - required
    public function setExponente($Exponente)
    {
        return $this->setParameter('Exponente', $Exponente);
    }    
    public function setIdioma($Idioma)
    {
        return $this->setParameter('Idioma', $Idioma);
    }
    public function setCifrado($Cifrado)
    {
        return $this->setParameter('Cifrado', $Cifrado);
    }
    public function setClaveEncriptacion($clave_encriptacion)
    {
        return $this->setParameter('clave_encriptacion', $clave_encriptacion);
    }
    public function setURL_OK($url)
    {
        return $this->setParameter('URL_OK', $url);
    }
    public function setURL_NOK($url)
    {
        return $this->setParameter('URL_NOK', $url);
    }
    public function setNumOperacion($Num_operacion)
    {
        return $this->setParameter('Num_operacion', $Num_operacion);
    }
    public function setPagoSoportado($Pago_soportado)
    {
        return $this->setParameter('Pago_soportado', $Pago_soportado);
    }
    public function setDescripcion($Descripcion)
    {
        return $this->setParameter('Descripcion', $Descripcion);
    }






    public function getData()
    {
        $data = array();
        $clave_encriptacion = $this->getParameter('clave_encriptacion');

        $data['MerchantID'] = $this->getParameter('MerchantID');
        $data['AcquirerBIN'] = $this->getParameter('AcquirerBIN');
        $data['TerminalID'] = $this->getParameter('TerminalID');

        $data['Num_operacion'] = $this->getParameter('Num_operacion');
        $data['Importe'] = (float)$this->getAmount();
        $data['TipoMoneda'] = $this->getParameter('TipoMoneda');
        $data['Exponente'] = $this->getParameter('Exponente');
        
        $data['URL_OK'] = $this->getParameter('URL_OK');
        $data['URL_NOK'] = $this->getParameter('URL_NOK');
        $data['Cifrado'] = $this->getParameter('Cifrado');
        $data['Idioma'] = $this->getParameter('Idioma');
        $data['Pago_soportado'] = $this->getParameter('Pago_soportado');
        $data['Descripcion'] = $this->getParameter('Descripcion');
        $data['Firma'] = $this->generateSignature($data, $clave_encriptacion);

        return $data;

    }

    public function sendData($data)
    {
        return $this->response = new PurchaseResponse($this, $data);
    }

    public function getEndpoint()
    {
        return $this->getEndpointBase();
    }

    public function getEndpointBase()
    {
        return $this->getTestMode() ? $this->testEndpoint : $this->liveEndpoint;
    }

    protected function generateSignature($parameters, $clave_encriptacion)
    {
        $signature = 
            $clave_encriptacion 
            . $parameters['MerchantID'] 
            . $parameters['AcquirerBIN'] 
            . $parameters['TerminalID'] 
            . $parameters['Num_operacion'] 
            . $parameters['Importe'] 
            . $parameters['TipoMoneda'] 
            . $parameters['Exponente'] 
            . $parameters['Cifrado'] 
            . $parameters['URL_OK'] 
            . $parameters['URL_NOK'];
        if ($parameters['Cifrado'] == "SHA2") {
            $signature = strtolower(hash('sha256',  $signature));
        }
        else {
            $signature = strtolower(sha1($signature));
        }
        return $signature;           
    }
}
