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

        foreach(range(0, $sheetCount-1) as $sheetIndex){
            Excel::selectSheetsByIndex($sheetIndex)->filter('chunk')->load($fileName)->chunk(1000, function($results) use (&$rows){
                foreach($results as $row){
                    foreach($row as $colName => $value){
                        dd($colName);
                    }
                    array_push($rows, $row->toArray());
                }
            });
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