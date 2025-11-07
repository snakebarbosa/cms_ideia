<template>
    <div class="link-selector">
        <div class="form-group">
            <div class="form-line">
                <label for="link-type">{{ typeLabel }}</label>
                <select id="link-type" v-model="linkType" class="form-control" @change="onLinkTypeChange">
                    <option value="external">Link Externo</option>
                    <option value="artigo">Artigo</option>
                    <option value="documento">Documento</option>
                    <option value="custom">Link Personalizado</option>
                </select>
            </div>
        </div>

        <!-- External Link -->
        <div v-show="linkType === 'external'" class="form-group">
            <div class="form-line">
                <label for="external-url">URL Externa:</label>
                <input 
                    type="text" 
                    id="external-url" 
                    v-model="externalUrl" 
                    class="form-control" 
                    placeholder="https://exemplo.com"
                    @input="updateFinalUrl"
                >
                <small class="text-muted">Insira a URL completa (ex: https://google.com)</small>
            </div>
        </div>

        <!-- Artigo Link -->
        <div v-show="linkType === 'artigo'" class="form-group">
            <content-selector
                type="artigo"
                label="Pesquisar Artigo:"
                placeholder="Digite para pesquisar artigos..."
                hint="Digite o título do artigo em PT ou EN para pesquisar"
                :initial-id="initialArtigoId"
                @selected="onArtigoSelected"
                @cleared="onArtigoCleared"
            ></content-selector>
        </div>

        <!-- Documento Link -->
        <div v-show="linkType === 'documento'" class="form-group">
            <content-selector
                type="documento"
                label="Pesquisar Documento:"
                placeholder="Digite para pesquisar documentos..."
                hint="Digite o nome do documento em PT ou EN para pesquisar"
                :initial-id="initialDocumentoId"
                @selected="onDocumentoSelected"
                @cleared="onDocumentoCleared"
            ></content-selector>
        </div>

        <!-- Custom Link -->
        <div v-show="linkType === 'custom'" class="form-group">
            <div class="form-line">
                <label for="custom-url">Link Personalizado:</label>
                <input 
                    type="text" 
                    id="custom-url" 
                    v-model="customUrl" 
                    class="form-control" 
                    placeholder="/pagina-custom"
                    @input="updateFinalUrl"
                >
                <small class="text-muted">Insira o caminho (ex: /faq, /contactos)</small>
            </div>
        </div>

        <!-- Final URL Display -->
        <div class="form-group">
            <div class="form-line">
                <label for="final-url">URL Final (gerado automaticamente):</label>
                <input 
                    type="text" 
                    id="final-url" 
                    :name="urlFieldName"
                    v-model="finalUrl" 
                    class="form-control" 
                    readonly 
                    style="background-color: #f0f0f0;"
                >
            </div>
        </div>
    </div>
</template>

<script>
import ContentSelector from './ContentSelector.vue';

export default {
    name: 'LinkSelector',
    components: {
        ContentSelector
    },
    props: {
        typeLabel: {
            type: String,
            default: 'Tipo de Link:'
        },
        urlFieldName: {
            type: String,
            default: 'url'
        },
        initialUrl: {
            type: String,
            default: ''
        }
    },
    data() {
        return {
            linkType: 'external',
            externalUrl: '',
            customUrl: '',
            artigoId: null,
            artigoUrl: '',
            documentoId: null,
            documentoUrl: '',
            finalUrl: '',
            initialArtigoId: null,
            initialDocumentoId: null
        };
    },
    mounted() {
        // Parse initial URL to determine link type
        this.parseInitialUrl();
    },
    methods: {
        parseInitialUrl() {
            const url = this.initialUrl;
            
            if (!url) {
                this.linkType = 'external';
                this.updateFinalUrl();
                return;
            }

            // Check for external URL
            if (url.indexOf('http') === 0 || url.indexOf('//') === 0) {
                this.linkType = 'external';
                this.externalUrl = url;
            }
            // Check for artigo URL
            else if (url.indexOf('/artigo/') !== -1) {
                this.linkType = 'artigo';
                const parts = url.split('/artigo/');
                if (parts.length > 1) {
                    this.initialArtigoId = parseInt(parts[1]);
                    this.artigoId = this.initialArtigoId;
                }
            }
            // Check for documento URL
            else if (url.indexOf('/documento/') !== -1) {
                this.linkType = 'documento';
                const parts = url.split('/documento/');
                if (parts.length > 1) {
                    this.initialDocumentoId = parseInt(parts[1]);
                    this.documentoId = this.initialDocumentoId;
                }
            }
            // Otherwise it's a custom URL
            else {
                this.linkType = 'custom';
                this.customUrl = url;
            }

            this.finalUrl = url;
        },
        onLinkTypeChange() {
            // Clear final URL when changing type
            this.updateFinalUrl();
        },
        onArtigoSelected(item) {
            this.artigoId = item.id;
            this.artigoUrl = item.url;
            this.updateFinalUrl();
        },
        onArtigoCleared() {
            this.artigoId = null;
            this.artigoUrl = '';
            this.updateFinalUrl();
        },
        onDocumentoSelected(item) {
            this.documentoId = item.id;
            this.documentoUrl = item.url;
            this.updateFinalUrl();
        },
        onDocumentoCleared() {
            this.documentoId = null;
            this.documentoUrl = '';
            this.updateFinalUrl();
        },
        updateFinalUrl() {
            let url = '';

            switch (this.linkType) {
                case 'external':
                    url = this.externalUrl;
                    break;
                case 'artigo':
                    url = this.artigoUrl || (this.artigoId ? `/artigo/${this.artigoId}` : '');
                    break;
                case 'documento':
                    url = this.documentoUrl || (this.documentoId ? `/documento/${this.documentoId}` : '');
                    break;
                case 'custom':
                    url = this.customUrl;
                    break;
            }

            this.finalUrl = url;

            // Emit event to parent component
            this.$emit('url-changed', {
                type: this.linkType,
                url: this.finalUrl
            });
        }
    }
};
</script>

<style scoped>
.link-selector {
    margin-bottom: 15px;
}

.form-group {
    margin-bottom: 15px;
}

.form-line {
    position: relative;
}

.form-line label {
    font-weight: 500;
    color: #555;
    margin-bottom: 8px;
    display: block;
}

.form-control {
    width: 100%;
    padding: 8px 12px;
    border: 1px solid #ddd;
    border-radius: 4px;
    font-size: 14px;
    transition: border-color 0.3s;
}

.form-control:focus {
    outline: none;
    border-color: #00bcd4;
    box-shadow: 0 0 0 2px rgba(0, 188, 212, 0.1);
}

.form-control[readonly] {
    cursor: not-allowed;
}

.text-muted {
    display: block;
    margin-top: 5px;
    font-size: 12px;
    color: #999;
}

/* Animation for field transitions */
.form-group {
    animation: fadeIn 0.3s ease-in-out;
}

@keyframes fadeIn {
    from {
        opacity: 0;
        transform: translateY(-10px);
    }
    to {
        opacity: 1;
        transform: translateY(0);
    }
}
</style>
