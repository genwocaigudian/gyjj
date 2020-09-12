<?php

namespace app\common\lib;

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use PhpOffice\PhpSpreadsheet\IOFactory;

class Excel
{
    /**
     * Excel导出
     *
     * @param array $datas 导出数据，格式['A1' => 'XXXX公司报表', 'B1' => '序号']
     * @param string $fileName 导出文件名称
     * @param string $format 导出文件类型
     * @param array $options 操作选项，例如：
     *                           bool   print       设置打印格式
     *                           string freezePane  锁定行数，例如表头为第一行，则锁定表头输入A2
     *                           array  setARGB     设置背景色，例如['A1', 'C1']
     *                           array  setWidth    设置宽度，例如['A' => 30, 'C' => 20]
     *                           bool   setBorder   设置单元格边框
     *                           array  mergeCells  设置合并单元格，例如['A1:J1' => 'A1:J1']
     *                           array  formula     设置公式，例如['F2' => '=IF(D2>0,E42/D2,0)']
     *                           array  format      设置格式，整列设置，例如['A' => 'General']
     *                           array  alignCenter 设置居中样式，例如['A1', 'A2']
     *                           array  bold        设置加粗样式，例如['A1', 'A2']
     *                           string savePath    保存路径，设置后则文件保存到服务器，不通过浏览器下载
     * @param array $options = [
     *                           [
     *                           'column' =>'字段名',
     *                           'name' => '列名',
     *                           'width' => '列宽度',
     *                           'height' => '行高',
     *                           'color' => '颜色',
     *                           'size' => '字体大小',
     *                           'font' => '字体',
     *                           'image' => '是否是图片',
     *                           'border' => '边框',
     *                           'dataType' => 'n数字 ',
     *                           ]
     *
     * ]
     * @return bool
     */
    function exportSheelExcel($datas, $options = [], $fileName = '', $format = 'Xlsx', $type = 0)
    {
        set_time_limit(0);
        //初始化
        $spreadsheet = new Spreadsheet();
        // $fileName    = iconv('utf-8', 'gb2312', $fileName);//文件名称
        //设置标题
        $spreadsheet->getActiveSheet()->setTitle($fileName);
        $filename = $fileName . '_' . date('YmdHis');
        $cellNum = count($options);

        /* 设置默认文字居中 */
        $styleArray = [
            'alignment' => [
                'horizontal' => 'left',
                'vertical' => 'left',
            ],
        ];
        $spreadsheet->getDefaultStyle()->applyFromArray($styleArray);
        /* 设置Excel Sheet */
        $spreadsheet->setActiveSheetIndex(0);
        $cellName = [
            'A',
            'B',
            'C',
            'D',
            'E',
            'F',
            'G',
            'H',
            'I',
            'J',
            'K',
            'L',
            'M',
            'N',
            'O',
            'P',
            'Q',
            'R',
            'S',
            'T',
            'U',
            'V',
            'W',
            'X',
            'Y',
            'Z',
            'AA',
            'AB',
            'AC',
            'AD',
            'AE',
            'AF',
            'AG',
            'AH',
            'AI',
            'AJ',
            'AK',
            'AL',
            'AM',
            'AN',
            'AO',
            'AP',
            'AQ',
            'AR',
            'AS',
            'AT',
            'AU',
            'AV',
            'AW',
            'AX',
            'AY',
            'AZ'
        ];

        $spreadsheet->setActiveSheetIndex(0)->setCellValue('A1', $fileName);
        //设置行高
        $spreadsheet->getActiveSheet()->getRowDimension(1)->setRowHeight(30);
        $spreadsheet->getActiveSheet()->getStyle('A1')->getFont()->setBold(true)->setName('Arial')->setSize(20);;
        //设置行高
        $spreadsheet->getActiveSheet()->getRowDimension('A1')->setRowHeight(30);
        //合并单元格
        $spreadsheet->getActiveSheet()->mergeCells('A1:' . $cellName[$cellNum - 1] . '1');
        //默认水平居中
        $styleArray = [
            'alignment' => [
                'horizontal' => 'center',
                'vertical' => 'center',
            ],
        ];
        $spreadsheet->getActiveSheet()->getStyle('A1')->applyFromArray($styleArray);
        $color = [
            'Black' => 'FF000000',
            'White' => 'FFFFFFFF',
            'Red' => 'FFFF0000',
            'Red1' => 'FF800000',//COLOR_DARKRED
            'Green' => 'FF00FF00',
            'Green1' => 'FF008000',//COLOR_DARKGREEN
            'Blue' => 'FF0000FF',
            'Blue1' => 'FF000080',//COLOR_DARKBLUE
            'Yellow' => 'FFFFFF00',
            'Yellow1' => 'FF808000',//COLOR_DARKYELLOW
        ];

        //设置excel第2行数据
        foreach ($options as $key => $val) {
            $column = $cellName[$key] . '2';
            //设置表头
            $spreadsheet->setActiveSheetIndex(0)
                ->setCellValue($column, $val['name']);
            //设置列宽
            if (isset($val['width']) && !empty($val['width'])) {
                $spreadsheet->getActiveSheet()->getColumnDimension($cellName[$key])->setWidth($val['width']);
            } else {
                $spreadsheet->getActiveSheet()->getDefaultColumnDimension()->setWidth(15);//设置默认列宽为
            }
            //设置字体 粗体
            $spreadsheet->getActiveSheet()->getStyle($column)->getFont()->setBold(true);
            //设置行高
            if (!empty($val['height'])) {
                $spreadsheet->getActiveSheet()->getRowDimension($column)->setRowHeight($val['height']);
                //设置默认行高 $spreadsheet->getActiveSheet()->getDefaultRowDimension()->setRowHeight(15);
            }
            //设置颜色
            if (!empty($val['color']) && isset($color[$val['color']])) {
                $spreadsheet->getActiveSheet()->getStyle($column)->getFont()->getColor()->setARGB($color[$val['color']]);
            } else {
                $spreadsheet->getActiveSheet()->getStyle($column)->getFont()->getColor()->setARGB('FF000000');
            }
        }
        $yieldData = $this->yieldData($datas);
        $i = 0;
        foreach ($yieldData as $val) {
            for ($j = 0; $j < $cellNum; $j++) {
                //$spreadsheet->setActiveSheetIndex(0)->setCellValue($cellName[$j].($i+3),' '.$val[$options[$j]['column']].' ');

                //数据类型
                $dataType = isset($options[$j]['dataType']) ? $options[$j]['dataType'] : 's';
                switch ($dataType) {
                    case 'n'://数字
                        $spreadsheet->setActiveSheetIndex(0)->setCellValueExplicit($cellName[$j] . ($i + 3), $val[$options[$j]['column']], $dataType);
                        break;
                    case 'str2num':
                        $spreadsheet->setActiveSheetIndex(0)->setCellValueExplicit($cellName[$j] . ($i + 3), $val[$options[$j]['column']], $dataType);
                        break;
                    case 'str':
                        $spreadsheet->setActiveSheetIndex(0)->setCellValueExplicit($cellName[$j] . ($i + 3), $val[$options[$j]['column']], $dataType);
                        break;
                    case 's':
                    case 'inlineStr':
                        $spreadsheet->setActiveSheetIndex(0)->setCellValueExplicit($cellName[$j] . ($i + 3), $val[$options[$j]['column']], $dataType);
                        break;
                    case 'null':
                        $spreadsheet->setActiveSheetIndex(0)->setCellValueExplicit($cellName[$j] . ($i + 3), $val[$options[$j]['column']], $dataType);
                        break;
                    case 'f':
                    default:
                        $spreadsheet->setActiveSheetIndex(0)->setCellValueExplicit($cellName[$j] . ($i + 3), $val[$options[$j]['column']], $dataType);
                        break;
                }
            }
            $i++;
        }
        header('pragma:public');
        if ($format == 'Xlsx') {
            header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        } elseif ($format == 'Xls') {
            header('Content-Type: application/vnd.ms-excel');
        }
        // type等于1直接下载
        if ($type) {
            $objWriter = new Xlsx($spreadsheet);
            $objWriter->setPreCalculateFormulas(false);
            header("Content-Type: application/force-download");
            header("Content-Type: application/octet-stream");
            header("Content-Type: application/download");
            header("Content-Disposition: attachment;filename=" . $filename . '.' . strtolower($format));
            header('Cache-Control: max-age=0');//禁止缓存
            header('Cache-Control: must-revalidate, post-check=0, pre-check=0');
            header("Content-Transfer-Encoding:binary");
            header("Expires: 0");
            ob_clean();
            ob_start();
            $objWriter->save('php://output');
            /* 释放内存 */
            $spreadsheet->disconnectWorksheets();
            unset($spreadsheet);
            ob_end_flush();
            return true;
        } else {
            $objWriter = IOFactory::createWriter($spreadsheet, $format);
            $rootPath = app()->getRootPath();
            $savePath = '/upload' . '/exportExcel/' . $filename . '.' . strtolower($format);
            $a = $rootPath . 'public' . $savePath;
            $objWriter->save($a);
            /* 释放内存 */
            $spreadsheet->disconnectWorksheets();
            unset($spreadsheet);
            ob_end_flush();
            return $savePath;
        }
    }


