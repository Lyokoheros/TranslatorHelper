<?php
    class SimpleRenderer
    {
        protected $language="Pl";
        protected $Errors = ['no base file', 'no add file', 'base file ext error', 'add file ext error'];
        protected $ErrorMessages = [
            'no base file' => 'Musisz podać plik bazowy!',
            'no add file' => 'Musisz podać plik dodatkowy!',
            'base file ext error' => 'Błędne rozszerzenie pliku bazowego',
            'add file ext error' => 'Błędne rozszerzenie pliku dodatkowego'
        ];
        
        public function RenderPage($title, $content)
        {
            Echo <<<PAGE
            <!doctype html lang="$this->language">
            <html>
            <head>
                <title>$title</title>
                <meta charset="utf-8">
                <meta name="author" content="Lyokoheros(Maciej Tomaszyk)">
            
                <link rel="stylesheet" type="text/css" href="style.css" title="CSS-Styles">
            </head>
            <body>
                $content
            </body>
            </html>
            PAGE;
        }

        public function Div($content, $class=NULL, $id=NULL)
        {
            $element = '<div';
            if(isset($class))
            {
                $element = $element." class=\"$class\"";
            }
            if(isset($id))
            {
                $element = $element." id=\"$id\"";
            }
            $element = $element.">$content</div>";

            return $element;
        }

        public function renderErrorsMessages()
        {
            $messages="";
            session_start();
            foreach($this->Errors as $Error)
            {
                if(isset($_SESSION[$Error]))
                {
                    
                    $message = $this->ErrorMessages[$Error];

                    $messages = $messages."<script>alert('$message')</script>";
                    unset($_SESSION[$Error]);
                }
            }
            return $messages;
        }
    }

?>