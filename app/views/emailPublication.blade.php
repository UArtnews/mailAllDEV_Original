<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <meta name="viewport" content="width=device-width"/>
    @if(isset($insertCss) && $insertCss)
        @include('editor.editorStyle')
    @endif
</head>
<body class="colorPanel">
<div class="row">
    <div class="colorPanel">
        <div class="contentDiv" id="publication{{ $publication->id }}" >
            <img class="publicationBanner img-responsive" src="{{ $publication->banner_image }}" style="margin:0em auto; max-width:100%;"/>
            {{ isset($tweakables['publication-header']) ? $tweakables['publication-header'] : '' }}
            {{-- Insert Article Summary Conditionally --}}
            @if( isset($tweakables['publication-headline-summary']) ? $tweakables['publication-headline-summary'] : $default_tweakables['publication-headline-summary'] == 1)
                <h3 class="headline-summary-header">Today's Headlines:</h3>
                @foreach($publication->articles as $article)
                    <a href="#articleTitle{{ $article->id }}">{{ strip_tags($article->title) }}</a><br/>
                @endforeach
            @endif
            @foreach($publication->articles as $article)
            <div class="article" id="article{{ $article->id }}">
                @include('snippet.article', array('contentEditable' => false, 'shareIcons' => false))
            </div>
            @endforeach
            {{ isset($tweakables['publication-footer']) ? $tweakables['publication-footer'] : $default_tweakables['publication-footer'] }}
        </div>
    </div>
</div>
</body>
</html>