<br>
<br>
<br>

{{ Form::open(array('route'=>'upload_route', 'files' => true, 'as'=>'upload','method'=>'post')) }}
{{ Form::hidden('cloudName','Dropbox' )	}}
{{ Form::hidden('userCloudID','1' )	}}
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
{{ Form::hidden('userCloudID','1' )	}}
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
{{ Form::hidden('userCloudID','1')	}}
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

