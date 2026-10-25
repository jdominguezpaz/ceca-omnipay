<?php

namespace Omnipay\Ceca\Message;

use Omnipay\Common\Message\AbstractRequest;

/**
 * Ceca Refund Request
 *
 * @author Javier Sampedro <jsampedro77@gmail.com>
 * @author NitsNets Studio <github@nitsnets.com>
 */
class RefundRequest extends AbstractRequest
{

    protected $liveEndpoint = 'https://comercios.ceca.es/webapp/ConsTpvVirtWeb/ConsTpvVirtS?modo=anularOperacionExt';
    protected $testEndpoint = 'https://democonsolatpvvirtual.ceca.es/webapp/ConsTpvVirtWeb/ConsTpvVirtS?modo=anularOperacionExt';




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
    //Set num_operacion - required
    public function setNumOperacion($Num_operacion)
    {
        return $this->setParameter('Num_operacion', $Num_operacion);
    }
    //Set Importe - required
    public function setImporte($Importe)
    {
        return $this->setParameter('Importe', $Importe);
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
    public function setReferencia($Referencia)
    {
        return $this->setParameter('Referencia', $Referencia);
    }   
    public function setCifrado($Cifrado)
    {
        return $this->setParameter('Cifrado', $Cifrado);
    }
    public function setIdioma($Idioma)
    {
        return $this->setParameter('Idioma', $Idioma);
    }
    public function setPagina($Pagina)
    {
        return $this->setParameter('Pagina', $Pagina);
    }
    public function setClaveEncriptacion($clave_encriptacion)
    {
        return $this->setParameter('clave_encriptacion', $clave_encriptacion);
    }

    public function getData()
    {
        $data = array();
        $clave_encriptacion = $this->getParameter('clave_encriptacion');

        $data['MerchantID'] = $this->getParameter('MerchantID');
        $data['AcquirerBIN'] = $this->getParameter('AcquirerBIN');
        $data['TerminalID'] = $this->getParameter('TerminalID');

        $data['Num_operacion'] = $this->getParameter('Num_operacion');
        $data['Importe'] = $this->getParameter('Importe');
        $data['TipoMoneda'] = $this->getParameter('TipoMoneda');
        $data['Exponente'] = $this->getParameter('Exponente');

        $data['Referencia'] = $this->getParameter('Referencia');
        
        $data['Cifrado'] = $this->getParameter('Cifrado');
        $data['Idioma'] = $this->getParameter('Idioma');
        $data['Pagina'] = $this->getParameter('Pagina');

        $data['Firma'] = $this->generateSignature($data, $clave_encriptacion);
        return $data;

    }

    protected function createResponse($data)
    {
        return $this->response = new RefundResponse($this, $data);
    }

    public function sendData($data)
    {

        $httpRequest = $this->httpClient->post($this->getEndpoint(), null, $this->getData());
        $httpResponse = $httpRequest->send();

        return $this->createResponse($httpResponse->xml());
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
            . $parameters['Referencia'] 
            . $parameters['Cifrado'];


        if ($parameters['Cifrado'] == "SHA2") {
            $signature = strtolower(hash('sha256', $signature));
        }
        else {
            $signature = strtolower(sha1($signature));
        }
        return $signature;           
    }
}
