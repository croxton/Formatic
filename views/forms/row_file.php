<label for="{id}"{row_class}>{label} {required}<br />
{field}
<? if (empty($cv)): ?>
  <p>No CV yet</p>
<? else: ?>
  <p>Current file: <?= $cv ?></p>
<? endif; ?>
</label>