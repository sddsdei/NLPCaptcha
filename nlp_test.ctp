
<?php echo $this->Form->create(); ?>

<!--Start NLPCaptcha Embed Code -->
<script type="text/javascript">
    var NLPOptions = {
        key: '283a91fa6ee584cd11c30e72dd07ac56' //Your Publisher Key
    };
</script>
<script type="text/javascript" src="http://call.nlpcaptcha.in/js/captcha.js" ></script>
<!--End NLPCaptcha Embed Code -->


<?php echo $this->Form->end('Go'); ?>
