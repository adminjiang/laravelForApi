<?php
namespace App\Traits;

use Illuminate\Http\Response;
use Illuminate\Contracts\Pagination\Paginator;

trait ApiResponse
{
    /**
     * @var int
     */
    protected $statusCode = Response::HTTP_OK;

    /**
     * @return mixed
     */
    public function getStatusCode()
    {
        return $this->statusCode;
    }

    /**
     * @param $statusCode
     * @return $this
     */
    public function setStatusCode($statusCode)
    {

        $this->statusCode = $statusCode;
        return $this;
    }

    /**
     * @param $data
     * @param array $header
     * @return mixed
     */
    public function respond($data, $header = [])
    {
        return response()->json($data,$this->getStatusCode(),$header);
    }

    /**
     * @param $status
     * @param array $data
     * @param null $code
     * @return mixed
     */
    public function status($status, array $data, $code = null){

        if ($code){
            $this->setStatusCode($code);
        }

        $status = [
            'status' => $status,
            'code' => $this->statusCode
        ];

        $data = array_merge($status,$data);

        return $this->respond($data);

    }

    /**
     * @param $message
     * @param int $code
     * @param string $status
     * @return mixed
     */
    public function failed($message, $code = Response::HTTP_BAD_REQUEST, $status = 'error'){

        return $this->setStatusCode($code)->message($message,$status);
    }


    /**
     * @param $message
     * @param string $status
     * @return mixed
     */
    public function message($message, $status = "success"){

        return $this->status($status,[
            'message' => $message
        ]);
    }

    /**
     * @param string $message
     * @return mixed
     */
    public function internalError($message = "Internal Error!"){

        return $this->failed($message,Response::HTTP_INTERNAL_SERVER_ERROR);
    }

    /**
     * @param string $message
     * @return mixed
     */
    public function created($message = "created")
    {
        return $this->setStatusCode(Response::HTTP_CREATED)
            ->message($message);

    }

    /**
     * @param $data
     * @param string $status
     * @return mixed
     */
    public function success($data, $status = "success"){

        if(is_object($data)&&$data->resource instanceof Paginator){
            $pageData=$data->resource->toArray();
            $data=[
                'meta'=>[
                    "current_page"=>$pageData['current_page'],
                    "per_page"=>$pageData['per_page'],
                    "total"=>$pageData['total'],
                    "last_page"=>$pageData['last_page']
                ],
                "list"=>$pageData['data']
            ];
        }
        return $this->status($status,compact('data'));
    }

    /**
     * @param string $message
     * @return mixed
     */
    public function notFound($message = 'Not Found!')
    {
        return $this->failed($message,Response::HTTP_NOT_FOUND);
    }

}
