<template>
    <div class="container">
        <h1>
            Desks
        </h1>
        <div class="row">
            <div class="col-lg-4" v-for="desk in desks" v-bind:key="desk.id">
                <div class="card mt-3">
                    <router-link
                        class="card-body"
                        :to="{ name: 'showDesk', params: { deskId: desk.id } }"
                    >
                        <h4 class="card-title">{{ desk.name }}</h4>
                    </router-link>
                    <button type="button" class="btn btn-danger mt-3">Delete</button>
                </div>
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
import axios from 'axios';
export default {
    data() {
        return {
            desks: [],
            errorred: false,
            loading: true,
        };
    },
    mounted() {
        axios
            .get('/api/v1/desks')
            .then(response => {
                this.desks = response.data.data;
            })
            .catch(error => {
                console.log(error);
                this.errorred = true;
            })
            .finally(() => {
                this.loading = false;
            });
    },
};
</script>
