<script setup>
const props = defineProps({
    meta: { type: Object, default: null },
});
const emit = defineEmits(['change']);

function goTo(page) {
    if (!props.meta) return;
    if (page < 1 || page > props.meta.last_page) return;
    if (page === props.meta.current_page) return;
    emit('change', page);
}
</script>

<template>
    <div v-if="meta && meta.total > 0" class="flex items-center justify-between text-sm text-slate-600">
        <p>
            Mostrando <span class="font-medium">{{ meta.from }}</span> a
            <span class="font-medium">{{ meta.to }}</span> de
            <span class="font-medium">{{ meta.total }}</span> registros
        </p>
        <div class="flex items-center gap-1">
            <button
                type="button"
                class="rounded border border-slate-300 px-3 py-1 disabled:opacity-50"
                :disabled="meta.current_page <= 1"
                @click="goTo(meta.current_page - 1)"
            >
                Anterior
            </button>
            <span class="px-3 py-1">{{ meta.current_page }} / {{ meta.last_page }}</span>
            <button
                type="button"
                class="rounded border border-slate-300 px-3 py-1 disabled:opacity-50"
                :disabled="meta.current_page >= meta.last_page"
                @click="goTo(meta.current_page + 1)"
            >
                Próxima
            </button>
        </div>
    </div>
</template>
