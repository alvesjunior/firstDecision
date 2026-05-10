<script setup>
defineProps({
    open: { type: Boolean, default: false },
    title: { type: String, default: '' },
});
defineEmits(['close']);
</script>

<template>
    <Teleport to="body">
        <Transition
            enter-active-class="transition ease-out duration-150"
            enter-from-class="opacity-0"
            enter-to-class="opacity-100"
            leave-active-class="transition ease-in duration-100"
            leave-from-class="opacity-100"
            leave-to-class="opacity-0"
        >
            <div
                v-if="open"
                class="fixed inset-0 z-50 flex items-center justify-center bg-slate-900/50 p-4"
                @click.self="$emit('close')"
            >
                <div class="bg-white rounded-lg shadow-xl w-full max-w-md">
                    <header class="px-5 py-4 border-b border-slate-200">
                        <h3 class="text-lg font-semibold text-slate-900">{{ title }}</h3>
                    </header>
                    <div class="px-5 py-4">
                        <slot />
                    </div>
                    <footer class="px-5 py-3 border-t border-slate-200 flex justify-end gap-2">
                        <slot name="footer" />
                    </footer>
                </div>
            </div>
        </Transition>
    </Teleport>
</template>
