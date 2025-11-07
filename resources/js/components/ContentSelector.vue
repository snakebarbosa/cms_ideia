<template>
    <div class="content-selector">
        <div class="form-group">
            <label :for="'search-' + type">{{ label }}</label>
            <input
                :id="'search-' + type"
                v-model="searchQuery"
                type="text"
                class="form-control"
                :placeholder="placeholder"
                @input="onSearchInput"
                @keyup="onSearchKeyup"
                @focus="showDropdown = true"
            />
            <small class="text-muted">{{ hint }}</small>
        </div>

        <!-- Dropdown Results -->
        <div v-if="showDropdown && (filteredItems.length > 0 || isLoading)" class="search-dropdown">
            <div v-if="isLoading" class="dropdown-item loading">
                <i class="material-icons rotating">refresh</i> Carregando...
            </div>
            <div v-else-if="filteredItems.length === 0" class="dropdown-item no-results">
                Nenhum resultado encontrado
            </div>
            <div
                v-else
                v-for="item in filteredItems"
                :key="item.id"
                class="dropdown-item"
                :class="{ selected: selectedId == item.id }"
                @click="selectItem(item)"
            >
                <i class="material-icons">{{ type === 'artigo' ? 'article' : 'description' }}</i>
                <div class="item-details">
                    <span class="item-name">{{ item.titulo_pt || item.name }}</span>
                    <small v-if="item.titulo_en" class="item-name-en">EN: {{ item.titulo_en }}</small>
                </div>
                <span v-if="selectedId == item.id" class="check-icon">
                    <i class="material-icons">check_circle</i>
                </span>
            </div>
        </div>

        <!-- Selected Item Display -->
        <div v-if="selectedItem" class="selected-item-display">
            <div class="selected-item-card">
                <i class="material-icons">{{ type === 'artigo' ? 'article' : 'description' }}</i>
                <div class="item-info">
                    <strong>Selecionado:</strong>
                    <span>{{ selectedItem.titulo_pt || selectedItem.name }}</span>
                    <small v-if="selectedItem.titulo_en">EN: {{ selectedItem.titulo_en }}</small>
                </div>
                <button type="button" class="btn-clear" @click="clearSelection">
                    <i class="material-icons">close</i>
                </button>
            </div>
        </div>
    </div>
</template>

