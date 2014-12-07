<?php


class FileModel extends Eloquent  {

	protected $table = 'files';
	protected $nullable = 'hash';
	protected $primaryKey = 'fileID';
	public function sharedFile(){
        return $this->hasMany('SharedFile', 'fileID' , 'fileID');
    }
	public function userCloud(){
		$this->belongsTo('UserCloudInfo','userCloudID','userCloudID');
	}
	public function tempFile(){
		$this->hasOne('Temp','fileID','fileID');
	}
/**********************************************************************************************/	
	/*
	*	@params:
	*		fileName = Name of the file along with extension Eg : file.txt 
	*		userCloudID : ID of the user 's cloud
	*		path = Path to the file For eg: /Project/UniCloud for /Project/UniCloud/file.txt
	*		isDirectory = true if the file is actually a directory otherwise false
	*		lastModifiedTime=  Last Modified Time in YYYY-MM-DD HH:MM:SS format ie the database format
	*		size = size of the file
	*		rev = revision of the file 
	*		isEncrypted = is this parameter is not passed , the default value of false will be taken
	*	@return value:
	*	 	None
	*	@decription : Adds a new file to app database 
	*
	*/
	public static function addOrUpdateFile($userCloudID, $fileArray){
		//Log::info("fileArray info ", array("filepath"=>$fileArray['path'], "fileName"=>$fileArray['fileName'] , "userCloudID"=>$userCloudID));
		$file = self::getFile($userCloudID, $fileArray['path'], $fileArray['fileName']);
		if($file == null){
		//	Log::info("file object was null");
			$file = new FileModel();
		}
//		Log::info("file info ", array("fileID"=>$file->fileID, "fileName"=>$file->file_name , "Path"=>$file->path));
		$file->user_cloudID = $userCloudID;
		$file->path = $fileArray['path'];
		//$file->is_encrypted = false;
		//$file->encryption_key_hash = null;  I am doing this so as to 
		// avoid overwriting the hash 
		// Also ...fileArray can never have any hash because it is the data coming from 
		// dropbox 
		$file->file_name = $fileArray['fileName'];
		$file->last_modified_time= Utility::changeDateFormatToDBFormat($fileArray['lastModifiedTime']);
		$file->is_directory= $fileArray['isDirectory'];
		$file->rev = $fileArray['rev'];
		$file->size = $fileArray['size'];
		$file->hash = $fileArray['hash'];
//		Log::info("to be saved : file info ", array("fileID"=>$file->fileID, "fileName"=>$file->file_name));
		$file->save();

	}
/**********************************************************************************************/	
	/*
	*	@params:
	*		userCloudID : ID of the user 's cloud
	*		path : Path to the file For eg /Project/UniCloud for a file at /Project/UniCloud/file.txt
	*		fileName:	Name of the file For eg file.txt
	*	@return value:
	*	 	object of class FileModel
	*/
	public static function getFile($userCloudID, $path, $fileName){
		$file =  FileModel::where('user_cloudID','=',$userCloudID)
							->where('path','=',$path)->where('file_name','=',$fileName)
							->get()->first();
		return  $file; 

	}
/**********************************************************************************************/	
	/*
	*	@params:
	*		userCloudID : ID of the user 's cloud
	*		path : Path to the file For eg /Project/UniCloud for a file at /Project/UniCloud/file.txt
	*		fileName:	Name of the file For eg file.txt
	*		attributes: array of attributes to be fetched and returned
	*	@return value:
	*	 	fileModel object with specified attributes
	*	@decription : Returns the attributes of file of a user at a particular path on the cloud 
	*
	*/
	public static function getFileAttributes($userCloudID, $path, $fileName, $attributes){
			return FileModel::where('user_cloudID','=',$userCloudID)
							->where('path','=',$path)->where('file_name','=',$fileName)
							->select($attributes)->get()->first();
	}
/**********************************************************************************************/
	/*
	*	@params:
	*		userCloudID : ID of the user 's cloud
	*		path : Path to the file For eg /Project/UniCloud for a file at /Project/UniCloud/file.txt
	*		fileName:	Name of the file For eg file.txt
	*	@return value:
	*	 	None
	*	@decription : Deleted the file at user's cloud 
	*
	*/
	public static function deleteFile($userCloudID, $path, $fileName){
		$file = FileModel::where('user_cloudID','=',$userCloudID)->where('path','=',$path)
					->where('file_name','=',$fileName);
		if($file !=null)
					$file->delete();
	}
/**********************************************************************************************/
	/*
	*	@params:
	*		userCloudID: ID of the user's cloud
	*		fullPath: The path to the folder or file whose hash is required
	*					In dropbox, files do not have hash
	*	@return value:
	*	 	Boolean : hash of the file/folder 
	*/
	public static function getHash($userCloudID, $fullPath){
		list($path, $fileName)= Utility::splitPath($fullPath);
		$file= FileModel::where('user_cloudID','=',$userCloudID)->where('path','=',$path)
						->where('file_name','=',$fileName)->get()->first();			
		if($file==null)return null;
		else return $file->hash;
	}
/**********************************************************************************************/
	/*
	*	@params:
	*		userCloudID : ID of the user 
	*		path : 	Path to the folder 
	*				For eg if path = '/Project/UniCloud'
	*				The function shall return the contents of this folder 
	*	@return value:
	*				an Array of files each containing fileName, last_modified_time, isDirectory and size of the file
	*	@decription : Returns the file(s) of a user at a particular path on the cloud 
	*
	*/
	public static function getFolderContents($userCloudID, $path){
		return FileModel::where('user_cloudID','=',$userCloudID)->where('path','=',$path)->get()->toArray();
		// I am making this function generic and not specifically getting particular attributes 
		// so that when db caches this query , it will be more effective
		// Also, may functions require FolderContents in different attributes
		// It is in my view better not to make similar functions , just returning
		// different attributes
//				->select(array('file_name','last_modified_time','is_directory','size'))->get()->toArray();
		//toJson() can also be used in place of toArray 

	}	
/**********************************************************************************************/
	public static function setEncryptionKeyHash($userCloudID,$fileName,$path,$encryptionKeyHash) {
		$file =  FileModel::where('user_cloudID','=',$userCloudID)
							->where('file_name','=',$fileName)
							->where('path','=',$path)->get()->first();

		$file->is_encrypted = true;
		$file->encryption_key_hash = $encryptionKeyHash;
		$file->save();
	}
/**********************************************************************************************/

