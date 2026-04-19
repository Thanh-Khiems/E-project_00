<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;
use Illuminate\Support\Str;
use Throwable;

class Blog extends Model
{
    use HasFactory;

    public const STATUS_DRAFT = 'draft';
    public const STATUS_PUBLISHED = 'published';

    protected $fillable = [
        'title',
        'slug',
        'excerpt',
        'content',
        'thumbnail',
        'status',
        'is_featured',
        'published_at',
    ];

    protected $casts = [
        'is_featured' => 'boolean',
        'published_at' => 'datetime',
    ];

    protected static function booted(): void
    {
        static::creating(function ($blog) {
            if (blank($blog->slug)) {
                $blog->slug = Str::slug($blog->title);
            }
        });
    }

    public function scopePublished($query)
    {
        return $query->where('status', self::STATUS_PUBLISHED)
            ->where(function ($q) {
                $q->whereNull('published_at')->orWhere('published_at', '<=', now());
            });
    }

    public static function hardcodedBlogs(): Collection
    {
        return collect([
            new static([
                'id' => -1,
                'title' => '7 Daily Habits That Help Patients Stay Healthy',
                'slug' => '7-daily-habits-that-help-patients-stay-healthy',
                'excerpt' => 'Simple routines like hydration, movement, sleep, and routine checkups can significantly improve long-term health.',
                'content' => "Good health is built through small habits repeated every day.\n\n1. Drink enough water throughout the day to support digestion, blood circulation, and body temperature regulation.\n\n2. Sleep 7 to 8 hours per night so your body has time to recover and your immune system stays strong.\n\n3. Move your body every day, even if it is just a 20 to 30 minute walk. Regular movement supports the heart, joints, and mental health.\n\n4. Choose balanced meals with vegetables, fruits, lean protein, and whole grains. Limiting sugary drinks and overly processed foods can reduce many health risks.\n\n5. Follow your medicine schedule correctly and never stop treatment without asking a doctor.\n\n6. Monitor important warning signs such as unusual pain, persistent fever, dizziness, or breathing difficulty. Early action often prevents complications.\n\n7. Schedule regular medical checkups. Preventive care helps detect problems early and gives you peace of mind.\n\nAt MediConnect, we encourage patients to combine healthy daily habits with timely medical advice for better outcomes.",
                'thumbnail' => 'images/news/news-1.webp',
                'status' => self::STATUS_PUBLISHED,
                'is_featured' => true,
                'published_at' => Carbon::parse('2026-04-01 08:00:00'),
            ]),
            new static([
                'id' => -2,
                'title' => 'When You Should Book an Online Consultation',
                'slug' => 'when-you-should-book-an-online-consultation',
                'excerpt' => 'Online appointments are ideal for follow-ups, mild symptoms, medication guidance, and early medical advice without waiting in line.',
                'content' => "Online consultation is a convenient choice for many common healthcare needs.\n\nYou should consider booking an online consultation when you need a follow-up visit after treatment, advice about mild symptoms, a second opinion, or guidance on medication use. It is also useful when you want to discuss lab results or ask general questions without traveling to the clinic.\n\nHowever, online consultation is not suitable for emergencies. If you have severe chest pain, shortness of breath, heavy bleeding, loss of consciousness, or sudden neurological symptoms, seek emergency care immediately.\n\nTo get the best result from an online appointment, prepare a short summary of your symptoms, list any medications you are taking, and keep your previous test results nearby.\n\nMediConnect makes it easier for patients to connect with doctors quickly so they can receive timely guidance and decide whether an in-person visit is needed.",
                'thumbnail' => 'images/services/service-main.webp',
                'status' => self::STATUS_PUBLISHED,
                'is_featured' => true,
                'published_at' => Carbon::parse('2026-03-28 09:30:00'),
            ]),
            new static([
                'id' => -3,
                'title' => 'How to Prepare for Your First Doctor Appointment',
                'slug' => 'how-to-prepare-for-your-first-doctor-appointment',
                'excerpt' => 'Bring your medical history, current medicines, symptom notes, and key questions so your first visit is clear and efficient.',
                'content' => "A well-prepared appointment helps both the patient and the doctor.\n\nBefore your first visit, write down your main symptoms, when they started, and what makes them better or worse. Bring a list of any medicines, vitamins, or supplements you are currently using. If you have allergies or past surgeries, note them clearly.\n\nIt is also helpful to bring recent test results, health insurance information, and identification documents if required by the clinic.\n\nPrepare a few important questions in advance, such as possible causes of your symptoms, recommended tests, treatment options, and the next steps after the appointment.\n\nDuring the consultation, be honest and specific. The more accurate information you provide, the easier it is for your doctor to assess your condition and recommend the right treatment plan.\n\nMediConnect is designed to make that first appointment smoother by helping patients manage schedules, connect with doctors, and stay informed throughout their care journey.",
                'thumbnail' => 'images/about/hospital-room.webp',
                'status' => self::STATUS_PUBLISHED,
                'is_featured' => true,
                'published_at' => Carbon::parse('2026-03-20 14:00:00'),
            ]),
        ])->map(function (self $blog) {
            $blog->exists = false;
            return $blog;
        });
    }

    public static function publishedWithHardcoded(): Collection
    {
        $hardcoded = static::hardcodedBlogs();

        try {
            $databaseBlogs = static::published()->get();
        } catch (Throwable $e) {
            $databaseBlogs = collect();
        }

        return $databaseBlogs
            ->concat($hardcoded)
            ->unique('slug')
            ->sortByDesc(fn (self $blog) => optional($blog->published_at)->timestamp ?? 0)
            ->values();
    }

    public static function featuredWithHardcoded(int $limit = 3): Collection
    {
        return static::publishedWithHardcoded()
            ->where('is_featured', true)
            ->take($limit)
            ->values();
    }

    public static function findPublishedBySlugWithHardcoded(string $slug): ?self
    {
        return static::publishedWithHardcoded()->firstWhere('slug', $slug);
    }

    public static function paginatePublishedWithHardcoded(int $perPage = 9, ?string $excludeSlug = null): LengthAwarePaginator
    {
        $items = static::publishedWithHardcoded();

        if ($excludeSlug) {
            $items = $items->reject(fn (self $blog) => $blog->slug === $excludeSlug)->values();
        }

        $currentPage = LengthAwarePaginator::resolveCurrentPage();
        $pageItems = $items->slice(($currentPage - 1) * $perPage, $perPage)->values();

        return new LengthAwarePaginator(
            $pageItems,
            $items->count(),
            $perPage,
            $currentPage,
            [
                'path' => LengthAwarePaginator::resolveCurrentPath(),
                'pageName' => 'page',
            ]
        );
    }

    public function getThumbnailUrlAttribute(): string
    {
        if ($this->thumbnail) {
            if (Str::startsWith($this->thumbnail, ['http://', 'https://'])) {
                return $this->thumbnail;
            }

            if (Str::startsWith($this->thumbnail, ['images/', 'uploads/', 'storage/']) || file_exists(public_path($this->thumbnail))) {
                return asset($this->thumbnail);
            }

            return asset('storage/' . ltrim($this->thumbnail, '/'));
        }

        return asset('images/news/news-1.webp');
    }

    public function getExcerptTextAttribute(): string
    {
        return $this->excerpt ?: Str::limit(trim(strip_tags($this->content)), 140);
    }
}
