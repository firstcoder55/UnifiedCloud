<?php

class SharedFile extends Eloquent  {

	protected $table = 'shared_files';
	protected $primaryKey = 'shared_fileID';
/**********************************************************************************************/	
	public function owner()
    {
    	//User:	Model name 
    	//userID : foreign key
    	//ownerID : local key
        return $this->belongsTo('User','ownerID','userID');
    }

    public function sharer(){
    	return $this->belongsTo('User','sharerID','userID');
    }
    
	public function file(){
		return $this->belongsTo('FileModel','fileID','fileID');
	}    
/**********************************************************************************************/	
	
	public static function removeSharing($fileID, $sharerID){
		$sharedFile = SharedFile::where('fileID','=',$fileID)->where('sharerID','=',$sharerID)->get()->first();
		if($sharedFile != null)
			$sharedFile->delete();
	}
/**********************************************************************************************/	
	public static function getFile($sharedFileID){
		$file = SharedFile::find($sharedFileID)->file;
		return $file;
	}
/**********************************************************************************************/	
	public static function createSharedFile($fileID, $ownerID, $sharerID){
		$sharedFile = new SharedFile;
		$sharedFile->fileID = $fileID;
		$sharedFile->ownerID = $ownerID;
		$sharedFile->sharerID = $sharerID;
		$sharedFile->save();
	}
/**********************************************************************************************/	
	public static function getFilesSharedByUser($ownerID){
		return DB::select('
			SELECT shared_files.shared_fileID, files.size ,files.fileID, files.path,shared_files.sharerID, file_name, shared_files.created_at, first_name,last_name,email
			FROM shared_files
			LEFT JOIN users on (shared_files.sharerID = users.userID)
			LEFT JOIN files on (shared_files.fileID = files.fileID)
			WHERE shared_files.ownerID = ?
			', array($ownerID));
        
        }
/**********************************************************************************************/	
        public static function getFilesSharedWithUser($sharerID){
        	return DB::select('
        		SELECT shared_files.shared_fileID,files.size,files.fileID,files.path, shared_files.ownerID, file_name, shared_files.created_at, first_name,last_name,email
				FROM shared_files
				LEFT JOIN users on (shared_files.ownerID = users.userID)
				LEFT JOIN files on (shared_files.fileID = files.fileID)
				WHERE sharerID = ?
        		', array($sharerID));
        }
/**********************************************************************************************/	

}






