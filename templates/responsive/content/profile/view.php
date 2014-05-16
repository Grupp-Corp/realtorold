<?php

$GetProfile = new UserProfile();
$User = $GetProfile->GetProfileByUsername(trim($_GET['user']));

$PageTitle = 'Profile - '.$User['username'];

?>

<div class="container-fluid well offset1  span10">
    <div class="row-fluid">
        <div class="span2" >
            <img src="//placehold.it/224x173" class="">
        </div>

        <div class="span8">
            <h3><?php echo $User['username']; ?></h3>
            <h6>Email: <a href="mailto:<?php echo $User['email']; ?>"><?php echo $User['email']; ?></a></h6>
            <h6>Business Address: <address><?php echo $User['business_address'] ?> <?php echo $User['business_city']; ?>, <?php echo $User['business_state']; ?> <?php echo $User['business_zip']; ?></address></h6>
        </div>
        <div class="span2">
            <?php if($User['facebook_link']): ?><a href="<?php echo $User['facebook_link'] ?>" target="_blank">Facebook</a><?php endif; ?>
            <?php if($User['linkedin_link']): ?><a href="<?php echo $User['linkedin_link'] ?>" target="_blank">LinkedIn</a><?php endif; ?>
            <?php if($User['instagram_link']): ?><a href="<?php echo $User['instagram_link'] ?>" target="_blank">Instagram</a><?php endif; ?>
            <?php if($User['pinterest_link']): ?><a href="<?php echo $User['pinterest_link'] ?>" target="_blank">Pinterest</a><?php endif; ?>
            <?php if($User['youtube_link']): ?><a href="<?php echo $User['youtube_link'] ?>" target="_blank">Youtube</a><?php endif; ?>
            <?php if($User['vine_link']): ?><a href="<?php echo $User['vine_link'] ?>" target="_blank">Vine</a><?php endif; ?>
            <?php if($User['twitter_link']): ?><a href="<?php echo $User['twitter_link'] ?>" target="_blank">Twitter</a><?php endif; ?>
            <?php if($User['myspace_link']): ?><a href="<?php echo $User['myspace_link'] ?>" target="_blank">MySpace</a><?php endif; ?>
        </div>
    </div>
</div>
<div class="clearfix"></div>