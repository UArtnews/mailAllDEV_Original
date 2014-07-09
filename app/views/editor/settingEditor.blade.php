<div class="panel panel-default colorPanel">
    <div class="panel-heading" id="settingPanelHead">
        <ul class="nav nav-tabs">
            <li {{$subAction == 'appearanceTweakables' ? 'class="active"' : '' ;}}><a href="{{ URL::to("/edit/$instanceName/settings/appearanceTweakables") }}">Appearance Settings</a></li>
            <li {{$subAction == 'contentStructureTweakables' ? 'class="active"' : '' ;}}><a href="{{ URL::to("/edit/$instanceName/settings/contentStructureTweakables") }}">Content/Structure Options</a></li>
            <li {{$subAction == 'headerFooterTweakables' ? 'class="active"' : '' ;}}><a href="{{ URL::to("/edit/$instanceName/settings/headerFooterTweakables") }}">Header/Footer</a></li>

        </ul>
    </div>
    <div class="panel-body" id="settingPanelBody">
        <div class="col-md-5 col-sm-12" id="settingChooser">
            {{ Form::open(array('url' => URL::to('/save/'.$instance->id.'/settings'), 'method' => 'post')) }}
            @foreach($default_tweakables as $defName => $defVal)
                @if(in_array($defName,$$subAction))
                <!-- {{$defName}} Form Input -->
                <div class="row form-group" style="line-height:250%">
                    <div class="col-xs-5" style="text-align:right;">
                        {{ Form::label($defName,$default_tweakables_names[$defName]) }}
                    </div>


                    {{-- Color Pickers --}}
                    @if( $tweakables_types[$defName] == 'color' )
                    <div class="col-xs-7" style="padding-right:5px!important">
                        <div class="input-group colorPicker">
                            {{ Form::text($defName, isset($tweakables[$defName]) ? $tweakables[$defName] : $defVal , array('class' => 'form-control ','placeholder' => '')) }}
                            <span class="input-group-addon"><i></i></span>
                        </div>
                    </div>


                    {{-- CKEditor Regions --}}
                    {{-- These are self-contained editors --}}
                    @elseif( $tweakables_types[$defName] == 'textarea' )
                    <div class="col-xs-7" style="background-color:white;">
                        <div class="editable" id="{{ $defName }}">
                            {{ isset($tweakables[$defName]) ? $tweakables[$defName] : strlen($defVal) > 0 ? $defVal : '&nbsp'; }}
                        </div>
                    </div>


                    {{-- Boolean Radio Boxes --}}
                    @elseif( $tweakables_types[$defName] == 'bool' )
                    <div class="col-xs-7">
                        {{-- Non-Default Value --}}
                        @if(isset($tweakables[$defName]))
                            Yes&nbsp{{ Form::radio($defName, 1, $tweakables[$defName] == 1 ? true : false) }}
                            No&nbsp{{ Form::radio($defName, 0, $tweakables[$defName] == 0 ? true : false) }}
                        @else
                            Yes&nbsp{{ Form::radio($defName, 1, $defVal == 1 ? true : false) }}
                            No&nbsp{{ Form::radio($defName, 0, $defVal == 0 ? true : false) }}
                        @endif
                    </div>

                    {{-- Normal Textfields --}}
                    @else
                    <div class="col-xs-7">
                        {{ Form::text($defName, isset($tweakables[$defName]) ? $tweakables[$defName] : $defVal , array('class' => 'form-control ','placeholder' => '')) }}
                    </div>
                    @endif
                </div>
                @endif
            @endforeach
            <script>
                $(function(){
                    $('.colorPicker').colorpicker();
                });
            </script>

            @if($subAction != 'headerFooterTweakables')

            {{ Form::submit('Save Settings', array('class'=>'form-control btn btn-primary btn-xl')) }}

            @endif
        </div>
        <div class="col-md-6 col-sm-12 " id="settingsPreviewer">
                <!-- Now to iterate through the articles -->
                <div class="contentDiv">
                    <img class="publicationBanner img-responsive" src="{{ $tweakables['publication-banner-image'] or $default_tweakables['publication-banner-image'] }}"/>

                    {{ isset($tweakables['publication-repeated-items']) ? '<h3 class="repeated-items-heading">Today\'s News</h3>' : $default_tweakables['publication-repeated-items'] == 1 ? '<h3 class="repeated-items-heading">Today\'s News</h3>' : ''  }}

                    <h1 >
                        Heading H1 <br/>
                        (how article titles look)
                    </h1>
                    {{ isset($tweakables['publication-hr-titles']) ? '<hr/>' : $default_tweakables['publication-hr-titles'] == 1 ? '<hr/>' : '' ; }}
                    <h2 >Heading H2</h2>
                    <h3 >Heading H3</h3>
                    <h4 >Heading H4</h4>
                    <p >This is Paragraph text. </p>
                    <p>Ducimus eius suscipit minus veritatis dignissimos fugit et. Atque id laboriosam aut pariatur quaerat ex minima. In et est sint sed porro vero alias. Dolorum quo et earum. Culpa optio iusto et ducimus. Tenetur consequatur et nostrum ut et. Aut tempora aspernatur neque cumque officiis repellat dolorem tempore. Molestias eligendi molestiae libero quas dolor sed. Eum cupiditate voluptatem et qui molestias atque. Architecto aut eligendi illum vel. Omnis ut dolorem et perspiciatis labore saepe. Repellat perspiciatis deleniti mollitia ut quae minus. <a href="#">Hyperlinkitus </a>ut voluptas animi quis ipsam alias. Non sed enim neque asperiores.</p>
                    <p >Nisi et sunt illo quas. Ducimus eius suscipit minus veritatis dignissimos fugit et. Atque id laboriosam aut pariatur quaerat ex minima. In et est sint sed porro vero alias. Dolorum quo et earum. Culpa optio iusto et ducimus. Tenetur consequatur et nostrum ut et. Aut tempora aspernatur neque cumque officiis repellat dolorem tempore. Molestias eligendi molestiae libero quas dolor sed. Eum cupiditate voluptatem et qui molestias atque. Architecto aut eligendi illum vel. Omnis ut dolorem et perspiciatis labore saepe. Repellat perspiciatis deleniti mollitia ut quae minus. <a href="#">Hyperlinkitus </a>ut voluptas animi quis ipsam alias. Non sed enim neque asperiores.</p>
                    <p >Nisi et sunt illo quas. Ducimus eius suscipit minus veritatis dignissimos fugit et. Atque id laboriosam aut pariatur quaerat ex minima. In et est sint sed porro vero alias. Dolorum quo et earum. Culpa optio iusto et ducimus. Tenetur consequatur et nostrum ut et. Aut tempora aspernatur neque cumque officiis repellat dolorem tempore. Molestias eligendi molestiae libero quas dolor sed. Eum cupiditate voluptatem et qui molestias atque. Architecto aut eligendi illum vel. Omnis ut dolorem et perspiciatis labore saepe. Repellat perspiciatis deleniti mollitia ut quae minus. <a href="#">Hyperlinkitus </a>ut voluptas animi quis ipsam alias. Non sed enim neque asperiores.</p>
                    {{ isset($tweakables['publication-hr-articles']) ? '<hr/>' : $default_tweakables['publication-hr-articles'] == 1 ? '<hr/>' : '' ; }}
                    <h1 >
                        Heading H1 <br/>
                    </h1>
                    {{ isset($tweakables['publication-hr-titles']) ? '<hr/>' : $default_tweakables['publication-hr-titles'] == 1 ? '<hr/>' : '' ; }}
                    <p >This is Paragraph text. </p>
                    <p>Ducimus eius suscipit minus veritatis dignissimos fugit et. Atque id laboriosam aut pariatur quaerat ex minima. In et est sint sed porro vero alias. Dolorum quo et earum. Culpa optio iusto et ducimus. Tenetur consequatur et nostrum ut et. Aut tempora aspernatur neque cumque officiis repellat dolorem tempore. Molestias eligendi molestiae libero quas dolor sed. Eum cupiditate voluptatem et qui molestias atque. Architecto aut eligendi illum vel. Omnis ut dolorem et perspiciatis labore saepe. Repellat perspiciatis deleniti mollitia ut quae minus. <a href="#">Hyperlinkitus </a>ut voluptas animi quis ipsam alias. Non sed enim neque asperiores.</p>
                    <p >Nisi et sunt illo quas. Ducimus eius suscipit minus veritatis dignissimos fugit et. Atque id laboriosam aut pariatur quaerat ex minima. In et est sint sed porro vero alias. Dolorum quo et earum. Culpa optio iusto et ducimus. Tenetur consequatur et nostrum ut et. Aut tempora aspernatur neque cumque officiis repellat dolorem tempore. Molestias eligendi molestiae libero quas dolor sed. Eum cupiditate voluptatem et qui molestias atque. Architecto aut eligendi illum vel. Omnis ut dolorem et perspiciatis labore saepe. Repellat perspiciatis deleniti mollitia ut quae minus. <a href="#">Hyperlinkitus </a>ut voluptas animi quis ipsam alias. Non sed enim neque asperiores.</p>
                    <p >Nisi et sunt illo quas. Ducimus eius suscipit minus veritatis dignissimos fugit et. Atque id laboriosam aut pariatur quaerat ex minima. In et est sint sed porro vero alias. Dolorum quo et earum. Culpa optio iusto et ducimus. Tenetur consequatur et nostrum ut et. Aut tempora aspernatur neque cumque officiis repellat dolorem tempore. Molestias eligendi molestiae libero quas dolor sed. Eum cupiditate voluptatem et qui molestias atque. Architecto aut eligendi illum vel. Omnis ut dolorem et perspiciatis labore saepe. Repellat perspiciatis deleniti mollitia ut quae minus. <a href="#">Hyperlinkitus </a>ut voluptas animi quis ipsam alias. Non sed enim neque asperiores.</p>
                    {{ isset($tweakables['publication-hr-articles']) ? '<hr/>' : $default_tweakables['publication-hr-articles'] == 1 ? '<hr/>' : '' ; }}

                    {{ isset($tweakables['publication-repeated-items']) ? '<h3 class="repeated-items-heading">Repeated News Items Follow</h3>' : $default_tweakables['publication-repeated-items'] == 1 ? '<h3 class="repeated-items-heading">Repeated News Items Follow</h3>' : '' ; }}

                    <h1 >
                        Heading H1 <br/>
                    </h1>
                    {{ isset($tweakables['publication-hr-titles']) ? '<hr/>' : $default_tweakables['publication-hr-titles'] == 1 ? '<hr/>' : '' ; }}
                    <p >This is Paragraph text. </p>
                    <p>Ducimus eius suscipit minus veritatis dignissimos fugit et. Atque id laboriosam aut pariatur quaerat ex minima. In et est sint sed porro vero alias. Dolorum quo et earum. Culpa optio iusto et ducimus. Tenetur consequatur et nostrum ut et. Aut tempora aspernatur neque cumque officiis repellat dolorem tempore. Molestias eligendi molestiae libero quas dolor sed. Eum cupiditate voluptatem et qui molestias atque. Architecto aut eligendi illum vel. Omnis ut dolorem et perspiciatis labore saepe. Repellat perspiciatis deleniti mollitia ut quae minus. <a href="#">Hyperlinkitus </a>ut voluptas animi quis ipsam alias. Non sed enim neque asperiores.</p>
                    <p >Nisi et sunt illo quas. Ducimus eius suscipit minus veritatis dignissimos fugit et. Atque id laboriosam aut pariatur quaerat ex minima. In et est sint sed porro vero alias. Dolorum quo et earum. Culpa optio iusto et ducimus. Tenetur consequatur et nostrum ut et. Aut tempora aspernatur neque cumque officiis repellat dolorem tempore. Molestias eligendi molestiae libero quas dolor sed. Eum cupiditate voluptatem et qui molestias atque. Architecto aut eligendi illum vel. Omnis ut dolorem et perspiciatis labore saepe. Repellat perspiciatis deleniti mollitia ut quae minus. <a href="#">Hyperlinkitus </a>ut voluptas animi quis ipsam alias. Non sed enim neque asperiores.</p>
                    <p >Nisi et sunt illo quas. Ducimus eius suscipit minus veritatis dignissimos fugit et. Atque id laboriosam aut pariatur quaerat ex minima. In et est sint sed porro vero alias. Dolorum quo et earum. Culpa optio iusto et ducimus. Tenetur consequatur et nostrum ut et. Aut tempora aspernatur neque cumque officiis repellat dolorem tempore. Molestias eligendi molestiae libero quas dolor sed. Eum cupiditate voluptatem et qui molestias atque. Architecto aut eligendi illum vel. Omnis ut dolorem et perspiciatis labore saepe. Repellat perspiciatis deleniti mollitia ut quae minus. <a href="#">Hyperlinkitus </a>ut voluptas animi quis ipsam alias. Non sed enim neque asperiores.</p>
                </div>
        </div>

    </div>
    <div class="panel-footer" id="settingPanelFoot">
    </div>
</div>