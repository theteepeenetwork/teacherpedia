/* =============================================================================
 * tp-tool.js — tiny shared helpers for every resource tool.
 * -----------------------------------------------------------------------------
 * Loaded BEFORE each tool's own script (build.js, code-breaker.js,
 * maths-maze.js, …) so the difficulty "code" is defined in ONE place.
 *
 * Difficulty is shown as a filled/empty circle meter rather than the attainment
 * band names, so the level isn't spelled out for pupils/parents. Teachers read
 * it against the 1-5 slider: ●●●○○ = level 3 of 5.
 * ========================================================================== */
window.TP_diffDots = function (d) {
  d = Math.max(0, Math.min(5, d | 0));
  return '●●●●●'.slice(0, d) + '○○○○○'.slice(0, 5 - d);
};
