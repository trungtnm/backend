<?php
namespace Trungtnm\Backend\Utility;

use Anchu\Ftp\Facades\Ftp;

class FtpUtil
{
	const FILE_READ_MODE  = 0644;
	const FILE_WRITE_MODE = 0666;
	const DIR_READ_MODE   = 0755;
	const DIR_WRITE_MODE  = 0777;

	public $ftpCon = '';

	public function __construct($connection = ''){
		if($connection)
			$this->ftpCon = Ftp::connection($connection);
		else
			$this->ftpCon = Ftp::connection();
	}

	function uploadFtp($dirUpload,$tmpFile, $fileName, $chmodFolder = '', $chmodFile = ''){
	    $isDir = $this->ftpCon->getDirListing($dirUpload);
	    if(!empty($isDir)){
	    	//folder ton tai, upload file
	    	if($this->ftpCon->uploadFile($tmpFile, $dirUpload.$fileName)){
	    		if($chmodFile != '')
	    			$this->ftpCon->permission($chmodFile, $dirUpload.$fileName);
	    		return true;
	    	}
	    }
	    else
	    {
	        try {
	            //tao folder neu chua co
		    	$chmodFolder = $chmodFolder == '' ? $this::DIR_WRITE_MODE : $chmodFolder;
		 		$dirCreated = $this->makeDirs($dirUpload, $chmodFolder);
		 		if($dirCreated){
		 			$this->uploadFtp($dirUpload,$tmpFile, $fileName, $chmodFolder, $chmodFile);
		 		}
	        } catch (Exception $e) {
	            throw $e;
	        }
	    }
	    return false;
	}
	function makeDirs($ftpPath = '', $chmod){
		$chmod = $chmod == '' ? $this::DIR_WRITE_MODE : $chmod;
	   	if($ftpPath){
	   		$parts = explode('/',$ftpPath); // 2013/06/11/username
	   		$dir = "";
		   	foreach($parts as $part){
		   		$dir .= "/" . $part;
		   		// pr($this->ftpCon->getDirListing($dir));
	      		if(!$this->ftpCon->getDirListing($dir)){
			        $this->makeDir($dir, $chmod);
	      		}
		   }
		   return true;
	   	}
	   	else
	   	return false;
	}

	public function makeDir($directory, $chmod = '')
    {
    	$chmod = $chmod == '' ? $this::DIR_WRITE_MODE : $chmod;
        if ($this->ftpCon->makeDir($directory)){
        	$this->ftpCon->permission($chmod, $directory);
            return true;
        }
        else
            return false;
    }

 	public function deleteFile($file){
 		if( !empty($file) ){
 			if($file['0'] != "/"){
 				$file = "/".$file;
 			}
	 		if( $this->ftpCon->size($file) > 0 ){
	 			return $this->ftpCon->delete($file);
	 		}else{
	 			return FALSE;
	 		} 		
	 	}
	 	return FALSE;
 	}

 	public function rename($remoteFile, $newFile){
 		return $this->ftpCon->rename($remoteFile, $newFile);
 	}

 	public function disconnect(){
 		return $this->ftpCon->disconnect();
 	}

 	public function size($remoteFile){
 		return $this->ftpCon->size($remoteFile);
 	}
}