<script>
export default {
    name: 'ContentSelector',
    props: {
        type: {
            type: String,
            required: true,
            validator: (value) => ['artigo', 'documento'].includes(value)
        },
        label: {
            type: String,
            required: true
        },
        placeholder: {
            type: String,
            default: 'Digite para pesquisar...'
        },
        hint: {
            type: String,
            default: ''
        },
        initialId: {
            type: [String, Number],
            default: null
        },
        initialName: {
            type: String,
            default: null
        },
        initialUrl: {
            type: String,
            default: null
        },
        hiddenFieldId: {
            type: String,
            default: null
        }
    },
    data() {
        return {
            searchQuery: '',
            items: [],
            filteredItems: [],
            selectedId: null,
            selectedItem: null,
            showDropdown: false,
            isLoading: false,
            debounceTimer: null,
            urlFieldId: null
        };
    },
    mounted() {
        // Load initial data first
        this.loadItems().then(() => {
            // Set initial selection if provided (after items are loaded)
            if (this.initialId) {
                const item = this.items.find(i => i.id == this.initialId);
                if (item) {
                    this.selectedId = item.id;
                    this.selectedItem = item;
                    this.searchQuery = item.titulo_pt || item.name;
                }
            }
        });

        // Close dropdown when clicking outside
        document.addEventListener('click', this.handleClickOutside);
    },
    beforeDestroy() {
        document.removeEventListener('click', this.handleClickOutside);
    },
    methods: {
        loadItems() {
            this.isLoading = true;
            
            const baseUrl = window.appBaseUrl || window.location.origin;
            const endpoint = this.type === 'artigo' 
                ? baseUrl + '/Administrator/Artigos/json-list'
                : baseUrl + '/Administrator/Documentacao/json-list';
            
            return fetch(endpoint)
                .then(response => response.json())
                .then(data => {
                    this.items = data.map(item => ({
                        id: item.id,
                        name: item.alias || item.nome,
                        keyword: item.keyword || '',
                        titulo_pt: item.titulo_pt || '',
                        titulo_en: item.titulo_en || '',
                        url: item.url || `/${this.type}/${item.id}`
                    }));
                    
                    // TEST: Show sample item structure
                    if (this.items.length > 0) {
                        // console.log('=== COMPONENT TEST ===');
                        // console.log('Type:', this.type);
                        // console.log('Total items loaded:', this.items.length);
                        // console.log('Sample item:', this.items[0]);
                        // console.log('Searchable fields for first item:', {
                        //     name: this.items[0].name,
                        //     keyword: this.items[0].keyword,
                        //     titulo_pt: this.items[0].titulo_pt,
                        //     titulo_en: this.items[0].titulo_en
                        // });
                    }
                    
                    this.filteredItems = this.items;
                    this.isLoading = false;
                })
                .catch(error => {
                    console.error(`Error loading ${this.type}s:`, error);
                    this.items = [];
                    this.filteredItems = [];
                    this.isLoading = false;
                });
        },
        onSearchInput() {
            clearTimeout(this.debounceTimer);
            this.debounceTimer = setTimeout(() => {
                this.filterItems();
            }, 300);
        },
        onSearchKeyup() {
            // Immediate search on keyup for responsive feedback
            this.filterItems();
        },
        filterItems() {
            const query = this.searchQuery.toLowerCase().trim();
            
            if (!query) {
                this.filteredItems = this.items;
                this.showDropdown = true;
                return;
            }

            // TEST: Log search details
            // console.log('=== SEARCH TEST ===');
            // console.log('Query:', query);
            
            // Search in alias/nome, PT title, EN title, and keyword
            this.filteredItems = this.items.filter(item => {
                const searchFields = [
                    item.name,           // This is alias (artigos) or nome (documentos)
                    item.titulo_pt,      // PT title
                    item.titulo_en       // EN title
                ];
                
                // Add keyword field if it exists (for artigos)
                if (item.keyword) {
                    searchFields.push(item.keyword);
                }
                
                const normalizedFields = searchFields.map(field => (field || '').toLowerCase());
                const matches = normalizedFields.some(field => field.includes(query));
                
                // TEST: Log matching items
                if (matches) {
                    // console.log('MATCH FOUND:', {
                    //     id: item.id,
                    //     name: item.name,
                    //     keyword: item.keyword,
                    //     titulo_pt: item.titulo_pt,
                    //     titulo_en: item.titulo_en,
                    //     matched_in: normalizedFields.filter(field => field.includes(query))
                    // });
                }
                
                return matches;
            });
            
            // console.log('Results:', this.filteredItems.length, 'items found');
            this.showDropdown = true;
        },
        selectItem(item) {
            this.selectedId = item.id;
            this.selectedItem = item;
            this.searchQuery = item.titulo_pt || item.name;
            this.showDropdown = false;
            
            // Update hidden field if specified
            if (this.hiddenFieldId) {
                const hiddenField = document.getElementById(this.hiddenFieldId);
                if (hiddenField) {
                    hiddenField.value = item.id;
                }
            }
            
            // Update URL field if exists
            this.updateUrlField(item.url);
            
            // Emit event to parent
            this.$emit('selected', {
                id: item.id,
                name: item.name,
                titulo_pt: item.titulo_pt,
                titulo_en: item.titulo_en,
                url: item.url
            });
        },
        updateUrlField(url) {
            // Try to find and update common URL field IDs
            const urlFieldIds = ['final-url', 'url', 'link'];
            
            for (const fieldId of urlFieldIds) {
                const field = document.getElementById(fieldId);
                if (field) {
                    field.value = url;
                    // Trigger change event for any listeners
                    field.dispatchEvent(new Event('input', { bubbles: true }));
                    break;
                }
            }
        },
        clearSelection() {
            this.selectedId = null;
            this.selectedItem = null;
            this.searchQuery = '';
            this.filteredItems = this.items;
            
            // Clear hidden field if specified
            if (this.hiddenFieldId) {
                const hiddenField = document.getElementById(this.hiddenFieldId);
                if (hiddenField) {
                    hiddenField.value = '';
                }
            }
            
            // Clear URL field
            this.updateUrlField('');
            
            // Emit clear event
            this.$emit('cleared');
        },
        handleClickOutside(event) {
            if (!this.$el.contains(event.target)) {
                this.showDropdown = false;
            }
        }
    }
};
</script>

