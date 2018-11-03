<?php 
session_start();
if (empty($_SESSION['user'])) {
    header('location: index.php');
}
?>
<?php require_once('include/header.php'); ?>

<div>
    <div class="container">
        <nav>
            <div class="navigation">
                <div>
                    <div class="logo-container">
                        <img src="img/logo.png" alt="Notes logo" class="logo" />
                    </div>
                </div>

                <div class="logout-container">
                    <i class="fa fa-user"></i>
                    <a href="logout.php">Logga ut</a>
                </div>
            </div>
        </nav>
    </div>

    <div class="container">
        <div class="widget-area">
            <div id="notes-data"></div>
        </div>
    </div>
</div>

<div class="pane">
    <div class="pane-content">
        <div>
            <span>&nbsp;</span>
        </div>

        <div>
            <a href="javascript:;" onclick="createNote();">
                <i class="fa fa-plus-circle"></i>
            </a>
        </div>

        <div>
            <div id="trash-bin">
                <span class="fa fa-trash"></span>
            </div>
        </div>
    </div>
</div>

<div style="display:none;">
    <div class="note" id="note-template">
        <div class="note-content"></div>
        <div class="note-editor"></div>
    </div>
</div>

<script>    
    var notesObj = {
        user: <?php echo $_SESSION['user']; ?>,
        ver: '1.0',

        //Initera manager
        manager: new NotesManager(this.user),

        //Lite kontroll över när anteckningar ska sparas/uppdateras
        saveQueue: null
    };
</script>
<script src="js/notes-dom.js"></script>
<?php require_once('include/footer.php'); ?>