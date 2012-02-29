<?php
   require("../config/config.inc");
   class  BD
   {
      private $Server;
      private $Port;
      private $Database;
      private $User;
      private $Password;
      function __construct()
      {
         $this->Server=_DBServer;
         $this->Port=_DBPort;
         $this->Database=_DBName;
         $this->User=_DBUser;
         $this->Password=_DBPassword;
      }
	function connect()
	{
	  try 
	  {
	    $driver = "host=$this->Server dbname=$this->Database user=$this->User password=$this->Password sslmode=disable";
	    $conn = pg_connect($driver);

	    if(!$conn)
	    {
	      throw new Exception("Database Connection Error");
	    }
	    return $conn;
	  }
	  catch (Exception $e) 
	  {
	    print "<h1 class='error'>Db Error->".$e->getMessage()."</h1>";
	    die();
	  }
	}
      function execute($sql)
      {
         $Db=$this->connect();
         if(!$Db){
            return "Error establishing connection to database, please check the 'config.inc' file in 'modules/config'"; //Si no se pudo conectar
         }else
         {
            //Valor es resultado de base de dato y Consulta es la Consulta a realizar
            $result=pg_query($Db,$sql);
	    if($result === false ){
	      print "<h1 class='error'>Db Error->".pg_last_error($Db)."</h1>";
	    }
            return $result;// retorna si fue afectada una fila
         }
      }
   }
?>
