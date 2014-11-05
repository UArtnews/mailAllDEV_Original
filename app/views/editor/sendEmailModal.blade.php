<div class="modal fade" id="sendEmailModal{{ $publication->id }}" role="dialog">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
                <h4 class="modal-title">Send Publication Email
                    <small class="alert-warning">
                        &nbsp;&nbsp;
                        @if($publication->published == 'N')
                        This will publish this publication!
                        @else
                        This publication is already published.
                        @endif
                    </small>
                </h4>
            </div>
            <div class="modal-body ">
                {{ Form::open(array('method' => 'post','url' => URL::to('sendEmail/'.$instance->name.'/'.$publication->id))) }}

                {{ Form::label('addressTo', 'To: ') }}
                {{ Form::text('addressTo',null ,array('class' => 'form-control')) }}
                <br/>

                {{ Form::label('addressFrom', 'From Address: ') }}
                {{ Form::text('addressFrom',null ,array('class' => 'form-control')) }}
                <br/>

                {{ Form::label('nameFrom', 'From Name: ') }}
                {{ Form::text('nameFrom',null ,array('class' => 'form-control')) }}
                <br/>

                {{ Form::label('subject', 'Subject: ') }}
                {{ Form::text('subject',null ,array('class' => 'form-control')) }}
                <br/>

                {{ Form::label('isTest', 'Send Test Email ONLY: ') }}
                {{ Form::checkbox('isTest', 'true', array('class' => 'btn btn-warning')) }}
                <br/>

                <div class="btn-group">
                    <span class="btn btn-success" onclick="$('#publishEmailSubmit{{ $publication->id }}').toggle()">Email Publication</span>
                    {{ Form::submit('Are you sure you want to publish? ', array('id' => 'publishEmailSubmit'.$publication->id, 'class' => 'btn btn-warning', 'style' => 'display:none;')) }}
                    <span class="btn btn-default" disabled="disabled"><span class="glyphicon glyphicon-send"></span></span>
                </div>

                {{ Form::close() }}
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
            </div>
        </div><!-- /.modal-content -->
    </div><!-- /.modal-dialog -->
</div><!-- /.modal -->