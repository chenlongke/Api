<?php
/**
 * Created by JetBrains PhpStorm.
 * User: seaman
 * Date: 5/18/13
 * Time: 11:12 AM
 * To change this template use File | Settings | File Templates.
 */

class UUID {

    public static function create_guid()
    {
        $microTime = microtime();
        list($a_dec, $a_sec) = explode(" ", $microTime);
        $dec_hex = dechex($a_dec* 1000000);
        $sec_hex = dechex($a_sec);
        UUID::ensure_length($dec_hex, 5);
        UUID::ensure_length($sec_hex, 6);
        $guid = "";
        $guid .= $dec_hex;
        $guid .= UUID::create_guid_section(3);
        $guid .= '-';
        $guid .= UUID::create_guid_section(4);
        $guid .= '-';
        $guid .= UUID::create_guid_section(4);
        $guid .= '-';
        $guid .= UUID::create_guid_section(4);
        $guid .= '-';
        $guid .= $sec_hex;
        $guid .= UUID::create_guid_section(6);
        return $guid;
    }

    static function create_guid_section($characters)
    {
        $return = "";
        for($i=0; $i<$characters; $i++)
        {
            $return .= dechex(mt_rand(0,15));
        }
        return $return;
    }

    static function ensure_length(&$string, $length)
    {
        $strlen = strlen($string);
        if($strlen < $length)
        {
            $string = str_pad($string,$length,"0");
        }
        else if($strlen > $length)
        {
            $string = substr($string, 0, $length);
        }

    }

    static function genToken( $len = 32, $md5 = true ) {
        # Seed random number generator
        # Only needed for PHP versions prior to 4.2
        mt_srand( (double)microtime()*1000000 );
        # Array of characters, adjust as desired
        $chars = array(
            'Q', '@', '8', 'y', '%', '^', '5', 'Z', '(', 'G', '_', 'O', '`',
            'S', '-', 'N', '<', 'D', '{', '}', '[', ']', 'h', ';', 'W', '.',
            '/', '|', ':', '1', 'E', 'L', '4', '&', '6', '7', '#', '9', 'a',
            'A', 'b', 'B', '~', 'C', 'd', '>', 'e', '2', 'f', 'P', 'g', ')',
            '?', 'H', 'i', 'X', 'U', 'J', 'k', 'r', 'l', '3', 't', 'M', 'n',
            '=', 'o', '+', 'p', 'F', 'q', '!', 'K', 'R', 's', 'c', 'm', 'T',
            'v', 'j', 'u', 'V', 'w', ',', 'x', 'I', '$', 'Y', 'z', '*'
        );
        # Array indice friendly number of chars;
        $numChars = count($chars) - 1; $token = '';
        # Create random token at the specified length
        for ( $i=0; $i<$len; $i++ )
            $token .= $chars[ mt_rand(0, $numChars) ];
        # Should token be run through md5?
        if ( $md5 ) {
            # Number of 32 char chunks
            $chunks = ceil( strlen($token) / 32 ); $md5token = '';
            # Run each chunk through md5
            for ( $i=1; $i<=$chunks; $i++ )
                $md5token .= md5( substr($token, $i * 32 - 32, 32) );
            # Trim the token
            $token = substr($md5token, 0, $len);
        } return $token;
    }
}