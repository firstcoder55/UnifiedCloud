$(function(){

cloud ="";
userCloudID=0;
var baseUrl = window.location.pathname;
//console.log(baseUrl);

/* ========================================================
 *	Core helper functions
 * ========================================================
 */

/**
 * test function
 */
function testFunction(str) {
	$(".cloud-controls").click(function(){
		alert(str);
	});
}

/**
 * call notify function with default values
 * @param  {String} message     Message text.
 * @param  {String} messageType Type of message to configure presentation of notification
 */
function notification(message,messageType) {
	$('.container').notify(message,{
		'arrowShow' : false,
		'elementPosition' : 'top center',
		'globalPosition' : 'top center',
		'className' : messageType,
		'autoHideDelay' : '2000',
		'showAnimation' : 'fadeIn',
		'hideAnimation' : 'fadeOut'
	});
}

/**
 * convert bytes to human readable sizes
 * @param  {Integer} size Size of file in bytes
 */
function getReadableSize(size) {
	if(size < 1024) {
		return size.toString() + " Bytes";
	}
	else if(size >= 1024 && size < 1048576) {
		return (size/1024).toString() + "Kb";
	}
	else if(size >= 1048576 && size < 1073741824) {
		return (size/1048576).toString() + "Mb";
	} else {
		return(size/1073741824).toString() + "Gb";
	}
}	


/*
* @param {String} ext File extension
* @return: {associative array} class for glyphicon
*							
*/
function getClassFromExtension(ext) {
	var result = {};
	if(ext == 'png' || ext == 'jpeg' || ext == 'jpg' || ext == 'gif') {
		result['class'] = 'glyphicon glyphicon-picture';
		result['ext'] = 'Image';
		return result;
	}
	else if(ext == 'mp3') {
		result['class'] = 'glyphicon glyphicon-music';
		result['ext'] = 'Music';
		return result;
	}
	else if(ext == 'avi' || ext == 'mp4' || ext == 'wmv' || ext == 'mkv') {
		result['class'] = 'glyphicon glyphicon-film';
		result['ext'] = 'Video';
		return result;	
	} 
	else {
		result['class'] = 'glyphicon glyphicon-file';
		result['ext'] = "Document";
		return result;
	}
}

/**
 * function triggered on create new folder event
 * @param  {string} folderName 
 * @param  {[type]} jObj       li element over which this function is called
 */
function createNewFolder(folderName,jObj) {
	if(folderName == '') {
		
		//remove tr when not folder name specified
		jObj.parent().parent().remove();
	
		notification('folder name required','error');

	} else {

		var fPath = $('#cwd').html() + '/' + folderName;
		$.ajax({
			 type: 'GET',
            url: 'new_folder',
            data: {cloudName: cloud, folderPath : fPath, userCloudID: userCloudID},
            cache: false 
		}).done(function() {

			notification('Folder created','success');
		});

		jObj.parent().parent().remove();
	}

}

/**
 * get the folder content of the current folder
 * @param  {String} cloud cloud name for which action must be performed
 * @param  {String} fPath file path 
 * @param  {Boolean String} cache to decide if the folder content should be cached
 */
function getFolderContents(cloud,fPath,cache) {

	$('.loading').addClass('loading-gif');
	$.ajax({
		type:'GET',
		url:'folder_content',
		data: {cloudName: cloud , folderPath: fPath , userCloudID: userCloudID , cached : cache},
		cache: false
	})
	.done(function(jsonData){

		$('.loading').removeClass('loading-gif');
		//console.log(jsonData);
		//server sends json as string
		//parsing json string to json object
		jsonData = $.parseJSON(jsonData);
		console.log(jsonData);

		var table = $('#file-explorer');
		var tbody = table.find('tbody');

		//we need the cloud name to make further ajax calls
		//therefore appending cloud name as class name to tbody
		//tbody.addClass(cloud);
		tbody.html('');
		$.each(jsonData,function(i,file){
			var ext, extClass;

			if(file.is_directory == '1') {
				var tr=$("<tr class='folder'></tr>");
				tbody.append(tr);
				var td = $("<td class='context-menu-one'><span class='glyphicon glyphicon-folder-close'></span><a  href='#' class='directory'>" + file.file_name +"</a></td>" );
				tr.append(td);
			} else {
				var tr=$("<tr></tr>");
				tbody.append(tr);

				//getting file extension
				ext = file.file_name.split('.').pop();
				extClass = getClassFromExtension(ext);
				var td = $('<td class="context-menu-one"><span class="' + extClass['class'] + '"></span><a href="#" class="file">' + file.file_name +'</a></td>' );
				tr.append(td);
			}

			//getting extension of file
			//ext = file.file_name.split('.').pop();

			//using jqery-dateformat plugin to get more readable date data.
			var td = $("<td>" + $.format.date(file.last_modified_time,'h:mm p d MMM yyyy') +"</td>" );
			tr.append(td);
			
			if(file.is_directory == '1') {
				var td = $("<td>-</td>" );
				tr.append(td);
				var td = $("<td>Folder</td>" );
				tr.append(td);
			} else {
				var td = $("<td>" + getReadableSize(file.size) +"</td>" );
				tr.append(td);
				var td = $("<td>" + extClass['ext'] +"</td>" );
				tr.append(td);

			}
		});

		$("table").trigger("update");
		
	});
	

}
/* ========================================================
 *	Sorting for file explorer
 * ========================================================
 */
// Intializing tablesorter plugin
    $("table").tablesorter({ 
        // define a custom text extraction function 
        textExtraction: function(node) { 
            // extract data from markup and return it  
             var aTag = node.childNodes[1];
             console.log(aTag);
             //return aTag.innerHTML;
             if(typeof(aTag) == 'undefined') {
             	return "";
             } else {
             	console.log(aTag.innerHTML);
             	return aTag.innerHTML;
             }
        },
        headers: { 1: {sorter : false },2: {sorter : false}, 3: {sorter : false}} 
    }); 



//sorting on thead click 
var direction = 1;
$('th').on('click',function(){
	if(direction == 0)
		direction = 1;
	else
		direction = 0;

	var index = $(this).index();
	console.log('index: '+index+' direction: '+ direction);
	
	var sorting = [[0,direction]];
		$("table").trigger("sorton",[sorting]);
});


/* ========================================================
 *	All tooltips
 * ========================================================
 */
$('#download').tooltip({
	'trigger' : 'hover',
	'title' : 'Download'
});

$('#upload').tooltip({
	'trigger' : 'hover',
	'title' : 'Upload'
});

$('#refresh').tooltip({
	'trigger' : 'hover',
	'title' : 'Refresh'
});

$('#settings').tooltip({
	'trigger' : 'hover',
	'title' : 'Settings'
});

$('#new-folder').tooltip({
	'trigger' : 'hover',
	'title' : 'New Folder'
});
$('#delete').tooltip({
	'trigger' : 'hover',
	'title' : 'Delete'
});

$('#add-cloud').tooltip({
	'trigger' : 'hover',
	'title' : 'Add new cloud'
});

/* ========================================================
 *	Core functions start here
 * ========================================================
 */

// get data based on the clicked user cloud
$('.cloud').click(function(){

	$('.cloud').removeClass('selected');
	$(this).addClass('selected');
	cloud = this.id;
	userCloudID = $(this).find('span').attr('id');
	//console.log(userCloudID);
	var fPath = '/';

	//populate breadcrumb
	$('.breadcrumb').html('<li>'+cloud+'</li>');
	$('#cwd').html(fPath);
	
	getFolderContents(cloud,fPath,'true');
});

$("#file-explorer tbody").on("click","a.directory",function(){
	//console.log("working!" + $(this).html());
	var nextPath =	$(this).html();
	var cwd = $('#cwd').html();

	//get breadcrumb list
	var breadcrumb = $('.breadcrumb');
	if(cwd == '/')
		var fPath = cwd  + nextPath;
	else
		var fPath = cwd + '/' + nextPath;
	
	breadcrumb.append('<li>'+nextPath+'</li>');
	$('#cwd').html(fPath);

	getFolderContents(cloud,fPath,'true');
});

//register click on table row.
$('#file-explorer tbody').on('click','tr',function(e){
	//console.log('clicked table row');
	$('tr').not(this).removeClass('clicked-row');
    $(this).toggleClass('clicked-row'); 
    e.stopPropagation();
});

//remove class clicked-row when tbody loses focus 
//NOTE: focus is only associated with elements like input. 
//Table cannot have that is why I'm using this trick to handle focusout. 
$(document).on('click',function(){
	//console.log('focusout from table row');
	$('tr.clicked-row').removeClass('clicked-row');
});

//breacrumb controls
$('.breadcrumb').on('click','li',function(){
	//console.log($(this).html());
	var val = $(this);
    fPathArray = new Array();
    
    while(!(typeof(val.prev().html()) === 'undefined')){
        fPathArray.push(val.html());
        val = val.prev();
    }
    
    val = $(this);
    val = val.nextAll().remove();
    
    fPathArray.push('r');
    fPathArray.reverse();
    var fPath = fPathArray.join('/').substr(1);
	if(fPath == '')
		fPath = '/';
	$('#cwd').html(fPath);
	getFolderContents(cloud,fPath,'true');
	//console.log(fPath);
});

//download
function downloadContainer() {
	//console.log("download menu button clicked !");

	//set variables for ajax call
	var cwd = $('#cwd').html();
	var file = $('#file-explorer tbody tr.clicked-row').find('a.file').html(); 
	
	if(typeof(file) == 'undefined') {
		
		var folder = $('#file-explorer tbody tr.clicked-row').find('a.directory').html(); 
		var folderPath='';
		if(cwd == '/') 
			folderPath = cwd+folder;
		else 
			folderPath = cwd + '/' + folder;

		console.log(folderPath);
		if(typeof(folder) == 'undefined') {
			notification('Select a file/folder first','error');
		} else {
			url = "download_folder?userCloudID="+ userCloudID +"&cloudName=" + cloud + "&folderPath=" + folderPath; 	
			window.location.href = url;

		}
	}
	else {
	//console.log(cwd + " : "+ file);
		url = "download?userCloudID="+ userCloudID +"&cloudName=" + cloud + "&cloudSourcePath=" + cwd + "&fileName=" + file; 
		console.log(url);
		window.location.href = url;
	}
}
$('#download').on('click',function(){
	downloadContainer();
});


//upload
//input passkey input when checkbox clicked
$('#encryptCheck').on('change',function(){
	if(this.checked) {
		var passKeyInputDiv = $('#fileUploadForm .passKeyInput');
    	passKeyInputDiv.html("<input type='password' name='passKey' class='form-control' placeholder='Encryption Pass Key'>");
    }  	
});

$('#fileUploadForm').submit(function(e) {
       	e.preventDefault();

       	$('[name="cloudDestinationPath"]').attr('value',$('#cwd').html());
       	$('[name="userCloudID"]').attr('value',userCloudID);
        data = new FormData($('#fileUploadForm')[0]);
        console.log('Submitting');

        var encryptFile = $('#encryptCheck').prop('checked');
        console.log("encrypt file before upload: "+encryptFile);

        if(encryptFile) {
        	console.log('sending via Encryption');
        	ajaxUpload('uploadWithEncryption/Dropbox');
        } else {
			// url: 'upload/Dropbox'
			ajaxUpload('upload/Dropbox');		        	
        }


});

function ajaxUpload(url) {

	//hide form and start loading gif until upload is complete or failed
	$('#fileUploadModal').modal('hide');
	$('.loading').addClass('loading-gif');
	$.ajax({
        type: 'POST',
        url: url,
        data: data,
        cache: false,
        contentType: false,
        processData: false,
    	success: function(data) {
	        //console.log(data);

	        //remove loading gif after upload is complete
	        $('.loading').removeClass('loading-gif');
	        //notify user on success
			notification('File uploaded','success');
	 		//update folder contents
			//getFolderContents(cloud,$('#cwd').html(),'false');
			
			//reset form
			//$('#fileUploadForm').clearForm();
    	},
    	error: function(jqXHR,status, errorThrown) {
	        console.log(errorThrown);
	        console.log(jqXHR.responseText);
	        console.log(jqXHR.status);
	        $('#fileUploadModal').modal('hide');
	        $('.loading').removeClass('loading-gif');
	        notification('Upload failed!','error');

	        //reset form
			//$('#fileUploadForm').clearForm();
    	},
    	complete: function() {
    		$('#fileUploadForm').each(function(){
    			this.reset();
    		});
    	}
    });
}

//refresh

$('#refresh').on('click',function(){
	
	getFolderContents(cloud,$('#cwd').html(),'false');
});

//settings
// TODO Abhishek Nair


//new-folder
$('#new-folder').on('click',function(){
	var tbody = $('tbody');
	var tr = $('<tr></tr>');
	tbody.prepend(tr);
	var td = $('<td></td>');
	var td_input = $('<td><input type="text" class="form-control" placeholder="new folder" id="new-folder-input"></td>');
	tr.append(td_input);
	tr.append(td);
	var td = $('<td>Folder</td>');
	tr.append(td);
	var td = $('<td></td>');
	tr.append(td);
	$('#new-folder-input').focus();
});

$('tbody').on('focusout','#new-folder-input',function(){
	var folderName = $('#new-folder-input').val();
	createNewFolder(folderName,$(this));
});

$('tbody').on('keypress','#new-folder-input',function(e){
	
	//keyCode for Enter key is 13
	if(e.keyCode == 13) {
		e.preventDefault();

		var folderName = $('#new-folder-input').val();
		createNewFolder(folderName,$(this));
	}
});


//delete file or folder 
$('#delete').on('click',function(){

	var fileOrFolder = $('#file-explorer tbody tr.clicked-row').find('a.file').html(); 
	var currentDir = $('#cwd').html();
	var pathToCurrentDir = ''; //create path from cwd.
	var pathToFileOrFolder = ''; //actual path to file or folder.
	if(currentDir != '/') {
		pathToCurrentDir = currentDir + '/';
	} else {
		pathToCurrentDir = currentDir;
	}

	if(typeof(fileOrFolder) == 'undefined') {
		fileOrFolder = $('#file-explorer tbody tr.clicked-row').find('a.directory').html();
		
		
		if(typeof(fileOrFolder) == 'undefined') {
			//notify to select file/folder
			notification('Select a file/folder first','error');
		}
	} else {

		console.log('folder: '+fileOrFolder);

		pathToFileOrFolder = pathToCurrentDir + fileOrFolder;

		$('.loading').addClass('loading-gif');

		$.ajax({
			type: 'DELETE',
            url: 'delete',
            data: {cloudName: cloud, path : pathToFileOrFolder, userCloudID: userCloudID},
            cache: false 
		}).done(function() {

			$('.loading').removeClass('loading-gif');
			getFolderContents(cloud,currentDir,'false');
			notification('deleted!','success');
		});
	}
});

//auth call for Dropbox
$('#Dropbox-auth').on('click',function(){
	var userCloudName = $('[name="userCloudName"]').val();
	var cloudName = $('#dropboxAuthModal .modal-title').html();
	url = "authenticate/" + cloudName + "/" +userCloudName;
	console.log(url);
	window.location.href = url;
});


//auth call for Google Drive
$('#Drive-auth').on('click',function(){
	var userCloudName = $('[name="userCloudName"]').val();
	var cloudName = $('#googleDriveAuthModal .modal-title').html();
	url = "authenticate/" + cloudName + "/" +userCloudName;
	console.log(url);
	window.location.href = url;
});


/* ========================================================
 *	Context Menu functions
 * ========================================================
 */
$.contextMenu({
    selector: '.context-menu-one', 
    /*trigger: 'hover',
    delay: 500,*/
    callback: function(key, options) {
        var m = "You clicked: " + key;
        console.log(m);
        switch(key) {
        	case "download" : 
        		console.log("downloading...");
        		downloadContainer();
        		break;
        	default:
        		console.log("defaulting...");
        		break;
        } 
    },
    items: {
        "download": {name: "Download" , icon:"context-menu-icon glyphicon glyphicon-download"},
        "share": {name: "Share" , icon:"context-menu-icon glyphicon glyphicon-share"},
        "delete": {name: "Delete" , icon:"context-menu-icon glyphicon glyphicon-trash"}
    }
});

/*$('.context-menu-one').on('click', function(e){
    console.log('clicked', this);
})*/
	
});//end of document