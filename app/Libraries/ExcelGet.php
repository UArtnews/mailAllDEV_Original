<?

class ExcelGet {

    function __construct(){
    }

    function toArray($fileName)
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
                    array_push($rows, $row->toArray());
                }
            });
        }

        return $rows;
    }

    function oneRow($fileName)
    {
        $sheetCount = 0;
        $row = array();

        //Get sheet count
        Excel::filter('chunk')->load($fileName)->chunk(1000, function($results) use (&$sheetCount){
            foreach($results as $sheet){
                $sheetCount++;
            }
        });

        foreach(range(0, $sheetCount-1) as $sheetIndex){
            Excel::selectSheetsByIndex($sheetIndex)->filter('chunk')->load($fileName)->limit(1)->chunk(1000, function($results) use (&$rows){
                foreach($results as $row){
                    array_push($rows, $row->toArray());
                }
            });
        }

        return $rows;
    }

}