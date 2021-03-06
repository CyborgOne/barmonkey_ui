<?php

/**
 * Der ClassLoader ist wie der Name schon sagt fÃÂ¼r das Laden der Klassen zustÃÂ¤ndig.
 *
 * Ebenfalls beinhaltet diese Klasse die Funktion, das Klassenverzeichniss zu Updaten.
 * (Funktion unvollstÃÂ¤ndig da nun Update via SVN geplant ist)
 * 
 * die entscheidenden Methoden dieser Klasse sind:
 * - loadAllClasses($path) 
 * - updateClasses()
 */


class ClassLoader {
    private $PATH;
    private $LASTCHANGE;

    function ClassLoader( $path, $update = false ) {
        $this->PATH = $path;

        if ( $update == "true" ) {
            echo "nun wÃÂ¼rde ein update laufen... wenn es akiviert wÃÂ¤re :P ";
            //  $this->updateClasses(); //Methode prÃÂ¼ft selber ob Heute schon ein Update erfolgt ist
        }
    }


    /**
     * lÃÂ¤dt rekursiv (incl aller Unterverzeichnisse) alle Klassen im angegebenen Verzeichniss
     */
    function loadAllClasses() {
        // Aktuelles Verzeichniss laden
        $reloadArray = array();
        $verzeichnis = opendir( $this->PATH );
        $file_last_modified = 0;
        // EintrÃÂ¤ge
        $eintrag = readdir( $verzeichnis );

        //Alle Dateien im ÃÂ¼bergebenen Ordner Laden
        while ( $eintrag ) {

            if ( ( substr( $eintrag, 0, 1 ) != "." ) ) {
                if ( is_file( $this->PATH . "/" . $eintrag ) && strpos( $eintrag, '.php' ) > 0 ) {
                    $file = $this->PATH . "/" . $eintrag;
                    $cnt = count( $reloadArray );
                    $changeDate = getlastmod();

                    if ( $changeDate > $file_last_modified ) {
                        $this->LASTCHANGE = $changeDate;
                    }

                    $className = substr( basename( $file ), 0, strlen( basename( $file ) ) - 4 );
                    if ( !class_exists( $className ) ) {
                        if ( !include ( $file ) ) {
                            //Wenn ein Fehler beim einbinden auftritt fÃÂ¼r Nachladen eintragen.
                            $reloadArray[$cnt] = $file;
                        }
                    }
                }
            }

            $eintrag = readdir( $verzeichnis );
        }

        if ( count( $reloadArray ) > 0 ) {
            //echo "Dateien nachladen!!!<br>";
            $this->reloadClasses( $reloadArray );
        }
        $this->loadSubClasses();
    }


    function getLastChange() {
        return $this->LASTCHANGE;
    }


    /**
     * lÃÂ¤dt alle Klassen die sich in Unterverzeichnissen des aktuellen Verzeichnisses befinden
     */
    function loadSubClasses() {
        // Aktuelles Verzeichniss laden
        $reloadArray = array();
        $verzeichnis = opendir( $this->PATH );

        // EintrÃÂ¤ge
        $eintrag = readdir( $verzeichnis );

        //Alle Dateien im ÃÂ¼bergebenen Ordner Laden
        while ( $eintrag ) {
            if ( substr( $eintrag, 0, 1 ) != "." ) {
                if ( is_dir( $this->PATH . "/" . $eintrag ) ) {
                    //Unterverzeichniss gefunden
                    $clSub = new ClassLoader( $this->PATH . "/" . $eintrag, false );
                    $clSub->loadAllClasses();
                }
            }

            $eintrag = readdir( $verzeichnis );
        }

        if ( count( $reloadArray ) > 0 ) {
            //echo "Dateien nachladen!!!<br>";
            $this->reloadClasses( $reloadArray );
        }

    }

    /**
     * lÃÂ¤dt alle Klassen nach die in dem Parameter als Array of Strings mitgegeben werden.
     * reload da die Methode fÃÂ¼r das Nachladen von Klassen die beim beim automatischen Laden einen Fehler verursacht haben.
     */
    function reloadClasses( $arr ) {
        $reloadArray = array();
        foreach ( $arr as $file ) {
            $className = substr( basename( $file ), 0, strlen( basename( $file ) ) - 4 );

            if ( !class_exists( $className ) ) {
                if ( !include ( $file ) ) {
                    $reloadArray[$cnt] = $file;
                }
            }
        }
        if ( count( $reloadArray ) > 0 ) {
            reloadClasses( $reloadArray );
        }
    }


    /**
     *  Hier wird ein im Dateisystem abgelegtes Datum des letzten Updates gegen das aktuelle geprÃÂ¼ft.
     *  Ist das Updatedatum in der Vergangenheit, werden die Klassen aktualisiert und das PrÃÂ¼fdatum in der Datei neu gesetzt.
     */
    function updateClasses() {
        $lastUpdate = $this->getLastClassUpdateDate();
        $now = date( "Ymd" );

        if ( $lastUpdate < $now ) {
            echo "Bitte warten! Die Seite wird gerade aktualisiert...";
            $classList = fopen( "http://framework.cyborgone.de/classList.php", "r" );
            $classes = array();

            $buffer = fgets( $classList );
            $classes = explode( ";", $buffer );

            //FÃÂ¼r jede Klasse die in der classList ÃÂ¼bergeben wurde:
            foreach ( $classes as $className ) {
                $n = "http://frameworkclasses.cyborgone.de/" . substr( $className, 8 );
                if ( $n != "http://frameworkclasses.cyborgone.de/" ) { // um evtl leeren eintrag zu ÃÂ¼berspringen
                    $lines = file( $n );
                    $dateiname = dirname( __file__ ) . "/" . $className;

                    if ( !file_exists( dirname( $dateiname ) ) ) {
                        mkdir( dirname( $dateiname ), 0755 );
                    }

                    $handler = fOpen( $dateiname, "w+" );

                    // Durchgehen des Arrays und Speichern der Klassen
                    foreach ( $lines as $line_num => $line ) {
                        fWrite( $handler, $line );
                    }

                    fClose( $handler );
                }
            }

            fclose( $classList );
            $this->setLastClassUpdateDate();
        }
    }


    function getLastClassUpdateDate() {
        $line = "";

        if ( file_exists( dirname( __file__ ) . "/config/lastclassupdate.tmp" ) ) {
            $lcuFile = fOpen( dirname( __file__ ) . "/config/lastclassupdate.tmp", "r+" );
            $line = fRead( $lcuFile, 8 );
        }

        return $line;
    }


    function setLastClassUpdateDate() {
        $lcuFile = fOpen( dirname( __file__ ) . "/config/lastclassupdate.tmp", "w+" );
        $line = fWrite( $lcuFile, date( "Ymd" ) );
    }


}

?>