<style scoped>
.content-selector {
    position: relative;
    margin-bottom: 15px;
}

.search-dropdown {
    position: absolute;
    top: 100%;
    left: 0;
    right: 0;
    max-height: 300px;
    overflow-y: auto;
    background: white;
    border: 1px solid #ddd;
    border-radius: 4px;
    box-shadow: 0 4px 12px rgba(0,0,0,0.15);
    z-index: 9999;
    margin-top: 5px;
}

.dropdown-item {
    padding: 12px 15px;
    cursor: pointer;
    display: flex;
    align-items: center;
    gap: 10px;
    transition: background-color 0.2s;
    border-bottom: 1px solid #f0f0f0;
}

.dropdown-item:last-child {
    border-bottom: none;
}

.dropdown-item:hover {
    background-color: #f5f5f5;
}

.dropdown-item.selected {
    background-color: #e3f2fd;
    color: #1976d2;
}

.dropdown-item.loading,
.dropdown-item.no-results {
    cursor: default;
    color: #999;
    justify-content: center;
}

.dropdown-item .material-icons {
    font-size: 20px;
    color: #666;
}

.dropdown-item.selected .material-icons {
    color: #1976d2;
}

.item-details {
    flex: 1;
    display: flex;
    flex-direction: column;
    gap: 2px;
    overflow: hidden;
}

.item-name {
    overflow: hidden;
    text-overflow: ellipsis;
    white-space: nowrap;
    font-weight: 500;
}

.item-name-en {
    font-size: 11px;
    color: #999;
    font-style: italic;
    overflow: hidden;
    text-overflow: ellipsis;
    white-space: nowrap;
}

.dropdown-item.selected .item-name-en {
    color: #1976d2;
    opacity: 0.7;
}

.check-icon {
    margin-left: auto;
}

.check-icon .material-icons {
    color: #4caf50;
    font-size: 18px;
}

.selected-item-display {
    margin-top: 10px;
}

.selected-item-card {
    display: flex;
    align-items: center;
    gap: 10px;
    padding: 12px;
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    color: white;
    border-radius: 8px;
    box-shadow: 0 2px 8px rgba(102, 126, 234, 0.3);
}

.selected-item-card .material-icons {
    font-size: 24px;
}

.item-info {
    flex: 1;
    display: flex;
    flex-direction: column;
    gap: 2px;
}

.item-info strong {
    font-size: 11px;
    opacity: 0.9;
    text-transform: uppercase;
    letter-spacing: 0.5px;
}

.item-info span {
    font-size: 14px;
}

.item-info small {
    font-size: 11px;
    opacity: 0.8;
    font-style: italic;
}

.btn-clear {
    background: rgba(255, 255, 255, 0.2);
    border: none;
    border-radius: 50%;
    width: 30px;
    height: 30px;
    display: flex;
    align-items: center;
    justify-content: center;
    cursor: pointer;
    transition: all 0.2s;
}

.btn-clear:hover {
    background: rgba(255, 255, 255, 0.3);
    transform: rotate(90deg);
}

.btn-clear .material-icons {
    font-size: 18px;
    color: white;
}

.rotating {
    animation: rotate 1s linear infinite;
}

@keyframes rotate {
    from { transform: rotate(0deg); }
    to { transform: rotate(360deg); }
}

/* Custom scrollbar */
.search-dropdown::-webkit-scrollbar {
    width: 8px;
}

.search-dropdown::-webkit-scrollbar-track {
    background: #f1f1f1;
}

.search-dropdown::-webkit-scrollbar-thumb {
    background: #888;
    border-radius: 4px;
}

.search-dropdown::-webkit-scrollbar-thumb:hover {
    background: #555;
}
</style>
