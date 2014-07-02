<script>
    //EditorContents variable for storing/retrieving original copies of articles
    var EditorData = (function(){
        var editorContents = {};

        return {
            contents: editorContents,
        };
    })();

    function revertEdits(id)
    {
        /////////////////////////////////////////////////
        //  Revert Edits should revert Title AND Body  //
        /////////////////////////////////////////////////

        $('#articleTitle'+id).html(EditorData.contents[id].title);
        $('#articleContent'+id).html(EditorData.contents[id].content);
    }

    function saveEdits(id)
    {
        /////////////////////////////////////////////
        //  Save Edits should save Title AND Body  //
        /////////////////////////////////////////////

        //Save to EditorData Object
        EditorData.contents[id] = {};
        EditorData.contents[id].title = $('#articleTitle'+id).html();
        EditorData.contents[id].content = $('#articleContent'+id).html();

        ///////////////////////////
        //  Do Indicator Things  //
        ///////////////////////////


        /*
        $('#'+id+'indicator').css('background-color','rgba(0,255,0,0.75)');
        setTimeout(function(){
            $('#'+id+'indicator').remove();
        },2000);
        */

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


    //Initialize all the editor click handlers
    $(document).ready(function(){

        //Prepare click handler for all editable elements
        $('.editable').click(function() {

            //Get idNum
            var idNum = this.id.replace('articleContent','');
            idNum = idNum.replace('articleTitle','');

            //Save the content as it currently is into the EditorData object
            if(typeof EditorData.contents[idNum] == 'undefined'){
                EditorData.contents[idNum] = {};
                EditorData.contents[idNum].title = $('#articleTitle'+idNum).html();
                EditorData.contents[idNum].content = $('#articleContent'+idNum).html();
            }

            //Remove all other editorSaveRevert divs
            $('.editorSaveRevert').remove();

            //Place save/revert controls off to side of article, NOT TITLE
            $controls = '<div id="'+'articleContent'+idNum+'save" class="editorSaveRevert" style="z-index:50;"><button type="button" class="btn btn-primary btn-block" onclick="saveEdits(\''+idNum+'\');">Save</button><button type="button" class="btn btn-warning btn-block" onclick="revertEdits(\''+this.id+'\');">Revert</button></div>';
            $('#articleContent'+idNum).after($controls);

            //Adjust positioning of save/revert controls
            $('#articleContent'+idNum+'save').css('top',$('#articleContent'+idNum).position().top+'px');
            $('#articleContent'+idNum+'save').css('left',$('#articleContent'+idNum).parent().outerWidth()+'px');

            //Check if instance is already fired up.  Exit click handler if already fired up, we're done here.
            var name;
            for(name in CKEDITOR.instances) {
                var instance = CKEDITOR.instances[name];
                if(this && this == instance.element.$) {
                    return;
                }
            }

            //Init editor since it's not fired up!
            $(this).attr('contenteditable', true);
            CKEDITOR.inline(this,{toolbar:'Advanced'});
            $(this).trigger('click');


        });
    });
</script>
