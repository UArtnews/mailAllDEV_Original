<?

class ArrayTools {
	public static function reindexArray($arr, $newIndexName){
		$newArr = array();
		foreach($arr as $item){
			$newArr[$item[$newIndexName]] = $item;
		}

		return $newArr;
	}
}







?>