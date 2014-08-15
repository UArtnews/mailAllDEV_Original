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
        &nbsp;&nbsp;&nbsp;Unsaved<br/>
        &nbsp;&nbsp;&nbsp;Changes
    </div>
    @if(isset($tweakables['publication-hr-articles']))
        @if($tweakables['publication-hr-articles'] == 1)
            <hr/>
        @endif
    @elseif($default_tweakables['publication-hr-articles'] == 1)
        <hr/>
    @endif
</div>
