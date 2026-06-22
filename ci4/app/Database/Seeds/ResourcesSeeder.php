<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

class ResourcesSeeder extends Seeder
{
    public function run()
    {
        $now = date('Y/m/d');

        $resources = [
            [
                'resource_name'        => 'Sample Resource',
                'resource_author'      => 1,
                'resource_description' => 'A simple interactive resource used for testing the resource page and routing.',
                'resource_excerpt'     => 'A simple interactive resource for testing.',
                'link'                 => 'public_html/resources/2020/06-07-90/05-13-11',
                'keywords'             => 'sample, test, demo',
                'action'               => 'generator.php',
                'level'                => 'free',
                'year'                 => 'Year 3',
                'category'             => '4,5,8',
                'resource_banner'      => '/assets/img/banners_thumbnails/sample_banner.jpg',
                'resource_thumb'       => '/assets/img/banners_thumbnails/sample_thumb.jpg',
                'slug'                 => 'sample-resource',
                'dateAdded'            => $now,
            ],
            [
                'resource_name'        => 'Times Tables Trainer',
                'resource_author'      => 1,
                'resource_description' => 'Practise multiplication facts with this quick-fire times tables trainer.',
                'resource_excerpt'     => 'Quick-fire multiplication practice.',
                'link'                 => 'public_html/resources/2020/06-07-90/05-27-13',
                'keywords'             => 'maths, multiplication, times tables',
                'action'               => 'generator.php',
                'level'                => 'free',
                'year'                 => 'Year 4',
                'category'             => '4,5,8',
                'resource_banner'      => '/assets/img/banners_thumbnails/times_tables_banner.jpg',
                'resource_thumb'       => '/assets/img/banners_thumbnails/times_tables_thumb.jpg',
                'slug'                 => 'times-tables-trainer',
                'dateAdded'            => $now,
            ],
            [
                'resource_name'        => 'Spelling Bee',
                'resource_author'      => 1,
                'resource_description' => 'A fun spelling game that helps pupils master common spelling patterns.',
                'resource_excerpt'     => 'Master common spelling patterns.',
                'link'                 => 'public_html/resources/2020/06-07-90/06-49-31',
                'keywords'             => 'english, spelling, literacy',
                'action'               => 'generator.php',
                'level'                => 'free',
                'year'                 => 'Year 2',
                'category'             => '4,6,10',
                'resource_banner'      => '/assets/img/banners_thumbnails/spelling_banner.jpg',
                'resource_thumb'       => '/assets/img/banners_thumbnails/spelling_thumb.jpg',
                'slug'                 => 'spelling-bee',
                'dateAdded'            => $now,
            ],
            [
                'resource_name'        => 'Number Bonds',
                'resource_author'      => 1,
                'resource_description' => 'Build fluency with number bonds to 10, 20 and 100 in this adaptive activity.',
                'resource_excerpt'     => 'Build fluency with number bonds.',
                'link'                 => 'public_html/resources/2020/06-07-90/06-52-51',
                'keywords'             => 'maths, addition, number bonds',
                'action'               => 'generator.php',
                'level'                => 'premium',
                'year'                 => 'Year 1',
                'category'             => '4,5,9',
                'resource_banner'      => '/assets/img/banners_thumbnails/number_bonds_banner.jpg',
                'resource_thumb'       => '/assets/img/banners_thumbnails/number_bonds_thumb.jpg',
                'slug'                 => 'number-bonds',
                'dateAdded'            => $now,
            ],
            [
                'resource_name'        => 'Reading Comprehension',
                'resource_author'      => 1,
                'resource_description' => 'Short passages with questions to develop reading comprehension skills.',
                'resource_excerpt'     => 'Develop reading comprehension skills.',
                'link'                 => 'public_html/resources/2020/06-07-90/06-53-09',
                'keywords'             => 'english, reading, comprehension',
                'action'               => 'generator.php',
                'level'                => 'free',
                'year'                 => 'Year 5',
                'category'             => '4,6,10',
                'resource_banner'      => '/assets/img/banners_thumbnails/reading_banner.jpg',
                'resource_thumb'       => '/assets/img/banners_thumbnails/reading_thumb.jpg',
                'slug'                 => 'reading-comprehension',
                'dateAdded'            => $now,
            ],
        ];

        $this->db->table('resources')->insertBatch($resources);
    }
}
