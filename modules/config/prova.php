<?php
	include_once("dbconfig.php");
	require("config.inc");
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01//EN" "http://www.w3.org/TR/html4/strict.dtd">
<HTML>
   <HEAD>
      <title>PlayMob: Play Mobility -> Indexer Files</title>
      <link rel="stylesheet" href="../../style/default.css" type="text/css">
   </HEAD>
   <BODY>
      <?php $User  = new Usuario("pepe","juan","sds","sd","sdd");

	echo "Buscar ".$User->BuscarTodo(); ?>
   </BODY>
</HTML>
<?php


class Usuario
{
   private $login;
   private $contrasena;
   private $cedula;
   private $tipo;
   private $status;
   function __construct($login,$contrasena,$cedula,$tipo,$status)
   {
      $this->login=$login;
      $this->contrasena=md5($contrasena);
      $this->cedula=$cedula;
      $this->tipo=$tipo;
      $this->status=$status;
   }

   function Insertar()
   {            
      $BaseDato=new BD(SERVIDOR,PUERTO,BD,USUARIO,CLAVE);//declarar el objeto de la clase base de dato
      $Consulta="INSERT INTO cuentausuario VALUES ('$this->login','$this->contrasena','$this->cedula','$this->tipo','Inactivo')";//declarar la consulta
      $ExisteCedula=$this->Existencia('cedula');
      $ExisteLogin=$this->Existencia('login');
      $ExisteContrasena=$this->Existencia('contrasena');
      if (!$ExisteCedula&&!$ExisteLogin&&!$ExisteContrasena)//si los dos resultados son cero Inserta los datos
      {
         $Resultado=$BaseDato->Consultas($Consulta);
         if(pg_affected_rows($Resultado)>=0)//Si resulto almenos una fila afectada
         return 1;
         else
         return 0;
      }
      
      if($ExisteCedula&&$ExisteLogin)// si los dos resultados son 1
      return -1;
      else
      if(!$ExisteCedula&&$ExisteLogin)// si $ExisteCedula es igual a cero y el otro no
      return -2;
      else
      if($ExisteCedula&&!$ExisteLogin)// si $ExisteLogin es igual a cero y el otro no
      return -3;
      
   }
   function Modificar()
   {
      include_once('../php/conexion.php');
      $resultado=pg_query($conexion,"UPDATE cuentausuario SET  contrasena='$this->contrasena', tipo='$this->tipo'
      WHERE login='$this->login'");
      if(pg_affected_rows($resultado)>=0)
      return 1;
      else
      return 0;
   }
   function Eliminar()
   {
      require_once('../php/conexion.php');
      $resultado=pg_query($conexion,"DELETE FROM cuentausuario WHERE  login='$this->login'");
      if(pg_affected_rows($resultado)>=0)
      return 1;
      else
      return 0;
   }
   function Buscar()
   {
      $BaseDato=new BD(SERVIDOR,PUERTO,BD,USUARIO,CLAVE);//declarar el objeto de la clase base de dato
      $Consulta="SELECT * FROM cuentausuario AS a,privilegio AS b, personal as c WHERE (a.login='$this->login') AND
      (a.contrasena='$this->contrasena') AND (a.login=b.login) and (a.cedula=c.cedula)";//declarar la consulta
      $Resultado=$BaseDato->Consultas($Consulta);//llamar a la funcion de la base de dato que realiza las consulta
      
      $Datos=@pg_fetch_all($Resultado);//Devuelve los datos en forma de arreglo
         
      if($Datos[0]['login'])//verificar si arrojo algun resultado
         return $Datos;
      else
         return 0;      
   }
   function BuscarUsuario()
   {
      $BaseDato=new BD(SERVIDOR,PUERTO,BD,USUARIO,CLAVE);//declarar el objeto de la clase base de dato
      
      if($this->login!="")
      {
         $Condicion=$Condicion."a.login LIKE"."'$this->Login%'";
         $Operador="AND";
      }
      
      if($this->nombre!="")
      {
         $Condicion=$Condicion." ".$Operador." a.nombre LIKE"."'$this->Nombre%'";
         $Operador="AND";
      }
      
      if($this->cedula!="")
      {
         $Condicion=$Condicion." ".$Operador." a.cedula LIKE"."'$this->Cedula%'";
         $Operador="AND";
      }      
      if($this->tipo!="")
      {
         $Condicion=$Condicion." ".$Operador." a.tipo="."'$this->Tipo'";
         $Operador="AND";
      }
      
      $Consulta="SELECT * FROM cuentausuario AS a,privilegio AS b WHERE ".$Condicion." AND (a.login=b.login)";//declarar la consulta
      $Resultado=$BaseDato->Consultas($Consulta);//llamar a la funcion de la base de dato que realiza las consulta
      
      $Datos=@pg_fetch_all($Resultado);//Devuelve los datos en forma de arreglo
         
      if($Datos[0]['login'])//verificar si arrojo algun resultado
         return $Datos;
      else
         return 0;      
   }
   function Existencia($Condicion)
   {
      $BaseDato=new BD(SERVIDOR,PUERTO,BD,USUARIO,CLAVE);

      if($Condicion=='login')
	$Condicion="login="."'$this->login'";
      else
	if($Condicion=='cedula')
		$Condicion="cedula="."'$this->cedula'";
	else
		$Condicion="contrasena="."'$this->contrasena'";

      $Consulta="SELECT *FROM cuentausuario WHERE ".$Condicion;

      $Resultado=$BaseDato->Consultas($Consulta);//llamar a la funcion de la base de dato que realiza las consulta
      $Datos=@pg_fetch_all($Resultado);//Devuelve los datos en forma de arreglo

      if($Datos[0]['login'])
         return 1;
      else
         return 0;
   }
   function getAllSongs()
   {
	
      $BaseDato = new BD(_DBServer,_DBPort,_DBName,_DBUser,_DBPassword);//declarar el objeto de la clase base de dato

      $Consulta = "SELECT * FROM songs";//declarar la consulta

      $Resultado = $BaseDato->select($Consulta);//llamar a la funcion de la base de dato que realiza las consulta
      $Datos =@pg_fetch_all($Resultado);//Devuelve los datos en forma de arreglo
      //print_r($Datos);
	echo $Datos[0]['name'];
      if($Datos[0]['name'])//verificar si arrojo algun resultado
         return $Datos;
      else
         return "No results";
   }
   function select($Consulta=false)
   {
	
      $BaseDato = new BD(_DBServer,_DBPort,_DBName,_DBUser,_DBPassword);//declarar el objeto de la clase base de dato

      if(!$Consulta)
	return "No SQL query";

      $Resultado = $BaseDato->select($Consulta);//llamar a la funcion de la base de dato que realiza las consulta
      $Datos =@pg_fetch_all($Resultado);//Devuelve los datos en forma de arreglo
      //print_r($Datos);
	echo $Datos[0]['name'];
      if($Datos[0]['name'])//verificar si arrojo algun resultado
         return $Datos;
      else
         return "No results";
   }
}
?>
