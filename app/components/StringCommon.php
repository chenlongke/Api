<?php
/**
 * Created by PhpStorm.
 * User: seaman
 * Date: 10/25/13
 * Time: 4:40 PM
 */

class StringCommon
{
    public static function startsWith($haystack, $needle)
    {
        return $needle === "" || strpos($haystack, $needle) === 0;
    }

    public static function endsWith($haystack, $needle)
    {
        return $needle === "" || substr($haystack, -strlen($needle)) === $needle;
    }

    public static function Qiniu_URLSafeBase64Encode($str) // URLSafeBase64Encode
    {
        $find = array('+', '/');
        $replace = array('-', '_');
        return str_replace($find, $replace, base64_encode($str));
    }

    /**
     * 发邮件前对编码进行变换
     * @param $password
     * @return string
     */
    public static function string_mask($password){
        if(empty($password)){
            return "";
        }
        $password_array=str_split($password);
        $end = strlen($password);
        //全部替换成*
        $password = "";

        for($i = 0;$i<$end;$i++){
            if($i != 0 && $i != $end-1){
                $password = $password . "*";
            }else{
                $password = $password . $password_array[$i];
            }
        }
        return $password;
    }

    /**
     * Indents a flat JSON string to make it more human-readable.
     * @param string $json The original JSON string to process.
     * @return string Indented version of the original JSON string.
     */
    public static function indentJSON($json)
    {
        $result = '';
        $pos = 0;
        $strLen = strlen($json);
        $indentStr = ' ';
        $newLine = "\n";
        $prevChar = '';
        $outOfQuotes = true;

        for ($i = 0; $i <= $strLen; $i++) {

// Grab the next character in the string.
            $char = substr($json, $i, 1);
// Are we inside a quoted string?
            if ($char == '"' && $prevChar != '\\') {
                $outOfQuotes = !$outOfQuotes;
// If this character is the end of an element,
// output a new line and indent the next line.
            } else if (($char == '}' || $char == ']') && $outOfQuotes) {
                $result .= $newLine;
                $pos--;
                for ($j = 0; $j < $pos; $j++) {
                    $result .= $indentStr;
                }
            }
// Add the character to the result string.
            $result .= $char;
// If the last character was the beginning of an element,
// output a new line and indent the next line.
            if (($char == ',' || $char == '{' || $char == '[') && $outOfQuotes) {
                $result .= $newLine;
                if ($char == '{' || $char == '[') {
                    $pos++;
                }
                for ($j = 0; $j < $pos; $j++) {
                    $result .= $indentStr;
                }
            }
            $prevChar = $char;
        }

        return $result;

    }
}
