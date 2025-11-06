<?php

namespace App\Services;

use App\Model\Categoria;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;

class CategoriaService
{
    /**
     * Get categories tree for a specific type
     *
     * @param string $tipo
     * @return array
     */
    public function getCategoriesTree($tipo)
    {
        $categorias = Categoria::where('categoria_tipo', $tipo)
            ->where('parent', 0)
            ->where('ativado', 1)
            ->orderBy('order', 'asc')
            ->get();

        return $this->buildTree($categorias, $tipo);
    }

    /**
     * Build hierarchical tree structure
     *
     * @param $categorias
     * @param string $tipo
     * @return array
     */
    public function buildTree($categorias, $tipo)
    {
        $tree = [];
        
        foreach ($categorias as $categoria) {
            $children = Categoria::where('categoria_tipo', $tipo)
                ->where('parent', $categoria->id)
                ->where('ativado', 1)
                ->orderBy('order', 'asc')
                ->get();

            $tree[] = [
                'categoria' => $categoria,
                'subcategorias' => $children
            ];
        }

        return $tree;
    }

    /**
     * Get category tree in legacy format for views (with 'childreen' key)
     * Returns tree structure with root category and nested children
     *
     * @param string $tipo
     * @return array
     */
    public function getCategoriesTreeLegacy($tipo)
    {
        // Get root category (default = 1)
        $catRaiz = DB::table('categorias')
            ->where('categoria_tipo', $tipo)
            ->where('default', 1)
            ->first();

        if (!$catRaiz) {
            return [
                'id' => 0,
                'titulo' => 'Root',
                'childreen' => []
            ];
        }

        $tree = [
            'id' => $catRaiz->id,
            'titulo' => $catRaiz->titulo,
            'childreen' => []
        ];

        // Get first level children
        $catChildren = DB::table('categorias')
            ->where('categoria_tipo', $tipo)
            ->where('default', 0)
            ->where('parent', $catRaiz->id)
            ->orderBy('order', 'asc')
            ->get();

        $subtree1 = [];

        if ($catChildren) {
            foreach ($catChildren as $value) {
                // Get second level children
                $catSubChildren = DB::table('categorias')
                    ->where('categoria_tipo', $tipo)
                    ->where('default', 0)
                    ->where('parent', $value->id)
                    ->orderBy('order', 'asc')
                    ->get();

                $subtree2 = [];
                if ($catSubChildren && count($catSubChildren) > 0) {
                    foreach ($catSubChildren as $value2) {
                        $subtree2[] = [
                            'id' => $value2->id,
                            'titulo' => $value2->titulo,
                            'ativado' => $value2->ativado,
                            'leaf' => 1,
                        ];
                    }
                }

                $subtree1[] = [
                    'id' => $value->id,
                    'titulo' => $value->titulo,
                    'childreen' => $subtree2,
                    'ativado' => $value->ativado,
                ];
            }
        }

        $tree['childreen'] = $subtree1;

        return $tree;
    }

    /**
     * Get all categories for management interface
     *
     * @param string $tipo
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function getAllCategoriesForManagement($tipo)
    {
        return Categoria::where('categoria_tipo', $tipo)
            ->orderBy('order', 'asc')
            ->orderBy('order', 'asc')
            ->get();
    }

    /**
     * Create a new category
     *
     * @param array $data
     * @return Categoria
     * @throws Exception
     */
    public function createCategory(array $data)
    {
        DB::beginTransaction();
        
        try {
            // Map modern field names to database schema
            $dbData = [
                'titulo' => $data['titulo'] ?? $data['categoria_nome'] ?? null,
                'categoria_tipo' => $data['categoria_tipo'],
                'parent' => $data['parent'] ?? 0,
                'ativado' => $data['ativado'] ?? $data['publicado'] ?? 1,
                'order' => $this->getLastOrder($data['categoria_tipo'], $data['parent'] ?? null) + 1,
                'default' => $data['default'] ?? 0,
            ];

            // Validate required fields
            if (!$dbData['titulo'] || !$dbData['categoria_tipo']) {
                throw new Exception('Title and category type are required');
            }

            // Check name uniqueness
            if ($this->checkNameExists($dbData['titulo'], $dbData['categoria_tipo'], $dbData['parent'])) {
                throw new Exception('Category name already exists at this level');
            }

            // Generate slug
            $dbData['slug'] = $this->generateSlug($dbData['titulo']);

            $categoria = Categoria::create($dbData);

            DB::commit();
            return $categoria;

        } catch (Exception $e) {
            DB::rollback();
            throw $e;
        }
    }

