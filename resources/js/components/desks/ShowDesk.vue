<template>
    <div class="continer">
        <h1>{{ name }}</h1>
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

        <form @submit.prevent="addNewDeskList">
            <div class="form-group">
                <input
                    type="text"
                    class="form-control"
                    placeholder="Enter List Name"
                    v-model="desk_list_name"
                    :class="{ 'is-invalid': $v.desk_list_name.$error }"
                />
                <div
                    class="invalid-feedback"
                    v-if="!$v.desk_list_name.required"
                >
                    Field is required
                </div>
                <div
                    class="invalid-feedback"
                    v-if="!$v.desk_list_name.maxLength.max"
                >
                    Name must have at least
                    {{ $v.desk_list_name.$params.maxLength.max }} letters.
                </div>
            </div>

            <button type="submit" class="btn btn-primary">Add List</button>
        </form>

        <div class="alert alert-danger mt-3" role="alert" v-if="errored">
            Data request error.
        </div>

        <div class="row">
            <div
                class="col-lg-4"
                v-for="desk_list in desk_lists"
                v-bind:key="desk_list.id"
            >
                <div class="card mt-3">
                    <a href="#" class="card-body">
                        <h4 class="card-title">{{ desk_list.name }}</h4>
                    </a>
                    <router-link
                        class="card-body"
                        :to="{
                            name: 'showDesk',
                            params: { deskId: desk_list.id },
                        }"
                    >
                    </router-link>
                    <button
                        type="button"
                        class="btn btn-danger mt-3"
                        @click="deleteDeskList(desk_list.id)"
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
export default {
    props: ['deskId'],
    data() {
        return {
            name: null,
            errored: false,
            loading: true,
            desk_lists: true,
            desk_list_name: null,
        };
    },
    methods: {
        getDeskLists() {
            axios
                .get('/api/v1/desk-lists/', {
                    params: { desk_id: this.deskId },
                })
                .then(response => {
                    this.desk_lists = response.data.data;
                })
                .catch(error => {
                    console.log(error);
                    this.errored = true;
                })
                .finally(() => {
                    this.loading = false;
                });
        },
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
                    this.errored = true;
                })
                .finally(() => {
                    this.loading = false;
                });
        },
        addNewDeskList() {
            this.$v.$touch();
            if (this.$v.$anyError) {
                return;
            }
            axios
                .post('/api/v1/desk-lists', {
                    name: this.desk_list_name,
                    desk_id: this.deskId,
                })
                .then(response => {
                    this.desk_list_name = '';
                    this.desk_lists = [];
                    this.getDeskLists();
                })
                .catch(error => {
                    console.log(error.response);

                    this.errored = true;
                })
                .finally(() => {
                    this.loading = false;
                });
        },
        deleteDeskList(id) {
            axios
                .post('/api/v1/desk-lists/' + id, {
                    _method: 'DELETE',
                })
                .then(response => {
                    this.desk_lists = [];
                    this.getDeskLists();
                })
                .catch(error => {
                    console.log(error.response);
                    this.errored = true;
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
                this.errored = true;
            })
            .finally(() => {
                this.loading = false;
            });
        this.getDeskLists();
    },
    validations: {
        name: {
            required,
            maxLength: maxLength(64),
        },
        desk_list_name: {
            required,
            maxLength: maxLength(64),
        },
    },
};
</script>
