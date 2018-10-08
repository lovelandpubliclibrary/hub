<div class="form-group">
	{{ Form::label('patrons[]', 'Patrons Involved:', ['class' => 'control-label']) }}
	{{ Form::select('patrons[]', $patrons, $incident->patron, ['class' => 'selectpicker form-control',
														'multiple' => 'multiple',
														'id' => 'patrons']) }}
</div>	{{-- .form-group --}}

<div class="form-group">
	<div>
		<button type="button" id="togglePatronModal" class="btn btn-default" data-toggle="modal" data-target="#addPatronModal">
			<div>
				<span class="glyphicon glyphicon-plus-sign"></span>
			</div>
			Add a new patron
		</button>
	</div>
</div> 	{{-- .form-group --}}