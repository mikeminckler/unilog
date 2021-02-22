<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Attachment extends Model {

	protected $fillable = ['attachment_filename', 'hidden'];

	public function fileLink() {

		$filelink = "";

		$filelink .= '<a href="http://'.$_SERVER['SERVER_NAME'].'/messages/attachments/'.$this->id.'">';
		$filelink .= substr( $this->attachment_filename, strrpos( $this->attachment_filename, '/' ) + 1);
		$filelink .= '</a>';

		return $filelink;

	}
	
}

?>
