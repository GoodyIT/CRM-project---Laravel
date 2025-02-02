<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use DateTime;
use Config;

class Login extends Model {

    
     /**
    * Login Verify function           
    * @access public verifylogin
    * @param  string $email
    * @param  string $password
    * @return array $admindata
    */
    public function verifylogin($email, $password) {
        $admindata = DB::table('users as usr')
                    ->select('usr.id','usr.user_name','usr.email','usr.status','usr.profile_photo','usr.name','r.title','r.slug','usr.name','usr.reset_password')
        			 ->leftjoin('roles as r','r.id', '=' ,'usr.role_id')
        			 ->where('usr.email', '=', $email)
        			 ->where('usr.password', '=', md5($password))
        			 ->where('usr.is_delete','=','1')
                     ->where('usr.status','=','1')
        			 ->get();
        return $admindata;
    }

    /**
    * Login time with userId          
    * @access public user_id
    * @return Last Inserted Id
    */
    public function loginRecord($user_id)
    {
        $result = DB::table('login_record')->insert(['user_id'=>$user_id,'login_time'=>date('Y-m-d H:i:s')]);
        $insertedid = DB::getPdo()->lastInsertId();

        return $insertedid;
    }

    /**
    * Update login record Update logout timeStamp           
    * @access public loginRecordUpdate
    * @param  $loginid
    */
    public function loginRecordUpdate($token)
    {
        $result = DB::table('login_token')->where('token','=',$token)->delete();
    }
     /**
    * Forgot password          
    * @access public check user's email
    * @param  $email
    */
    public function forgot_password($email)
    {
        $result =  DB::table('users as usr')
                    ->select('usr.*')
                    ->where('usr.email','=',$email)
                    ->get();
        return $result;
    }

    /**
     * Send Password reset link and email
     *
     * @param  $email, $user_id, password
     * @return Response
     */
    public function ResetEmail($email,$user_id,$password)
    {
        $string = $this->getString(16);
        

        DB::table('reset_password')->insert(['user_id'=>$user_id,'string'=>$string,'date_time'=>date('Y-m-d H:i:s'),'date_expire'=>date('Y-m-d H:i:s',strtotime('+6 hour')),'password'=>$password]);
        $link = $string."&".base64_encode($email);
        $url = "http://".$_SERVER['HTTP_HOST']."/reset/".$link; // LIVE

        //$url = Config::get('app.url')."/stokkup/#/access/reset-password/".$link; // LOCAL
        return $url;
    }
     /**
     * Create random string to reset password
     *
     * @param  $string length
     * @return Response
     */
    public function getString($length)
    {
        $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $charactersLength = strlen($characters);
        $randomString = '';
        for ($i = 0; $i < $length; $i++) {
            $randomString .= $characters[rand(0, $charactersLength - 1)];
        }
        $encode_string = base64_encode($randomString);

        return $encode_string;
    }
     /**
     * Create random string to Login Token
     *
     * @param  string length
     * @return Response
     */
    public function getToken($length)
    {
        $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $charactersLength = strlen($characters);
        $randomString = '';
        for ($i = 0; $i < $length; $i++) {
            $randomString .= $characters[rand(0, $charactersLength - 1)];
        }
        return $randomString.time();
    }

    public function check_user_password($string,$email)
    {
        $result =  DB::table('reset_password as rp')
                    ->select('rp.id','rp.status','rp.string','u.email')
                    ->join('users as u','u.id','=','rp.user_id')
                    ->where('rp.string','=',$string)
                    ->where('rp.date_expire','>',date('Y-m-d H:i:s'))
                    ->where('rp.status','=','0')
                    ->where('u.email','=',$email)
                    ->get();
        return $result;
    }
    public function change_password($string,$email,$password)
    {
        $result = DB::table('users')->where('email','=',$email)->update(array('password'=>md5($password),'updated_date'=>date('Y-m-d H:i:s'),'reset_password'=>'0'));
        $reset_password = DB::table('reset_password')->where('string','=',$string)->update(array('status'=>1));
        return 1;
    }
    public function check_session($token)
    {
        $result =   DB::table('login_token as l')
                    ->join('users as u','u.id','=','l.user_id')
                    ->join('roles as r','r.id','=','u.role_id')
                    ->where('l.token','=',$token)
                    ->select('*')
                    ->get();
        return $result;
    }

    public function verifyloginUser($email, $id) {
        $admindata = DB::table('users as usr')
                    ->select('usr.id','usr.user_name','usr.email','usr.status','usr.password','usr.profile_photo','usr.name','r.title','r.slug','usr.name','usr.reset_password')
                     ->leftjoin('roles as r','r.id', '=' ,'usr.role_id')
                     ->where('usr.email', '=', $email)
                     ->where('usr.id', '=', $id)
                     ->where('usr.is_delete','=','1')
                     ->where('usr.status','=','1')
                     ->get();
        return $admindata;
    }
}
