{{	Form::label('Welcome')	}}
<br>
<br>
<br>

{{ Form::open(array('route'=>'download_shared_file_route', 'as'=>'download_shared_file','method'=>'get')) }}
{{ Form::label('sharedFileID','sharedfileID')}}
{{ Form::text('sharedFileID')	}}

{{ Form::submit('Download Shared File')	}}
{{ Form::close()	}}
<br>
<br>
<br>

{{ Form::open(array('route'=>'change_access_rights_route', 'as'=>'change_access_rights','method'=>'get')) }}
{{ Form::label('sharedFileID','sharedfileID')}}
{{ Form::text('sharedFileID')	}}
{{ Form::label('Access_rights','accessRights')}}
{{ Form::text('accessRights')	}}

{{ Form::submit('Get files shared with this Sharer')	}}
{{ Form::close()	}}
<br>
<br>
<br>

{{ Form::open(array('route'=>'files_shared_with_user_route', 'as'=>'sharedwith','method'=>'get')) }}
{{ Form::label('SharerID','SharerID')}}
{{ Form::text('sharerID')	}}
{{ Form::submit('Get files shared with this Sharer')	}}
{{ Form::close()	}}

<br>
<br>
<br>

{{ Form::open(array('route'=>'files_shared_by_user_route', 'as'=>'sharedby','method'=>'get')) }}
{{ Form::label('ownerID','OwnerID')}}
{{ Form::text('ownerID')	}}
{{ Form::submit('Get files shared by this owner')	}}
{{ Form::close()	}}

<br>
<br>
<br>

{{ Form::open(array('route'=>'share_file_route', 'as'=>'share','method'=>'get')) }}
{{ Form::label('userCloudID','userCloudID:')}}
{{ Form::text('userCloudID')	}}<br>
{{ Form::label('Path', 'Path:')	}}
{{ Form::text('path')}}			
{{ Form::label('file name', 'File Name:')	}}
{{ Form::text('fileName')}}			
{{ Form::label('Share', 'Share with? Email ID ')	}}
{{ Form::text('sharerEmail')}}			
{{ Form::label('Access', 'Access Rights: ')	}}
{{ Form::text('accessRights')}}			
{{ Form::submit('Share')	}}
{{ Form::close()	}}
<br>
<br>
<br>

{{ Form::open(array('route'=>'upload_route', 'files' => true, 'as'=>'upload','method'=>'post')) }}
{{ Form::hidden('cloudName','Dropbox' )	}}
{{ Form::label('userCloudID','userCloudID:')}}
{{ Form::text('userCloudID')	}}<br>
{{ Form::label('cloudDestination path', 'Cloud destination without trailing slash:')	}}
{{ Form::text('cloudDestinationPath')}}			
{{ Form::label('file', 'File:')	}}
{{ Form::file('files[]',array('multiple'=>true))	}}
{{ Form::submit('Upload Files ')	}}
{{ Form::close()	}}

<br>
<br>
<br>
{{ Form::open(array('route'=>'download_folder_route', 'as'=>'download_folder','method'=>'get')) }}
{{ Form::label('userCloudID','userCloudID:')}}
{{ Form::text('userCloudID')	}}<br>
{{ Form::hidden('cloudName','Dropbox') }}
{{ Form::label('Folder Path', 'Folder Path:')	}}
{{ Form::text('folderPath')	}}<br>
{{ Form::submit('Download Folder')	}}
{{ Form::close()	}}

<br>
<br>
<br>
<br>


{{ Form::open(array('route'=>'download_route', 'files' => true, 'as'=>'download','method'=>'get')) }}
{{ Form::hidden('cloudName','Dropbox')	}}
{{ Form::label('cloudSource','Cloud SOurce Path eg. /Project/SubProject :::')}}
{{ Form::text('cloudSourcePath')	}}<br>
{{ Form::label('file','FileName: ')}}
{{ Form::text('fileName')	}}<br>
{{ Form::label('userCloudID','userCloudID:')}}
{{ Form::text('userCloudID')	}}<br>
{{ Form::submit('Download')	}}
{{ Form::close()	}}
<br>
<br>
<br>


{{ Form::open(array('route'=>'folder_content_route',  'as'=>'folder_content','method'=>'get')) }}
{{ Form::hidden('cloudName','Dropbox')	}}
{{ Form::label('folder','Folder eg /Projects/Subproject :::')}}
{{ Form::text('folderPath')	}}<br>
{{ Form::label('userCloudID','userCloudID:')}}
{{ Form::text('userCloudID')	}}<br>
{{ Form::label('cached','cached:')}}
{{ Form::text('cached')	}}<br>

{{ Form::submit('Get folder Contents')	}}
{{ Form::close()	}}

<br>
<br>
<br>


{{ Form::open(array('route'=>'create_folder_route', 'as'=>'create_folder','method'=>'get')) }}
{{ Form::hidden('cloudName','Dropbox')	}}
{{ Form::label('folder','Folder eg /Projects/Subproject :::')}}
{{ Form::text('folderPath')	}}<br>
{{ Form::label('userCloudID','userCloudID:')}}
{{ Form::text('userCloudID')	}}<br>
{{ Form::submit('Create folder ')	}}
{{ Form::close()	}}

<br>
<br>
<br>


{{ Form::open(array('route'=>'delete_route', 'as'=>'delete_folder','method'=>'delete')) }}
{{ Form::hidden('cloudName','Dropbox')	}}
{{ Form::label('file/folder','Folder eg /Projects/Subproject File eg /Projects/file.txt:::')}}
{{ Form::text('path')	}}<br>
{{ Form::label('userCloudID','userCloudID:')}}
{{ Form::text('userCloudID')	}}<br>
{{ Form::submit('Delete ')	}}
{{ Form::close()	}}

<br>
<br>
<br>
<br>
{{ Form::open(array('route'=>'upload_with_encryption', 'cloudName' => 'Dropbox' ,'files' => true, 'as'=>'upload','method'=>'post'))}}
{{ Form::hidden('cloudName','Dropbox' )	}}
{{ Form::label('userCloudID','userCloudID:')}}
{{ Form::text('userCloudID')	}}<br>
{{ Form::label('cloudDestination path', 'Cloud destination without trailing slash:')	}}
{{ Form::text('cloudDestinationPath')}}			
{{ Form::text('passKey','User Pass Key')}}			
{{ Form::label('file', 'File:')	}}
{{ Form::file('files[]',array('multiple'=>true))	}}
{{ Form::submit('Upload Encrypted Files ')	}}
{{ Form::close()	}}

<br>
<br>
<br>
<br>


{{ Form::open(array('route'=>'download_encrypted_file', 'files' => true, 'as'=>'download','method'=>'post')) }}
{{ Form::hidden('cloudName','Dropbox')	}}
{{ Form::label('cloudSource','Cloud SOurce Path eg. /Project/SubProject :::')}}
{{ Form::text('cloudSourcePath')	}}<br>
{{ Form::label('file','FileName: ')}}
{{ Form::text('fileName')	}}<br>
{{ Form::text('passKey','User Pass Key')}}	
{{ Form::label('userCloudID','userCloudID:')}}
{{ Form::text('userCloudID')	}}<br>
{{ Form::submit('Download Encrypted File')	}}
{{ Form::close()	}}
<br>
<br>
<br>
