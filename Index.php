<?php
    require_once("SimpleRenderer.php");
    require_once("Modules.php");


    $Renderer = new SimpleRenderer();
    
    $content = $Renderer->Div("Pomocnik Tłumacza", $class='title');


    $content = $content.$Renderer->renderErrorsMessages();
    //TO DO: Succes Messages
 
    foreach($ModulesList as $ModuleClass) //$ModulesList specified in Module.php file
    {
        $Module = new $ModuleClass();

        $content = $content.$Renderer->Div($Module->getName(), $class='moduleTitle');

        $content = $content.$Renderer->Div($Module->getForm(), $class='module')."<br><br>";

        unset($Module); //we won't need that class instance after that...

    }



    $Renderer->renderPage("Pomocnik Tłumacza", $content);  

?>