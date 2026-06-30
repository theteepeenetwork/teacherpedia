<?php

namespace App\Models;

use CodeIgniter\Model;

class ObjectiveModel extends Model
{
    protected $table         = 'objectives';
    protected $primaryKey    = 'id';
    protected $returnType    = 'array';
    protected $useTimestamps = true;
    protected $allowedFields = [
        'key_stage', 'subject', 'year', 'strand', 'text',
        'generator_key', 'auto_generating',
    ];

    /**
     * All objectives for a given year, ordered by strand then text.
     */
    public function byYear(int $year): array
    {
        return $this->where('year', $year)
            ->orderBy('strand', 'ASC')
            ->orderBy('id', 'ASC')
            ->findAll();
    }

    /**
     * All objectives for a given strand.
     */
    public function byStrand(string $strand): array
    {
        return $this->where('strand', $strand)
            ->orderBy('year', 'ASC')
            ->orderBy('id', 'ASC')
            ->findAll();
    }

    /**
     * Objectives grouped by strand. Optionally filtered by year.
     * Returns ['Strand name' => [objective, ...], ...].
     */
    public function groupedByStrand(?int $year = null): array
    {
        $builder = $this->orderBy('strand', 'ASC')->orderBy('id', 'ASC');
        if ($year !== null) {
            $builder->where('year', $year);
        }
        $grouped = [];
        foreach ($builder->findAll() as $row) {
            $grouped[$row['strand']][] = $row;
        }
        return $grouped;
    }

    /**
     * Coverage stats per year: total objectives, how many are auto-generating
     * (have a generator) and how many are not.
     * Returns [year => ['total'=>n, 'auto'=>n, 'manual'=>n], ...].
     */
    public function countsByYear(): array
    {
        $rows = $this->select('year, auto_generating, COUNT(*) AS cnt')
            ->groupBy('year, auto_generating')
            ->orderBy('year', 'ASC')
            ->findAll();

        $stats = [];
        foreach ($rows as $row) {
            $year = (int) $row['year'];
            if (! isset($stats[$year])) {
                $stats[$year] = ['total' => 0, 'auto' => 0, 'manual' => 0];
            }
            $cnt = (int) $row['cnt'];
            $stats[$year]['total'] += $cnt;
            if ((int) $row['auto_generating'] === 1) {
                $stats[$year]['auto'] += $cnt;
            } else {
                $stats[$year]['manual'] += $cnt;
            }
        }
        return $stats;
    }

    /**
     * Total counts of auto vs non-auto across the whole library.
     */
    public function autoCounts(): array
    {
        $total  = $this->countAllResults(false);
        $auto   = $this->where('auto_generating', 1)->countAllResults();
        return ['total' => $total, 'auto' => $auto, 'manual' => $total - $auto];
    }
}
