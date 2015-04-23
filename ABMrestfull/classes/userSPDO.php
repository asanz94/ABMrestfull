<?php
    class userSPDO extends PDO
    {
            private static $instance = null;
            CONST dsn ='mysql:host=localhost;dbname=2daw13_restful';
			CONST user='2daw13_root';
			CONST password='2daw13_root';

            public function __construct()
            {
                    
                    try{
                        parent::__construct(self::dsn,self::user,self::password);
                    }
                    catch (PDOException $e) {
                     echo 'Connection failed: ' . $e->getMessage();}

            }

            public static function singleton()
            {
                    if( self::$instance == null )
                    {
                            self::$instance = new self();
                    }
                    return self::$instance;
            }
            
    }