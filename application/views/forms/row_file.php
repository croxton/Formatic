<label for="{id}"{row_class}>{label} {required}<br />
{field}
{error}
<? if (empty($cv)): ?>
  <p>No CV yet</p>
<? else: ?>
  <p>Current file: <?= $cv ?></p>
<? endif; ?>
</label>