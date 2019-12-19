<?php
  class TemplateEngine{
    //Templatevariablen und default Pfad.
    private $templateDir = "templates/";
    private $templateName = "";
    private $templateFile = "";
    private $template = "";

    // limiter der Begrenzer von Variablen, Kommentaren und Einbindung von anderen Templates.
    private $leftDelimiter = "{\$";
    private $rightDelimiter = "}";
    private $leftDelimiterF = "{";
    private $rightDelimiterF = "}";
    private $leftDelimiterC = '{\*';
    private $rightDelimiterC = '\*}';
    private $leftForeach = "{FOREACH";
    private $rightForeach = "}";

    //Möglichkeit einen alternativen Templatepfad festzulegen.
    public function __construct($tplDir=""){
      if(!empty($tplDir)){
        $this->$templateDir = $tplDir;
      }
    }

    public function load($file, $sections){
      $this->templateName = $file;
      $this->templateFile = $this->templateDir.$file;

      // Überprüfung ob $templateFile leer ist und ob diese Datei existiert
      if( !empty($this->templateFile) ) {
          if( file_exists($this->templateFile) ) {
              $this->template = file_get_contents($this->templateFile);
          } else {
              return false;
          }
      } else {
         return false;
      }
       $this->__parseForeach($sections);
       $this->__parseIncludes();
    }

    public function assign($replaceArr){
      //Suchen der Variablentags und mit Inhalt ersetzen
      foreach ($replaceArr as $tag => $content) {
        $this->template = str_replace($this->leftDelimiter.$tag.$this->rightDelimiter,
          $content, $this->template);
      }

    }
    private function __parseForeach($sections=""){
        while( preg_match( "/" . $this->leftForeach . "(.*)\.(.*)". $this->rightForeach . "/", $this->template, $includes, PREG_OFFSET_CAPTURE)){
            $replacementName = $includes[1][0].'.'.$includes[2][0];


          if(file_exists($this->templateDir.$replacementName)){
            foreach ($sections as $name) {
              $content = $this->leftDelimiterF . $name . "." . $includes[2][0] . $this->rightDelimiterF;
              $replacement .= file_get_contents($this->templateDir.$replacementName);
              $replacement = str_replace($this->leftDelimiter."content".$this->rightDelimiter,
                $content, $replacement);
            }
          }
          else{
            $this->template = "TemplateError: ".$replacementName;
            break;
          }
          $this->template = preg_replace( "/" .$this->leftForeach .$replacementName
            .$this->rightForeach."/", $replacement, $this->template );
      }
    }
    private function __parseIncludes(){

      while( preg_match( "/" .$this->leftDelimiterF ."(.*)\.(.*)"
        .$this->rightDelimiterF."/", $this->template, $includes, PREG_OFFSET_CAPTURE) ){
        //zwischenspeicher des Namens der Template
        $replacementName = $includes[1][0].'.'.$includes[2][0];
        //Überprüfung ob die Template existiert, falls ja, einlesen als String und Tag mit Inhalt ersetzen
        if(file_exists($this->templateDir.$replacementName)){
          $replacement = file_get_contents($this->templateDir.$replacementName);
          $this->template = preg_replace( "/" .$this->leftDelimiterF .$replacementName
            .$this->rightDelimiterF."/", $replacement, $this->template );
        }
        else{
          $this->template = "TemplateError: ".$replacementName;
          break;
        }
      }
    }
    private function __parseComments(){
      $this->template = preg_replace( "/" .$this->leftDelimiterC ."(.*)" .$this->rightDelimiterC ."/",
                                    "", $this->template );
    }
    public function display(){
      echo $this->template;
    }
  }
?>
