<?php
//
// Помощник работы с БД
//
class M_MSQL
{
	private static $instance;
	private $link1;
	
	public static function Instance()
	{
		if (self::$instance == null)
			self::$instance = new M_MSQL();
		
		return self::$instance;
	}
	
	private function __construct()
	{
		// здесь подключение к базе
		$hostname = 'localhost';
		$username = 'root';
		$password = '';
		$dbName = 'bot';
		
		date_default_timezone_set('Europe/Moscow');
		
		setlocale(LC_ALL, 'UTF-8'); //jazyk 'ru_RU.UTF-8' 'ru_RU.CP1251');
		mb_internal_encoding('UTF-8'); // kodirovka strok
		$this -> link1 = mysqli_connect($hodtname, $username, $password, $dbName);
		if (mysqli_connect_errno()) {
			printf("No connect with data base: %s\n", mysqli_connect_error());
			exit();
		}
		mysqli_query($this -> link1,'SET NAMES UTF-8');
		
		session_start();
	}
	
	public function Escape($str)
	{
	    return mysqli_real_escape_string($this -> link1, $str);//$str;
	}
	
	//
	// Выборка строк
	// $query     - полный текст SQL запроса
	// результат  - массив выбранных объектов
	//
	public function Select($query)
	{
		$result = mysqli_query($this -> link1,$query);
		
		if (!$result)
			die(mysqli_error($this -> link1));
		
		$n = mysqli_num_rows($result);
		$arr = array();
		
		for($i = 0; $i < $n; $i++)
		{
			$row = mysqli_fetch_assoc($result);
			$arr[] = $row;
		}
		
		return $arr;
	}
	
	//
	// Вставка строки
	// $table    - имя таблицы
	// $object   - ассоциативный массив с парами вида "
	// результат - идентификатор новой строки
	//
	public function Insert($table, $object)
	{
		$columns = array();
		$values = array();
		
		foreach ($object as $key => $value)
		{
			//echo "k=".$key."v=".mysqli_real_escape_string($this -> link1,$value . '');
			$key = mysqli_real_escape_string($this -> link1,$key . '');
			$columns[] = $key;
			if ($value ===null)
			{
				$values[] = 'NULL';
			}
			else
			{
				$value = mysqli_real_escape_string($this -> link1,$value . '');
				$values[] = "'$value'";
			}
		}
		
		$columns_s = implode(',', $columns);
		$values_s = implode(',', $values);
		
		$query = "INSERT INTO $table ($columns_s) VALUES ($values_s)"; // ?????
		#echo $query;
		$result = mysqli_query($this -> link1,$query);
		
		if (!$result)
			die(mysqli_error($this -> link1));
		
		return mysqli_insert_id($this -> link1);
	}
	
	//
	//
	// $table   -
	// $object
	// $where 
	//
	//
	public function Update($table, $object, $where)
	{
		$sets = array();
		
		foreach ($object as $key => $value)
		{
			$key = mysqli_real_escape_string($this -> link1,$key . '');
			
			if ($value === null)
			{
				$sets[] = "$key=NULL"; // "$value=NULL";
			}
			else
			{
				$value = mysqli_real_escape_string($this -> link1,$value . '');
				$sets[] = "$key='$value'";
			}
		}
		
		$sets_s = implode(',', $sets);
		$query = "UPDATE $table SET $sets_s WHERE $where";
		$result = mysqli_query($this -> link1,$query);
		
		if (!$result)
			die(mysqli_error($this -> link1));
		
		return mysqli_affected_rows($this -> link1);
	}
	
	public function CleareTable($table)
	{
		$query = "DELETE FROM $table";
		$result = mysqli_query($this -> link1, $query);
		
		if (!$result)
			die(mysqli_error($this -> link1));
	}
	
	//
	// Удаление строк
	// $table    - имя таблицы
	// $where    - условие (часть SQL запроса)
	// результат - число удаленных строк
	//
	
	public function Delete($table, $where)
	{
		$query = "DELETE FROM $table WHERE $where";
		$result = mysqli_query($this -> link1,$query);
		
		if (!$result)
			die(mysqli_error($this -> link1));
		
		return mysqli_affected_rows($this -> link1);
	}
}