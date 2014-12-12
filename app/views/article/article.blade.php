@if(!$isEmail)
<div class="article" id="article{{ $article->id }}">
@endif
    {{-- Setup Href Headlines for Repeats --}}
    @if( $isEmail && ( isset($tweakables['publication-show-titles']) ? $tweakables['publication-show-titles'] : $default_tweakables['publication-show-titles'] ) == false)
    @elseif($isRepeat && $hideRepeat && !$isEditable)
        <a href="{{ URL::to($instanceName.'/archive/'.$article->originalPublication().'#article'.$article->id) }}">
            <h1 id="articleTitle{{ $article->id }}" class="articleTitle{{ $isEditable ? ' editable' : '' }}">{{ stripslashes($article->title) }}</h1>
        </a>
    {{-- Setup Href Headlines for Emails --}}
    @elseif($isEmail)
        <a href="{{ URL::to($instanceName.'/archive/'.$publication->id.'#article'.$article->id) }}">
            <h1 id="articleTitle{{ $article->id }}" class="articleTitle">{{ stripslashes($article->title) }}</h1>
        </a>
    @elseif($isEditable)
        <h1 id="articleTitle{{ $article->id }}" class="articleTitle editable">{{ stripslashes($article->title) }}</h1>
    @else
        <a href="{{ URL::to($instanceName.'/article/'.$article->id) }}">
            <h1 id="articleTitle{{ $article->id }}" class="articleTitle{{ $isEditable ? ' editable' : '' }}">{{ stripslashes($article->title) }}</h1>
        </a>
    @endif
    {{-- Conditional HR's after Titles --}}
    @if(isset($tweakables['publication-hr-titles']))
        @if($tweakables['publication-hr-titles'] == 1)
            <hr/>
        @endif
    @elseif($default_tweakables['publication-hr-titles'] == 1)
        <hr/>
    @endif
    {{-- Email Article Content Body --}}
    @if($isEmail && $isRepeat && $hideRepeat)
        <div class="repeatedArticleContent">This article originally appeared on
            <a href="{{ URL::to($instanceName.'/archive/'.$article->originalPublication().'#articleTitle'.$article->id) }}">{{ date('n-d-Y',strtotime($article->originalPublishDate())); }}</a>
        </div>
    @elseif($isEmail && $isRepeat)
        <div id="articleContent{{ $article->id }}" class="articleContent{{ $isEditable ? ' editable' : '' }}"><p>{{ stripslashes($article->content) }}</p></div>
    @elseif($isEmail)
        <div id="articleContent{{ $article->id }}" class="articleContent{{ $isEditable ? ' editable' : '' }}"><p>{{ stripslashes($article->content) }}</p></div>
    {{-- Non-Email Article Content Body --}}
    @elseif($isRepeat)
        <{{ $isEditable ? 'div' : 'div' }} class="repeatedArticleContent" style="{{ $hideRepeat?'':'display:none;' }}">This article originally appeared on
            <a href="{{ URL::to($instanceName.'/archive/'.$article->originalPublication().'#articleTitle'.$article->id) }}">{{ date('n-d-Y',strtotime($article->originalPublishDate())); }}</a>
            @if($isEditable)
                <button type="button" class="btn btn-xs btn-default" onclick="unhideRepeated({{ $article->id }}, '{{ $publication->id or ''}}');">Show Full Article</button>
            @endif
        </{{ $isEditable ? 'div' : 'div' }}>
        <{{ $isEditable ? 'div' : 'div' }} id="articleContent{{ $article->id }}" class="articleContent{{ $isEditable ? ' editable' : '' }}" style="{{ $hideRepeat?'display:none;':'' }}">{{ stripslashes($article->content) }}</{{ $isEditable ? 'div' : 'div' }}>
    @else
        <{{ $isEditable ? 'div' : 'div' }} id="articleContent{{ $article->id }}" class="articleContent{{ $isEditable ? ' editable' : '' }}">{{ stripslashes($article->content) }}</{{ $isEditable ? 'div' : 'div' }}>
    @endif
    {{-- Conditional Share Icons --}}
    @if($shareIcons)
        @include('public.share', array('shareURL' => URL::to($instanceName.'/article/'.$article->id),'shareTitle' => stripslashes(strip_tags($article->title)) ) )
    @endif
    {{-- Editor Controls --}}
    @if($isEditable)
        <div id="articleIndicator{{ $article->id }}" class="side-indicator">
            <div id="articleIndicator{{ $article->id }}" class="side-indicator-hitbox">
            </div>
            &nbsp;&nbsp;&nbsp;Unsaved<br/>
            &nbsp;&nbsp;&nbsp;Changes
        </div>
    @endif
    @yield('details')
@if(!$isEmail)
</div>
@else
    @if(isset($tweakables['publication-hr-articles']) && $isEmail)
        @if($tweakables['publication-hr-articles'] == 1)
            <hr/>
        @endif
    @elseif($default_tweakables['publication-hr-articles'] == 1 && $isEmail)
        <hr/>
    @endif
@endif