    function yieldData($data)
    {
        foreach ($data as $val) {
            yield $val;
        }
    }

    //导入
    public function importExcel()
    {
        set_time_limit(0);
        //文件上传导入
        // $fileController=new FileController();
        $res = self::uploadFileImport();
        if ($res['code']) {
            $data = $res['data'];
            //修正路径
            $filename = 'upload/' . str_replace('\\', '/', $data);
            //进行读取
            $spreadsheet = IOFactory::load($filename);
            $sheetData = $spreadsheet->getActiveSheet()->toArray(null, true, true, true);
            array_shift($sheetData);  //删除标题;
            array_shift($sheetData);  //删除标题;
            return $sheetData;
        } else {
            return '';
        }
    }

    //文件上传,导入文件专用,数据不入库
    public function uploadFileImport()
    {
        // 获取表单上传文件
        $file = \request()->file('file');
        $return = array('status' => 1, 'info' => '上传成功', 'data' => []);
        // 移动到框架应用根目录/public/uploads/ 目录下
        if ($file) {
            $savename = \think\facade\Filesystem::disk('public')->putFile('importExcel', $file);
            return self::setResAr(1, '上传成功', $savename);
        }
        return self::setResAr(0, '上传失败');
    }

    public function setResAr($code = 0, $msg = '', $data = array())
    {
        return ['code' => $code, 'msg' => $msg, 'data' => $data];
    }
}
