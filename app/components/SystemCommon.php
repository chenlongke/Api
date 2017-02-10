<?php
/**
 * Created by PhpStorm.
 * User: seaman
 * Date: 10/29/13
 * Time: 6:18 PM
 */

class SystemCommon {

    /**
     * 执行系统命令
     * @param $cmd 命令行
     * @return mixed ["stdout"] 标准输出 ["stderr"] 错误输出
     */
    public static function runningCmd($cmd){
        $descriptorspec = array(
            0 => array("pipe", "r"),  // stdin
            1 => array("pipe", "w"),  // stdout
            2 => array("pipe", "w"),  // stderr
        );

        $process = proc_open($cmd, $descriptorspec, $pipes, dirname(__FILE__), null);

        $stdout = stream_get_contents($pipes[1]);
        fclose($pipes[1]);

        $stderr = stream_get_contents($pipes[2]);
        fclose($pipes[2]);

//        Yii::log("CDM:" . $cmd);
//        Yii::log("ERR:" . $stderr);
//        Yii::log("OUT:" . $stdout);

        $return["stdout"] = $stdout;
        $return["stderr"] = $stderr;
        proc_close($process);

        return $return;
    }

    public static function transformXSLFile($xml, $xslFile, $xmlStoreFile){
        $xsl = new DOMDocument;
        $xsl->substituteEntities = true;    // <===added line
        $xsl->load($xslFile);

        $xslt = new XSLTProcessor();
        $xslt->importStylesheet($xsl);
        $returnString = $xslt->transformToXml(new SimpleXMLElement($xml));
        $returnString = str_replace("<?xml version=\"1.0\"?>","",$returnString);
        file_put_contents($xmlStoreFile, $returnString);
    }

    public static function transform($xml, $xsl) {
        $xslt = new XSLTProcessor();
        $xslt->importStylesheet(new  SimpleXMLElement($xsl));
        return $xslt->transformToXml(new SimpleXMLElement($xml));
    }
} 