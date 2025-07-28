<script setup lang="ts">
import { Link } from '@inertiajs/vue3';

defineProps({
    prev: String,
    next: String,
    from: Number,
    to: Number,
    total: Number,
});
</script>

<template>
    <div class="pagination">
        <!-- Información de rango -->
        <div>
            <p>
                Showing {{ from }} {{ to }} from {{ total }} results
            </p>
        </div>
        
        <!-- Botones de navegación -->
        <div>
            <Link
                v-if="prev" 
                :href="prev"
                class="pagination-link"
                preserve-state
            >
                &laquo; Previous
            </Link>
            <span v-else class="pagination-link disabled">
                &laquo; Previous
            </span>
            
            <Link
                v-if="next" 
                :href="next"
                class="pagination-link"
                preserve-state
            >
                Next &raquo;
            </Link>
            <span v-else class="pagination-link disabled">
                Next &raquo;
            </span>
        </div>
    </div>
</template>

<style>
.pagination {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-top: 1rem;
}

.pagination-link {
    padding: 0.5rem 1rem;
    margin: 0 0.25rem;
    text-decoration: none;
    color: #333;
    border: 1px solid #ddd;
    border-radius: 4px;
}

.pagination-link:hover {
    background-color: #eee;
}

.pagination-link.disabled {
    color: #999;
    cursor: not-allowed;
    background-color: #f5f5f5;
}
</style>