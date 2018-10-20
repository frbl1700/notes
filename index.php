<?php require_once('include/header.php'); ?>

<div class="container">

    <div class="login-container">

        <div>
            <form method="post" action="login.php" id="login-form">
                <div class="form-item-container">
                    <label>Fyll i din e-postadress</label>
                    <input type="text" name="email" class="form-item" />
                </div>

                <div class="form-item-container">
                    <button class="form-item" type="submit">Logga in</button>
                </div>
            </form>
        </div>


    </div>


</div>

<?php require_once('include/footer.php'); ?>