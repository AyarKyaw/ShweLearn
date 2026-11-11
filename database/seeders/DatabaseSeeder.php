<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\CourseCategory;
use App\Models\Category;
use App\Models\Course;
use Illuminate\Database\Seeder;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    public function run(): void
    {
        // 1. Create a Test User
        User::factory()->create([
            'name' => 'Test User',
            'email' => 'test@example.com',
        ]);

        // 2. Course Categories (Top Tier)
        $diplomaType = CourseCategory::create([
            'name' => 'Diploma Programs',
            'slug' => 'diploma-programs',
            'description' => 'Multi-month accredited diploma qualifications.',
        ]);

        $certificateType = CourseCategory::create([
            'name' => 'Certificate Programs',
            'slug' => 'certificate-programs',
            'description' => 'Short skill-focused certification tracks.',
        ]);

        // 3. Categories (Middle Tier - Subcategories of CourseCategory)
        $logisticsCat = Category::create([
            'course_category_id' => $diplomaType->id,
            'name' => 'International Logistics & Freight',
            'slug' => 'international-logistics-freight',
            'description' => 'Global supply chain and freight handling.',
            'is_active' => true,
        ]);

        $customsCat = Category::create([
            'course_category_id' => $certificateType->id,
            'name' => 'Customs & Trade Compliance',
            'slug' => 'customs-trade-compliance',
            'description' => 'Customs brokerage and import/export documentation.',
            'is_active' => true,
        ]);

        // 4. Courses (Bottom Tier - Linked via category_id)
        Course::create([
            'category_id' => $logisticsCat->id,
            'code' => 'DIP-101',
            'title' => 'FIATA Higher Diploma in Freight Forwarding',
            'hour' => 180,
            'price' => 450.00,
            'desc' => 'Comprehensive international freight forwarding program.',
        ]);

        Course::create([
            'category_id' => $customsCat->id,
            'code' => 'CERT-201',
            'title' => 'Customs Valuation & Tariff Certification',
            'hour' => 40,
            'price' => 120.00,
            'desc' => 'Hands-on training for customs regulations and HS codes.',
        ]);
    }
}