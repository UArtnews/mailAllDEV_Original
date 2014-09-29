@if($contentEditable)
    <div class="article" id="article{{ $article->id }}">
        <h1 id="articleTitle{{ $article->id }}" class="editable articleTitle">{{ stripslashes($article->title) }}</h1>
        @if(isset($tweakables['publication-hr-titles']))
        @if($tweakables['publication-hr-titles'] == 1)
        <hr/>
        @endif
        @elseif($default_tweakables['publication-hr-titles'] == 1)
        <hr/>
        @endif
        <p id="articleContent{{ $article->id }}" class="editable articleContent">{{ stripslashes($article->content) }}<p>
        <div id="articleIndicator{{ $article->id }}" class="side-indicator">
            <div id="articleIndicator{{ $article->id }}" class="side-indicator-hitbox">
            </div>
        </div>
    </div>
    <div class="clearfix"></div>
    @if(isset($tweakables['publication-hr-articles']))
    @if($tweakables['publication-hr-articles'] == 1)
    <hr/>
    @endif
    @elseif($default_tweakables['publication-hr-articles'] == 1)
    <hr/>
    @endif
@else
    <div class="article" id="article{{ $article->id }}">
        <h1 id="articleTitle{{ $article->id }}" class="articleTitle">{{ stripslashes($article->title) }}</h1>
        @if(isset($tweakables['publication-hr-titles']))
        @if($tweakables['publication-hr-titles'] == 1)
        <hr/>
        @endif
        @elseif($default_tweakables['publication-hr-titles'] == 1)
        <hr/>
        @endif
        <p id="articleContent{{ $article->id }}" class=" articleContent">{{ stripslashes($article->content) }}<p>
    </div>
    <div class="clearfix"></div>
    @if($shareIcons)
        @include('snippet.share', array('shareURL' => URL::to($instanceName.'/archive/'.$publication->id),'shareTitle' => stripslashes(strip_tags($article->title)) ) )
    @endif
    @if(isset($tweakables['publication-hr-articles']))
        @if($tweakables['publication-hr-articles'] == 1)
            <hr/>
        @endif
    @elseif($default_tweakables['publication-hr-articles'] == 1)
        <hr/>
    @endif
@endif
