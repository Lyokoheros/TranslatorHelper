<?php
    class SimpleRenderer
    {
        protected $language="Pl";
        
        public function RenderPage($title, $content)
        {
            Echo <<<PAGE
            <!doctype html lang="Pl">
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
    }

?>