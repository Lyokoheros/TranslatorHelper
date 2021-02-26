<?php
    require_once("Imodule.php");
    require_once("SubtitleLine.php");

    session_start();

    class SubtitlesConnector implements IModule
    {
        public function getName()
        {
            return "Łączenie napisów (pliki SRT)";
        }

        public function getForm()
        {
            return "
                <form action='Modules/SubtitlesConnector.php' method='post' enctype='multipart/form-data'>
                Bazowy plik napisów: <input type='file' name='BaseSubtitleFile'><br>
                Dodatkowy plik napisów: <input type='file' name='AddSubtitleFile'><br>
                Naza pliku wynikowego: <input type='text' name='OutputFileName'><br>
                <input type='submit' value='Połącz i zapisz na dysku'>
                Uwaga - oba pliki powinny mieć kompatybilne sygnatury czasowe
                </form>
            ";
        }

        public function action($baseFile, $AddFile, $OutputName)
        {
            $baseSubs = $this->parseSRT($baseFile);
            $AddSubs = $this->parseSRT($AddFile);
            var_dump($baseSubs);

        }

        protected function parseSRT($file)
        {
            $lines = file($file, FILE_SKIP_EMPTY_LINES);
            $lasttype = null;
            $subs = array();
            $subLine = new SubtitleLine();
            foreach($lines as $line)
            {
                if(is_numeric($line))
                {
                    if($lasttype=="text")
                    {
                        array_push($subs, $subLine); //adding previous line to returned array 
                        $subLine = new SubtitleLine();
                    }
                    $subLine->number=$line;
                    $lasttype="number";
                }
                elseif (is_numeric($line[0]))//timestamps starts with number
                {
                    $subLine->time=$line;
                    $lasttype="time";
                }
                else //it's the text - empty lines are skipped
                {
                    array_push($subLine->text, $line);//text can be multiple lines, so is stored in array
                    $lasttype="text";
                }

            }
            return $subs;
        }
    }


    if(isset($_POST['OutputFileName']))
    {       
        $Module = new SubtitlesConnector();
        $allowed = array('srt'); //allowed extension (for reuse and generalization)
        $errorCount=0;

        //checking it there are files uploaded, a
        if(!isset($_FILES['BaseSubtitleFile']))
        {
            $_SESSION['no base file']=true;
            $errorCount++;
        }
        else //and then extension
        {
            $filename = $_FILES['BaseSubtitleFile']['name'];
            $ext = strtolower(pathinfo($filename, PATHINFO_EXTENSION));
            if(!in_array($ext, $allowed))
            {
                $_SESSION['base file ext error']=true;
                $errorCount++;
            }

        }

        if(!isset($_FILES['AddSubtitleFile']))
        {
            $_SESSION['no add file']=true;
            $errorCount++;
        }
        else
        {
            $filename = $_FILES['AddSubtitleFile']['name'];
            $ext = strtolower(pathinfo($filename, PATHINFO_EXTENSION));
                
            if(!in_array($ext, $allowed))
            {
                $_SESSION['add file ext error']=true;
                $errorCount++;          
            }   
        }

        if($errorCount==0)//oba pliki są poprawne
        {            
            $Module->action($_FILES['BaseSubtitleFile'], $_FILES['AddSubtitleFile'], htmlentities($_POST['OutputFileName'])); 
        }
    
        unset($Module);
        header("Location: /TranslatorHelper/Index.php");
    }  
?>
