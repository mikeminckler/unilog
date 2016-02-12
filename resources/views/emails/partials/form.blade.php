<div class="form">

    {!! Form::open(['files' => true, 'url' => '/email']) !!}

	<div class="input-block">
                <div class="label">
            {!! Form::label('contact_id', 'Student') !!}
		</div>
		<div class="input">
            {!! Form::text('student_id', null, ['class' => 'student-list']) !!}
            {!! Form::select('contact_id[]', ['' => ''] + $contacts, $selected, ['class' => 'student-list required autocomplete', 'multiple']) !!}
			<div class="student-list"></div>
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


	<div class="input-block top-border">
		<div class="label">
            {!! Form::label('to', 'To') !!}
		</div>
		<div class="input">
            {!! Form::text('to', $student_emails, ['class' => 'text-input required email-to']) !!}
		</div>
	</div>

	<div class="input-block">
		<div class="label">
            {!! Form::label('subject', 'Subject') !!}
		</div>
		<div class="input">
            {!! Form::text('subject', null, ['class' => 'text-input required']) !!}
		</div>
	</div>

	<div class="input-block">
		<div class="label">
            {!! Form::label('body', 'Message') !!}
		</div>
		<div class="input">
            {!! Form::textarea('body', null, ['class' => 'required']) !!}
		</div>
	</div>

	
	<div class="input-block">
                <div class="label">
            {!! Form::label('attachment', 'Attachment') !!}
		</div>
		<div class="input">
            {!! Form::file('attachment') !!}
		</div>
	</div>



	<div class="input-block">
		<div class="submit">
            {!! Form::submit('Send Email') !!}
		</div>
	</div>

    {!! Form::close() !!}

</div>
