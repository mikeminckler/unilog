<div class="form">


	@if (isset($message)) 
        {!! Form::model($message, ['files' => true, 'route' => ['messages.update', $message->id]]) !!}
	@else
        {!! Form::open(['files' => true, 'url' => '/messages/create']) !!}
	@endif

	<div class="input-block">
                <div class="label">
            {!! Form::label('contact_id', 'Student') !!}
		</div>
		<div class="input">
			@if (isset($student) || isset($message) || isset($selected))
				@if (isset($student))
                    {!! Form::text('student_id', null, ['class' => 'student-list']) !!}
                    {!! Form::select('contact_id[]', ['' => ''] + $contacts, $student->id, ['class' => 'student-list required autocomplete', 'multiple']) !!}
				@elseif (isset($message))
                    {!! Form::text('student_id', $message->Contact->fullName(), ['class' => 'student-list']) !!}
                    {!! Form::select('contact_id[]', ['' => ''] + $contacts, $message->Contact->id, ['class' => 'student-list required autocomplete single']) !!}
				@else 
                    {!! Form::text('student_id', null, ['class' => 'student-list']) !!}
                    {!! Form::select('contact_id[]', ['' => ''] + $contacts, $selected, ['class' => 'student-list required autocomplete', 'multiple']) !!}
				@endif
			@else 
                {!! Form::text('student_id', null, ['class' => 'student-list']) !!}
                {!! Form::select('contact_id[]', ['' => ''] + $contacts, null, ['class' => 'student-list required autocomplete', 'multiple']) !!}
			@endif

			@if(!isset($message))
				<div class="student-list"></div>
			@endif

		</div>
	</div>


	<div class="input-block">
                <div class="label">
            {!! Form::label('school_id', 'School') !!}
		</div>
		<div class="input">
            {!! Form::select('school_id', ['' => ''] + $schools) !!}
            {!! Form::text('filter', null, ['class' => 'filter']) !!}
		</div>
	</div>


	<div class="input-block">
                <div class="label">
            {!! Form::label('message_type_id', 'Category') !!}
		</div>
		<div class="input">
            {!! Form::select('message_type_id', ['' => ''] + $message_types, null, ['class' => 'required']) !!}
            {!! Form::text('filter', null, ['class' => 'filter']) !!}
		</div>
	</div>


	<div class="input-block">
		<div class="label">
            {!! Form::label('contents', 'Log') !!}
		</div>
		<div class="input">
            {!! Form::textarea('contents') !!}
		</div>
	</div>

	
	<div class="input-block">
                <div class="label">
            {!! Form::label('attachment', 'Attachment') !!}
		</div>
		<div class="input">
			@if (isset($message))
				@if ($message->Attachment)
					<div class="attachment-name">{!! $message->Attachment->fileLink() !!}</div>
					<img src="/images/error.png" data-message-id="{{ $message->id }}" class="clickable remove-attachment" />
				@else
                    {!! Form::file('attachment') !!}
				@endif
			@else	
                {!! Form::file('attachment') !!}
			@endif
		</div>
	</div>
		
	<div class="input-block">
		<div class="submit">
			@if (isset($message))
                {!! Form::submit('Update Log') !!}
			@else
                {!! Form::submit('Create Log') !!}
			@endif
		</div>
	</div>

	@if (isset($message))

	<div class="input-block">
                <div class="label no-input">
                        Author
                </div>
                <div class="input no-input">
                        {{ $message->User->username }}
                </div>
        </div>

	<div class="input-block">
                <div class="label no-input">
                        Updated
                </div>
                <div class="input no-input">
                        {{ $message->updated_at }}
                </div>
        </div>


	<div class="input-block">
                <div class="label no-input">
                        Created
                </div>
                <div class="input no-input">
                        {{ $message->created_at }}
                </div>
        </div>



	@endif




{!! Form::close() !!}

</div>
