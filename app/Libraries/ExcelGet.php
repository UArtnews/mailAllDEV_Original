<?

class ExcelGet {

    function __construct(){
    }

    function asArray($fileName, $columnNames)
    {
        $sheetCount = 0;
        $rows = array();

        //Get sheet count
        Excel::filter('chunk')->load($fileName)->chunk(1000, function($results) use (&$sheetCount){
            foreach($results as $sheet){
                $sheetCount++;
            }
        });

        dd($sheetCount);

        foreach(range(0, $sheetCount-1) as $sheetIndex){
            Excel::selectSheetsByIndex($sheetIndex)->filter('chunk')->load($fileName)->chunk(1000, function($results) use (&$rows, &$columnNames){
                foreach($results as $row){
                    $tmpArray = array();
                    foreach($row as $colName => $value){
                        $colName = strtolower(str_replace(' ','_',$colName));
                        if(in_array($colName, $columnNames)){
                            $tmpArray[$colName] = $value;
                        }
                    }
                    array_push($rows, $tmpArray);
                }
            });
            dd($rows);
        }

        return $rows;
    }

    function oneRow($fileName)
    {
        $thisRow = array();

        Excel::selectSheetsByIndex(0)->filter('chunk')->load($fileName)->limit(1)->chunk(1000, function($results) use (&$thisRow){
            foreach($results as $row){
                $thisRow = $row->toArray();
                break;
            }
        });

        return $thisRow;
    }

}