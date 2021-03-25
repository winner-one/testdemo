<?php

namespace addons\shopro\library;

use EasyWeChat\Factory;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

/**
 *
 */
class Export
{


    public function __construct()
    {
        
    }

    public function exportExcel($expTitle, $expCellName, $expTableData){
        $fileName = $expTitle;
        $cellNum = count($expCellName);
        $dataNum = count($expTableData);

        $spreadsheet = new Spreadsheet();
        $cellName = array('A', 'B', 'C', 'D', 'E', 'F', 'G', 'H', 'I', 'J', 'K', 'L', 'M', 'N', 'O', 'P', 'Q', 'R', 'S', 'T', 'U', 'V', 'W', 'X', 'Y', 'Z', 'AA', 'AB', 'AC', 'AD', 'AE', 'AF', 'AG', 'AH', 'AI', 'AJ', 'AK', 'AL', 'AM', 'AN', 'AO', 'AP', 'AQ', 'AR', 'AS', 'AT', 'AU', 'AV', 'AW', 'AX', 'AY', 'AZ');
        $sheet = $spreadsheet->getActiveSheet(0);

        // for ($i = 0; $i < $cellNum; $i++) {
        //     $sheet->getColumnDimension($cellName[$i])->setWidth(30);
        // }

        $sheet->getStyle('A1:' . $cellName[$cellNum - 1] . '1')->getFont()->setBold(true);

        $i = 0;
        foreach ($expCellName as $key => $cell) {
            $sheet->setCellValue($cellName[$i] . '1', $cell);
            $i ++;
        }

        for ($i = 0; $i < $dataNum; $i++) {
            if ($i == ($dataNum - 1)) {
                $sheet->mergeCells('A' . ($i + 2) . ':' . $cellName[$cellNum - 1] . ($i + 2));
                $sheet->setCellValue('A' . ($i + 2), $expTableData[$i][key($expCellName)]);
            } else {
                $j = 0;
                foreach ($expCellName as $key => $cell) {
                    $sheet->setCellValue($cellName[$j] . ($i + 2), $expTableData[$i][$key]);
                    $j++;
                }
            }
        }

        header('pragma:public');
        header('Content-type:application/vnd.ms-excel;charset=utf-8;name="' . $fileName . '.xls"');
        header("Content-Disposition:attachment;filename=$fileName.xls"); //attachment新窗口打印inline本窗口打印
        $writer = \PhpOffice\PhpSpreadsheet\IOFactory::createWriter($spreadsheet, 'Xlsx');
        $writer->save('php://output');
    }
}
