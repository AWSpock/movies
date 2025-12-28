<div class="header">
    <h1>Delete Movie File</h1>
</div>

<nav class="breadcrumbs">
    <ul>
        <li><a href="/">Movies</a></li>
        <li><a href="/movie-file">Movie Files</a></li>
        <li><a href="/movie-file/<?php echo htmlentities($recMovie_File->id()); ?>/edit">Edit <?php echo htmlentities($recMovie_File->file_name()); ?></a></li>
        <li>Delete</li>
    </ul>
</nav>

<div class="content">
    <form method="post" action="" id="frm" class="form-group main-form">
        <input type="hidden" id="warranty.id" name="warranty.id" value="<?php echo htmlentities($recMovie_File->id()); ?>" />
        <p>Are you sure you wish to delete this Movie File?</p>
        <div class="input-group">
            <label class="form-control">File Name</label>
            <div><samp><?php echo htmlentities($recMovie_File->file_name()); ?></samp></div>
        </div>
        <div class="input-group">
            <label class="form-control">Movie</label>
            <div><samp><?php echo htmlentities($recMovie->title()); ?></samp></div>
        </div>
        <div class="input-group">
            <label class="form-control">From Disk</label>
            <div><samp><?php echo $recMovie_File->from_disk() ? "Yes" : "No"; ?></samp></div>
        </div>
        <div class="input-group">
            <label class="form-control">Bluray</label>
            <div><samp><?php echo $recMovie_File->bluray() ? "Yes" : "No"; ?></samp></div>
        </div>
        <div class="input-group">
            <label class="form-control">Quality</label>
            <div><samp><?php echo htmlentities($recMovie_File->quality()); ?></samp></div>
        </div>
        <div class="input-group">
            <label class="form-control">Quality Notes</label>
            <div><samp><?php echo htmlentities($recMovie_File->quality_notes()); ?></samp></div>
        </div>
        <div class="input-group">
            <label class="form-control">File Size</label>
            <div><samp><?php echo readableBytes($recMovie_File->file_size()); ?></samp></div>
        </div>
        <div class="input-group">
            <label class="form-control">Title</label>
            <div><samp><?php echo htmlentities($recMovie_File->title()); ?></samp></div>
        </div>
        <div class="input-group">
            <label class="form-control">Year</label>
            <div><samp><?php echo htmlentities($recMovie_File->year()); ?></samp></div>
        </div>
        <div class="input-group">
            <label class="form-control">Notes</label>
            <div><samp><?php echo htmlentities($recMovie_File->notes()); ?></samp></div>
        </div>
        <div class="input-group">
            <label class="form-control">File Type</label>
            <div><samp><?php echo htmlentities($recMovie_File->file_type()); ?></samp></div>
        </div>
        <div class="input-group">
            <label class="form-control">Create</label>
            <div><samp data-dateformatter><?php echo htmlentities($recMovie_File->created()); ?></samp></div>
        </div>
        <div class="input-group">
            <label class="form-control">Updated</label>
            <div><samp data-dateformatter><?php echo htmlentities($recMovie_File->updated()); ?></samp></div>
        </div>
        <div class="button-group">
            <button type="submit" class="button remove"><i class="fa-solid fa-trash"></i>Confirm Delete</button>
            <a href="/movie-file/<?php echo htmlentities($recMovie_File->id()); ?>/edit" class="button secondary"><i class="fa-solid fa-ban"></i>Cancel</a>
        </div>
    </form>
</div>