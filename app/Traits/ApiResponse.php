<?php


namespace App\Traits;

use phpDocumentor\Reflection\Types\This;

trait ApiResponse
{
    protected static $successMessageKeyName='message';
    protected static $errorMessageKeyName='message';

//    public function __construct()
//    {
//        self::$successMessageKeyName='message';
//        self::$errorMessageKeyName='message';
//    }

    /**
     * @param $result
     * @param $message
     * @param $code
     * @return mixed
     */
    public function sendResponse($result,$name,$message='OK',$code=200){
        return response()->json(self::makeResponse($message,$result,$name), $code);
    }

    /**
     * @param $error
     * @param int $code
     * @param array $data
     * @return mixed
     */
    public function sendError($error,$code = 200, $data=[] ,$key_name=null ){
        return response()->json(self::makeError($error,$data,$key_name), $code);
    }

    /**
     * @param string $message
     * @param mixed  $data
     *
     * @return array
     */
    public static function makeResponse($message, $data, $name)
    {
        $response['success'] = true;
        if (!empty($message) || $message==0){
            $response[self::$successMessageKeyName] = $message;
        }
        if (!empty($data)){
            $response[''.$name] = $data;
        }

        return $response;
    }

    /**
     * @param string $message
     * @param array  $data
     *
     * @return array
     */
    public static function makeError($message, array $data = [],$key_name = null)
    {
        $res = [
            'success' => false,
            self::$errorMessageKeyName => $message,
        ];
        if ($key_name){
            if (!empty($data)) {
                $res[$key_name] = $data;
            }
        }else{
            if (!empty($data)) {
                $res['data'] = $data;
            }
        }


        return $res;
    }
}