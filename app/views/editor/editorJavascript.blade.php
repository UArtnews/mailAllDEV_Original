<script>
    //EditorContents variable for storing/retrieving original copies of articles
    var EditorData = (function(){
        var editorContents = {};

        return {
            contents: editorContents
        };
    })();

    function saveNewArticle(){
        //Get title and body
        var title = $('#newArticleTitle').html();
        var body = $('#newArticleContent').html();

        //If title or body is empty or boilerplate, don't bother saving the article, alert the user
        if(title == '[Click here to begin editing Title]' || title.length == 0){
            alert('Cannot save empty or boilerplate title');
            return;
        }
        if(body == '[Click here to begin editing Body]' || body.length == 0){
            alert('Cannot save empty or boilerplate content');
            return;
        }

        //Save that article!
        $.ajax({
            url:'{{ URL::to('resource/article') }}',
            type: 'POST',
            data: {
                'instance_id': "{{$instanceId}}",
                'title': title,
                'content': body
        }
        }).done(function(data){
            location.reload();
        });


    }

    function deleteArticle(id)
    {
        if(confirm('This article will no longer be available for future publications, are you sure you wish to delete?')){
            //Delete that article!

            $.ajax({
                url:'{{ URL::to('resource/article') }}/'+id,
                type: 'DELETE',
                data: {
                    'instance_id': "{{$instanceId}}",
                    'id': id,
            }
            }).done(function(data){
                location = '{{ URL::to('edit/'.$instance->name.'/articles') }}'
            });
        }
    }

    function revertEdits(id)
    {
        /////////////////////////////////////////////////
        //  Revert Edits should revert Title AND Body  //
        /////////////////////////////////////////////////
        $('#articleTitle'+id).html(EditorData.contents[id].title);
        $('#articleContent'+id).html(EditorData.contents[id].content);
        getArticleState(id);

    }

    function saveEdits(id)
    {
        /////////////////////////////////////////////
        //  Save Edits should save Title AND Body  //
        /////////////////////////////////////////////

        //Save to EditorData Object
        EditorData.contents[id] = {};
        EditorData.contents[id].title = $('#articleTitle'+id).html().replace('<div>','').replace('</div>','');
        EditorData.contents[id].content = $('#articleContent'+id).html().replace('<div>','').replace('</div>','');
        EditorData.contents[id].state = 'No Unsaved Changes';
        EditorData.contents[id].color = 'Green';
        getArticleState(id);

        //////////////////
        //  AJAX Stuff  //
        //////////////////
        $.ajax({
            url:'{{URL::to('resource/article');}}/'+id,
            type: 'PUT',
            data: {
                'id': id,
                'instance_id': "{{$instanceId}}",
                'title': $('#articleTitle'+id).html(),
                'content': $('#articleContent'+id).html()
                }
        }).done(function(data){
            console.log(data);
        });

    }

    ////////////////////////////////////////////////////
    //  Initialize everything                        //
    //  1. Mouse-Over-Hitboxes for save indicators   //
    //  2. ckeditors, edit states and click handler  //
    ////////////////////////////////////////////////////
    $(document).ready(function(){

        //Prepare mouseover on side-indicators
        $('.side-indicator-hitbox').hover(function(){
            //Hover On
            if($(this).parent().css('opacity') > 0)
                $(this).parent().css('animation','slideout 1s forwards');
        }, function(){
            //Hover Off

            //Set elem to this so it survives scope change in setTimeout closure
            elem = this;
            if($(this).parent().css('opacity') > 0)
                setTimeout(function(){
                    $(elem).parent().css('animation','slidein 1s forwards');
                },1000);
        });

        //////////////////////////////////////////
        //  NEW ARTICLE EDITABLE CLICK HANDLER  //
        //////////////////////////////////////////
        $('.newEditable').click(function(){
            $(this).attr('contenteditable', true);
            $(this).ckeditor({
                "extraPlugins": "imagebrowser,sourcedialog",
                "imageBrowser_listUrl": "{{ URL::to('json/'.$instanceName.'/images') }}"
            });
        });

        ///////////////////////////////////////////////////////////////////
        //  EXISTING ARTICLE EDITABLE CLICK HANDLER AND SAVE INDICATORS  //
        ///////////////////////////////////////////////////////////////////
        $('.editable').click(function() {
            editorClick(this);
        });
    });

    function editorClick(elem){
        //Get idNum
        if(typeof elem.id == 'undefined'){
            console.log('undefined');
            var idNum = $(elem).id.replace('articleContent','');
        }else{
            var idNum = elem.id.replace('articleContent','');
        }
        idNum = idNum.replace('articleTitle','');

        initEditor(idNum, $(elem));
    }

    function initEditor(idNum, thisSelector){
        console.log('init');
        if(typeof thisSelector.parent().find('.articleTitle').position() == 'undefined'){
            return;
        }

        //Save the content as it currently is into the EditorData object
        if(typeof EditorData.contents[idNum] == 'undefined'){
            EditorData.contents[idNum] = {};
            EditorData.contents[idNum].title = $('#articleTitle'+idNum).html();
            EditorData.contents[idNum].content = $('#articleContent'+idNum).html();
            getArticleState(idNum);
        }

        //Remove all other editorSaveRevert divs
        $('.editorSaveRevert').remove();

        if('{{ $action }}' == 'publications' || '{{ $action }}' == 'publication' || '{{ $action }}' == ''){
            //Place save/revert controls off to side of article, NOT TITLE
            $controls = '<div id="'+'article'+idNum+'save" class="editorSaveRevert" style="z-index:50;">' +
                '<button type="button" class="btn btn-success btn-block" onclick="saveEdits(\''+idNum+'\');"><span class="glyphicon glyphicon-floppy-disk"></span>&nbsp;Save</button>' +
                '<button type="button" class="btn btn-warning btn-block" onclick="revertEdits(\''+idNum+'\');"><span class="glyphicon glyphicon-remove"></span>&nbsp;Revert</button>' +
                '<button type="button" class="btn btn-primary btn-block" onclick="moveArticle(\''+idNum+'\',\'up\',this);"><span class="glyphicon glyphicon-hand-up"></span>&nbsp;Move</button>' +
                '<button type="button" class="btn btn-primary btn-block" onclick="moveArticle(\''+idNum+'\',\'down\',this);"><span class="glyphicon glyphicon-hand-down"></span>&nbsp;Move</button>' +
                '<button type="button" class="btn btn-danger btn-block" onclick="removeArticle('+idNum+',this);"><span class="glyphicon glyphicon-trash"></span>&nbsp;Remove</button>' +
                '<button type="button" class="addToCartBtn btn btn-success btn-block" onclick="addArticleToCart(\''+idNum+'\');">Add to Cart</button>' +
                '<button type="button" class="hideRepeatedBtn btn btn-warning btn-block" onclick="hideRepeated('+idNum+',{{ $publication->id or ''}});">Repeat</button>' +
                '<div class="row" id="articleIndicator" style="text-align:center;color:'+EditorData.contents[idNum].color+';">'+
                '</div>' +
                '</div>';
        }else if('{{ $action }}' == 'newPublication'){
            //Place save/revert controls off to side of article, NOT TITLE
            $controls = '<div id="'+'article'+idNum+'save" class="editorSaveRevert" ' +
                'style="z-index:50;min-height:8.5em;">' +
                '<button type="button" class="btn btn-success btn-block" onclick="saveEdits(\''+idNum+'\');"><span class="glyphicon glyphicon-floppy-disk"></span>&nbsp;Save</button>' +
                '<button type="button" class="btn btn-warning btn-block" onclick="revertEdits(\''+idNum+'\');"><span class="glyphicon glyphicon-remove"></span>&nbsp;Revert</button>' +
                '<button type="button" class="btn btn-primary btn-block" onclick="moveArticle(\''+idNum+'\',\'up\',this);"><span class="glyphicon glyphicon-hand-up"></span>&nbsp;Move</button>' +
                '<button type="button" class="btn btn-primary btn-block" onclick="moveArticle(\''+idNum+'\',\'down\',this);"><span class="glyphicon glyphicon-hand-down"></span>&nbsp;Move</button>' +
                '<button type="button" class="btn btn-danger btn-block" onclick="removeNewArticle('+idNum+');"><span class="glyphicon glyphicon-trash"></span>&nbsp;Remove</button>' +
                '<div class="row" id="articleIndicator" style="text-align:center;color:'+EditorData.contents[idNum].color+';">'+
                '</div>' +
                '</div>';
        }else{
            //Place save/revert controls off to side of article, NOT TITLE
            $controls = '<div id="'+'article'+idNum+'save" class="editorSaveRevert" ' +
                'style="z-index:50;min-height:8.5em;">' +
                '<button type="button" class="btn btn-success btn-block" onclick="saveEdits(\''+idNum+'\');"><span class="glyphicon glyphicon-floppy-disk"></span>&nbsp;Save</button>' +
                '<button type="button" class="btn btn-warning btn-block" onclick="revertEdits(\''+idNum+'\');"><span class="glyphicon glyphicon-remove"></span>&nbsp;Revert</button>' +
                '<div class="row" id="articleIndicator" style="text-align:center;color:'+EditorData.contents[idNum].color+';">'+
                '</div>' +
                '</div>';
        }

        //Find the parent, then find the div with class articleContent and slap the controls there, remove any instance first.
        $('#article'+idNum+'save').remove()
        thisSelector.parent().find('.articleContent').after($controls);

        //Adjust positioning of save/revert controls
        thisSelector.parent().find('.editorSaveRevert').css('top',thisSelector.parent().find('.articleTitle').position().top+'px');
        thisSelector.parent().find('.editorSaveRevert').css('left',thisSelector.parent().find('.articleTitle').parent().parent().outerWidth()+'px');

        //Adjust Height
        thisSelector.parent().find('.editorSaveRevert').css('height',thisSelector.parent().height()+'px');

        //Init editor since it's not fired up!
        thisSelector.attr('contenteditable', true);

        thisEditor = thisSelector.ckeditor({
            "extraPlugins": "imagebrowser,sourcedialog",
            "imageBrowser_listUrl": "{{ URL::to('json/'.$instanceName.'/images') }}"
        });

        //Setup Indicator stuff
        thisSelector.parent().find('.articleTitle').unbind('keyup').bind('keyup', function(){
            //thisSelector.parent().find('.articleTitle').html(thisSelector.parent().find('.articleTitle').html().replace('<div>','').replace('</div>',''));
            getArticleState(idNum);
        });

        thisSelector.parent().find('.articleContent').unbind('keyup').bind('keyup', function(){
            //thisSelector.parent().find('.articleContent').html(thisSelector.parent().find('.articleContent').html().replace('<div>','').replace('</div>',''));
            getArticleState(idNum);
        });

    }

    //////////////////////////////////////////////////////
    //  Determine if changes have been made to article  //
    //  fire up appropriate indicator                   //
    //////////////////////////////////////////////////////
    function getArticleState(idNum){
        console.log('Checking '+idNum+'\'s state!');
        //Set Some EditorData for this article so we can alert the user to save or not
        if(EditorData.contents[idNum].title != $('#articleTitle'+idNum).html() || EditorData.contents[idNum].content != $('#articleContent'+idNum).html()){
            EditorData.contents[idNum].state = 'Unsaved Changes Pending';
            EditorData.contents[idNum].color = 'Red';

            if($('#articleIndicator'+idNum).css('opacity') == 0)
                $('#articleIndicator'+idNum).css('animation', 'fadein 1s forwards');

            $('#articleIndicator'+idNum).css('opacity','0.5');
            $('#articleIndicator'+idNum).css('background-color',EditorData.contents[idNum].color);
        }else{
            EditorData.contents[idNum].state = 'No Unsaved Changes';
            EditorData.contents[idNum].color = 'Green';

            if($('#articleIndicator'+idNum).css('opacity') > 0){
                $('#articleIndicator'+idNum).css('opacity','0.5');
                $('#articleIndicator'+idNum).css('background-color',EditorData.contents[idNum].color);
                $('#articleIndicator'+idNum).css('animation', 'fadeout 1s forwards');
            }
        }

        $('#articleIndicator').css('color',EditorData.contents[idNum].color);

        $('#articleIndicator'+idNum).css('height',$('#articleIndicator'+idNum).parent().height()+'px');
        $('#articleIndicator'+idNum).css('top',$('#articleIndicator'+idNum).parent().position().top+'px');
    }

    function removeArticle(idNum, thisSelector){
        thisObject = $(thisSelector);

        //If this publication isn't saved yet, bail
        if( '{{ $action }}' == 'newPublication'){
            return;
        }

        thisObject.closest('.article').remove();

        publication_id = $('.contentDiv').attr('id').replace('publication','');

        savePublicationOrder(publication_id);
    }

    //////////////////////////////////////////////////////
    //  Move an article Up or Down                      //
    //  Ajax off the results (save the publication)     //
    //////////////////////////////////////////////////////

    function moveArticle(idNum, direction, thisSelector){
        thisObject = $(thisSelector);
        //Target and insert depending on direction
        if(direction == 'up'){
            thisObject.parent().parent().prev().before(thisObject.parent().parent());
        }else{
            thisObject.parent().parent().next().after(thisObject.parent().parent());
        }

        //Fire up editors again
        initEditor(idNum,thisObject.closest('.articleTitle').first());
        initEditor(idNum,thisObject.closest('.articleContent').first());

        //Save new order
        //Get Publication_id

        publication_id = thisObject.closest('.contentDiv').attr('id').replace('publication','');

        savePublicationOrder(publication_id);

    }

    function saveImage(elem){
        thisSelector = $(elem);

        idNum = thisSelector.closest('.imageDetails').attr('id').replace('image','');

        $.ajax({
            url: '{{ URL::to('resource/image') }}/'+idNum,
            type: 'PUT',
            data: {
                'title': $('#title'+idNum).val(),
                'alt_text': $('#alttext'+idNum).val(),
                'filename': $('#filename'+idNum).val()
            }
        }).done(function(data){
            console.log(data);
            if(data['success']){
                $('#msgSuccess'+idNum).show();
                $('#msgSuccess'+idNum).text(data['success']);
                setTimeout(function(){
                    $('#msgSuccess'+idNum).hide();
                },5000);
            }else if(data['error']){
                $('#msgError'+idNum).show();
                $('#msgError'+idNum).text(data['error']);
                setTimeout(function(){
                    $('#msgError'+idNum).hide();
                },5000);
            }
        });
    }

    function setUpload(idNum){
        $('#modalForm').attr('action','{{ URL::to('resource/image') }}/'+idNum);
    }

    function deleteImage(elem){
        if(confirm('Are you sure you wish to delete this image?')){

            idNum = $(elem).closest('.imageDetails').attr('id').replace('image','');

            $.ajax({
                url: '{{ URL::to('resource/image') }}/'+idNum,
                type: 'DELETE'
            }).done(function(data){
                console.log(data);
                if(data['success']){
                    $(elem).closest('.imageDetailsRow').remove();

                }
            });
        }
    }

    function newPublicationFromCal(elem){
        thisSelector = $(elem);

        day = thisSelector.prev('.btn-disabled').text();

        monthYearStr = $('th[colspan="5"]', '#publicationChooser').text().split(' ');

        monthStr = monthYearStr[0];
        year = monthYearStr[1];

        //Make a string php's strtotime() can handle
        dateStr = day+' '+monthStr+' '+year;

        console.log(dateStr);

        window.location = '{{ URL::to('edit/'.$instance->name.'/newPublication?publish_date=') }}'+dateStr;


    }

    function addArticleToCart(idNum){
        $.ajax({
            url: '{{ URL::to('/cart/'.$instance->name.'/add/') }}',
            type: 'POST',
            data: {
                article_id: idNum
            }
        }).done(function(data){
            console.log(data);
            if(data['success']){
                content = '';
                count = 0;

                $.each(data.cart, function(article_id, article){
                    content += articleListItem(article_id, article);
                    count++;
                });

                $('.cartList').html(content);
                $('.cartCountBadge').text(count);

                //Toggle indicator in articles
                if( '{{ $action }}' == 'articles'){
                    $('.addToCartButton'+idNum).attr('onclick', 'removeArticleFromCart('+idNum+');');
                    $('.badge', '.addToCartButton'+idNum).removeClass('alert-success').addClass('alert-danger').text('Remove from Cart');
                }
            }else if(data['error']){
                alert(data['error']);
            }
        })
    }

    function removeArticleFromCart(idNum){
        $.ajax({
            url: '{{ URL::to('/cart/'.$instance->name.'/remove/') }}',
            type: 'POST',
            data: {
                article_id: idNum
            }
        }).done(function(data){
            console.log(data);
            if(data['success']){
                content = '';
                count = 0;

                $.each(data.cart, function(article_id, article){
                    content += articleListItem(article_id, article);
                    count++;
                });

                if(count == 0){
                    content = '<li id="emptyCartItem" class="list-group-item list-group-item-warning">There are no articles in your cart!</li>';
                }

                $('.cartList').html(content);
                $('.cartCountBadge').text(count);

                //Toggle indicator in articles
                if( '{{ $action }}' == 'articles'){
                    $('.addToCartButton'+idNum).attr('onclick', 'addArticleFromCart('+idNum+');');
                    $('.badge', '.addToCartButton'+idNum).removeClass('alert-danger').addClass('alert-success').text('Add Article to Cart');
                }
            }else if(data['error']){
                alert(data['error']);
            }
        })
    }

    function clearArticleCart(){
        if(confirm('Are you sure you wish to remove all items from your cart?')){
            $.ajax({
                url: '{{ URL::to('/cart/'.$instance->name.'/clear/') }}',
                type: 'POST'
            }).done(function(data){
                console.log(data);
                if(data['success']){
                    content = '<li id="emptyCartItem" class="list-group-item list-group-item-warning">There are no articles in your cart!</li>';
                    $('.cartList').html(content);
                    $('.cartCountBadge').text(0);
                }else if(data['error']){
                    alert(data['error']);
                }
            });
        }
    }

    function articleListItem(article_id, title){
        return '<li class="list-group-item cartItem">'+
            '<a href="{{ URL::to('edit/'.$instance->name.'/article/') }}/'+article_id+'">'+title+'</a>&nbsp;&nbsp;' +
            '<button class="btn btn-xs btn-danger" onclick="removeArticleFromCart('+article_id+')">Remove from cart</button>' +
            '</li>';
    }

    function addArticleToNewPublication(article_id){
        if($('#article'+article_id).length > 0){
            return;
        }
        $.ajax({
            url: '{{ URL::to('/editable/article/') }}/'+article_id,
            type: 'post'
        }).done(function(data){
            if(data.indexOf('repeatedArticleContent') == -1){
                $('.article-container').append(data);
            }else{
                $('.repeat-container').append(data);
            }

            initEditor(article_id, $('#articleTitle'+article_id));
            initEditor(article_id, $('#articleContent'+article_id));

            $('#articleTitle'+article_id).click(function(){
                editorClick(this);
            });
            $('#articleContent'+article_id).click(function(){
                editorClick(this);
            });

            $('#addCartArticle'+article_id).append('<span class="alert-success">&nbsp;&nbsp;Added To Publication');

            $('.editorSaveRevert').remove();
        });
    }

    function addArticleToExistingPublication(article_id, publication_id, doSave){
        if($('#publication'+publication_id+' #article'+article_id).length > 0){
            return;
        }

        $.ajax({
            url: '{{ URL::to('/editable/article/') }}/'+article_id+'/'+publication_id+'?',
            type: 'post'
        }).done(function(data){
            console.log('data.indexOf == '+data.indexOf('repeatedArticleContent'));

            if(data.indexOf('repeatedArticleContent') == -1){
                $('.article-container').append(data);
            }else{
                $('.repeat-container').append(data);
            }

            initEditor(article_id, $('#publication'+publication_id+' #articleTitle'+article_id));
            initEditor(article_id, $('#publication'+publication_id+' #articleContent'+article_id));

            $('#publication'+publication_id+' #articleTitle'+article_id).click(function(){
                editorClick(this);
            });
            $('#publication'+publication_id+' #articleContent'+article_id).click(function(){
                editorClick(this);
            });

            $('#publication'+publication_id+' #addCartArticle'+article_id).append('<span class="alert-success">&nbsp;&nbsp;Added To Publication');

            $('.editorSaveRevert').remove();
            if(doSave)
                savePublicationOrder(publication_id);
        });
    }

    function addArticleCartToNewPublication(){
        $('.addCartItem').each(function(index, elem){
            addArticleToNewPublication($(elem).attr('id').replace('addCartArticle',''));
        });
    }

    function addArticleCartToExistingPublication(publication_id){
        $('#addFromCartModal'+publication_id+' .addCartItem').each(function(index, elem){
            addArticleToExistingPublication($(elem).attr('id').replace('addCartArticle',''), publication_id, false);
        });

        //TODO: Fix this so it's not a dumb timeout
        setTimeout(function(){
            savePublicationOrder(publication_id);
        },2000);
    }

    function removeNewArticle(idNum){
        $('#article'+idNum).remove();
    }

    function saveNewPublication(){
        var articles = new Array();

        $('.article').each(function(index, elem){
            articles.push($(elem).attr('id').replace('article',''));
        });

        $.ajax({
            url: '{{ URL::to('/resource/publication') }}',
            type: 'POST',
            data: {
                'instance_id': '{{ $instance->id }}',
                'publish_date': $('#publish_date').val(),
                'banner_image': $('#banner_image').val(),
                'type': $('input[name=type]:checked').val(),
                'article_order': JSON.stringify(articles)
            }
        }).done(function(data){
            if(data['success']){
                window.location = '{{ URL::to('/edit/'.$instance->name.'/publication') }}/'+data['publication_id'];
            }else{
                alert('Something went wrong!');
            }
        });
    }

    function promoteSubmission(submission_id)
    {
        //Disable the button so double clicks don't happen.
        onclick = $('#promote'+submission_id).attr('onclick');
        $('#promote'+submission_id).attr('onclick','');

        //Construct article with or without contact info
        $.ajax({
            url: '{{ URL::to('promote/'.$instance->name) }}/'+submission_id,
            type: 'POST'
        }).done(function(data){
            console.log(data);
            if(data['success']){
                location = '{{ URL::to('edit/'.$instance->name.'/article') }}/'+data['article_id'];
            }else{
                alert('Something went wrong! Try again in a moment please.');
                $('#promote'+submission_id).attr('onclick',onclick);
            }
        });

    }

    function deleteSubmission(submission_id)
    {
        if(confirm('Are you sure you wish to delete this submission?')){
            //disable button
            onclick = $('#delete'+submission_id).attr('onclick');
            $('#delete'+submission_id).attr('onclick','');

            $.ajax({
                url: '{{ URL::to('resource/submission') }}/'+submission_id,
                type: 'DELETE'
            }).done(function(data){
                console.log(data);
                if(data['success']){
                    location = '{{ URL::to('edit/'.$instance->name.'/submissions') }}';
                }else{
                    alert('Something went wrong! Try again in a moment please.');
                    $('#promote'+submission_id).attr('onclick',onclick);
                }
            });

        }
    }

    function savePublicationOrder(publication_id){
        //If this publication isn't saved yet, bail
        if( '{{ $action }}' == 'newPublication'){
            return;
        }

        var articleArray = new Array();
        //Iterate over contents
        $('#publication'+publication_id).find('.article').each(function(index){
            var likeNew = 'N';
            if($('.repeatedArticleContent', this).is(':hidden')){
                likeNew = 'Y';
            }

            articleArray[index] = [$(this).attr('id').replace('article',''), likeNew];
        });

        //////////////////
        //  AJAX Stuff  //
        //////////////////

        $.ajax({
            url:'{{URL::to('resource/publication/updateOrder');}}/'+publication_id,
            type: 'POST',
            data: {
                'article_order': JSON.stringify(articleArray)
            }
        }).done(function(data){
            console.log(data);
        });

    }

    function publishStatus(publication_id){

        $.ajax({
            url: '{{ URL::to('resource/publication/') }}/'+publication_id,
            type: 'PUT',
            data: {
                'published': 'Y'
            }
        }).done(function(data){
            if(data['success']){
                location = '{{ URL::to('edit/'.$instance->name.'/publication') }}/'+publication_id;
            }
        });
    }

    function unpublishStatus(publication_id){

        $.ajax({
            url: '{{ URL::to('resource/publication/') }}/'+publication_id,
            type: 'PUT',
            data: {
                'published': 'N'
            }
        }).done(function(data){
            if(data['success']){
                location = '{{ URL::to('edit/'.$instance->name.'/publication') }}/'+publication_id;
            }
        });
    }

    function unhideRepeated(article_id, publication_id){
        //Move article
        $('#article'+article_id).appendTo('.article-container');

        //Unhide content
        $('.repeatedArticleContent', '#article'+article_id).hide();
        $('.articleContent', '#article'+article_id).show();

        //Update the model
        savePublicationOrder(publication_id);
    }

    function hideRepeated(article_id, publication_id){
        if(!$('#article'+article_id).parents('div').hasClass('repeat-container')){
            if($('#article'+article_id).find('.repeatedArticleContent').size() > 0){
                //Move article
                $('#article'+article_id).appendTo('.repeat-container');

                //Hide content
                $('.repeatedArticleContent', '#article'+article_id).show();
                $('.articleContent', '#article'+article_id).hide();

                //Update the model
                savePublicationOrder(publication_id);
            }else{
                alert('This article is not a repeat!');
            }
        }
    }

</script>
