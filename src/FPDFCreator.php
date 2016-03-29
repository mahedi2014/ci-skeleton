<?php
require_once '../vendor/autoload.php';
class FPDFCreator extends FPDF
{
    var $extGStates = array();

    // transparent: real value from 0 (transparent) to 1 (opaque)
    // bm:    blend mode, one of the following:
    //          Normal, Multiply, Screen, Overlay, Darken, Lighten, ColorDodge, ColorBurn,
    //          HardLight, SoftLight, Difference, Exclusion, Hue, Saturation, Color, Luminosity
    
    function SetAlpha($transparent, $bMode='Normal')
    {
        // set transparent for stroking (CA) and non-stroking (ca) operations
        $gState = $this->AddExtGState(array('ca'=>$transparent, 'CA'=>$transparent, 'BM'=>'/'.$bMode));
        $this->SetExtGState($gState);
    }

    function AddExtGState($parms)
    {
        $num = count($this->extGStates)+1;
        $this->extGStates[$num]['parms'] = $parms;
        return $num;
    }

    function SetExtGState($gState)
    {
        $this->_out(sprintf('/GS%d gs', $gState));
    }

    function _enddoc()
    {
        if(!empty($this->extGStates) && $this->PDFVersion<'1.4')
            $this->PDFVersion='1.4';
        parent::_enddoc();
    }

    function _putextGStates()
    {
        for ($i = 1; $i <= count($this->extGStates); $i++)
        {
            $this->_newobj();
            $this->extGStates[$i]['n'] = $this->n;
            $this->_out('<</Type /ExtGState');
            $parms = $this->extGStates[$i]['parms'];
            $this->_out(sprintf('/ca %.3F', $parms['ca']));
            $this->_out(sprintf('/CA %.3F', $parms['CA']));
            $this->_out('/BM '.$parms['BM']);
            $this->_out('>>');
            $this->_out('endobj');
        }
    }

    function _putresourcedict()
    {
        parent::_putresourcedict();
        $this->_out('/ExtGState <<');
        foreach($this->extGStates as $k=>$extgstate)
            $this->_out('/GS'.$k.' '.$extgstate['n'].' 0 R');
        $this->_out('>>');
    }

    function _putresources()
    {
        $this->_putextGStates();
        parent::_putresources();
    }

}