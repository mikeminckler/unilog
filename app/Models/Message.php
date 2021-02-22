<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Collection;

class Message extends Model {

	protected $fillable = ['contact_id', 'user_id', 'message_type_id', 'school_id', 'contents', 'attachment_id', 'hidden'];

	public function MessageType() {
		return $this->belongsTo('App\MessageType');
	}
	
	public function School() {
		return $this->belongsTo('App\School');
	}
	
	public function Contact() {
		return $this->belongsTo('App\Contact');
	}

	public function Attachment() {
		return $this->belongsTo('App\Attachment');
	}

	public function User() {
		return $this->belongsTo('App\User');
	}

	public $rules = array(
		'contact_id' => 'required',
		'message_type_id' => 'required',
        'contents' => 'required'
	);

	public function searchTerms($query) {
		$terms = explode(' ', $query);

		$contact_array = array();

                foreach($terms as $term) {

			$results = $this->search($term)->get(); 

			foreach ($results as $result) {

				if (array_key_exists($result->id, $contact_array)) {

					//$contact_array[$result->id] = $contact_array[$result->id] + strtotime($result->updated_at);
					$contact_array[$result->id] ++;

				} else {
					//$contact_array[$result->id] = strtotime($result->updated_at);
					$contact_array[$result->id] = 1;
				}

			}
	
                }

		// drop results that dont have the max hits
		$return_array = array();
		if (count($contact_array) > 0) {
			$hit_count = max($contact_array);

			$filtered = array_filter($contact_array, function ($x) use ($hit_count) { return $x >= $hit_count; });

			foreach ($filtered as $item => $foo) {
				$item_result = $this->find($item);	
				$return_array[$item] = strtotime($item_result->updated_at);
			}
		}

		return $return_array;
	}

	public function scopeSearch($query, $search) {

		$messages = $this->where('hidden', '0')
	
			->where('messages.updated_at', '>', session()->get('start_date'))
                        ->where('messages.updated_at', '<', session()->get('end_date'))
		
		->where(function($query) use ($search) {

			$query->where('messages.contents', 'LIKE', "%$search%")
			->orWhere('messages.id', $search)
			->orWhereHas('Contact', function($query) use ($search) {
				$query->where(function($query) use ($search) {
					$query->where('contacts.name', 'LIKE', "%$search%")
					->orWhere('contacts.surname', 'LIKE', "%$search%"); 
				});
			})->orWherehas('School', function($query) use ($search) {
				$query->where(function($query) use ($search) {
					$query->where('schools.school_name', 'LIKE', "%$search%");
				});
			})->orWherehas('MessageType', function($query) use ($search) {
				$query->where(function($query) use ($search) {
					$query->where('message_types.message_type_name', 'LIKE', "%$search%");
				});
			})->orWherehas('Attachment', function($query) use ($search) {
				$query->where(function($query) use ($search) {
					$query->where('attachments.attachment_filename', 'LIKE', "%$search%");
				});
			});

		})->orderBy('messages.updated_at', 'DESC');

		return $messages;

	}

    public static function processIds($ids) 
    {
        $messages = new Collection;
        foreach ($ids as $message_id => $foo) {
            $message = self::find($message_id);
            if ($message instanceof Message) {
                $messages->add($message);
            }
        } 
        return $messages;
    }

}

?>
