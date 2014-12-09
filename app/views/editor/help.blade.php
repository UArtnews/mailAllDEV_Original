@extends('editor.master')
@section('content')
<div class="panel panel-default colorPanel">
    <div class="panel-heading" id="helpPanelHead">
        <h3>Editor Help</h3>
    </div>
    <div class="panel-body">
        <div class="well">
            <h3><a href="#articles">Articles</a></h3>
            <h3><a href="#submissions">Submissions</a></h3>
            <h3><a href="#publications">Publications</a></h3>
            <h3><a href="#publishing">Publishing</a></h3>
            <h3><a href="#images">Images</a></h3>
            <h3><a href="#settings">Settings</a></h3>
        </div>
        <div class="well">
            <a name="articles"></a>
            <h2>Articles</h2>
            To create and edit articles click on the <button class="btn btn-default"><span class="glyphicon glyphicon-file">&nbsp;Articles</span></button> tab in the navbar.
            <h3>Creating New Articles</h3>
            New articles can be created by clicking the <button class="btn btn-primary"><b>Create New Article</b></button> just above the list of existing articles.<br/>
            This will expand the article creation area.
            Articles are edited "in place" inside a content area that matches the current settings of the publication.
            This is to give you a better idea of the final result without much distortion.<br/>
            To begin editing the article click on either the title or body areas.
            You'll notice a toolbar will appear above the section with formatting and insertion tools.  This works akin to
            many common word processing programs.  You can edit text freely within these areas as well as insert links,
            images and setup anchors for easy navigation and deep-linking.  The area will expand as you type.  To save your
            article click the <button class="btn btn-success">Save</button> button below the article.

            <h3>Editing Existing Articles</h3>
            To edit an existing article pick one from the list of articles.  This list is sorted in reverse chronological order.<br/>
            Articles can also be found via the navbar's search box.  Anywhere an article appears** within the editor you can edit
            the contents freely by simply clicking within the contents and editing as if you were using a word processor.<br/>
            When the article has been modified from the saved contents a red bar will appear alongside
            indicating the article has been modified and must be saved to retain the changes.  To save your changes click the
            <button class="btn btn-success"><span class="glyphicon glyphicon-floppy"></span>&nbsp;Save</button> button.
            To undo any changes and revert to the saved state click the
            <button class="btn btn-warning"><span class="glyphicon glyphicon-remove"></span>&nbsp;Revert</button> button.<br/>
            Once saved or reverted the red indicator alongside the article will turn green and fade away, indicating the article
            no longer contains any pending changes.<br/><br/>
            <b>**</b>You can even edit articles "in place" when viewing/editing publications.  As long as you're in the editor
            side of the application (as opposed to the public facing side) you can edit the articles.
        </div>

        <div class="well">
            <a name="submissions"></a>
            <h2>Submissions</h2>
            If user submissions have been enabled in the publication settings you can access submissions by clicking on the
            <button class="btn btn-default"><span class="glyphicon glyphicon-inbox">&nbsp;Submissions</span></button> tab
            in the navbar.

            <h3>Promoting Submissions to Articles</h3>
            Submissions cannot be included into publications without first being promoted to articles.<br/>
            Once a user has submitted an article to your publication it will appear in a list on the submissions page.
            To view the submission simple click it's title.  This will open the submission for review.  The submitter
            still has the ability to edit this submission.  This means corrections could be made by the submitter if you
            were to contact them and let them know.  Otherwise you can promote the submission to an article by clicking the
            "Promote to Article" button.  This will take you directly to the article editing page for convenience.  Everything
            is already saved and ready for inclusion in a publication.
        </div>


        <div class="well">
            <a name="publications"></a>
            <h2>Publications</h2>
            To create and edit publications click on the
            <button class="btn btn-default"><span class="glyphicon glyphicon-inbox">&nbsp;Publications</span></button> tab in the navbar.

            <h3>Creating New Publications</h3>
            Publications require a publication target date, so the publication creation method is picking a date off the
            calendar and click the <button class="btn btn-success">+</button> button associated with that date.

            Once this is done you'll be taken to the new publication creation page.  Here you can modify the publication
            target date if needed, define the header image (the default defined within your publication settings will be
            pre-selected) and choose the publication type (Regular or Special edition)

            Once these three steps are done you can click the <button class="btn btn-success">Save Publication</button>
            button to schedule a publication on this date.  You can also add articles to the publication before hitting save
            via the <button class="btn btn-success">Add Article From Cart</button> and
            <button class="btn btn-success">Add All Articles From Cart</button> buttons.  The article cart is explained below.

            <h3>Adding Articles to Publications and The Article Cart</h3>
            Articles must be staged in your Article Cart before they can be added to publications.  You can do this by visiting
            the <button class="btn btn-default"><span class="glyphicon glyphicon-file">&nbsp;Articles</span></button> tab
            and browsing the list of existing articles.  You will notice an
            <span class="badge alert-success">Add Article to Cart</span> button for each article in the list.  Clicking
            this will add the article to your Article Cart.  You can see a count of articles in your cart in the navbar.
            <button class="btn btn-default">
                <span class="glyphicon glyphicon-shopping-cart"></span>
                &nbsp;Article Cart&nbsp;<span class="badge" style="background-color:#428bca;">5</span>
            </button><br/>
            Click this button in the navbar will open your Article Cart.  Click an article title will go to the article's
            detail page.  You can also remove the article from your cart by clicking the
            <button class="btn btn-xs btn-danger">Remove from cart</button> button.<br/>
        </div>

        <div class="well">
            <a name="images"></a>
            <h2>Images</h2>
            Images can be uploaded for use in publications.  Simply click the <button class="btn btn-primary">Upload New Image</button>
            button, fill out the form and choose an image to upload.  A URL that can be used anywhere will be available
            on the Image listing page.  If you are using the WYSIWYG editor and wish to include an image, click the insert
            image button and then click the "Browse Server" button.  You should see a simplified listing of the images
            you have uploaded to choose from.  Choose an image and apply any final tweaks (sizing, margins, alignment) and
            the image will be included in your article.<br/><br/>
            Be careful deleting images as they will become unavailable to any publications they were published in.
        </div>




    </div>
    <ul class="list-group">
        <li class="list-group-item list-group-item-warning">
            List Group Item!
        </li>
    </ul>
</div>
@stop