    /**
     * Update category
     *
     * @param int $id
     * @param array $data
     * @return Categoria
     * @throws Exception
     */
    public function updateCategory($id, array $data)
    {
        DB::beginTransaction();
        
        try {
            $categoria = Categoria::findOrFail($id);

            // Map modern field names to database schema
            $dbData = [];
            
            if (isset($data['titulo']) || isset($data['categoria_nome'])) {
                $dbData['titulo'] = $data['titulo'] ?? $data['categoria_nome'];
            }
            
            if (isset($data['categoria_tipo'])) {
                $dbData['categoria_tipo'] = $data['categoria_tipo'];
            }
            
            if (isset($data['parent'])) {
                $dbData['parent'] = $data['parent'];
            }
            
            if (isset($data['ativado']) || isset($data['publicado'])) {
                $dbData['ativado'] = $data['ativado'] ?? $data['publicado'];
            }

            // Check name uniqueness if title changed
            if (isset($dbData['titulo']) && $this->checkNameExists($dbData['titulo'], $categoria->categoria_tipo, $categoria->parent, $id)) {
                throw new Exception('Category name already exists at this level');
            }

            // Update slug if name changed
            if (isset($dbData['titulo']) && $categoria->titulo !== $dbData['titulo']) {
                $dbData['slug'] = $this->generateSlug($dbData['titulo']);
            }

            $categoria->update($dbData);

            DB::commit();
            return $categoria;

        } catch (Exception $e) {
            DB::rollback();
            throw $e;
        }
    }

    /**
     * Delete category and handle cascading
     *
     * @param int $id
     * @return bool
     * @throws Exception
     */
    public function deleteCategory($id)
    {
        DB::beginTransaction();
        
        try {
            $categoria = Categoria::findOrFail($id);

            // Check if has subcategories
            $hasSubcategories = Categoria::where('parent', $id)->exists();
            if ($hasSubcategories) {
                throw new Exception('Cannot delete category with subcategories');
            }

            // Check if has associated content (you may need to add these checks based on your models)
            // Example: $hasArticles = Artigo::where('categoria_id', $id)->exists();

            $categoria->delete();

            DB::commit();
            return true;

        } catch (Exception $e) {
            DB::rollback();
            throw $e;
        }
    }

    /**
     * Update category status
     *
     * @param int $id
     * @param int $status (1 for published, 0 for unpublished)
     * @return Categoria
     * @throws Exception
     */
    public function updateCategoryStatus($id, $status)
    {
        DB::beginTransaction();
        
        try {
            $categoria = Categoria::findOrFail($id);
            
            $categoria->update([
                'ativado' => $status
            ]);

            DB::commit();
            return $categoria;

        } catch (Exception $e) {
            DB::rollback();
            throw $e;
        }
    }

    /**
     * Move category up in order
     *
     * @param int $id
     * @return bool
     * @throws Exception
     */
    public function moveUp($id)
    {
        DB::beginTransaction();
        
        try {
            $categoria = Categoria::findOrFail($id);
            
            $previousCategory = Categoria::where('categoria_tipo', $categoria->categoria_tipo)
                ->where('parent', $categoria->parent)
                ->where('order', '<', $categoria->order)
                ->orderBy('order', 'desc')
                ->first();

            if ($previousCategory) {
                $tempOrder = $categoria->order;
                $categoria->update(['order' => $previousCategory->order]);
                $previousCategory->update(['order' => $tempOrder]);
            }

            DB::commit();
            return true;

        } catch (Exception $e) {
            DB::rollback();
            throw $e;
        }
    }

