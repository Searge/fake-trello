<template>
    <div class="container">
        <h1>
            Desks
        </h1>

        <form @submit.prevent="addNewDesk">
            <div class="form-group">
                <input
                    type="text"
                    class="form-control"
                    placeholder="Enter Desk Name"
                    v-model="name"
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

            <button type="submit" class="btn btn-primary">Add</button>
        </form>
        <div class="alert alert-danger mt-3" role="alert" v-if="errored">
            Data request error. <br />
            {{ errors[0] }}
        </div>

        <div class="row">
            <div class="col-lg-4" v-for="desk in desks" v-bind:key="desk.id">
                <div class="card mt-3">
                    <router-link
                        class="card-body"
                        :to="{ name: 'showDesk', params: { deskId: desk.id } }"
                    >
                        <h4 class="card-title">{{ desk.name }}</h4>
                    </router-link>
                    <button
                        type="button"
                        class="btn btn-danger mt-3"
                        @click="deleteDesk(desk.id)"
                    >
                        Delete
                    </button>
                </div>
            </div>
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
import axios from 'axios';

export default {
    data() {
        return {
            desks: [],
            errored: false,
            errors: [],
            loading: true,
            name: null,
        };
    },
    mounted() {
        this.getAllDesks();
    },
    methods: {
        getAllDesks() {
            axios
                .get('/api/v1/desks')
                .then(response => {
                    this.desks = response.data.data;
                })
                .catch(error => {
                    console.log(error);
                    this.errored = true;
                })
                .finally(() => {
                    this.loading = false;
                });
        },
        deleteDesk(id) {
            if (confirm('Did you want to delete this?')) {
                axios
                    .post('/api/v1/desks/' + id, {
                        _method: 'DELETE',
                    })
                    .then(response => {
                        this.desks = [];
                        this.getAllDesks();
                    })
                    .catch(error => {
                        console.log(error);
                        this.errored = true;
                    })
                    .finally(() => {
                        this.loading = false;
                    });
            }
        },
        addNewDesk() {
            this.$v.$touch();
            if (this.$v.$anyError) {
                return;
            }
            axios
                .post('/api/v1/desks', {
                    name: this.name,
                })
                .then(response => {
                    this.name = '';
                    this.desks = [];
                    this.getAllDesks();
                })
                .catch(error => {
                    console.log(error.response);
                    if (error.response.status == 422) {
                        this.errors = [];
                        this.errors.push(error.response.data.errors.name[0]);
                    }
                    if (error.response.status == 500) {
                        this.errors = [];
                        this.errors.push(error.response.data.message);
                    }
                    this.errored = true;
                })
                .finally(() => {
                    this.loading = false;
                });
        },
    },
    validations: {
        name: {
            required,
            maxLength: maxLength(64),
        },
    },
};
</script>
