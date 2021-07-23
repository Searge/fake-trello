<template>
    <div class="continer">
        <div class="form-group">
            <input
                type="text"
                @blur="saveName"
                v-model="name"
                class="form-control"
                :class="{ 'is-invalid': $v.name.$error }"
            />
            <div class="invalid-feedback" v-if="!$v.name.required">
                Field is required
            </div>
            <div class="invalid-feedback" v-if="!$v.name.maxLength.max">
                Name must have at least
                {{ $v.name.$params.maxLength.max }} letters.
            </div>
        </div>
        <div class="alert alert-danger" role="alert" v-if="errored">
            Data request error.
        </div>
        <div class="text-center">
            <div class="spinner-border" role="status" v-if="loading">
                <span class="sr-only">Loading...</span>
            </div>
        </div>
    </div>
</template>

<script>
import { required, maxLength } from 'vuelidate/lib/validators';
export default {
    props: ['deskId'],
    data() {
        return {
            name: null,
            errorred: false,
            loading: true,
        };
    },
    methods: {
        saveName() {
            this.$v.$touch();
            if (this.$v.$anyError) {
                return;
            }
            axios
                .post('/api/v1/desks/' + this.deskId, {
                    _method: 'PUT',
                    name: this.name,
                })
                .then(response => {
                    // this.name = response.data.data.name;
                })
                .catch(error => {
                    console.log(error);
                    this.errorred = true;
                })
                .finally(() => {
                    this.loading = false;
                });
        },
    },
    mounted() {
        axios
            .get('/api/v1/desks/' + this.deskId)
            .then(response => {
                this.name = response.data.data.name;
            })
            .catch(error => {
                console.log(error);
                this.errorred = true;
            })
            .finally(() => {
                this.loading = false;
            });
    },
    validations: {
        name: {
            required,
            maxLength: maxLength(64),
        },
    },
};
</script>
