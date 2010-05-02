<?php  

    class BackupPcParser {
        
        private $file;
        
        public function __construct($file) {
            
            if(is_null($file) || empty($file)) {
                
                throw new InvalidArgumentException();
            }
            $this->file = $file;
        }
        
        public function parseBackupsFile() {
            
            if(!file_exists($this->file)) {
                
                return false;
            }
            
            $backupFile = file_get_contents($this->file);
            
            $lines = explode("\n", $backupFile);
            
            $revisionsInfo = array();
            
            if(is_array($lines)) {

                foreach($lines as $i => $line) {
                    

                    $line = trim($line);

                    if($line !== '') {
                       
                        //$fields = explode("\t", $line);
                        $fields = preg_split("/\s+/", $line);
                        
                        $revision = trim($fields[0]);
                        $field = trim($fields[2]);
                        $date = date("Y.m.d", $field);
                        $dateParts = explode('.', $date);
                        
                        $revisionsInfo[] = array(
                            'number' => $revision,
                            'date'   => $dateParts[1].".".$dateParts[2]
                        );
                    }
                }
            }
            
            return $revisionsInfo;            
        }
        
        public function parseMySqlDirectories() {
            
            if(!file_exists($this->file)) {
                
                return false;
            }
            
            require_once 'BackupPcFinder.php';
                    
            $finder = new BackupPcFinder($this->file);
            
            $dirs = $finder->listDirs();
            
            if(empty($dirs)) return $dirs;
            
            $revisions = array();
            foreach($dirs as $dir) {
                $date = preg_replace('/2010/', '', $dir);
                
                $date = substr($date, 0, 2) . '.' . substr($date, 2, 3);
                
                $revisions[] = array(
                    'number'    => $dir,
                    'date'      =>$date
                );
            }
            return $revisions;            
        }
    }

?>