<?php
namespace App\Traits;
use Mail;
use Auth;


trait SendMail
{
    protected $namespace = '';

    public static function send($data = [], $class = '', $type = '') {
        
        $userId = Auth::user()->id;

        $namespace = 'App\Mail';
        $email = $data['email'] ?? '';
        $classname = $namespace . '\\' . $class;
        if(empty($type)) {
            return response(['message' => 'Type not found', 'status' => 400],400);
        }

        $type = strtoupper($type);

        switch($type)
        {
            case 'RM':


        }

        if(self::isValidEmail($email)) {
            try {
                if( class_exists($classname)) {

                    $mail = Mail::to($email)->send(new $classname($data));

                    return response(['status' => 200, 'message' => 'Success'], 200);
                }
                else {
                    return response(['message' => 'Class not found', 'status' => 400],400);
                }
            } catch(\Exception $e) {
                return response(['message' => "Mail error ".$e->getMessage(), 'status' => 500], 500);
            }
        } else {
            return response(['message' => 'Mail was not fired, either the mail address is invalid or empty', 'status' => 400], 400);
        }
        
    }

    public static function isValidEmail($email){ 
        return filter_var($email, FILTER_VALIDATE_EMAIL) !== false;
    }
}

?>