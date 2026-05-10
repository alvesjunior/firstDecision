import { reactive, ref } from 'vue';

const emptyForm = () => ({
    name: '',
    description: '',
    price: '',
    stock: '',
});

export function useProductForm(initial = {}) {
    const form = reactive({ ...emptyForm(), ...initial });
    const errors = ref({});

    function reset() {
        Object.assign(form, emptyForm());
        errors.value = {};
    }

    function setServerErrors(serverErrors = {}) {
        errors.value = Object.fromEntries(
            Object.entries(serverErrors).map(([key, msgs]) => [key, Array.isArray(msgs) ? msgs[0] : msgs]),
        );
    }

    function validate() {
        const e = {};
        if (!form.name || form.name.trim().length < 2) {
            e.name = 'O nome é obrigatório (mín. 2 caracteres).';
        }
        if (form.price === '' || form.price === null) {
            e.price = 'O preço é obrigatório.';
        } else if (Number.isNaN(Number(form.price)) || Number(form.price) <= 0) {
            e.price = 'O preço deve ser um número maior que zero.';
        }
        if (form.stock === '' || form.stock === null) {
            e.stock = 'O estoque é obrigatório.';
        } else if (!Number.isInteger(Number(form.stock)) || Number(form.stock) < 0) {
            e.stock = 'O estoque deve ser um inteiro não negativo.';
        }
        errors.value = e;
        return Object.keys(e).length === 0;
    }

    function payload() {
        return {
            name: form.name.trim(),
            description: form.description?.trim() || null,
            price: Number(form.price),
            stock: Number(form.stock),
        };
    }

    return { form, errors, reset, validate, payload, setServerErrors };
}
