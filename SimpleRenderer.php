<?php
    class SimpleRenderer
    {
        protected $language="Pl";
        protected $Errors = ['no base file', 'no add file', 'base file ext error', 'add file ext error'];
        protected $ErrorMessages = [
            'no base file' => '',
            'no add file' => '',
            'base file ext error' => '',
            'add file ext error' => ''
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
            session_start();
            //TO DO: Rendering error messages
        }
    }

?>