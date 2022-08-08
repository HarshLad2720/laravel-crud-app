<?php

namespace App\Traits;

use App\Models\User\Import_csv_log;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Facades\Excel;
use function is_array;
use function is_null;
use Carbon\Carbon;

trait Scopes
{

    /** Below method is used for perform sorting for any field from any tables in laravel.
     *
     * @param $query - Query get from controller
     * @param $this - model object variable ( e.g. $this->user got from [ $user = new User() ] )
     * @param $tablename - actual table name (e.g. users)
     * @param $sort - requested $sort value (e.g. first_name or mobile_number)
     * @param $order_by - requested $order_by value (e.g. asc or desc)
     *
     * @return mixed
     */
    public function scopeWithOrderBy($query, $sort, $order_by, $tablename, $export_select)
    {
        if (is_null($tablename)) // if $tablename is null
            $tablename = $this->getTable(); // get tablename from model
        if ((is_null($sort) && is_null($order_by))) { // if sort & order_by is null
            return $query; // return actual query
        }
        if (!is_null($this->sortable) && in_array($sort, $this->sortable)) { // if sortable is not null property for respected model is exists requested sort field.
            return $query->orderBy($sort, $order_by); // defined order by clause.
        }

        $foreignSortable = $this->foreign_sortable;
        $isTrue = -1;

        if(!is_null($foreignSortable)){
            foreach ($foreignSortable as $key => $foreignKey){
                if(is_array($foreignKey)){
                    $lastForeignKey = $foreignKey[count($foreignKey) - 1];
                    if($sort == $lastForeignKey)
                        $isTrue = $key;
                }else{
                    if($sort == $foreignKey)
                        $isTrue = $key;
                }
            }

        }

        //if foreign_sortable is not null property for respected model is exists requested sort field & foreign_table & foreign_key is not null
        if (!is_null($this->foreign_sortable) && !is_null($this->foreign_table) && !is_null($this->foreign_key)) {
            for ($i = 0; $i < count($this->foreign_sortable); $i++) {
                if($isTrue == $i){
                    if(!is_null($this->pivot_table))
                        $query->join($this->foreign_table[$i], $this->pivot_table[$i] . '.' . $this->foreign_sortable[$i], '=', $this->foreign_table[$i] . '.id');
                    else {
                        if(is_array($this->foreign_sortable[$i]) && is_array($this->foreign_table[$i])){
                            $foreignSortables = $this->foreign_sortable[$i];
                            $foreignTables = $this->foreign_table[$i];
                            for ($j = 0; $j < count($foreignSortables); $j++) {
                                if($j == 0)
                                    $query->join($foreignTables[$j], $tablename . '.' . $foreignSortables[$j], '=', $foreignTables[$j] . '.id');
                                else
                                    $query->join($foreignTables[$j], $foreignTables[$j-1] . '.' . $foreignSortables[$j], '=', $foreignTables[$j] . '.id');
                            }
                        }
                        else
                            $query->join($this->foreign_table[$i], $tablename . '.' . $this->foreign_sortable[$i], '=', $this->foreign_table[$i] . '.id');
                    }
                    if ( !$export_select ) {
                        $query->select($tablename . '.*');
                    }
                    if(is_array($this->foreign_key[$i])) {
                        $foreignKeys = $this->foreign_key[$i];
                        $foreignTables = $this->foreign_table[$i];
                        for ($j = 0; $j < count($foreignKeys); $j++) {
                            if(is_array($foreignTables))
                                $query->orderBy($foreignTables[count($foreignTables) - 1] . '.' . $foreignKeys[$j], $order_by); // defined foreign table order by clause.
                            else
                                $query->orderBy($foreignTables . '.' . $foreignKeys[$j], $order_by); // defined foreign table order by clause.
                        }
                    }
                    else
                        $query->orderBy($this->foreign_table[$i] . '.' . $this->foreign_key[$i], $order_by); // defined foreign table order by clause.
                }
            }
            if($isTrue != -1)
                return $query;
        }

        $typeSortable = $this->type_sortable;
        $isTrue = -1;

        if(!is_null($typeSortable)){
            foreach ($typeSortable as $key => $typeKey){
                if(is_array($typeKey)){
                    $lastTypeKey = $typeKey[count($typeKey) - 1];
                    if($sort == $lastTypeKey)
                        $isTrue = $key;
                }else{
                    if($sort == $typeKey)
                        $isTrue = $key;
                }
            }
        }


        //if type_sortable is not null property for respected model is exists requested sort field & type_enum & type_enum_text is not null
        if (!is_null($this->type_sortable) && !is_null($this->type_enum) && !is_null($this->type_enum_text)) {
            for ($j = 0; $j < count($this->type_sortable); $j++) {// for loop type_sortable property array.
                if($isTrue == $j){
                    $logic = '';
                    $typeSortable = $this->type_sortable[$j];
                    for ($k = 0; $k < count($this->type_enum[$j]); $k++) {// for loop type_enum array of array
                        if(is_array($typeSortable))
                            $sort = $typeSortable[count($typeSortable) - 1];
                        $logic .= 'WHEN ' . $sort . ' = "' . config($this->type_enum[$j][$k]) . '" THEN "' . config($this->type_enum_text[$j][$k]) . '"'; // generate DB:raw query
                    }
                    $textValue = 'facelab';
                    if ( !$export_select ) {
                        $query->select($tablename . '.*', DB::raw('(CASE ' . $logic . ' ELSE "" END) AS '.$textValue.'_text'));
                    }else{
                        $query->addSelect(DB::raw('(CASE ' . $logic . ' ELSE "" END) AS '.$textValue.'_text'));
                    }
                    if(is_array($typeSortable)){
                        for ($k = 0; $k < count($typeSortable); $k+=4) {
                            if($k == 0)
                                $query->join($typeSortable[$k], $tablename . '.' . $typeSortable[$k+1], '=', $typeSortable[$k] . '.id');
                            else
                                $query->join($typeSortable[$k], $typeSortable[$k-4] . '.' . $typeSortable[$k+1], '=', $typeSortable[$k] . '.id');
                        }
                    }
                    $query->orderBy($textValue.'_text', $order_by);// perform order by
                }
            }
        }
        if($isTrue != -1)
            return $query;
    }

