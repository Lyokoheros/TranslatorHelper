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
                Naza pliku wynikowego: <input type='text' name='OutputFileName'><br> (bez rozszerzenia)
                <input type='submit' value='Połącz i zapisz na dysku'>
                Uwaga - oba pliki powinny mieć kompatybilne sygnatury czasowe
                </form>
            ";
        }

        public function merge($baseFile, $addFile, $OutputName)
        {
            //parsing files
            $baseSubs = $this->parseSRT($baseFile['tmp_name']);
            $addSubs = $this->parseSRT($addFile['tmp_name']);
            
            //choosing shorter length for loop
            $length = (count($baseSubs)>count($addSubs)) ? count($addSubs) : count($baseSubs);
            //and setting the longer Subs for complementing
            $longer = (count($baseSubs)<count($addSubs)) ? $addSubs : $baseSubs;

            $mergedSubs="";
            //echo $length."<br>";
            $i=0; //iterator will be needed after the lops ends
            for(; $i<$length; $i++)
            {   

                //echo "<br><br>";
                //echo $i."<br>";
                //var_dump($baseSubs[$i]);
                //echo "<br>";
                //var_dump($addSubs[$i]);
                //printing number and time stamp
                $mergedSubs = $mergedSubs.$baseSubs[$i]->number.$baseSubs[$i]->time;
                
                //printing base Subs...
                foreach($baseSubs[$i]->text as $subLine)
                {
                    $mergedSubs = $mergedSubs.$subLine;
                }
                
                //...and the additional Subs
                foreach($addSubs[$i]->text as $subLine)
                {
                    $mergedSubs = $mergedSubs.$subLine;
                }
                $mergedSubs = $mergedSubs."\n";
            }

            //echo "Różne długośći";
            //now we need lenght of the longer array
            $length = count($longer);
            //echo $length."<br>";
            //to easily loop for the remaining elements of the array - if they are of equal lenght this loop will not start
            for(;$i<$length; $i++)
            {
                //echo $i."<br>";   
                //printing remaining Subs...
                $mergedSubs = $mergedSubs.$longer[$i]->number."\n".$longer[$i]->time."\n";
                                
                foreach($longer[$i]->text as $subLine)
                {
                    $mergedSubs = $mergedSubs.$subLine."\n";
                }

            }  

            //saving the result in new file
            file_put_contents($this->getParentFolderPath()."\Outputs\\".$OutputName.".srt", $mergedSubs);

        }

        protected function parseSRT($file)
        {
            //echo "parsuję plik<br>";
            $lines = file($file, FILE_SKIP_EMPTY_LINES);
            $lasttype = null;
            $subs = array();
            $subLine = new SubtitleLine();
            $first = true;
            foreach($lines as $line)
            {
                if($first)//workaround for first line issue
                {
                    $subLine->number=$line;
                    $lasttype="number";
                    $first=false;
                }
                else
                {  
                    if(is_numeric($line[0]) and !str_contains($line, ":")) //(is_numeric(preg_replace("/\s+/", "", $line))) changed to alternate method
                    {
                        
                        if($lasttype=="text")
                        {
                            //echo "new object!<br>";
                            array_push($subs, $subLine); //adding previous line to returned array 
                            $subLine = new SubtitleLine();
                        }
                        $subLine->number=$line;
                        $lasttype="number";
                        //echo "numer: ";
                    }
                    elseif (is_numeric($line[0]))//timestamps starts with number
                    {
                        //echo "time: ";
                        $subLine->time=$line;
                        $lasttype="time";
                    }
                    elseif(!ctype_space($line)) //it's the text - empty lines are skipped
                    {
                        array_push($subLine->text, $line);//text can be multiple lines, so is stored in array
                        $lasttype="text";
                        //echo "text: ";
                    }
                    //var_dump($line);
                    //echo "<br>";            
                }
            }
            array_push($subs, $subLine);//adding last line

            //var_dump($subs);
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