	public static function getEncryptionKeyHash($userCloudID,$fileName,$path) {
		$encryptionKeyHash = FileModel::where('user_cloudID','=',$userCloudID)
							->where('file_name','=',$fileName)
							->where('path','=',$path)
							->select('encryption_key_hash')
							->get()->first();
		return $encryptionKeyHash->encryption_key_hash;
	}
/**********************************************************************************************/
	public static function getFilesForSearch($userID){
		$clouds = UserCloudInfo::getClouds($userID);
		$fileArray =array();
		foreach ($clouds as $cloud) {
			$files = self::getFilesOfCloud($cloud->user_cloudID);
			$fileArray = array_merge($fileArray, $files);
		}
		return $fileArray;
	}
/**********************************************************************************************/
	public static function getFilesAllClouds($userID){
		return DB::table('files')
	    ->where('userID', '=', $userID)
            ->join('user_cloud_info', 'files.user_cloudID', '=', 'user_cloud_info.user_cloudID')
            ->join('clouds', 'user_cloud_info.cloudID', '=', 'clouds.cloudID')            
            ->select('fileID','user_cloud_info.user_cloudID', 'is_encrypted','path','file_name',
			'is_directory','last_modified_time','size', 'clouds.name')
            ->orderBy('file_name')
            ->get();
	} 

/**********************************************************************************************/
	private static function getFilesOfCloud($userCloudID){
		return FileModel::where('user_CloudID','=',$userCloudID)->select('fileID','user_cloudID', 'is_encrypted','path','file_name',
			'is_directory','last_modified_time','size')
				->get()
				->toArray();
	}
/**********************************************************************************************/
	public static function getFileDetails($fileID){
		return FileModel::where('fileID','=',$fileID)->select('fileID','user_cloudID', 'is_encrypted','path','file_name',
			'is_directory','last_modified_time','size')
				->get();
	}
/**********************************************************************************************/
}