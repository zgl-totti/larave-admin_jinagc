<?php

namespace App\Admin\Extensions;

use Encore\Admin\Grid\Exporters\AbstractExporter;
use Maatwebsite\Excel\Classes\LaravelExcelWorksheet;
use Maatwebsite\Excel\Facades\Excel;

class ExcelExporter extends AbstractExporter
{
    //导出的表格名字
    protected $filename;
    //保留的字段
    protected $column;
    //详细列数目
    protected $line;
    //详细的列名
    protected $header;
    //每列的宽度
    protected $size;
    //链表的字段
    protected $relevance;

    public function __construct($filename,$column,$line=[],$header=[],$size=[],$relevance=[])
    {
        $this->filename=$filename;
        $this->column=$column;
        $this->line=$line;
        $this->header=$header;
        $this->size=$size;
        $this->relevance=$relevance;
    }


    public function export()
    {
        Excel::create($this->filename, function($excel) {

            $excel->sheet('Sheetname', function(LaravelExcelWorksheet $sheet) {

                //设置单元格大小
                for($i=0;$i<count($this->size);$i++)
                {
                    $sheet->setSize(array_keys($this->size)[$i].'1', array_values($this->size)[$i]);
                }

                //设置头部
                for($i=0;$i<count($this->line);$i++)
                {
                    $arr[$this->line[$i].'1']=$this->header[$i];
                }

                $this->chunk(function ($records) use ($sheet,$arr)
                {
                    $rows = $records->map(function ($item) {

                        return array_only($item->toArray(), $this->column);

                    });

                    $list[0]=$arr;

                    foreach ($rows as $k=>$row)
                    {
                        $list[]=$row;

                        for($i=0;$i<count($this->relevance);$i++)
                        {
                            $list[$k+1][array_keys($this->relevance)[$i]]=$row[array_keys($this->relevance)[$i]][array_values($this->relevance)[$i]];
                        }

                    }

                    $sheet->rows($list);

                });

            });

        })->export('xls');

    }


    /*public function export()
    {
        Excel::create($this->filename, function($excel) {

            $excel->sheet('Sheetname', function(LaravelExcelWorksheet $sheet) {

                $this->chunk(function ($records) use ($sheet) {

                    $rows = $records->map(function ($item) {
                        //return array_only($item->toArray(), ['id', 'title', 'author_id', 'content', 'rate', 'keywords']);
                        return array_only($item->toArray(), ['id', 'order_sn', 'user.name', 'order_price','status.status_name']);

                    });

                    $sheet->rows($rows);

                });

            });

        })->export('xls');
    }*/
}