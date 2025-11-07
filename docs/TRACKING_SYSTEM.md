# Universal Tracking System - Documentation

## Overview
The new tracking system uses **Laravel's Polymorphic Relationships** to track access to ANY model in your application.

## Database Structure

### Polymorphic Columns
- `countable_type`: Model class name (e.g., `App\Model\Artigo`)
- `countable_id`: Model ID
- `action_type`: Type of action (`view`, `download`, `click`, etc.)

### Legacy Columns (Kept for backward compatibility)
- `idArtigo`, `idDocumento`, `idFaq`, `idLink`

## Usage Examples

### 1. Basic Tracking (Any Model)

```php
use App\Services\TrackingService;

$tracking = app(TrackingService::class);

// Track article view
$article = Artigo::find(1);
$tracking->track($article, 'view');

// Track document download
$document = Documento::find(5);
$tracking->track($document, 'download');

// Track FAQ view
$faq = Faq::find(3);
$tracking->track($faq, 'view');

// Track link click
$link = Link::find(2);
$tracking->track($link, 'click');

// Track ANY model (e.g., Slide, Parceiro, etc.)
$slide = Slide::find(1);
$tracking->track($slide, 'impression');

$partner = Parceiro::find(4);
$tracking->track($partner, 'click');
```

### 2. Get Counts

```php
$tracking = app(TrackingService::class);
$article = Artigo::find(1);

// Total count (all actions)
$totalViews = $tracking->getCount($article);

// Count by specific action
$views = $tracking->getCount($article, 'view');
$downloads = $tracking->getCount($article, 'download');

// Unique visitors (by IP)
$uniqueVisitors = $tracking->getUniqueCount($article);
$uniqueDownloads = $tracking->getUniqueCount($article, 'download');

// Counts grouped by action
$stats = $tracking->getCountsByAction($article);
// Returns: ['view' => 150, 'download' => 45, 'click' => 12]
```

### 3. Using Relationships in Models

```php
// Get all tracking entries for an article
$article = Artigo::find(1);
$allTracking = $article->contadores; // Polymorphic relationship

// Get only views
$views = $article->contadores()->where('action_type', 'view')->count();

// Get recent activity
$recentViews = $article->contadores()
    ->where('action_type', 'view')
    ->orderBy('created_at', 'desc')
    ->take(10)
    ->get();
```

### 4. Blade Templates (Backward Compatible)

```blade
{{-- Old way (still works) --}}
<td>{{Count($item->contador)}}</td>

{{-- New way (polymorphic) --}}
<td>{{Count($item->contadores)}}</td>

{{-- Count by action type --}}
<td>{{$item->contadores()->where('action_type', 'view')->count()}}</td>
<td>{{$item->contadores()->where('action_type', 'download')->count()}}</td>
```

### 5. Controller Integration

```php
use App\Services\TrackingService;

class PagesController extends Controller
{
    protected $tracking;

    public function __construct(TrackingService $tracking)
    {
        $this->tracking = $tracking;
    }

    public function getArtigoSlug($slug)
    {
        $article = Artigo::where('slug', $slug)->first();
        
        // Track the view
        $this->tracking->track($article, 'view');
        
        return view('article', compact('article'));
    }

    public function downloadDocument($id)
    {
        $document = Documento::find($id);
        
        // Track the download
        $this->tracking->track($document, 'download');
        
        return response()->download($document->file_path);
    }
}
```

### 6. Query Scopes

```php
use App\Model\Contador;

// Get all views
$views = Contador::ofAction('view')->get();

// Get all article tracking
$articleTracking = Contador::ofType('App\Model\Artigo')->get();

// Combined
$articleViews = Contador::ofType('App\Model\Artigo')
    ->ofAction('view')
    ->count();
```

### 7. Analytics Examples

```php
// Most viewed articles (last 30 days)
$popularArticles = Artigo::select('artigos.*', DB::raw('COUNT(contadors.id) as view_count'))
    ->join('contadors', function($join) {
        $join->on('artigos.id', '=', 'contadors.countable_id')
             ->where('contadors.countable_type', 'App\Model\Artigo')
             ->where('contadors.action_type', 'view')
             ->where('contadors.created_at', '>=', now()->subDays(30));
    })
    ->groupBy('artigos.id')
    ->orderBy('view_count', 'desc')
    ->take(10)
    ->get();

// Most downloaded documents
$popularDocs = Documento::select('documentos.*', DB::raw('COUNT(contadors.id) as download_count'))
    ->join('contadors', function($join) {
        $join->on('documentos.id', '=', 'contadors.countable_id')
             ->where('contadors.countable_type', 'App\Model\Documento')
             ->where('contadors.action_type', 'download');
    })
    ->groupBy('documentos.id')
    ->orderBy('download_count', 'desc')
    ->take(10)
    ->get();
```

### 8. Track New Models (Future-Proof)

```php
// Add tracking to ANY new model in the future
// Step 1: Add relationship to model
class Event extends Model
{
    public function contadores()
    {
        return $this->morphMany('App\Model\Contador', 'countable');
    }
}

// Step 2: Use it
$event = Event::find(1);
$tracking->track($event, 'registration');
$tracking->track($event, 'attendance');

// That's it! No migration needed.
```

## Action Types (Suggested)

- `view` - Page/content viewed
- `download` - File downloaded
- `click` - Link clicked
- `share` - Content shared
- `impression` - Banner/slide shown
- `registration` - Event registration
- `attendance` - Event attendance
- `print` - Content printed
- `export` - Data exported

## Benefits

1. **Flexible**: Track ANY model without changing database structure
2. **Scalable**: Add new models without migrations
3. **Backward Compatible**: Old relationships still work
4. **Performance**: Indexed for fast queries
5. **Analytics-Ready**: Easy to build reports and dashboards
6. **Clean Code**: One service for all tracking needs

## Migration Command

```bash
php artisan migrate
```

This will:
1. Add `countable_type`, `countable_id`, `action_type` columns
2. Migrate existing `idArtigo` and `idDocumento` data to new structure
3. Keep old columns for backward compatibility
