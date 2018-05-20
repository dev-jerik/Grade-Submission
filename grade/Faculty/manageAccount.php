<?php
    include "../Common/header.php";
    if(isset($_POST['submit'])){
        $username = trim($_POST['username']);
        $oldPassword = trim($_POST['oldPassword']);
        $newPassword = trim($_POST['newPassword']);
        if($user->updateUserAccount($username, $newPassword, $oldPassword)){
            $user->doLogout();
            header("Location: ../../index.php");
        }
    }
?>
<div id="body">
    <div class="container">
        <div class="row">
            <div class="col-md-12">
                <form method="POST">                    
                    <div class="space-8"></div>

                    <div style="width: 300px; margin: auto; background-color: lightgray; border-radius: 4px; min-height: 100px; padding: 0;">

                        <h2 style="border-bottom: 1px solid gray; background-color: #32d666; "><small style="color: #fff; padding-left: 8px;">Manage Account</small></h2>
                        <div style="padding: 2px 20px;">
                            <table>
                                <tr>
                                    <td><label>Username:&nbsp; </label></td>
                                    <td>
                                        <input type="text" name="username" class="form-control" 
                                        value='<?php echo $user->getUserName($_SESSION['user_id']); ?>' autofocus required />
                                    </td>
                                </tr>
                                <tr>
                                    <td><label>Old Password:&nbsp;</label></td>
                                    <td>
                                        <input type="password" name="oldPassword" class="form-control" placeholder="Old password" required />
                                    </td>
                                </tr>
                                <tr>
                                    <td><label>New Password:&nbsp;</label></td>
                                    <td>
                                        <input type="password" name="newPassword" class="form-control" placeholder="New password" required />
                                    </td>
                                </tr>
                            </table>
                        </div>
                       
                       
                        <h2 style="border-top: 1px solid gray; padding: 8px"><button class="btn btn-success" style="width: 80px;" type="submit" name='submit'>Change</button></h2>
                        
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
<!--Script Here-->
<script>
    $(document).ready(function(){
        //$("ul.link li").removeClass("active");
        $("#f-manageAccount").addClass("active");
    });
</script>

<?php  include "../Common/footer.php";    ?>
