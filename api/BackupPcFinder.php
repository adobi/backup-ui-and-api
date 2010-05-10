<?php  

    class BackupPcFinder {
        
        private $iterator;
        private $absolutePath;
        
        public function __construct($absolutePath) {
            
            $this->absolutePath = $absolutePath;
            
            try {
                
                $this->iterator = new DirectoryIterator($this->absolutePath);
            }
            catch(Exception $e) {
                
                // ha nem letezik a konyvtar amit keresunk
                
                $this->iterator = false;
            }
        }
        
        public function listDirs() {
            
            $directories = array();
            
            foreach($this->iterator as $item) {
                
                if($item->isDir() && !$item->isDot()) {
                    
                    $dir = $item->getFilename();
                    $directories[$dir] = strtolower($dir);
                }
            }
            
            asort($directories, SORT_STRING);
            
            return array_keys($directories);
        }
        
        public function exists() {
            
            // mivel a FilesystemIterator csak 5.3-as php-ban van benne. 
            // a DirectoryIteratorral meg csak ugy lehetne eldonteni, hogy bejarjuk az adott revizio konyvtarat, ami nagyon eroforrasigenyes
            // ezert mara a paraszt emgoldas
            //return file_exists($file);  
                     
            return $this->iterator ? $this->iterator->isDir() || $this->iterator->isFile() : file_exists($this->absolutePath);
        }
    }

?>