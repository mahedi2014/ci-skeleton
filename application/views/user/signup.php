<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>

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
                    <h3 class="panel-title">Sign Up Form</h3>
                </div>
                <div class="panel-body">
                    <?= form_open() ?>
                    <fieldset>
                        <div class="form-group">
                            <input class="form-control" placeholder="Username" name="username" type="username" autofocus>
                        </div>
                        <div class="form-group">
                            <input class="form-control" placeholder="Password" name="password" type="password" value="">
                        </div>
                        <div class="form-group">
                            <input class="form-control" placeholder="Confirm Password" name="c_password" type="password" value="">
                        </div>
                        <div class="form-group">
                            <input class="form-control" placeholder="E-mail" name="email" type="email" autofocus>
                        </div>

                        <div class="form-group">
                            <input type="submit" class="btn btn-lg btn-success btn-block" value="Sign Up">
                        </div>
                    </fieldset>
                    <p>Have a account? <?php echo anchor(base_url().'user/signin', 'Sign In'); ?></p>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

