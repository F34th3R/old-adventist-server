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

class GeneratorHelper {

    // FTH This is an update
    protected static function forStringGenerator($length, $characterArray, $charactersLength)
    {
        $randomString = '';
        for ($i = 0; $i < $length; $i++) {
            $randomString .= $characterArray[rand(0, $charactersLength - 1)];
        }
        return $randomString;
    }
    protected static function randomString($length = 6, $type = "ALL")
    {
        $allCharacters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $numericUpperCaseCharacters = '0123456789ABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $numericLowerCaseCharacters = '0123456789abcdefghijklmnopqrstuvwxyz';
        $upperCaseCharacters = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $lowerCaseCharacters = 'abcdefghijklmnopqrstuvwxyz';
        $numericCharacters = '0123456789';
        switch ($type)
        {
            case 'ALL':
                $charactersLength = strlen($allCharacters);
                return self::forStringGenerator($length, $allCharacters, $charactersLength);
            case 'NUM_UPPER':
                $charactersLength = strlen($numericUpperCaseCharacters);
                return self::forStringGenerator($length, $numericUpperCaseCharacters, $charactersLength);
            case 'NUM_LOWER':
                $charactersLength = strlen($numericLowerCaseCharacters);
                return self::forStringGenerator($length, $numericLowerCaseCharacters, $charactersLength);
            case 'UPPER':
                $charactersLength = strlen($upperCaseCharacters);
                return self::forStringGenerator($length, $upperCaseCharacters, $charactersLength);
            case 'LOWER':
                $charactersLength = strlen($lowerCaseCharacters);
                return self::forStringGenerator($length, $lowerCaseCharacters, $charactersLength);
            case 'NUM':
                $charactersLength = strlen($numericCharacters);
                return self::forStringGenerator($length, $numericCharacters, $charactersLength);
        }
    }

    public static function code(string $belongs, int $length = 5)
    {
        switch (strtoupper($belongs))
        {
            case 'UNION':
                $code = 'U'.self::randomString($length, 'NUM_LOWER');
                while (Union::where('code', $code)->exists())
                {
                    $code = 'U'.self::randomString($length, 'NUM_LOWER');
                }
                return $code;

            case 'GROUP':
                $code = 'G'.self::randomString($length, 'NUM_LOWER');

                while (Group::where('code', $code)->exists()) {
                    $code = 'G'.self::randomString($length, 'NUM_LOWER');
                }
                return $code;

            case 'CHURCH':
                $code = 'C'.self::randomString($length, 'NUM_LOWER');

                while (Church::where('code', $code)->exists()) {
                    $code = 'C'.self::randomString($length, 'NUM_LOWER');
                }
                return $code;

            case 'DEPARTMENT':
                $code = 'D'.self::randomString($length, 'NUM_LOWER');

                while (Department::where('code', $code)->exists()) {
                    $code = 'D'.self::randomString($length, 'NUM_LOWER');
                }
                return $code;

            case 'USER':
                $code = 'F'.self::randomString($length, 'NUM_LOWER');

                while (User::where('code', $code)->exists()) {
                    $code = 'F'.self::randomString($length, 'NUM_LOWER');
                }
                return $code;

            case 'ADVERTISEMENT':
                $code = 'A'.self::randomString($length, 'NUM_LOWER');

                while (Advertisement::where('code', $code)->exists()) {
                    $code = 'A'.self::randomString($length, 'NUM_LOWER');
                }
                return $code;
        }
    }
}
