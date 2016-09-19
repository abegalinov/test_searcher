<?php
namespace TestTask\Exceptions;

class BaseException extends \Exception {
    public function toJson() {
         return json_encode(['status' => 'error', 'msg'=>$this->message, 'code'=>$this->code]);
    }
}

class BadQueryException extends BaseException {}
class SystemException extends BaseException {}
