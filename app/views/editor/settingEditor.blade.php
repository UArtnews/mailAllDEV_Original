<div class="panel panel-default colorPanel">
    <div class="panel-heading" id="settingPanelHead">
        <ul class="nav nav-tabs">
            <li {{$subAction == 'appearanceTweakables' ? 'class="active"' : '' ;}}><a href="{{ URL::to("/edit/$instanceName/settings/appearanceTweakables") }}">Appearance Settings</a></li>
            <li {{$subAction == 'contentStructureTweakables' ? 'class="active"' : '' ;}}><a href="{{ URL::to("/edit/$instanceName/settings/contentStructureTweakables") }}">Content/Structure Options</a></li>
            <li {{$subAction == 'headerFooterTweakables' ? 'class="active"' : '' ;}}><a href="{{ URL::to("/edit/$instanceName/settings/headerFooterTweakables") }}">Header/Footer</a></li>
            <li {{$subAction == 'workflowTweakables' ? 'class="active"' : '' ;}}><a href="{{ URL::to("/edit/$instanceName/settings/workflowTweakables") }}">Workflow</a></li>
        </ul>
    </div>
    <div class="panel-body" id="settingPanelBody">
        <div class="col-md-5 col-sm-12" id="settingChooser">
            {{ Form::open(array('url' => URL::to('/save/'.$instance->id.'/settings'), 'method' => 'post')) }}
            @foreach($default_tweakables as $defName => $defVal)
                @if(in_array($defName, $$subAction))
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
                    <div class="col-xs-7" >
                        <div class="editableSetting" id="{{ $defName }}" contenteditable="true" style="background-color:white;">
                            {{ isset($tweakables[$defName]) ? $tweakables[$defName] : ( strlen($defVal) > 0 ? $defVal : '&nbsp' ) }}
                        </div>
                        <button type="button" class="btn btn-primary btn-sm" onclick="saveSetting('{{ $defName }}')">Save {{ $default_tweakables_names[$defName] }}</button>

                    </div>
                    <script>
                        $(document).ready(function(){
                            $('.editableSetting').ckeditor({
                                "extraPlugins": "imagebrowser,sourcedialog",
                                "imageBrowser_listUrl": "{{ URL::to('json/'.$instanceName.'/images') }}"
                            });
                        });
                        function saveSetting(defName){
                            data = {};
                            data[defName] = CKEDITOR.instances[defName].getData();
                            $.ajax({
                                url: '{{ URL::to('save/'.$instance->id.'/settings') }}',
                                type: 'post',
                                data: data
                            }).done(function(data){
                                location.reload()
                            });
                        }
                    </script>


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
                    @elseif( $tweakables_types[$defName] == 'font' )
                    <div class="col-xs-7">
                        {{-- Dropdowns for fonts, weee!  --}}
                        <select class="form-control" name="{{ $defName }}">
                            @if(isset($tweakables[$defName]))
                            <option value='{{ $tweakables[$defName] }}' style='{{ $tweakables[$defName] }}' selected="true">{{ $tweakables[$defName] }}</option>
                            @endif
                            <option value='Arial, Helvetica, sans-serif' style='font-family:Arial, Helvetica, sans-serif!important;'>Arial, Helvetica, sans-serif</option>
                            <option value='"Arial Black", Gadget, sans-serif' style='font-family:"Arial Black", Gadget, sans-serif!important;'>"Arial Black", Gadget, sans-serif</option>
                            <option value='"Comic Sans MS", cursive, sans-serif' style='font-family:"Comic Sans MS", cursive, sans-serif!important;'>"Comic Sans MS", cursive, sans-serif</option>
                            <option value='Impact, Charcoal, sans-serif' style='font-family:Impact, Charcoal, sans-serif!important;'>Impact, Charcoal, sans-serif</option>
                            <option value='"Lucida Sans Unicode", "Lucida Grande", sans-serif' style='font-family:"Lucida Sans Unicode", "Lucida Grande", sans-serif!important;'>"Lucida Sans Unicode", "Lucida Grande", sans-serif</option>
                            <option value='Tahoma, Geneva, sans-serif' style='font-family:Tahoma, Geneva, sans-serif!important;'>Tahoma, Geneva, sans-serif</option>
                            <option value='"Trebuchet MS", Helvetica, sans-serif' style='font-family:"Trebuchet MS", Helvetica, sans-serif!important;'>"Trebuchet MS", Helvetica, sans-serif</option>
                            <option value='Verdana, Geneva, sans-serif' style='font-family:Verdana, Geneva, sans-serif!important;'>Verdana, Geneva, sans-serif</option>
                            <option value='Georgia, serif' style='font-family:Georgia, serif!important;'>Georgia, serif</option>
                            <option value='"Palatino Linotype", "Book Antiqua", Palatino, serif' style='font-family:"Palatino Linotype", "Book Antiqua", Palatino, serif!important;'>"Palatino Linotype", "Book Antiqua", Palatino, serif</option>
                            <option value='"Times New Roman", Times, serif' style='font-family:"Times New Roman", Times, serif!important;'>"Times New Roman", Times, serif</option>
                            <option value='"Courier New", Courier, monospace' style='font-family:"Courier New", Courier, monospace!important;'>"Courier New", Courier, monospace</option>
                            <option value='"Lucida Console", Monaco, monospace' style='font-family:"Lucida Console", Monaco, monospace!important;'>"Lucida Console", Monaco, monospace</option>
                        </select>
                    </div>
                    @elseif( $tweakables_types[$defName] == 'weight')
                    <div class="col-xs-7">
                        {{-- Dropdowns for weight --}}
                        <select class="form-control" name="{{ $defName }}">
                            @if(isset($tweakables[$defName]))
                            <option value="{{ $tweakables[$defName] }}">{{ $tweakables[$defName] }}</option>
                            @endif
                            <option value="normal">normal</option>
                            <option value="bold">bold</option>
                            <option value="lighter">lighter</option>
                            <option value="bolder">bolder</option>
                            <option value="100">100</option>
                            <option value="200">200</option>
                            <option value="300">300</option>
                            <option value="400">400</option>
                            <option value="500">500</option>
                            <option value="600">600</option>
                            <option value="700">700</option>
                            <option value="800">800</option>
                            <option value="900">900</option>
                        </select>
                    </div>
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