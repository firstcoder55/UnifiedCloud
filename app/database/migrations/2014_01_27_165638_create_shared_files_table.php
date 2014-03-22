<?php

use Illuminate\Database\Migrations\Migration;

class CreateSharedFilesTable extends Migration {

	public function up()
	{
			Schema::create('shared_files',function($table) {
			$table->increments('shared_fileID');
			$table->integer('fileID')->unsigned();
			$table->foreign('fileID')->references('fileID')->on('files')->onDelete('cascade');
			$table->integer('ownerID')->references('userID')->on('users')->onDelete('cascade');
			$table->integer('sharerID')->references('userID')->on('users')->onDelete('cascade');
			$table->unique(array('fileID','sharerID'));
			$table->timestamps();
		});
	}
	

	public function down()
	{
		Schema::drop('shared_files');
	}

}