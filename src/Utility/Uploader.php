<?php
namespace Trungtnm\Backend\Utility;

use Symfony\Component\HttpFoundation\File\UploadedFile;

class Uploader
{

    /**
     * @param $uploadField
     * @param $dirUpload
     * @return bool|string
     */
    public static function localUpload($uploadField, $dirUpload)
    {
        if (request()->hasFile($uploadField)) {
            $file = request()->file($uploadField);
            return self::saveFile($file, $dirUpload);
        }
        return '';
    }

    /**
     * @param UploadedFile $file
     * @param $dirUpload
     * @return string
     */
    private static function saveFile(UploadedFile $file, $dirUpload)
    {
        $dirUpload = trim($dirUpload, '/') . "/" ;
        $filename = $file->getClientOriginalName();
        $nameArr  = explode('.', $filename);
        //get file extension
        $extension = is_array($nameArr) && count($nameArr) > 1 ? end($nameArr) : '';
        if($extension){
            array_pop($nameArr);
            $filename = implode('.', $nameArr);
        }
        // add timestamp to filename
        $filename = str_slug($filename) . "-" . time() . "." . strtolower($extension);
        $uploadSuccess = $file->move($dirUpload, $filename);
        $path     = $dirUpload. $filename;
        if( $uploadSuccess ){
            return $path;
        }
        return '';
    }
}