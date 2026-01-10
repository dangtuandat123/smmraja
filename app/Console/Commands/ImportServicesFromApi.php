<?php

namespace App\Console\Commands;

use App\Models\Category;
use App\Models\Service;
use App\Services\SmmRajaService;
use App\Services\ExchangeRateService;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

class ImportServicesFromApi extends Command
{
    protected $signature = 'services:import 
                            {--markup=30 : Default markup percentage for new services}
                            {--update : Also update existing services}
                            {--dry-run : Preview changes without saving}';
    
    protected $description = 'Import new services from SMM Raja API and auto-create categories';

    public function handle(SmmRajaService $smmService)
    {
        $this->info('🔄 Đang lấy dữ liệu từ SMM Raja API...');
        
        try {
            $apiServices = $smmService->getServices(true);
        } catch (\Exception $e) {
            $this->error('❌ Không thể kết nối API: ' . $e->getMessage());
            Log::error('ImportServicesFromApi failed', ['error' => $e->getMessage()]);
            return 1;
        }

        $exchangeRate = ExchangeRateService::getRate();
        $this->info("💱 Tỷ giá hiện tại: 1 USD = " . number_format($exchangeRate, 0) . " VND");
        $this->info("📦 Tìm thấy " . count($apiServices) . " dịch vụ từ API");
        
        $markup = (float) $this->option('markup');
        $update = $this->option('update');
        $dryRun = $this->option('dry-run');
        
        if ($dryRun) {
            $this->warn("⚠️  DRY RUN MODE - Không lưu thay đổi vào database");
        }
        
        // Get existing services map
        $existingServices = Service::pluck('id', 'api_service_id')->toArray();
        
        // Get existing categories map
        $existingCategories = Category::pluck('id', 'name')->toArray();
        
        $newServicesCount = 0;
        $newCategoriesCount = 0;
        $updatedCount = 0;
        $skippedCount = 0;
        
        $bar = $this->output->createProgressBar(count($apiServices));
        $bar->start();

        foreach ($apiServices as $apiService) {
            $apiServiceId = $apiService['service'];
            
            // Check if service already exists
            $exists = isset($existingServices[$apiServiceId]);
            
            if ($exists && !$update) {
                $skippedCount++;
                $bar->advance();
                continue;
            }
            
            // Get or create category
            $categoryName = $apiService['category'] ?? 'Khác';
            
            if (!isset($existingCategories[$categoryName])) {
                if (!$dryRun) {
                    $category = Category::create([
                        'name' => $categoryName,
                        'slug' => Str::slug($categoryName),
                        'description' => "Danh mục {$categoryName}",
                        'icon' => $this->guessCategoryIcon($categoryName),
                        'is_active' => true,
                        'sort_order' => Category::max('sort_order') + 1,
                    ]);
                    $existingCategories[$categoryName] = $category->id;
                } else {
                    $existingCategories[$categoryName] = 0; // Placeholder for dry run
                }
                
                $newCategoriesCount++;
                $this->newLine();
                $this->info("  📁 Tạo danh mục mới: {$categoryName}");
            }
            
            $categoryId = $existingCategories[$categoryName];
            
            // Prepare service data
            $serviceData = [
                'category_id' => $categoryId,
                'api_service_id' => $apiServiceId,
                'name' => $apiService['name'],
                'api_name' => $apiService['name'],
                'type' => $apiService['type'] ?? 'Default',
                'description' => $apiService['description'] ?? '',
                'api_rate' => (float) $apiService['rate'],
                'markup_percent' => $markup,
                'min' => (int) ($apiService['min'] ?? 1),
                'max' => (int) ($apiService['max'] ?? 1000000),
                'refill' => (bool) ($apiService['refill'] ?? false),
                'cancel' => (bool) ($apiService['cancel'] ?? false),
                'is_active' => true,
                'sort_order' => 0,
            ];
            
            // Calculate price_vnd
            $priceVnd = (int) round($serviceData['api_rate'] * (1 + $markup / 100) * $exchangeRate);
            $serviceData['price_vnd'] = $priceVnd;
            
            if (!$dryRun) {
                if ($exists && $update) {
                    // Update existing service
                    $service = Service::where('api_service_id', $apiServiceId)->first();
                    $service->update($serviceData);
                    $updatedCount++;
                } else {
                    // Create new service
                    Service::create($serviceData);
                    $newServicesCount++;
                }
            } else {
                if ($exists) {
                    $updatedCount++;
                } else {
                    $newServicesCount++;
                }
            }
            
            $bar->advance();
        }

        $bar->finish();
        $this->newLine(2);
        
        // Summary
        $this->info("📊 Kết quả:");
        
        if ($newCategoriesCount > 0) {
            $this->info("   📁 Danh mục mới: {$newCategoriesCount}");
        }
        
        if ($newServicesCount > 0) {
            $this->info("   ✅ Dịch vụ mới: {$newServicesCount}");
        }
        
        if ($updatedCount > 0) {
            $this->info("   🔄 Đã cập nhật: {$updatedCount}");
        }
        
        if ($skippedCount > 0) {
            $this->comment("   ⏭️  Bỏ qua (đã tồn tại): {$skippedCount}");
        }
        
        if ($newServicesCount == 0 && $newCategoriesCount == 0 && $updatedCount == 0) {
            $this->info("✓ Không có dịch vụ mới để import.");
        }
        
        if ($dryRun) {
            $this->warn("⚠️  DRY RUN - Không có thay đổi nào được lưu. Bỏ --dry-run để thực hiện.");
        }
        
        Log::info('ImportServicesFromApi completed', [
            'new_categories' => $newCategoriesCount,
            'new_services' => $newServicesCount,
            'updated' => $updatedCount,
            'skipped' => $skippedCount,
        ]);
        
        return 0;
    }
    
    /**
     * Guess icon for category based on name
     */
    protected function guessCategoryIcon(string $categoryName): string
    {
        $name = strtolower($categoryName);
        
        $iconMap = [
            'instagram' => 'fa-instagram',
            'facebook' => 'fa-facebook',
            'youtube' => 'fa-youtube',
            'tiktok' => 'fa-tiktok',
            'twitter' => 'fa-twitter',
            'telegram' => 'fa-telegram',
            'spotify' => 'fa-spotify',
            'linkedin' => 'fa-linkedin',
            'pinterest' => 'fa-pinterest',
            'snapchat' => 'fa-snapchat',
            'discord' => 'fa-discord',
            'twitch' => 'fa-twitch',
            'soundcloud' => 'fa-soundcloud',
            'reddit' => 'fa-reddit',
            'like' => 'fa-heart',
            'follower' => 'fa-users',
            'view' => 'fa-eye',
            'comment' => 'fa-comments',
            'share' => 'fa-share',
        ];
        
        foreach ($iconMap as $keyword => $icon) {
            if (str_contains($name, $keyword)) {
                return $icon;
            }
        }
        
        return 'fa-folder';
    }
}