    /** Below method is used for perform searching for any field from any tables in laravel.
     *
     * @param $query - Query get from controller
     * @param $this - model object variable ( e.g. $this->user got from [ $user = new User() ] )
     * @param $search - search keyword
     *
     * @return mixed
     */
    public function scopeWithSearch($query, $search, $export_select = false)
    {
        if (is_null($search)) {// if $search is null
            return $query;
        }
        $searches = $this->sortable; // Get model $sortable property - When defined on which field search is apply.
        if (!is_null($searches)) {
            $query->where(function ($query) use ($search, $searches) {
                //defined multiple fields search is apply.
                foreach ($searches as $find) {
                    $query->orWhere($find, 'LIKE', "%$search%");
                }
            });
        }
        $foreign_keys = $this->foreign_key;// Get model $foreign_key property - It is foreign table field name which searching is applied on it. (e.g. User model roles table role field apply on search).
        $foreign_methods = $this->foreign_method; // Get model $foreign_method property - It is model method which is defined for relationship between model. (e.g. user model role() method).
        if (!is_null($foreign_keys) && !is_null($foreign_methods)) {
            $where = 'where';
            if (!is_null($searches))
                $where = 'orWhere';
            $query->$where(function ($query) use ($search, $foreign_keys, $foreign_methods) {
                for ($i = 0; $i < count($foreign_keys); $i++) {
                    //defined multiple foreign key fields search is apply.
                    $query->orWhereHas($foreign_methods[$i], function ($query) use ($search, $foreign_keys, $i) {
                        if(is_array($foreign_keys[$i]))
                            $query->where(DB::raw('CONCAT(' . implode('," ",', $foreign_keys[$i]) . ')'), 'LIKE', "%$search%");
                        else
                            $query->where($foreign_keys[$i], 'LIKE', "%$search%");
                    });
                }
            });
        }

        $combineds = $this->combined;// Get model $combined property - It is array of fields which is concate and apply search on it. ( e.g. User model title+first_name+last_name (Mr Chirag Parmar)). Search perform on this combined string.
        if (!is_null($combineds)) {
            $where = 'where';
            if (!is_null($searches) && !is_null($foreign_keys) && !is_null($foreign_methods))
                $where = 'orWhere';
            $query->$where(function ($query) use ($search, $combineds) {
                foreach ($combineds as $combined) {
                    //defined search on combined field.
                    $query->orWhere(DB::raw('CONCAT(' . implode('," ",', $combined) . ')'), 'LIKE', "%$search%");
                }
            });
        }

        $type_sortables = $this->type_sortable;// Get model $type_sortable property - It is enum type field. (e.g. M - Male, F - Female).
        $type_enums = $this->type_enum;// Get model $type_enum property - (parse enum value e.g. '0','1' ... and so on).
        $type_enum_texts = $this->type_enum_text;// Get model $type_enum_text - (parse enum text value e.g. 'Male', 'Female' ... and so on).

        //if (!is_null($type_sortables) && !is_null($type_enums) && !is_null($type_enum_texts) && !$export_select) {
        if (!is_null($type_sortables) && !is_null($type_enums) && !is_null($type_enum_texts)) {
            for ($j = 0; $j < count($type_sortables); $j++) {
                $logic = '';
                for ($k = 0; $k < count($type_enums[$j]); $k++) {
                    if(is_array($type_sortables[$j]))
                        $logic .= 'WHEN ' . $type_sortables[$j][count($type_sortables[$j]) - 1] . ' = "' . config($type_enums[$j][$k]) . '" THEN "' . config($type_enum_texts[$j][$k]) . '"';
                    else
                        $logic .= 'WHEN ' . $type_sortables[$j] . ' = "' . config($type_enums[$j][$k]) . '" THEN "' . config($type_enum_texts[$j][$k]) . '"';//defined case when condition for enum type and it's text.
                }
                $type_table = $this->type_table;
                if(!is_null($type_table)){
                    $query = $query->select($type_table.'.*', DB::raw('(CASE ' . $logic . ' ELSE "" END)'))
                        ->orWhere(DB::raw('(CASE ' . $logic . ' ELSE "" END)'), 'LIKE', "%$search%");
                }else{
                    if ( !$export_select )
                        $query = $query->select('*', DB::raw('(CASE ' . $logic . ' ELSE "" END)'))
                            ->orWhere(DB::raw('(CASE ' . $logic . ' ELSE "" END)'), 'LIKE', "%$search%");
                    else
                        $query = $query->orWhere(DB::raw('(CASE ' . $logic . ' ELSE "" END)'), 'LIKE', "%$search%");
                }
                if(is_array($type_sortables[$j])){
                    $tablename = $this->getTable();
                    $typeSortable = $type_sortables[$j];
                    for ($k = 0; $k < count($typeSortable); $k+=4) {
                        if($k == 0)
                            $query->join($typeSortable[$k], $tablename . '.' . $typeSortable[$k+1], '=', $typeSortable[$k] . '.id');
                        else
                            $query->join($typeSortable[$k], $typeSortable[$k-4] . '.' . $typeSortable[$k+1], '=', $typeSortable[$k] . '.id');
                    }
                }
                $query = $query->orWhere(DB::raw('(CASE ' . $logic . ' ELSE "" END)'), 'LIKE', "%$search%");
            }
        }
        return $query;
    }

