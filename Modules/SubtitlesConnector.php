<?php
    require_once("Imodule.php");
    require_once("SubtitleLine.php");

    //session_start();

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

        public function merge($baseFile, $addFile, $OutputName)
        {
            $baseSubs = $this->parseSRT($baseFile['tmp_name']);
            $addSubs = $this->parseSRT($addFile['tmp_name']);
            
            $lenght = (count($baseSubs)<count($addSubs)) ? count($addSubs) : count($baseSubs);


            $mergedSubs="test\ntest\ntest2";
            /*$subLine = new SubtitleLine();
            for($i=0; $i<$lenght; $i++)
            {
                

            }
            */




            file_put_contents($this->getParentFolderPath()."\Outputs\\".$OutputName.".srt", $mergedSubs);

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

        protected function getParentFolderPath($path = NULL)
        {
            //current path by default
            $path = (is_null($path)) ? getcwd() : $path;

            $path = explode(DIRECTORY_SEPARATOR, $path);
            unset($path[count($path)-1]);

            $path = implode(DIRECTORY_SEPARATOR, $path);

            return $path;
        }
    }


    if(isset($_POST['OutputFileName']))
    {       
        $Module = new SubtitlesConnector();
        $allowed = array('srt'); //allowed extension (for reuse and generalization)
        $errorCount=0;

        //checking it there are files uploaded, a
        if(!isset($_FILES['BaseSubtitleFile']) or $_FILES['BaseSubtitleFile']['size']==0)
        {
            $_SESSION['no base file']=true;
            $errorCount++;
            //echo "no base file<br>";
        }
        else //and then extension
        {
            $filename = $_FILES['BaseSubtitleFile']['name'];
            $ext = strtolower(pathinfo($filename, PATHINFO_EXTENSION));
            if(!in_array($ext, $allowed))
            {
                $_SESSION['base file ext error']=true;
                $errorCount++;
                //echo "base file ext error<br>";
            }

        }
        
        if(!isset($_FILES['AddSubtitleFile']) or $_FILES['AddSubtitleFile']['size']==0)  
        {
            $_SESSION['no add file']=true;
            $errorCount++;
            //echo "no add file<br>";
        }
        else
        {
            $filename = $_FILES['AddSubtitleFile']['name'];
            $ext = strtolower(pathinfo($filename, PATHINFO_EXTENSION));
                
            if(!in_array($ext, $allowed))
            {
                $_SESSION['add file ext error']=true;
                $errorCount++;          
                //echo "add file ext error<br>";
            }   
        }

        if($errorCount==0)//oba pliki są poprawne
        {            
            $Module->merge($_FILES['BaseSubtitleFile'], $_FILES['AddSubtitleFile'], htmlentities($_POST['OutputFileName'])); 
        }
    
        unset($Module);
        header("Location: /TranslatorHelper/Index.php");
    }  
?>
