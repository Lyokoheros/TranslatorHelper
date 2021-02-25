<?php
    require_once("Imodule.php");

    class SubtitlesConnector implements IModule
    {
        public function getName()
        {
            return "Łączenie napisów (pliki SRT)";
        }

        public function getForm()
        {
            return "
                <form action='Modules/SubtitlesConnector.php' method='post'>
                Bazowy plik napisów: <input type='text' name='BaseSubtitleFile'><br>
                Dodatkowy plik napisów: <input type='text' name='AddSubtitleFile'><br>
                Naza pliku wynikowego: <input type='text' name='OutputFileName'><br>
                <input type='submit' value='Połącz i zapisz na dysku'>
                </form>
            ";
        }

        public function action($baseFile, $AddFile, $OutputName)
        {

        }
    }



    if(isset($_POST['BaseSubtitleFile']))
    {       
        $Module = new SubtitlesConnector();

        $Module->action($_POST['BaseSubtitleFile'], $_POST['AddSubtitleFile'], $_POST['OutputFileName']);
        
        unset($Module);
        header("Location: /TranslatorHelper/Index.php");
    }

?>
