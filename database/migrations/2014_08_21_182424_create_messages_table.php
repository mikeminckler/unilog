<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateMessagesTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('messages', function(Blueprint $table)
		{
			$table->increments('id');
			$table->integer('contact_id');
                        $table->integer('user_id');
                        $table->integer('message_type_id');
                        $table->integer('school_id');
                        $table->mediumText('contents');
                        $table->integer('attachment_id');
                        $table->integer('email_id');
                        $table->integer('assessment_id');
                        $table->boolean('hidden');
			$table->timestamps();
		});
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('messages');
	}

}
