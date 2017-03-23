<?php
/**
 * 后台Model扩展
 * Author: flycorn
 * Email: ym1992it@163.com
 * Date: 2017/3/5
 * Time: 16:14
 */
namespace App\Models;

trait FcAdminModel
{
    /**
     * 对接datatables插件的查询器
     * @param array $fieldsOrSql
     * @param array $param
     * @param array $condition
     *     [
     *          //内连接
     *          'join' => [
     *              'tableName' => 'originalTableField = targetTableField'
     *          ],
     *          //左连接
     *          'leftJoin' => [
     *              'tableName' => 'originalTableField = targetTableField'
     *          ],
     *          //查询条件
     *          'condition' => [
     *              [
     *                  'where',
     *                  ['where', 'field opt %?%'],
     *                  ['orWhere', 'field opt ?'],
     *              ],
     *              [
     *                  'where',
     *                  ['where', 'field opt ?'],
     *                  ['orWhere', 'field opt ?'],
     *              ],
     *              [
     *                  'orWhere'
     *                  ['where', 'field opt ?'],
     *                  ['where', 'field opt ?'],
     *              ]
     *          ]
     *      ]
     *
     * @param array $sortFields 排序字段数组
     *
     * @param callable $recombine 回调重组数据方法
     *
     * @return array
     */
    public function dataTable($fieldsOrSql = ['*'], $param = [], $condition = [], $sortFields = [] , callable $recombine = null)
    {
        //返回结果集
        $result = [
            'data' => [],
            'sEcho' => 0,
            'iTotalDisplayRecords' => 0,
            'iTotalRecords' => 0
        ];

        $iDisplayStart = 0; //起始偏移量
        $iDisplayLength = 10; //显示条数
        if(isset($param['iDisplayStart']) && is_numeric($param['iDisplayStart'])){
            $iDisplayStart = intval($param['iDisplayStart']);
        }
        if(isset($param['iDisplayLength']) && is_numeric($param['iDisplayLength'])){
            $iDisplayLength = intval($param['iDisplayLength']);
        }

        try{
            //实例化model对象
            $mdl = new static;

            if(isset($param['sEcho']) && is_numeric($param['sEcho'])){
                $result['sEcho'] = $param['sEcho'];
            }

            //判断是否查询字段或sql语句
            if(is_array($fieldsOrSql) && count($fieldsOrSql) == 2 && isset($fieldsOrSql['sql']) && isset($fieldsOrSql['value'])){
                //查询sql语句
                $mdl = $mdl->select($fieldsOrSql['sql'], $fieldsOrSql['value']);
            } else {
                //获取指定字段数据或原生sql
                $mdl = $mdl->select($fieldsOrSql);

                //查询条件
                if(is_array($condition) && !empty($condition)){
                    //内连接
                    if(isset($condition['join']) && !empty($condition['join'])){
                        foreach ($condition['join'] as $k => $v){
                            $opt = explode('=', $v);
                            if(count($opt) >= 2) $mdl = $mdl -> join($k, trim($opt[0], ' '), '=', trim($opt[1], ' '));
                        }
                    }
                    //左连接
                    if(isset($condition['leftJoin']) && !empty($condition['leftJoin'])){
                        foreach ($condition['leftJoin'] as $k => $v){
                            $opt = explode('=', $v);
                            if(count($opt) >= 2)  $mdl = $mdl -> leftJoin($k, trim($opt[0], ' '), '=', trim($opt[1], ' '));
                        }
                    }
                }

                //查询条件
                $keyword = isset($param['sSearch']) && !empty($param['sSearch']) ? $param['sSearch'] : null;

                if(is_array($condition['condition']) && !empty($condition['condition'])){

                    $queryStr = 'where';
                    foreach ($condition['condition'] as $k => $v){
                        if(!is_array($v[0])) $queryStr = $v[0];

                        array_shift($v);
                        $tmpCondition = $v;
                        $mdl = $mdl -> $queryStr(function($query) use ($keyword, $tmpCondition){
                            foreach ($tmpCondition as $kk => $vv){
                                $queryStr = $vv[0];
                                $optArr = explode(' ', $vv[1]);
                                if(is_numeric(strpos($vv[1], '?'))){
                                    if(count($optArr) >= 3 && !empty($keyword)) $query->$queryStr(trim($optArr[0], ' '), trim($optArr[1], ' '), str_replace('?', $keyword, trim($optArr[2], ' ')));
                                } else {
                                    if(count($optArr) >= 3) $query->$queryStr(trim($optArr[0], ' '), trim($optArr[1], ' '), trim($optArr[2], ' '));
                                }
                            }

                        });
                    }
                }

            }

            //获取符合条件的总数据个数
            $result['iTotalRecords'] = $mdl->count();

            //排序
            if(isset($param['iSortCol_0']) && is_numeric($param['iSortCol_0']) && isset($param['sSortDir_0']) && !empty($param['sSortDir_0'])){
                $field = 'mDataProp_'.$param['iSortCol_0'];
                if(isset($param[$field]) && !empty($param[$field])){
                    $fieldKey = $param[$field];
                    if(!empty($sortFields)){
                        if(isset($sortFields[$fieldKey]) && !empty($sortFields[$fieldKey])){
                            $fieldKey = $sortFields[$fieldKey];
                        }
                    }
                    $mdl = $mdl->orderBy($fieldKey, $param['sSortDir_0']);
                }
            }

            //偏移量及获取指定条数的数据
            $list = $mdl->offset($iDisplayStart)->limit($iDisplayLength)->get();

            //判断是否需要重组数据
            if(gettype($recombine) == 'object'){
                $list = $recombine($list);
            }

            $result['iTotalDisplayRecords'] = $result['iTotalRecords'];
            $result['data'] = $list;

        }catch (\Exception $exception){
            if(env('APP_DEBUG')) exit($exception->getMessage());
        }

        //返回结果集
        return $result;
    }

}