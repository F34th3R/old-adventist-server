<?php
/**
 * Created by F34th3r.io
 */

namespace App\Http\Controllers\Helpers;

use App\Advertisement;
use App\Union;
use App\Group;
use App\Church;
use App\Department;
use App\User;

class CodeGenerator {

    protected function generateRandomString($length = 5) 
    {
        $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $charactersLength = strlen($characters);
        $randomString = '';
        for ($i = 0; $i < $length; $i++) {
            $randomString .= $characters[rand(0, $charactersLength - 1)];
        }
        return $randomString;
    }

    public function generator($belongs)
    {
        $number = 5;
        switch ($belongs) {
            case 'UNIONS':
                $code = 'U'.$this->generateRandomString($number);

                while (Union::where('code', $code)->exists()) {
                    $code = 'U'.$this->generateRandomString($number);
                }
                return $code;
            
            case 'GROUPS':
                $code = 'G'.$this->generateRandomString($number);

                while (Group::where('code', $code)->exists()) {
                    $code = 'G'.$this->generateRandomString($number);
                }
                return $code;

            case 'CHURCHES':
                $code = 'C'.$this->generateRandomString($number);

                while (Church::where('code', $code)->exists()) {
                    $code = 'C'.$this->generateRandomString($number);
                }
                return $code;

            case 'DEPARTMENTS':
                $code = 'D'.$this->generateRandomString($number);

                while (Department::where('code', $code)->exists()) {
                    $code = 'D'.$this->generateRandomString($number);
                }
                return $code;

            case 'USERS':
                $code = 'F'.$this->generateRandomString($number);

                while (User::where('code', $code)->exists()) {
                    $code = 'F'.$this->generateRandomString($number);
                }
                return $code;

            case 'ADVERTISEMENTS':
                $code = 'A'.$this->generateRandomString($number);

                while (Advertisement::where('code', $code)->exists()) {
                    $code = 'A'.$this->generateRandomString($number);
                }
                return $code;
        }
        
    }

}
