<html>
<head>
	<title>Formatic example</title>
	
	<style type="text/css" media="all">@import url('/_assets/css/formatic_default_theme.css');</style>
	<script type="text/javascript" src="https://ajax.googleapis.com/ajax/libs/jquery/1.4.3/jquery.min.js" charset="UTF-8"></script>
	
	<?
	/*
	// If using Carabiner, assets would be loaded like this
	echo $this->carabiner->display('js');
	echo $this->carabiner->display('css');
	*/
	?>
	
	<!-- Stuff managed assets -->
	<?= Stuff::render('main') ?>
	
</head>
<body>
	
<? if (!empty($valid_data)) : ?>

	<h2><?=$this->lang->line('form_success')?></h2>
	<pre>
	<? print_r($valid_data) ?>
	</pre>
		
<? else : ?>
	
	<form action="<?=$this->uri->uri_string()?>" method="post" enctype="multipart/form-data" class="data-capture">

		<fieldset>
		
			<legend>Example formatic form</legend>
	
			<?=form_token();?>

			<?=render_errors();?>
		
			<?= $f->name->row ?>
			<?= $f->date->row ?>
			<?= $f->timezone->row ?>
			<?= $f->map->row ?>
			<?= $f->show_marker->row ?>
			<?= $f->description->row ?>
			<?= $f->contacts->row ?>
			<?= $f->opening_times->row ?>
			<?= $f->categories->row ?>
			<?= $f->photo->row ?>
			<?= $f->file->row ?>
			<?= $f->industries->row ?>	
			<?= $f->captcha->row ?>

			<input type="submit" name="submit" value="<?=$this->lang->line('form_save')?>" class="btn">
		</fieldset>
	</form>

<? endif; ?>

</body>
</html>