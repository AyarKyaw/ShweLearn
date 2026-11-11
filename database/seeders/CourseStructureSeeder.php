<?php

namespace Database\Seeders;

use App\Models\Course;
use App\Models\Unit;
use App\Models\Section;
use App\Models\Lesson;
use App\Models\User;
use Illuminate\Database\Seeder;

class CourseStructureSeeder extends Seeder
{
    public function run(): void
    {
        // 1. Ensure a test user exists
        $user = User::first() ?? User::create([
            'name' => 'Test Student',
            'email' => 'student@example.com',
            'password' => bcrypt('password'),
        ]);

        // 2. Fetch existing course or create one safely if missing
        $course = Course::firstOrCreate(
            ['code' => 'MIFFA-LOG-101'],
            [
                'category_id' => 1,
                'title' => 'International Freight Forwarding & Logistics',
                'image' => null,
                'hour' => 40,
                'price' => 150000.00,
                'desc' => 'Comprehensive masterclass on global logistics, shipping documentation, customs clearance, and supply chain management.',
            ]
        );

        // 3. Define nested structure
        $unitsData = [
            [
                'title' => 'Unit 1: Introduction to Freight Forwarding',
                'description' => 'Fundamental principles of international trade, transport modes, and freight operations.',
                'sections' => [
                    [
                        'title' => 'Basics of Global Supply Chain',
                        'description' => 'Overview of logistics ecosystems and key stakeholders.',
                        'lessons' => [
                            [
                                'title' => 'Overview of the Freight Industry',
                                'type' => 'video',
                                'video_url' => 'https://www.youtube.com/embed/dQw4w9WgXcQ',
                                'content' => 'In this introductory video, we explore the scale and global significance of freight forwarding.',
                            ],
                            [
                                'title' => 'Key Industry Players: Carrier vs Forwarder',
                                'type' => 'article',
                                'video_url' => null,
                                'content' => '<p>Freight forwarders act as intermediaries between shippers and transportation services, coordinating logistics seamlessly across borders.</p>',
                            ],
                        ],
                    ],
                    [
                        'title' => 'Incoterms 2020 Essentials',
                        'description' => 'Understanding risk transfer and costs in global commerce.',
                        'lessons' => [
                            [
                                'title' => 'FOB vs CIF Deep Dive',
                                'type' => 'video',
                                'video_url' => 'https://www.youtube.com/embed/dQw4w9WgXcQ',
                                'content' => 'Detailed analysis of FOB (Free On Board) and CIF (Cost, Insurance, and Freight) shipping terms.',
                            ],
                            [
                                'title' => 'Incoterms Knowledge Check',
                                'type' => 'quiz',
                                'video_url' => null,
                                'content' => 'Complete this assessment to evaluate your knowledge of international trade terms.',
                            ],
                        ],
                    ],
                ],
            ],
            [
                'title' => 'Unit 2: Shipping Documentation & Customs',
                'description' => 'Master Bills of Lading, Commercial Invoices, and customs clearance procedures.',
                'sections' => [
                    [
                        'title' => 'Essential Logistics Documents',
                        'description' => 'Documentation required for international transit.',
                        'lessons' => [
                            [
                                'title' => 'Bill of Lading (B/L) Functions',
                                'type' => 'video',
                                'video_url' => 'https://www.youtube.com/embed/dQw4w9WgXcQ',
                                'content' => 'Understanding the three primary functions of a Bill of Lading: Receipt, Title, and Contract of Carriage.',
                            ],
                            [
                                'title' => 'Packing List & Commercial Invoice Prep',
                                'type' => 'article',
                                'video_url' => null,
                                'content' => '<p>Accurate commercial documentation prevents customs delays and regulatory fines during export processing.</p>',
                            ],
                        ],
                    ],
                ],
            ],
        ];

        // 4. Seed units, sections, and lessons safely
        foreach ($unitsData as $uIndex => $unitData) {
            $unit = Unit::firstOrCreate(
                [
                    'course_id' => $course->id,
                    'title' => $unitData['title'],
                ],
                [
                    'description' => $unitData['description'],
                    'order' => $uIndex + 1,
                ]
            );

            foreach ($unitData['sections'] as $sIndex => $sectionData) {
                $section = Section::firstOrCreate(
                    [
                        'unit_id' => $unit->id,
                        'title' => $sectionData['title'],
                    ],
                    [
                        'description' => $sectionData['description'],
                        'order' => $sIndex + 1,
                    ]
                );

                foreach ($sectionData['lessons'] as $lIndex => $lessonData) {
                    $lesson = Lesson::firstOrCreate(
                        [
                            'section_id' => $section->id,
                            'title' => $lessonData['title'],
                        ],
                        [
                            'type' => $lessonData['type'],
                            'video_url' => $lessonData['video_url'],
                            'content' => $lessonData['content'],
                            'order' => $lIndex + 1,
                        ]
                    );

                    // Attach sample completion pivot record if not already attached
                    if ($uIndex === 0 && $sIndex === 0 && $lIndex === 0) {
                        $user->lessons()->syncWithoutDetaching([
                            $lesson->id => [
                                'course_id' => $course->id,
                                'is_completed' => true,
                                'quiz_score' => 100,
                            ]
                        ]);
                    }
                }
            }
        }
    }
}