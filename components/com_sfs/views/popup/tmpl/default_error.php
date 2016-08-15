<style type="text/css">
	.error-page{
		height: 100%;
		padding-bottom: 40px;
	}
	.error-page .buttons{
		position: absolute;
		bottom: 0;
		text-align: right;
		right: 0;
	}
	body{
		height: 100%;
	}
</style>
<div class="wrap error-page">
	<div class="uk-alert uk-alert-danger"><?php
		echo $this->message;
	?>
	</div>
	<div class="buttons">
		<button class="btn orange" onclick="window.top.SqueezeBox.close()">CLOSE</button>
	</div>
</div>