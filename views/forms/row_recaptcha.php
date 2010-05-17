<div id="recaptcha_image"></div>

<div id="recaptcha_controls">
	<a href="javascript:Recaptcha.reload()">Get another CAPTCHA</a> | 
	<div class="recaptcha_only_if_image"><a href="javascript:Recaptcha.switch_type('audio')">Get an audio CAPTCHA</a></div>
	<div class="recaptcha_only_if_audio"><a href="javascript:Recaptcha.switch_type('image')">Get an image CAPTCHA</a></div>
</div>

<label for="{id}"{row_class}>{label} {required}
{field}
<div class="recaptcha_only_if_image desc">Enter the two words above</div>
<div class="recaptcha_only_if_audio desc">Enter the words you hear</div>
</label>

{recaptcha}