    /**
     * Move category down in order
     *
     * @param int $id
     * @return bool
     * @throws Exception
     */
    public function moveDown($id)
    {
        DB::beginTransaction();
        
        try {
            $categoria = Categoria::findOrFail($id);
            
            $nextCategory = Categoria::where('categoria_tipo', $categoria->categoria_tipo)
                ->where('parent', $categoria->parent)
                ->where('order', '>', $categoria->order)
                ->orderBy('order', 'asc')
                ->first();

            if ($nextCategory) {
                $tempOrder = $categoria->order;
                $categoria->update(['order' => $nextCategory->order]);
                $nextCategory->update(['order' => $tempOrder]);
            }

            DB::commit();
            return true;

        } catch (Exception $e) {
            DB::rollback();
            throw $e;
        }
    }

    /**
     * Generate slug for all categories
     *
     * @return int Number of categories updated
     */
    public function generateSlugsForAll()
    {
        $categorias = Categoria::all();
        $updated = 0;

        foreach ($categorias as $categoria) {
            $slug = $this->generateSlug($categoria->titulo);
            if ($categoria->slug !== $slug) {
                $categoria->update(['slug' => $slug]);
                $updated++;
            }
        }

        return $updated;
    }

    /**
     * Get category by ID
     *
     * @param int $id
     * @return Categoria
     */
    public function getCategoryById($id)
    {
        return Categoria::findOrFail($id);
    }

    /**
     * Get parent categories (categories with parent = 0)
     * Returns as array with id => titulo for dropdown usage
     *
     * @param string $tipo
     * @return \Illuminate\Support\Collection
     */
    public function getParentCategories($tipo)
    {
        return Categoria::where('categoria_tipo', $tipo)
            ->orderBy('order', 'asc')
            ->pluck('titulo', 'id');
    }

    /**
     * Check if category name exists
     *
     * @param string $nome
     * @param string $tipo
     * @param int|null $pai
     * @param int|null $excludeId
     * @return bool
     */
    private function checkNameExists($nome, $tipo, $pai = null, $excludeId = null)
    {
        $query = Categoria::where('titulo', $nome)
            ->where('categoria_tipo', $tipo)
            ->where('parent', $pai);

        if ($excludeId) {
            $query->where('id', '!=', $excludeId);
        }

        return $query->exists();
    }

    /**
     * Validate category level
     *
     * @param int $nivel
     * @param int|null $pai
     * @return bool
     */
    private function validateLevel($nivel, $pai = null)
    {
        if ($nivel == 1 && $pai !== null) {
            return false; // Level 1 should not have parent
        }

        if ($nivel == 2 && $pai === null) {
            return false; // Level 2 should have parent
        }

        if ($nivel > 2) {
            return false; // Only 2 levels allowed
        }

        return true;
    }

    /**
     * Get last order for category type and parent
     *
     * @param string $tipo
     * @param int|null $pai
     * @return int
     */
    private function getLastOrder($tipo, $pai = null)
    {
        return Categoria::where('categoria_tipo', $tipo)
            ->where('parent', $pai)
            ->max('order') ?? 0;
    }

    /**
     * Generate unique slug
     *
     * @param string $nome
     * @return string
     */
    private function generateSlug($nome)
    {
        $slug = Str::slug($nome);
        $counter = 1;
        $originalSlug = $slug;

        while (Categoria::where('slug', $slug)->exists()) {
            $slug = $originalSlug . '-' . $counter;
            $counter++;
        }

        return $slug;
    }

    /**
     * Validate category data
     *
     * @param array $data
     * @param int|null $excludeId
     * @return \Illuminate\Validation\Validator
     */
    private function validateCategoryData(array $data, $excludeId = null)
    {
        $rules = [
            'titulo' => 'required|string|max:150',
            'categoria_tipo' => 'required|in:artigo,documento,imagem,faq,evento,link',
            'parent' => 'nullable|integer|exists:categorias,id',
            'ativado' => 'boolean',
            'order' => 'nullable|integer'
        ];

        return Validator::make($data, $rules);
    }
}