    /**
     * @param $query - Query get from controller
     * @param $no_pages - parse number of pages
     * @return mixed
     */
    public function scopeWithPerPage($query, $no_pages)
    {
        if (is_null($no_pages)) {
            $no_pages = config('constants.paginate');
        }
        return $query->paginate($no_pages);
    }

    public function scopeCodeGenerator($query,$id,$length,$prefix = "")
    {
        $loop_length = ($length - strlen($id));
        $code_zeros = $prefix;
        for($i=0;$i<$loop_length;$i++)
            $code_zeros .= '0';
        return ($code_zeros.$id);
    }

    /**
     * This function will perform filter function.
     * @param $query - Query get from controller
     * @param $filters - filter records
     * @return mixed
     */
    public function scopeWithFilter($query, $filters, $request = null)
    {
        // get filters from request in JSON format.
        $filters = json_decode(urldecode($filters));
        // Apply filter if it is not null
        if (!is_null($filters)) {
            $query->where(function ($query) use ($filters,$request) {
                // Apply filter for each values
                foreach ($filters as $filterColumn => $filterValue) {
                    if($filterColumn == "custom_filter"){
                        $query->where(function ($query) use ($filterValue){
                            if($filterValue !== ""){
                                foreach ($filterValue as $key => $value) {
                                    foreach($value as $operator => $val){
                                        $query->where($key,$operator,$val);
                                    }
                                }
                            }
                        });
                    }else if($filterColumn == "two_column_filter"){ //Apply two column filter
                        if(is_array($filterValue)){
                            foreach ($filterValue as $data) {
                                //Apply filter for relationships
                                $query->whereHas($data->name, function ($query) use ($data) {
                                    if (!empty($data->value)) {
                                        $query->where( function ($q) use ($data){
                                            $q->where($data->value[0]->name,'>=',$data->value[0]->value)->where($data->value[0]->name,'<=',$data->value[1]->value);
                                        });//first condition
                                        $query->orWhere( function ($q) use ($data){
                                            $q->where($data->value[1]->name,'>=',$data->value[0]->value)->where($data->value[1]->name,'<=',$data->value[1]->value);
                                        });//second condition
                                    }
                                });
                            }
                        }
                    }else if($filterColumn == "pivot_filter"){  //Apply pivot column filter
                        if(is_array($filterValue)){
                            foreach ($filterValue as $data) {
                                $query->whereHas($data->name,function($query) use($data) {
                                    $query->whereIn('id',$data->value);
                                });
                            }
                        }
                    }else if(!is_array($filterValue)){  //check whether value is date range value
                        $dates = explode("to",$filterValue); //get two dates
                        if (Carbon::createFromFormat('Y-m-d', trim($dates[0])) !== false) {
                            $startDate = Patient::getEpochfromDateTimeUsingCarbon(trim($dates[0]).' 00:00:00', $request->user()->timezone);
                            $dates[0] = Patient::getDatefromEpochUsingCarbon($startDate, 'UTC', 'Y-m-d H:i:s');
                        }
                        if (Carbon::createFromFormat('Y-m-d', trim($dates[1])) !== false) {
                            $endDate = Patient::getEpochfromDateTimeUsingCarbon(trim($dates[1]).' 23:59:59', $request->user()->timezone);
                            $dates[1] = Patient::getDatefromEpochUsingCarbon($endDate, 'UTC', 'Y-m-d H:i:s');
                        }
                        $query->whereBetween($filterColumn,$dates); //filter values based on dates.
                    }else if ($filterValue !== '') {
                        $query->whereIn($filterColumn, $filterValue);
                    }
                }
            });
            //dd(\Str::replaceArray('?', $query->getBindings(), $query->toSql()));
            return $query;
        }
        return $query;
    }
    /**
     * This function will check whether date range is valid or not.
     * The format for date range is YYYY-MM-DDtoYYYY-MM-DD.
     * @param $date
     * @return boolean
     */
    function isDateRange($date){
        if (!is_object($date) && preg_match('/^[0-9]{4}-(0[1-9]|1[0-2])-(0[1-9]|[1-2][0-9]|3[0-1])to[0-9]{4}-(0[1-9]|1[0-2])-(0[1-9]|[1-2][0-9]|3[0-1])$/', $date))
            return true;
        else
            return false;
    }

