<?php
/**
 * tool_toolbar.php — the STANDARD resource editing toolbar.
 * -----------------------------------------------------------------------------
 * Shared by every resource tool (Worksheet Builder, Code Breaker, …) so the
 * editing controls are identical and familiar regardless of the resource:
 *   Row 1  — difficulty slider (1–5) + any resource-specific settings
 *   Row 2  — view / answer-key segmented toggle + Save + Print/PDF + New
 *
 * The page's JS wires behaviour to "<prefix>-…" ids. Hooks (kept consistent
 * across resources):
 *   #<prefix>-difficulty  .diff-thumb  button[data-diff]   (difficulty slider)
 *   #<prefix>-diff-label                                    (difficulty label)
 *   #<prefix>-tabs        .seg-thumb   button[data-tab]     (segmented toggle)
 *   #<prefix>-save  #<prefix>-print  #<prefix>-regen  #<prefix>-regen-icon
 *
 * Params:
 *   $prefix          string  id prefix the page JS binds to (e.g. 'build','cb')
 *   $tabs            array   exactly two: [['key'=>…,'label'=>…], …]
 *   $diff            int     initial difficulty 1-5 (rendered as a circle meter)
 *   $regen_label     string  primary action label (e.g. 'Regenerate','New puzzle')
 *   $settings_extra  string  raw HTML for resource-specific settings (optional)
 *   $show_difficulty bool    default true
 */
$prefix          = $prefix          ?? 'tool';
$tabs            = $tabs            ?? [['key' => 'worksheet', 'label' => 'Worksheet'], ['key' => 'answerkey', 'label' => 'Answer key']];
$diff            = isset($diff) ? max(1, min(5, (int) $diff)) : 3;
$regen_label     = $regen_label     ?? 'Regenerate';
$settings_extra  = $settings_extra  ?? '';
$show_difficulty = $show_difficulty ?? true;
$p = esc($prefix, 'attr');
// Difficulty shown as a filled/empty circle meter (●●●○○) — see tp-tool.js.
$diffDots = str_repeat('●', $diff) . str_repeat('○', 5 - $diff);
?>
<!-- Settings row: difficulty + resource-specific controls -->
<div class="app-toolbar tool-settings" style="border-bottom:1px solid rgba(28,36,32,.07); background:rgba(255,255,255,.4); flex-wrap:wrap; gap:14px;">
  <?php if ($show_difficulty): ?>
    <span class="build-eyebrow-lbl">Difficulty</span>
    <div id="<?= $p ?>-difficulty" class="difficulty" style="width:200px;">
      <div class="diff-thumb"></div>
      <?php for ($i = 1; $i <= 5; $i++): ?>
        <button type="button" data-diff="<?= $i ?>"><?= $i ?></button>
      <?php endfor; ?>
    </div>
    <span id="<?= $p ?>-diff-label" class="build-diff-label"><?= $diffDots ?></span>
  <?php endif; ?>
  <?= $settings_extra ?>
</div>

<!-- Actions row: view / answer-key toggle + Save / Print / New -->
<div class="app-toolbar tool-actions">
  <div id="<?= $p ?>-tabs" class="segmented">
    <div class="seg-thumb"></div>
    <?php foreach ($tabs as $t): ?>
      <button type="button" data-tab="<?= esc($t['key'], 'attr') ?>"><?= esc($t['label']) ?></button>
    <?php endforeach; ?>
  </div>
  <div style="flex:1;"></div>
  <button type="button" id="<?= $p ?>-save" class="btn btn-ghost btn-sm">&#9829; Save</button>
  <button type="button" id="<?= $p ?>-print" class="btn btn-ghost btn-sm">&#9113; Print / PDF</button>
  <button type="button" id="<?= $p ?>-regen" class="btn btn-primary btn-sm">
    <span id="<?= $p ?>-regen-icon" style="display:inline-block; font-size:16px;">&#10227;</span> <?= esc($regen_label) ?>
  </button>
</div>
