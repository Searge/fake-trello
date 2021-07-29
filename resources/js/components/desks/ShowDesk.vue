<template>
    <div class="continer">
        <h1>{{ name }}</h1>
        <!-- Desk Title Edit -->
        <div class="form-group">
            <input
                v-model="name"
                @blur="saveName"
                class="form-control"
                :class="{ 'is-invalid': $v.name.$error }"
                type="text"
            />

            <!-- Warnings -->
            <div class="invalid-feedback" v-if="!$v.name.required">
                Field is required
            </div>
            <div class="invalid-feedback" v-if="!$v.name.maxLength.max">
                Name must have at least
                {{ $v.name.$params.maxLength.max }} letters.
            </div>
        </div>

        <!-- Add New List Form-->
        <form @submit.prevent="addNewDeskList">
            <div class="form-group">
                <div class="input-group mb-3">
                    <input
                        v-model="desk_list_name"
                        :class="{ 'is-invalid': $v.desk_list_name.$error }"
                        class="form-control"
                        placeholder="Enter List Name"
                        type="text"
                    />
                    <button type="submit" class="btn btn-primary">
                        Add List
                    </button>
                    <!-- Warnings -->
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
            </div>
        </form>

        <!-- Data Request Error -->
        <div class="alert alert-danger mt-3" role="alert" v-if="errored">
            Data request error.
            <br />
            {{ errors[0] }}
        </div>

        <!-- List Item Template -->
        <div class="row">
            <div
                class="col-lg-4"
                v-for="desk_list in desk_lists"
                v-bind:key="desk_list.id"
            >
                <div class="card mt-3">
                    <!-- Card Items -->
                    <div class="card-body">
                        <!-- List Title Edit -->
                        <form
                            @submit.prevent="
                                updateDeskList(desk_list.id, desk_list.name)
                            "
                            v-if="desk_list_input_id == desk_list.id"
                            class="input-group mb-3"
                        >
                            <input
                                v-model="desk_list.name"
                                class="form-control"
                                type="text"
                                placeholder="Enter List Name"
                            />
                            <div class="input-group-append">
                                <button
                                    @click="desk_list_input_id = null"
                                    class="btn btn-outline-secondary pl-2 pr-2"
                                    aria-label="Close"
                                    type="button"
                                >
                                    <span class="close" aria-hidden="true">
                                        &nbsp;&times;&nbsp;
                                    </span>
                                </button>
                            </div>
                        </form>

                        <!-- List Title -->
                        <h4
                            class="card-title d-flex justify-content-between align-items-center edit"
                            @click="desk_list_input_id = desk_list.id"
                            v-else
                        >
                            {{ desk_list.name }}

                            <!-- Icon -->
                            <svg
                                class="edit__icon"
                                role="img"
                                xmlns="http://www.w3.org/2000/svg"
                                viewBox="0 0 512 512"
                            >
                                <path
                                    fill="currentColor"
                                    d="M497.9 142.1l-46.1 46.1c-4.7 4.7-12.3 4.7-17 0l-111-111c-4.7-4.7-4.7-12.3 0-17l46.1-46.1c18.7-18.7 49.1-18.7 67.9 0l60.1 60.1c18.8 18.7 18.8 49.1 0 67.9zM284.2 99.8L21.6 362.4.4 483.9c-2.9 16.4 11.4 30.6 27.8 27.8l121.5-21.3 262.6-262.6c4.7-4.7 4.7-12.3 0-17l-111-111c-4.8-4.7-12.4-4.7-17.1 0zM124.1 339.9c-5.5-5.5-5.5-14.3 0-19.8l154-154c5.5-5.5 14.3-5.5 19.8 0s5.5 14.3 0 19.8l-154 154c-5.5 5.5-14.3 5.5-19.8 0zM88 424h48v36.3l-64.5 11.3-31.1-31.1L51.7 376H88v48z"
                                ></path>
                            </svg>
                        </h4>
                        <!-- List Delete -->
                        <button
                            @click="deleteDeskList(desk_list.id)"
                            class="btn btn-danger mt-3"
                            type="button"
                        >
                            Delete List
                        </button>
                        <!-- Cards Items -->
                        <div
                            v-for="card in desk_list.cards"
                            v-bind:key="card.id"
                            class="card mt-3 bg-light"
                        >
                            <!-- NOTE: added `v-bind` to `:key` -->
                            <div class="card-body">
                                <h4
                                    class="card-title d-flex justify-content-between align-items-center edit"
                                >
                                    {{ card.name }}
                                    <!-- Icon -->
                                    <!-- <svg
                                        class="edit__icon"
                                        role="img"
                                        xmlns="http://www.w3.org/2000/svg"
                                        viewBox="0 0 512 512"
                                    >
                                        <path
                                            fill="currentColor"
                                            d="M497.9 142.1l-46.1 46.1c-4.7 4.7-12.3 4.7-17 0l-111-111c-4.7-4.7-4.7-12.3 0-17l46.1-46.1c18.7-18.7 49.1-18.7 67.9 0l60.1 60.1c18.8 18.7 18.8 49.1 0 67.9zM284.2 99.8L21.6 362.4.4 483.9c-2.9 16.4 11.4 30.6 27.8 27.8l121.5-21.3 262.6-262.6c4.7-4.7 4.7-12.3 0-17l-111-111c-4.8-4.7-12.4-4.7-17.1 0zM124.1 339.9c-5.5-5.5-5.5-14.3 0-19.8l154-154c5.5-5.5 14.3-5.5 19.8 0s5.5 14.3 0 19.8l-154 154c-5.5 5.5-14.3 5.5-19.8 0zM88 424h48v36.3l-64.5 11.3-31.1-31.1L51.7 376H88v48z"
                                        ></path>
                                    </svg> -->
                                </h4>
                                <button
                                    class="btn btn-secondary mt-3"
                                    type="button"
                                >
                                    Delete Card
                                </button>
                            </div>
                        </div>
                        <!-- Card Title Edit -->
                        <form
                            @submit.prevent="addNewCard(desk_list.id)"
                            class="mt-3"
                        >
                            <!-- TODO: Inspect this statement:
                            `$v.card_names.$each[desk_list.id]`
                            -->
                            <input
                                v-model="card_names[desk_list.id]"
                                :class="{
                                    'is-invalid':
                                        $v.card_names.$each[desk_list.id]
                                            .$error,
                                }"
                                class="form-control"
                                placeholder="Enter Card Name"
                                type="text"
                            />
                            <!-- Warnings -->
                            <div
                                class="invalid-feedback"
                                v-if="
                                    !$v.card_names.$each[desk_list.id].required
                                "
                            >
                                Field is required
                            </div>
                            <div
                                class="invalid-feedback"
                                v-if="
                                    !$v.card_names.$each[desk_list.id].maxLength
                                        .max
                                "
                            >
                                Name must have at least
                                {{
                                    $v.card_names.$each[desk_list.id].$params
                                        .maxLength.max
                                }}
                                letters.
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>

        <!-- Spinner -->
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
            errors: [],
            loading: true,
            desk_lists: true,
            desk_list_name: null,
            desk_list_input_id: null,
            card_names: [],
        };
    },
    methods: {
        addNewCard(desk_list_id) {
            this.$v.card_names.$each[desk_list_id].$touch();
            if (this.$v.card_names.$each[desk_list_id].$anyError) {
                return;
            }
            axios
                .post('/api/v1/cards', {
                    name: this.card_names[desk_list_id],
                    desk_list_id,
                })
                .then(response => {
                    this.card_names[desk_list_id] = '';
                    // TODO: and this
                    // this.$v.reset();
                    this.getDeskLists();
                })
                .catch(error => {
                    console.log(error.response);
                    this.errored = true;
                });
        },
        getDeskLists() {
            axios
                .get('/api/v1/desk-lists/', {
                    params: { desk_id: this.deskId },
                })
                .then(response => {
                    this.desk_lists = response.data.data;
                    // Make list with cards names
                    this.desk_lists.forEach(el => {
                        this.card_names[el.id] = '';
                    });
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
            this.$v.name.$touch();
            if (this.$v.name.$anyError) {
                return;
            }
            axios
                .post('/api/v1/desks/' + this.deskId, {
                    _method: 'PUT',
                    name: this.name,
                })
                .then(response => {
                    console.log(this.deskId, response);
                    // this.name = response.data.data.name;
                })
                .catch(error => {
                    console.log(error.response);
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
        updateDeskList(id, name) {
            axios
                .post('/api/v1/desk-lists/' + id, {
                    _method: 'PUT',
                    name,
                })
                .then(response => {
                    this.desk_list_input_id = null;
                })
                .catch(error => {
                    console.log(error);
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
        card_names: {
            $each: {
                required,
                maxLength: maxLength(64),
            },
        },
    },
};
</script>
