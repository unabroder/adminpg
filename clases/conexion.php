<?php 
class conectar{
    public function conexion(){
         $password = 'postgis';
         $user = 'postgres';
         $bdd = 'prueba';
         $pathServer = "127.0.0.1";
         $port = '5432';
		try {
			$base = new PDO("pgsql:host=$pathServer;port=$port;dbname=$bdd",$user, $password);
			$base->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);       
          //  $base->exec('set names utf8');
        } catch (Exception $e) {
			die("Ocurrio un error: " . $e->getMessage());
        }
        return $base;
    }
}
 ?>