    /**
     * Export Common for the Type enums.
     *
     * @param $query
     * @return \Illuminate\Database\Query\Expression
     */
    public function scopeWithTypeEnum( $query, $sort = '' ){
        $logic = '';
        if ( !is_null($this->type_enum) && !is_null($this->type_enum_text) ) {
            if ( $sort == '' ){
                $sort = $this->type_sortable[0];
                $field_name = $this->type_sortable[0];
            }else{
                $field_name = $sort;
            }
            if( $sort == 'attributes_subtext'){
                $field_name = 'attribute';
            }
            $j = array_search ($sort, $this->type_sortable);
            for ($k = 0; $k < count($this->type_enum[$j]); $k++) {// for loop type_enum array of array
                $logic .= 'WHEN ' . $field_name . ' = "' . config($this->type_enum[$j][$k]) . '" THEN "' . config($this->type_enum_text[$j][$k]) . '"'; // generate DB:raw query
            }
        }
        $type_text = DB::raw('(CASE ' . $logic . ' ELSE "" END) AS '.$sort.'_text');
        return $type_text;
    }
    /**
     * Import csv
     * @param $query
     * @param $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function scopeImportBulk($query,$request,$model,$modelType,$folderName)
    {

        if($request->hasfile('file')) {

            $only_file_name = str_replace(' ', '_',
                strtolower(pathinfo($request->file('file')->getClientOriginalName(),
                    PATHINFO_FILENAME)));
            $only_extension = pathinfo($request->file('file')->getClientOriginalName(), PATHINFO_EXTENSION);

            Artisan::call('config:cache');
            $filename = $only_file_name . '_' . config('constants.file.name') . '.' . $only_extension;

            $path = $request->file('file')->storeAs('/public/' . $folderName, $filename);
            $file_path = $folderName . pathinfo($path, PATHINFO_BASENAME);

            Excel::import($model, $path);
            if (count($model->getErrors()) > 0) {
                $error_json = json_encode($model->getErrors());
                Import_csv_log::create([
                    'file_path' => $file_path,
                    'filename' => $filename,
                    'model_name' => $modelType,
                    'error_log' => $error_json
                ]);
                return response()->json(['errors' => $model->getErrors()], config('constants.validation_codes.unprocessable_entity'));
            }
            return response()->json(['success' => true]);
        }
        else{
            return response()->json(['error' =>config('constants.messages.file_csv_error')], config('constants.validation_codes.unprocessable_entity'));
        }
    }
}
