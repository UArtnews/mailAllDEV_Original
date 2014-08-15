<div class="row publicationEditor" id="publicationEditor{{$thisPublication->id}}" style="display:{{ $display }};">
    <div class="col-sm-10 col-sm-offset-1 col-xs-12">
        <div class="well" style="text-align:center;">
            <div class="row">
                <div class="col-xs-4">
                    @if(count($thisPublication->submissions) > 0)
                    <div class="btn-group ">
                        <button class="btn btn-success" data-toggle="modal" data-target="#addFromCartModal{{ $thisPublication->id }}">Add From Cart</button>
                        <button class="btn btn-warning" data-toggle="modal" data-target="#addPendingSubmissionsModal{{ $thisPublication->id }}">Submitted Articles</button>
                    </div>
                    @else
                        <button class="btn btn-success pull-left" data-toggle="modal" data-target="#addFromCartModal{{ $thisPublication->id }}">Add Article From Cart</button>
                    @endif
                </div>
                <div class="col-xs-4">
                    <button class="btn btn-primary" data-toggle="modal" data-target="#sendEmailModal{{ $thisPublication->id }}">
                        <span class="glyphicon glyphicon-send"></span>&nbsp;
                        Publish Email
                    </button>
                </div>
                <div class="col-xs-4">
                    <div class="btn-group pull-right">
                        <button id="unpublishBtn{{ $thisPublication->id }}" class="btn btn-danger" onclick="$('#unpublishBtnConfirm{{ $thisPublication->id }}').toggle()" @if($thisPublication->published == 'N')disabled="disabled"@endif>Unpublish</button>
                        <button id="unpublishBtnConfirm{{ $thisPublication->id }}" class="btn btn-warning" onclick="unpublishStatus({{ $thisPublication->id }})" style="display:none;">Are you sure?</button>
                        <button id="publishBtnConfirm{{ $thisPublication->id }}" class="btn btn-warning" onclick="publishStatus({{ $thisPublication->id }})" style="display:none;">Are you sure?</button>
                        <button id="publishBtn{{ $thisPublication->id }}" class="btn btn-success" onclick="$('#publishBtnConfirm{{ $thisPublication->id }}').toggle()" @if($thisPublication->published == 'Y') disabled="disabled" @endif>Publish</button>
                    </div>
                </div>
            </div><br/>
            <div class="row">
                @if(isset($currentLivePublication) && $publication->id == $currentLivePublication->id)
                <a href="{{ URL::to($instance->name) }}"><span class="badge" style="background-color:red;">Live</span></a> Publication published on {{ date('m-d-Y',strtotime($thisPublication->publish_date)) }}
                @elseif($thisPublication->published == 'Y')
                <span class="badge alert-success">Published</span> on {{ date('m-d-Y',strtotime($thisPublication->publish_date)) }}
                @else
                <span class="badge alert-warning">Unpublished</span> to be published on {{ date('m-d-Y',strtotime($thisPublication->publish_date)) }}
                @endif
            </div>
        </div>
        <!-- Now to iterate through the articles -->
        <div class="contentDiv" id="publication{{ $thisPublication->id }}">
            <img class="publicationBanner  img-responsive" src="{{$thisPublication->banner_image}}"/>
            {{ isset($tweakables['publication-header']) ? $tweakables['publication-header'] : '' }}
            {{-- Insert Article Summary Conditionally --}}
            @if( isset($tweakables['publication-headline-summary']) ? $tweakables['publication-headline-summary'] : $default_tweakables['publication-headline-summary'] == 1)
                <h3 class="headline-summary-header">Today's Headlines:</h3>
                @foreach($thisPublication->articles as $article)
                    <a href="#articleTitle{{ $article->id }}">{{ strip_tags($article->title) }}</a><br/>
                @endforeach
            @endif
            <div class="article-container">
                @foreach($thisPublication->articles as $article)
                    @include('snippet.article', array('contentEditable' => true))
                @endforeach
            </div>
            {{ isset($tweakables['publication-footer']) ? $tweakables['publication-footer'] : $default_tweakables['publication-footer'] }}
        </div>
    </div>
</div>
<div class="modal fade" id="addFromCartModal{{ $thisPublication->id }}" role="dialog">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
                <h4 class="modal-title">Article Cart<small>&nbsp;&nbsp;Articles ready for inclusion in a publication.</small></h4>
            </div>
            <div class="modal-body">
                @if(isset($cart) && count($cart) > 0)
                <ul id="addFromCartList" class="list-group">
                    @foreach($cart as $article_id => $title)
                    <li id="addCartArticle{{ $article_id }}"class="list-group-item addCartItem">
                        {{ $title }}&nbsp;&nbsp;
                        <button class="btn btn-xs btn-success" onclick="addArticleToExistingPublication({{ $article_id }}, {{ $thisPublication->id }}, true)">
                            <strong>+</strong>&nbsp;Add Article to Publication
                        </button>
                    </li>
                    @endforeach
                </ul>
                <button class="btn btn-success btn-block" onclick="addArticleCartToExistingPublication({{ $thisPublication->id }})">Add All Articles From Cart</button>
                @else
                <ul id="cartList" class="list-group">
                    <li id="emptyCartItem" class="list-group-item list-group-item-warning">There are no articles in your cart!</li>
                </ul>
                @endif
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
            </div>
        </div><!-- /.modal-content -->
    </div><!-- /.modal-dialog -->
</div><!-- /.modal -->
<div class="modal fade" id="sendEmailModal{{ $thisPublication->id }}" role="dialog">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
                <h4 class="modal-title">Send Publication Email
                    <small class="alert-warning">
                        &nbsp;&nbsp;
                        @if($thisPublication->published == 'N')
                        This will publish this publication!
                        @else
                        This publication is already published.
                        @endif
                    </small>
                </h4>
            </div>
            <div class="modal-body ">
                {{ Form::open(array('method' => 'post','url' => URL::to('sendEmail/'.$instance->name.'/'.$thisPublication->id))) }}

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
                    <span class="btn btn-success" onclick="$('#publishEmailSubmit{{ $thisPublication->id }}').toggle()">Email Publication</span>
                    {{ Form::submit('Are you sure you want to publish? ', array('id' => 'publishEmailSubmit'.$thisPublication->id, 'class' => 'btn btn-warning', 'style' => 'display:none;')) }}
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

@if(count($thisPublication->submissions) > 0)
<div class="modal fade" id="addPendingSubmissionsModal{{ $thisPublication->id }}" role="dialog">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
                <h4 class="modal-title">Pending Submissions<small>&nbsp;&nbsp;Articles accepted from submitters.</small></h4>
            </div>
            <div class="modal-body">
                <ul id="addFromCartList{{ $thisPublication->id }}" class="list-group">
                    @foreach($thisPublication->submissions as $submission)
                    <li id="addPendingSubmission{{ $submission->id }}" class="list-group-item addPendingSubmission">
                        {{ stripslashes($submission->title) }}&nbsp;&nbsp;
                        <button class="btn btn-xs btn-success" onclick="addArticleToExistingPublication({{ $submission->id }}, {{ $thisPublication->id }})">
                            <strong>+</strong>&nbsp;Add Article to Publication
                        </button>
                        @if(preg_match('/'.$submission->id.'/', $thisPublication->article_order))
                            <span class="label label-warning">Included In Publication</span>
                        @endif
                    </li>
                    @endforeach
                </ul>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
            </div>
        </div><!-- /.modal-content -->
    </div><!-- /.modal-dialog -->
</div><!-- /.modal -->
@endif