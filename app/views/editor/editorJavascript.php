<script>
    //EditorContents variable for storing/retrieving original copies of articles
    var EditorData = (function(){
        var editorContents = [];

        return {
            contents: editorContents,
        };
    })();

    function revertEdits(id)
    {
        $('#'+id).html(EditorData.contents[id]);
    }

    function saveEdits(id)
    {
        EditorData.contents[id] = $('#'+id).html();
        $('#'+id+'indicator').css('background-color','rgba(0,255,0,0.75)');
        setTimeout(function(){
            $('#'+id+'indicator').remove();
        },2000);

        ///////////////////////////
        //  ADD AJAX STUFF HERE  //
        ///////////////////////////
        ///////////////////////////
        //  ADD AJAX STUFF HERE  //
        ///////////////////////////
    }


    $(document).ready(function(){

        //Prepare click handler for all editable elements
        $('.editable').click(function() {
            //Save the content as it currently is into the
            if(typeof EditorData.contents[this.id] == 'undefined'){
                EditorData.contents[this.id] = $(this).html();
            }

            //Remove all other editorSaveRevert divs
            $('.editorSaveRevert').remove();

            //Place save/revert controls off to side of article
            $controls = '<div id="'+this.id+'save" class="editorSaveRevert" ><button type="button" class="btn btn-primary btn-block" onclick="saveEdits(\''+this.id+'\');">Save</button><button type="button" class="btn btn-warning btn-block" onclick="revertEdits(\''+this.id+'\');">Revert</button></div>';
            $(this).after($controls);

            //Adjust positioning of save/revert controls
            $('#'+this.id+'save').css('top',$(this).position().top+'px');
            $('#'+this.id+'save').css('left',$(this).parent().outerWidth()+'px');

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
