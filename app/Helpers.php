<?php


function formatMessages($messages, $two_level = false) {

	$return = '<div id="expand_container"><div id="expand_all" class="expand-all button clickable">Expand All</div>'.link_to('/messages/create', 'Create Log', ['class' => 'button']).'</div>';

	foreach ($messages as $message) {

		$return .= '<div class="message-container">';

		$return .= '<div id="student_select"><input class="clickable" type="checkbox" name="message_id['.$message->id.']" /></div>';

		if (Auth::user()->hasRole('admin')) {
			$return .= '<div id="delete" class="clickable" data-message-id="'.$message->id.'"><img src="/images/error.png" /></div>';
		}
		$return .= '<div id="add" class="clickable"><a href="/messages/create/'.$message->Contact->id.'"><img src="/images/plus.png" /></a></div>';


		if ($message->contents) {
			$return .= ' <div class="expand"><img src="/images/info.png" /></div>';
		}

		if ($message->Attachment) {
			$return .= '<div class="attachment-icon clickable"><img src="/images/attachment.png"  /></div>'; 
		}

		$return .= '<div class="message-header">';

			$return .= '<a href="/messages/'.$message->id.'">'.sprintf('%04d', $message->id).'</a>';


			if ($_SERVER["REQUEST_URI"] == '/students/'.$message->Contact->id || $_SERVER["REQUEST_URI"] == '/' || $two_level == true || strpos($_SERVER["REQUEST_URI"], 'search') || strpos($_SERVER["REQUEST_URI"], 'page')) {
                                $student_href = '/students/'.$message->Contact->id;
                        } else {
                                $student_href = $_SERVER["REQUEST_URI"].'/students/'.$message->Contact->id;       
                        }


			$return .= '<div class="log-student"><a class="log-header" href="'.$student_href.'">'.$message->Contact->fullName().'</a></div>';

			$return .= '<div class="log-date">'.date('n/j g:ia', strtotime($message->updated_at)).'</div>';

			if ($message->School) {

				if ($_SERVER["REQUEST_URI"] == '/schools/'.$message->School->id || $_SERVER["REQUEST_URI"] == '/' || $two_level == true || strpos($_SERVER["REQUEST_URI"], 'search') || strpos($_SERVER["REQUEST_URI"], 'page')) {
					$school_href = '/schools/'.$message->School->id;
				} else {
					$school_href = $_SERVER["REQUEST_URI"].'/schools/'.$message->School->id;	
				}

				$return .= '<div class="log-item log-item-school"><a class="log-header" href="'.$school_href.'">'.$message->School->school_name.'</a></div>';
			} else {
				$return .= '<div class="log-item"></div>';
			}


			if ($_SERVER["REQUEST_URI"] == '/messages-type/'.$message->MessageType->id || $_SERVER["REQUEST_URI"] == '/' || $two_level == true || strpos($_SERVER["REQUEST_URI"], 'search') || strpos($_SERVER["REQUEST_URI"], 'page')) {
				$type_href = '/messages-type/'.$message->MessageType->id;
			} else {
				$type_href = $_SERVER["REQUEST_URI"].'/messages-type/'.$message->MessageType->id;	
			}

			$return .= '<div class="log-item"><a class="log-header" href="'.$type_href.'">'.$message->MessageType->message_type_name.'</a></div>';
			//$return .= ' - '.$message->MessageType->message_type_name;

			$return .= '<div class="log-initials">'.$message->User->initials().'</div>';


		$return .= '</div>';


		$return .= '<div style="clear: both;"></div>';

		if ($message->Attachment) {
			$return .= '<div class="attachment-container"><div class="log-attachment">'.$message->Attachment->fileLink().'</div></div>'; 
		}

		$return .= '<div class="message-contents">';
		$return .= nl2br($message->contents);
		$return .= '</div>';

		$return .= '</div>';


	}

	$return .= '<div class="select-all clickable button">Select All</div>';
	$return .= '<div class="create-email clickable button">Create Email</div>';
	$return .= '<div class="export-csv clickable button">Export CSV</div>';
	$return .= '<div class="create-log clickable button">Create Log</div>';
	return $return;

}


?>
