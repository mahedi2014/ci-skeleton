<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>

<script>
    $(document).ready(function(){
        $('#signinForm').on('submit',function(e) {
            $.ajax({
                url: "<?php echo base_url().'auth/test' ;?>",
                data: $(this).serialize(),
                type:'POST',
                beforeSend: function() {
                    $('#loading').show();
                },
                success:function(data){
                    var json = $.parseJSON(data);
                    if(json.status){
                        $('#loading').hide();
                        alert(json.statusMessage);
                        location.reload(true);
                    }
                },
                error:function(data){
                    alert(data);
                }
            });
            e.preventDefault();
        });
    });
</script>

<div class="container">
    <div class="row">

        <?php if (validation_errors()) : ?>
            <div class="alert alert-danger" role="alert">
                <?= validation_errors() ?>
            </div>
        <?php endif; ?>
        <?php if (isset($error)) : ?>
            <div class="alert alert-danger" role="alert">
                <?= $error ?>
            </div>
        <?php endif; ?>

        <div class="col-md-4 col-md-offset-4">
            <div class="login-panel panel panel-default">
                <div class="panel-heading">
                    <h3 class="panel-title">Sign In Form</h3>
                </div>
                <div class="panel-body">
                    <!--                    --><?//= form_open() ?>
                    <form id="signinForm" name="signinForm" action="" method="post">
                        <fieldset>
                            <div class="form-group">
                                <input class="form-control" placeholder="Username" name="username" type="username" autofocus>
                            </div>
                            <div class="form-group">
                                <input class="form-control" placeholder="Password" name="password" type="password" value="">
                            </div>

                            <div class="form-group">
                                <input type="submit"  class="btn btn-lg btn-success btn-block" value="Sign In">
                            </div>
                        </fieldset>
                        <p>You have no account? <?php echo anchor(base_url().'auth/signup', 'Sign Up'); ?></p>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>


