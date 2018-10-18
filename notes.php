<?php 
session_start();
if (empty($_SESSION['user'])) {
    header('location: index.php');
}
?>
<?php require_once('include/header.php'); ?>
<div>
    <h1 class="text-center">Notes!</h1>

    <div class="container">
        <div class="widget-area">
            <div id="notes-data"></div>
        </div>
    </div>
</div>
<script>    
    var notesObj = {
        user: <?php echo $_SESSION['user']; ?>,
        ver: '1.0'
    };

    (function() {
        //Initiera 
        var manager = new NotesManager(notesObj.user);
        
        //Hämta anteckningar
        manager.getNotes(function(err, data) {
            if (data && data.length > 0) {
                for (var i = 0; i < data.length; i++) {
                    var elm = document.createElement('p');
                    elm.innerHTML = data[i].text;

                    document.getElementById('notes-data').appendChild(elm);
                }
            }
        });
    }());
</script>
<?php require_once('include/footer.php'); ?>