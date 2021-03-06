<?php

//FileNAME: Checkbox.php

class Checkbox extends Object {
    private $TEXT;
    private $WERT;
    private $VALUE;
    private $SELECTED;


    /**
     * Konstruktor fÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂ¼r ein Checkbox-Objekt
     * 
     * @param $name   setzt den Namen
     * @param $text   setzt den anzuzeigenden Text
     * @param $wert   setzt den zuÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂ¼bergebenden Wert
     */
    function Checkbox( $name, $text = "", $wert = "J", $value = "N" ) {
        $this->NAME = $name;
        $this->setBorder( 0 );
        $this->WERT = $wert;
        $this->setSelected( $value == "J" );
        $this->TEXT = $text;
    }

    /**
     * die Methode gibt an ob die Checkbox ausgewÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂ¤hlt sein soll
     * 
     * @param $s boolean 
     */
    function setSelected( $s ) {
        $this->SELECTED = $s === true ? true : false;
    }

    /**
     * die Methode liefert true zurÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂ¼ck wenn die Checkbox ausgewÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂ¤hlt sein soll,
     * ansonsten false.
     * 
     * @return boolean 
     */
    function isSelected() {
        return $this->SELECTED === true ? true : false;
    }

    /**
     * die Methode setzt den zu ÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂ¼bergebenden Wert
     * 
     * @param $w String
     */
    function setWert( $w ) {
        $this->WERT = $w;
    }

    /**
     * die Methode liefert den zu ÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂ¼bergebenden Wert zurÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂ¼ck.
     * 
     * @return String
     */
    function getWert() {
        return $this->WERT;
    }
    /**
     * die Methode setzt den zu ÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂ¼bergebenden Wert
     * 
     * @param $w String
     */
    function setValue( $w ) {
        if ( $w == "J" ) {
            $this->VALUE = "J";
        } else {
            $this->VALUE = "N";
        }

        $this->setSelected( $this->VALUE == "J" );
    }

    /**
     * die Methode liefert den zu ÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂ¼bergebenden Wert zurÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂ¼ck.
     * 
     * @return String
     */
    function getValue() {
        return $this->VALUE;
    }


    /**
     * die Methode setzt den anzuzeigenden Text
     * 
     * @param $w String
     */
    function setText( $t ) {
        $this->TEXT = $t;
    }

    /**
     * die Methode liefert den anzuzeigenden Text zurÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂ¼ck.
     * 
     * @return String
     */
    function getText() {
        return $this->TEXT;
    }


    /**
     * zeigt die Checkboxs an
     */
    function show() {
        $x = "<input id = '" . $this->NAME . "'  type='checkbox' name='" . $this->NAME . "' value = '" . $this->WERT .
            "' " . $this->getToolTipTag() . " ";


        if ( $this->isSelected() ) {
            $x .= " checked ";
        }


        $x .= "> " . $this->TEXT . " ";

        $text = new Text( $x );
        $text->setFilter( false );
        $text->show();
    }


}

?>