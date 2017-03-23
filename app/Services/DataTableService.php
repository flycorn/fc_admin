<?php
/**
 * 对接datatable组件
 * Author: flycorn
 * Email: ym1992it@163.com
 * Date: 2017/3/6
 * Time: 15:00
 */

namespace App\Services;

trait DataTableService
{
    public function dataTable($model, $fieldsOrSql = ['*'], $param = [], $condition = [], $sortFields = [] , callable $recombine = null)
    {
        return (new $model())->dataTable($fieldsOrSql, $param, $condition, $sortFields, $recombine);
    }
}