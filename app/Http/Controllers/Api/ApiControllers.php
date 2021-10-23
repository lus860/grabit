<?php


namespace App\Http\Controllers\Api;


use App\Http\Controllers\Controller;
use App\Traits\ApiResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

abstract class ApiControllers extends Controller
{
    use ApiResponse;

    /**
     * @var model name for table in DB
     * @var name variable name in api where set source
     */
    protected $model;
    protected $name;
    protected $limit = 100;
    protected $offset = 0;

    /**
     * @param Request $request
     * @param string $whereHas
     * @param null $relation
     * @param array $where
     * @param string $select
     * @param array $whereIn
     * @return mixed
     */
    public function get(Request $request,$whereHas='',$relation=null,$where=[],$select='*',$whereIn=[]){
        $query=$this->getQuery($request,$whereHas,$relation,$where,$select,$whereIn);

        $result = $query->get();

        if (!$result || $result->count() == 0){
            return $this->sendError("Not Found",404);
        }

        return $this->sendResponse($result,$this->name,'OK',200);
    }

    /**
     * @param Request $request
     * @param $id
     * @param string $whereHas
     * @param null $relation
     * @param array $where
     * @param string $select
     * @param array $whereIn
     * @return mixed
     *
     * show only one member
     */
    public function show(Request $request,$whereHas='',$relation=null,$where=[],$select='*',$whereIn=[]){

        $query=$this->getQuery($request,$whereHas,$relation,$where,$select,$whereIn);

        $result = $query->get();

        if (!$result->count()){
            return $this->sendError("Not Found",404);
        }

       return $this->sendResponse($result,$this->name,'OK',200);
    }

    /**
     * @param $data
     * @return string
     *
     * Validate for injections
     */
    protected function validateFields($data) {
        $data = trim($data);
        $data = stripslashes($data);
        $data = htmlspecialchars($data);
        return $data;
    }

    /**
     * @param $request
     * @param $whereHas
     * @param $relation
     * @param $where
     * @param $select
     * @param $whereIn
     * @return mixed
     *
     * build query
     */
    private function getQuery($request,$whereHas,$relation,$where,$select,$whereIn){
        $limit = (!is_array($request))?(int) $request->get('limit',$this->limit):$this->limit;
        $offset = (!is_array($request))?(int) $request->get('offset',$this->offset):$this->offset;

        $query=$this->model;

        if ($whereHas){
            $query =  $this->setWherehas($query,$whereHas);
        }
        if ($relation){
            $query = $this->setRelations($query,$relation);
        }
        if ($where){
            $query = $this->setWhere($query,$where);
        }
        if ($whereIn){
            $query = $this->setWhereIn($query,$whereIn);
        }
        if ($select){
            $query = $this->setSelect($query,$select);
        }

        return  $query->limit($limit)->offset($offset);
    }


    /**
     * @param $query
     * @param $whereHas
     * @return mixed
     */
    private function setWherehas($query,$whereHas){
        return $query->whereHas($whereHas['table'],function ($query) use ($whereHas){
                $query->where($whereHas['key'],$whereHas['value']);
            });
    }

    /**
     * @param $query
     * @param $relation
     * @return mixed
     */
    private function setRelations($query,$relation){
        return $query->with($relation);
    }

    /**
     * @param $query
     * @param $where
     * @return mixed
     */
    private function setWhere($query,$where){
        return $query->where($where);
    }

    /**
     * @param $query
     * @param $whereIn
     * @return mixed
     */
    private function setWhereIn($query,$whereIn){
        return $query->whereIn('id',$whereIn);
    }

    /**
     * @param $query
     * @param $select
     * @return mixed
     */
    private function setSelect($query,$select){
        return $query->select($select);
    }

    /**
     * @param Request $request
     * @param array $data
     * @return mixed
     * Validate Data from api request
     */
    protected function validateData(Request $request,array $data,$messages=[],$key_name=null,$flag=false){
        $validatedData = Validator::make($request->all(),$data,$messages);
        if ($validatedData->fails()) {
            $new_messages=[$validatedData->errors()->messages()];
            if ($flag){
                $new_messages=[];
                foreach ($validatedData->errors()->messages() as $key=>$message){
                    $new_messages[$key]=$message[0];
                }
            }
            return $this->sendError(
                config('errors')['validate_error'],
                200,
                $new_messages,
                $key_name);
        }
        return true;
    }

    /**
     * @param $request
     * @param null $id
     * @param bool $response if its true in response will be selected column from select parameter
     * @param string $select
     * @param array $where
     * @return mixed
     */
    protected function _save($request,$id=null,$response=false,$select='*',$where=[]){
        try {
            if (is_array($request)){
                $result = get_class($this->model)::_save($request,$id);
            }else{
                $result = get_class($this->model)::_save($request->all(),$id);
            }
        }catch (\Exception $e){
            return $this->sendError($e->getMessage(),406);
        }
        if(!empty($result) && $result['success']){
            if ($response){
                return $this->sendResponse($this->response($request,$select,$where),$this->name,'Ok',201);
            }
            return $this->sendResponse($result['data'],$this->name,'Ok',201);
        }
        if (is_array($request)){
            return $this->sendError(config('errors')['place_order'],406);
        }
        return $this->sendError(config('errors')['other_error'],406);
    }

    /**
     * @param array $where
     * @return mixed
     *
     * delete from DB by given $where parameter
     */
    protected function _delete(array $where){
        try {
            $result = get_class($this->model)::where($where)->delete();
        }catch (\Exception $e){
            return $this->sendError($e->getMessage(),406);
        }
        if ($result){
            return $this->sendResponse('Ok',$this->name,'Success');
        }
        return $this->sendError(config('errors')['other_error']);
    }

    protected function response($request,$select,$where){
       $query =  $this->getQuery($request,false,false,$where,$select,false);
       return $query->first()->toArray();
//       $result =  $query->first()->toArray();
//       if (isset($result['action'])){
//           $action=json_decode($result['action'],true);
//       }
    }
}